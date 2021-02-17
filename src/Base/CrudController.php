<?php

namespace Pars\Admin\Base;

use DateTime;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Grid\Column;
use Pars\Component\Base\Grid\Row;
use Pars\Component\Base\Pagination\Pagination;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\Exception\NotFoundException;

/**
 * Class CrudController
 * @package Pars\Admin\Base
 * @method CrudModel getModel()
 */
abstract class CrudController extends BaseController
{
    protected CrudComponentFactory $componentFactory;

    /**
     * @return CrudComponentFactory
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function getComponentFactory(): CrudComponentFactory
    {
        if (!isset($this->componentFactory)) {
            $this->componentFactory = new CrudComponentFactory(
                $this->getUserBean(),
                $this->getPathHelper(),
                $this->getTranslator()
            );
        }
        return $this->componentFactory;
    }

    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     */
    protected function initView()
    {
        parent::initView();
        $this->getComponentFactory()->setDelete(str_replace('Controller', 'Delete', static::class));
        $this->getComponentFactory()->setDetail(str_replace('Controller', 'Detail', static::class));
        $this->getComponentFactory()->setEdit(str_replace('Controller', 'Edit', static::class));
        $this->getComponentFactory()->setOverview(str_replace('Controller', 'Overview', static::class));
    }


    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initModel()
    {
        parent::initModel();
        $this->getModel()->setCurrentPage($this->getCurrentPagination()->getPage());
    }

    /**
     * @return BaseOverview
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     * @throws NotFoundException
     */
    public function indexAction()
    {
        $overview = $this->createOverview();
        $overview->setToken($this->generateToken('submit_token'));
        $overview->setBeanList($this->getModel()->getBeanList());
        $this->getView()->append($overview);
        $this->initPagination($overview);
        return $overview;
    }

    /**
     * @param $overview
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     */
    protected function initPagination($overview)
    {
        $pagination = new Pagination();
        $count = $this->getModel()->getBeanFinder()->count();
        $limit = $this->getDefaultLimit();
        $pages = ceil($count / $limit);
        $current = $this->getCurrentPagination();
        for ($page = 1; $page <= $pages; $page++) {
            $parameter = new PaginationParameter($page, $limit);
            $parameter->setController($this->getControllerRequest()->getController());
            $parameter->setAction($this->getControllerRequest()->getAction());
            $path = $this->getPathHelper(true)->addParameter($parameter);
            $pagination->addPage($path->getPath(), $page, $current->getPage() == $page);
        }
        $overview->getAfter()->push($pagination);
    }

    /**
     * @return PaginationParameter
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function getCurrentPagination()
    {
        $sessionKey = $this->getControllerRequest()->getAction() . $this->getControllerRequest()->getController();
        if ($this->getControllerRequest()->hasPagingation()) {
            $current = $this->getControllerRequest()->getPagination();
            if (!$this->getControllerRequest()->acceptParameter($current)) {
                $current = new PaginationParameter(1, $this->getDefaultLimit());
                $current->setController($this->getControllerRequest()->getController());
                $current->setAction($this->getControllerRequest()->getAction());
            } else {
                $this->getSession()->set($sessionKey, $current->getPage());
            }
        } elseif ($this->getSession()->has($sessionKey)) {
            $current = new PaginationParameter($this->getSession()->get($sessionKey), $this->getDefaultLimit());
            $current->setController($this->getControllerRequest()->getController());
            $current->setAction($this->getControllerRequest()->getAction());
        } else {
            $current = new PaginationParameter(1, $this->getDefaultLimit());
            $current->setController($this->getControllerRequest()->getController());
            $current->setAction($this->getControllerRequest()->getAction());
        }
        return $current;
    }

    /**
     * @return BaseOverview
     */
    protected function createOverview(): BaseOverview
    {
        return $this->getComponentFactory()->createOverview();
    }

    /**
     * @return BaseDetail
     * @throws NotFoundException
     * @throws BeanException
     */
    public function detailAction()
    {
        $row = new Row();
        $detail = $this->createDetail();
        $bean = $this->getModel()->getBean();
        $detail->setBean($bean);
        $column = new Column();
        $column->setBreakpoint(Column::BREAKPOINT_EXTRA_LARGE);
        $this->getView()->append($detail);
        $row->push($column);
        $metaInfo = $this->initMetaInfo($bean);
        $column = new Column();
        $column->setBreakpoint(Column::BREAKPOINT_EXTRA_LARGE);
        $column->push($metaInfo);
        $row->push($column);
        $this->getView()->append($metaInfo);
        return $detail;
    }

