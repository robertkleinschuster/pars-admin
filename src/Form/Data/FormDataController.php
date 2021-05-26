<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;

/**
 * Class FormDataController
 * @package Pars\Admin\Form\Data
 * @method FormDataModel getModel()
 */
class FormDataController extends CrudController
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

    public function indexAction()
    {
        $overview = parent::indexAction();
        $fields = $this->getModel()->getFieldList($this->getControllerRequest()->getId()->getAttribute('Form_ID'), true);
        foreach ($fields as $field) {
            $overview->addField("FormData_Data[$field]", $this->translate('formfield.code.' . $field));
        }
        return $overview;
    }

    public function detailAction()
    {
        $detail = parent::detailAction();
        $fields = $this->getModel()->getFieldList($this->getControllerRequest()->getId()->getAttribute('Form_ID'));
        foreach ($fields as $field) {
            $detail->addSpan("FormData_Data[$field]", $this->translate('formfield.code.' . $field));
        }
        return $detail;
    }


}
