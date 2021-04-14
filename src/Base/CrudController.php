<?php

namespace Pars\Admin\Base;

use DateTime;
use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Breadcrumb\Breadcrumb;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Filter\Filter;
use Pars\Component\Base\Layout\DashboardLayout;
use Pars\Component\Base\Navigation\Item;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Pagination\Pagination;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\FilterParameter;
use Pars\Helper\Parameter\NavParameter;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Helper\Parameter\SearchParameter;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\Exception\NotFoundException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;

/**
 * Class CrudController
 * @package Pars\Admin\Base
 * @method CrudModel getModel()
 */
abstract class CrudController extends BaseController
{
    protected CrudComponentFactory $componentFactory;

    /**
     * @var Filter|null
     */
    protected ?Filter $filter = null;


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
        if ($this->getControllerRequest()->hasPagingation()) {
            $this->getSession()->set('limit', $this->getControllerRequest()->getPagination()->getLimit());
        }
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
        $this->getView()->getLayout()->getSubNavigation()->setSearch(
            SearchParameter::nameAttr('text'),
            $this->translate('search.placeholder')
        );
        if ($this->getControllerRequest()->hasSearch() && $this->getControllerRequest()->getSearch()->hasText()) {
            $this->getView()->getLayout()->getSubNavigation()->getSearch()->setValue($this->getControllerRequest()->getSearch()->getText());
            $this->getView()->getLayout()->getSubNavigation()->setSearchAction($this->getPathHelper()->getPath());
        }
        $overview = $this->createOverview();
        $this->injectContext($overview);
        $overview->setToken($this->generateToken('submit_token'));
        $overview->setBeanList($this->getModel()->getBeanList());
        $this->initFilter($overview);
        $this->getView()->append($overview);
        $this->initPagination($overview, $this->getModel()->getBeanFinder()->count());
        return $overview;
    }


    /**
     * @param Overview $overview
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initFilter(Overview $overview)
    {
        if ($this->hasFilter()) {
            $filterPath = $this->getPathHelper(true);
            $navParameter = new NavParameter();
            $id = 'filter'
                . $this->getControllerRequest()->getController()
                . $this->getControllerRequest()->getAction();
            $navParameter->setId($id);
            if ($this->getNavigationState($id) === 0) {
                $this->getFilter()->getForm()->addOption('show');
                $navParameter->setIndex(1);
            } else {
                $navParameter->setIndex(0);
            }
            $filterPath->addParameter($navParameter);
            $this->getFilter()->getForm()->setName($this->translate('admin.filter'));
            $this->getFilter()->getButton()->setPath($filterPath);
            $this->getFilter()->getForm()->addSubmit(
                '',
                $this->translate('admin.filter.apply'),
                null,
                null,
                null,
                10,
                1
            );
            $resetPath = $this->getPathHelper(true);
            $resetPath->getFilter()->clear();
            $resetPath->getSearch()->clear();
            $this->getFilter()->getForm()->addReset(
                '',
                $this->translate('admin.filter.reset'),
                null,
                null,
                null,
                10,
                2
            )->getInput()->setPath($resetPath->getPath());
            $overview->getToolbar()->push($this->getFilter()->getButton());
            $this->getView()->append($this->getFilter());
        }
    }

    /**
     * @param CrudComponentInterface $component
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function injectContext(CrudComponentInterface $component)
    {
        $context = new ContextParameter();
        $context->setPath($this->getPathHelper(true)->getPath());
        if (!$this->hasParent() && $component->hasName()) {
            $context->setTitle($component->getName());
        }
        $component->setNextContext($context);
        if ($this->getControllerRequest()->hasContext()) {
            $context = $this->getControllerRequest()->getContext();
            $component->setCurrentContext($context);
        }
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addItem(
            $this->translate('index.title'),
            $this->getPathHelper()->setController('index')->setAction('index')->getPath()
        );
        $layout = $this->getView()->getLayout();
        if ($layout instanceof DashboardLayout) {
            if ($layout->getNavigation()->hasActive()) {
                $navItem = $layout->getNavigation()->getElementById($layout->getNavigation()->getActive());
                if ($navItem) {
                    $span = $navItem->getElementsByTagName('span');
                    if ($span->count() && $navItem instanceof Item) {
                        $breadcrumb->addItem(
                            $span->first()->getContent(),
                            $navItem->getLink()->getPath()
                        );
                    }
                }
            }
        }

        $breadcrumb->addOption('modal-hidden');
        if ($component->hasCurrentContext() && $component->getCurrentContext()->hasPath()) {
            $contextList = $context->resolveContextFromPath();
            foreach ($contextList as $contextItem) {
                if ($contextItem->hasTitle()) {
                    $breadcrumb->addItem($contextItem->getTitle(), $contextItem->getPath());
                } else {
                    $breadcrumb->addItem('<<', $contextItem->getPath());
                }
            }
        }
        if ($component->getNextContext()->hasTitle()) {
            $breadcrumb->addItem($component->getNextContext()->getTitle(), $component->getNextContext()->getPath());
        }
        if (!$this->hasParent() && $breadcrumb->getItemList()->count() >= 1
            && $this->getView()->getLayout()->getComponentList()->isEmpty()
        ) {
            $component->unshift($breadcrumb);
        }
    }


    /**
     * @param $overview
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     */
    protected function initPagination(Overview $overview, int $count)
    {
        $this->getView()->set('OverviewCount', $count);
        $span = new Span($this->translate('overview.count'));
        $span->addOption('float-right');
        $span->addOption('border');
        $span->addOption('p-1');
        $span->addOption('d-none');
        $span->addOption('d-sm-block');
        $span->setContent($span->render($this->getView(), true));
        $span->clearOptions();
        $overview->getAfter()->push($span);

        $pagination = new Pagination();
        $limit = $this->getDefaultLimit();
        $pages = ceil($count / $limit);
        $current = $this->getCurrentPagination();
        $padding = 10;
        for ($page = 1; $page <= $pages; $page++) {
            if ($page == 1 && $current->getPage() > 1) {
                $parameter = new PaginationParameter($current->getPage() - 1, $limit);
                $parameter->setController($this->getControllerRequest()->getController());
                $parameter->setAction($this->getControllerRequest()->getAction());
                $path = $this->getPathHelper(true)->addParameter($parameter);
                $pagination->addPage($path->getPath(), '<', false);
            }

            $parameter = new PaginationParameter($page, $limit);
            $parameter->setController($this->getControllerRequest()->getController());
            $parameter->setAction($this->getControllerRequest()->getAction());
            $path = $this->getPathHelper(true)->addParameter($parameter);
            if (
                ($page - $current->getPage() < $padding && $page > $current->getPage() - $padding)
                || $page == 1 || $page == $pages
                || ($current->getPage() < $padding && $page <= $padding * 2)
                || ($current->getPage() > $pages - $padding && $page >= $pages - $padding * 2)
            ) {
                $item = $pagination->addPage($path->getPath(), $page, $current->getPage() == $page);
                if ($page != 1 && $page != $pages) {
                    if (abs($page - $current->getPage()) > $padding / 2) {
                        $item->addOption('d-none d-lg-block');
                    } elseif (abs($page - $current->getPage()) > $padding / 3) {
                        $item->addOption('d-none d-md-block');
                    } elseif (abs($page - $current->getPage()) > $padding / 4) {
                        $item->addOption('d-none d-sm-block');
                    }
                }
            } elseif ($page == 2 || $page == $pages - 1) {
                $pagination->addPage(null, '..', false);
            }

            if ($page == $pages && $current->getPage() < $pages) {
                $parameter = new PaginationParameter($current->getPage() + 1, $limit);
                $parameter->setController($this->getControllerRequest()->getController());
                $parameter->setAction($this->getControllerRequest()->getAction());
                $path = $this->getPathHelper(true)->addParameter($parameter);
                $pagination->addPage($path->getPath(), '>', false);
            }
        }
        $overview->getAfter()->push($pagination);

        $pagination = new Pagination();
        $parameter = new PaginationParameter($current->getPage(), 10);
        $parameter->setController($this->getControllerRequest()->getController());
        $parameter->setAction($this->getControllerRequest()->getAction());
        $path = $this->getPathHelper(true)->addParameter($parameter);
        $pagination->addPage($path->getPath(), '10', $this->getDefaultLimit() == 10);
        $parameter = new PaginationParameter($current->getPage(), 20);
        $parameter->setController($this->getControllerRequest()->getController());
        $parameter->setAction($this->getControllerRequest()->getAction());
        $path = $this->getPathHelper(true)->addParameter($parameter);
        $pagination->addPage($path->getPath(), '20', $this->getDefaultLimit() == 20);
        $parameter = new PaginationParameter($current->getPage(), 50);
        $parameter->setController($this->getControllerRequest()->getController());
        $parameter->setAction($this->getControllerRequest()->getAction());
        $path = $this->getPathHelper(true)->addParameter($parameter);
        $pagination->addPage($path->getPath(), '50', $this->getDefaultLimit() == 50);
        $parameter = new PaginationParameter($current->getPage(), 100);
        $parameter->setController($this->getControllerRequest()->getController());
        $parameter->setAction($this->getControllerRequest()->getAction());
        $path = $this->getPathHelper(true)->addParameter($parameter);
        $pagination->addPage($path->getPath(), '100', $this->getDefaultLimit() == 100);
        $parameter = new PaginationParameter($current->getPage(), 500);
        $parameter->setController($this->getControllerRequest()->getController());
        $parameter->setAction($this->getControllerRequest()->getAction());
        $path = $this->getPathHelper(true)->addParameter($parameter);
        $pagination->addPage($path->getPath(), '500', $this->getDefaultLimit() == 500);
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
        if ($this->getControllerRequest()->hasId()) {
            $sessionKey .= $this->getControllerRequest()->getId()->toString();
        }
        $sessionKey = md5($sessionKey);
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
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     */
    protected function createOverview(): BaseOverview
    {
        return $this->getComponentFactory()->createOverview()->setPathHelperCurrent($this->getPathHelper(true));
    }

    /**
     * @return BaseDetail
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     * @throws MvcException
     * @throws NotFoundException
     */
    public function detailAction()
    {
        $detail = $this->createDetail();
        $collapsable = $this->initCollapsable('detail', $this->expandCollapse, $detail)
            ->setTitle($this->translate("showdetails"));
        $this->injectContext($detail);
        $bean = $this->getModel()->getBean();
        $detail->setBean($bean);
        $this->getView()->append($collapsable);

        $metaInfo = $this->initMetaInfo($bean);
        $collapsable = $this->initCollapsable('metainfo', false, $metaInfo)
            ->setTitle($this->translate('showmetainfo'));
        $this->getView()->append($collapsable);
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
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
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
        $this->injectContext($edit);
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
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     * @throws NotFoundException
     */
    public function editAction()
    {
        $edit = $this->createEdit();
        $this->injectContext($edit);
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
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws MvcException
     * @throws NotFoundException
     */
    public function deleteAction()
    {
        $delete = $this->createDelete();
        $this->injectContext($delete);
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
        return $this->getSession()->get('limit') ?? $this->getModel()->getConfig('admin.pagination.limit') ?? 10;
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        if (!$this->hasFilter()) {
            $this->setFilter(new Filter());
        }
        return $this->filter;
    }

    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function setFilter(Filter $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFilter(): bool
    {
        return isset($this->filter);
    }

    /**
     * @param string $field
     * @param string $label
     * @param array $options
     * @param int $row
     * @param int $column
     * @return $this
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function addFilter_Select(
        string $field,
        string $label,
        array $options,
        int $row = 1,
        int $column = 1
    ): self
    {
        $parameter = new FilterParameter();
        $value = '';
        if (
            $this->getControllerRequest()->hasFilter()
            && $this->getControllerRequest()->getFilter()->hasAttribute($field)
        ) {
            $value = $this->getControllerRequest()->getFilter()->getAttribute($field);
        }
        $this->getFilter()->getForm()->addSelect(
            $parameter::nameAttr($field),
            $options,
            $value,
            $label,
            $row,
            $column
        );
        return $this;
    }

    /**
     * @param string $field
     * @param string $label
     * @param int $row
     * @param int $column
     * @return $this
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function addFilter_Search(string $label, int $row = 1, int $column = 1): self
    {
        $parameter = new SearchParameter();
        $value = '';
        if ($this->getControllerRequest()->hasSearch() && $this->getControllerRequest()->getSearch()->hasText()) {
            $value = $this->getControllerRequest()->getSearch()->getText();
        }
        $this->getFilter()->getForm()->addText(
            $parameter::getFormKeyText(),
            $value,
            $label,
            $row,
            $column
        );
        return $this;
    }

    /**
     * @param string $field
     * @param string $label
     * @param int $row
     * @param int $column
     * @return $this
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function addFilter_Checkbox(string $field, string $label, int $row = 1, int $column = 1): self
    {
        $parameter = new FilterParameter();
        $value = '';
        if (
            $this->getControllerRequest()->hasFilter()
            && $this->getControllerRequest()->getFilter()->hasAttribute($field)
        ) {
            $value = $this->getControllerRequest()->getFilter()->getAttribute($field);
        }
        $this->getFilter()->getForm()->addCheckbox(
            $parameter::nameAttr($field),
            $value,
            $label,
            $row,
            $column
        );
        return $this;
    }

}