    /**
     * @param $bean
     * @return Detail
     * @throws BeanException
     */
    protected function initMetaInfo($bean)
    {
        $metaInfo = new Detail();
        #  $metaInfo->setSection($this->translate('meta.info'));
        if ($bean->exists('Person_ID_Create') && !$bean->empty('Person_ID_Create')) {
            if ($bean->get('Person_ID_Create') > 0) {
                $user = $this->getModel()->getUserById($bean->get('Person_ID_Create'));
                $metaInfo->append(new Span($user->get('User_Displayname'), $this->translate('user.create')), 1, 1);
            }
        }
        if ($bean->exists('Person_ID_Edit') && !$bean->empty('Person_ID_Edit')) {
            if ($bean->get('Person_ID_Edit') > 0) {
                $user = $this->getModel()->getUserById($bean->get('Person_ID_Edit'));
                $metaInfo->append(new Span($user->get('User_Displayname'), $this->translate('user.edit')), 2, 1);
            }
        }
        if ($bean->exists('Timestamp_Create') && !$bean->empty('Timestamp_Create')) {
            $date = $this->getModel()->getBeanConverter()->convert($bean)->get('Timestamp_Create');
            $date = new DateTime($date);
            $metaInfo->append(new Span($date->format('d.m.Y H:i:s'), $this->translate('timestamp.create')), 1, 2);
        }
        if ($bean->exists('Timestamp_Edit') && !$bean->empty('Timestamp_Edit')) {
            $date = $this->getModel()->getBeanConverter()->convert($bean)->get('Timestamp_Edit');
            $date = new DateTime($date);
            $metaInfo->append(new Span($date->format('d.m.Y H:i:s'), $this->translate('timestamp.edit')), 2, 2);
        }
        return $metaInfo;
    }

    /**
     * @return BaseDetail
     */
    protected function createDetail(): BaseDetail
    {
        return $this->getComponentFactory()->createDetail();
    }

    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    public function createAction()
    {
        $edit = $this->createEdit();
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getEmptyBean($this->getPreviousAttributes()));
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $edit->setCreate(true);
        $this->getView()->append($edit);
        if ($this->getControllerRequest()->hasData()) {
            $edit->getBean()->fromArray($this->getControllerRequest()->getData()->getAttributes());
        }
        return $edit;
    }

    /**
     * @return BaseEdit
     * @throws NotFoundException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function editAction()
    {
        $edit = $this->createEdit();
        if ($this->getControllerRequest()->hasAttribute('context')) {
            $context = $this->getControllerRequest()->getAttribute('context');
            $edit->setContext($context);
        }
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getBean());
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $this->getView()->append($edit);
        if ($this->getControllerRequest()->hasData()) {
            $edit->getBean()->fromArray($this->getControllerRequest()->getData()->getAttributes());
        }
        return $edit;
    }

    /**
     * @return BaseEdit
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    protected function createEdit(): BaseEdit
    {
        return $this->getComponentFactory()->createEdit();
    }

    /**
     * @return BaseDelete
     * @throws NotFoundException
     */
    public function deleteAction()
    {
        $delete = $this->createDelete();
        if ($this->getControllerRequest()->hasAttribute('context')) {
            $context = $this->getControllerRequest()->getAttribute('context');
            $delete->setContext($context);
        }
        $delete->setToken($this->generateToken('submit_token'));
        $this->getView()->append($delete);
        $detail = $this->createDetail();
        $detail->setShowDelete(false);
        $detail->setShowBack(false);
        $detail->setShowEdit(false);
        $bean = $this->getModel()->getBean();
        $detail->setBean($bean);
        $this->getView()->append($detail);
        return $delete;
    }

    /**
     * @return BaseDelete
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    protected function createDelete(): BaseDelete
    {
        return $this->getComponentFactory()->createDelete();
    }

    /**
     * @return int
     */
    protected function getDefaultLimit(): int
    {
        return $this->getModel()->getConfig('admin.pagination.limit') ?? 10;
    }
}
