<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Icon;

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

    protected function createOverview(): BaseOverview
    {
        $overview = parent::createOverview();
        $formId = $this->getControllerRequest()->getId()->getAttribute('Form_ID');
        $fields = $this->getModel()->getFieldList($formId, true);
        foreach ($fields as $field => $type) {
            if ($type == 'checkbox') {
                $icon = new Icon();
                $icon->setContent($field);
                $icon->setTooltip($this->translate('formfield.code.' . $field));
                $icon->setFormat(new FormDataCheckboxFieldFormat());
                $overview->pushField($icon);
            } else {
                $overview->addField("FormData_Data[$field]", $this->translate('formfield.code.' . $field));
            }
        }
        $badge = new Badge($this->getModel()->getUnreadCount($formId), Badge::STYLE_INFO);
        $name = $this->translate('form.' . $this->getModel()->getForm_Code($formId) . '.overview');
        $overview->setName(Icon::inbox()->inline() . $badge . ' ' . $name);
        return $overview;
    }

    public function detailAction()
    {
        if ($this->getSession()->get('formdata.unread')
            != $this->getControllerRequest()->getId()->getAttribute('FormData_ID')) {
            $this->readAction();
        }
        $detail = parent::detailAction();
        $fields = $this->getModel()->getFieldList($detail->getBean()->Form_ID);
        foreach ($fields as $field => $type) {
            if ($type == 'checkbox') {
                $icon = new Icon();
                $icon->setContent($field);
                $icon->setLabel($this->translate('formfield.code.' . $field));
                $icon->setFormat(new FormDataCheckboxFieldFormat());
                $detail->unshiftField($icon);
            } else {
                $detail->addSpan("FormData_Data[$field]", $this->translate('formfield.code.' . $field));
            }
        }
        return $detail;
    }

    public function readAction()
    {
        $this->getModel()->save(['FormData_Read' => 'true']);
    }

    public function unreadAction()
    {
        $this->getModel()->save(['FormData_Read' => 'false']);
        $this->getSession()->set('formdata.unread', $this->getControllerRequest()->getId()->getAttribute('FormData_ID'));
    }

}
