<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;

/**
 * Class FormController
 * @package Pars\Admin\Form
 * @method FormModel getModel()
 */
class FormController extends CrudController
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
        $edit->setTypeOptions($this->getModel()->getFormTypeOption_List());
        return $edit;
    }

    public function detailAction()
    {
        $detail = parent::detailAction();
        $this->pushAction('formdata', 'index', $this->translate('formdata.overview'));
        $this->pushAction('formfield', 'index', $this->translate('formfield.overview'));
        return $detail;
    }


}
