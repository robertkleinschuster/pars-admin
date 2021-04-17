<?php

namespace Pars\Admin\Translation;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\Exception\NotFoundException;

/**
 * Class TranslationController
 * @package Pars\Admin\Translation
 * @method TranslationModel getModel()
 */
class TranslationController extends CrudController
{
    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('translation.create', 'translation.edit', 'translation.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('translation');
    }

    /**
     * @return mixed|void
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('translation');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
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
        $edit = parent::createEdit();
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        return $edit;
    }

    public function indexAction()
    {
        $this->addFilter_Select(
            'Locale_Code',
            $this->translate('locale.code'),
            $this->getModel()->getLocale_Options(true),
            1,
            1
        );
        $this->addFilter_Select(
            'Translation_Namespace',
            $this->translate('translation.namespace'),
            $this->getModel()->getNamespaceOptions(true),
            1,
            2
        );
        $this->addFilter_Select(
            'Translation_State',
            $this->translate('translation.state'),
            [
                '' => $this->translate('noselection'),
                'true' => $this->translate('translation.state.true'),
                'false' => $this->translate('translation.state.false'),
            ],
            1,
            3
        );
        return parent::indexAction();
    }


    /**
     * @return BaseEdit
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws NotFoundException
     */
    public function editAction()
    {
        $edit = parent::editAction();
        $source = $this->getModel()->getTranslationSource();
        if ($source) {
            $detail = $this->createDetail();
            $detail->setShowEdit(false);
            $detail->setShowBack(false);
            $detail->setShowDelete(false);
            $detail->setBean($source);
            $this->getView()->pushComponent($detail);
            $detail = new Detail();
            $detail->setName($this->translate('translation.edit.placeholder'));
            foreach ($this->getView() as $key => $value) {
                $span = new Span($value, "[{$key}]");
                $detail->append($span);
            }
            $this->getView()->pushComponent($detail);
        }
        return $edit;
    }
}
