<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;

/**
 * Class FormFieldController
 * @package Pars\Admin\Form\Field
 * @method FormFieldModel getModel()
 */
class FormFieldController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('form.create', 'form.edit', 'form.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('form');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('form');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createEdit(): BaseEdit
    {
        $edit = parent::createEdit();
        $edit->setTypeOptions($this->getModel()->getFormFieldTypeOption_List());
        return $edit;
    }


}
