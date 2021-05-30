<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BooleanValueFieldAccept;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Toolbar\CheckButton;
use Pars\Component\Base\Toolbar\MailButton;
use Pars\Helper\Parameter\RedirectParameter;

class FormDataDetail extends BaseDetail
{
    protected function initBase()
    {
        parent::initBase();
        $this->setShowEdit(false);
    }

    protected function initFields()
    {
        parent::initFields();
    }

    protected function initAdditionalAfter()
    {
        parent::initAdditionalAfter();


        $readButton = new MailButton();
        $readButton->setTooltip($this->translate('formdata.read.false.button'));
        $readButton->setStyle(MailButton::STYLE_INFO);
        $readButton->setPath($this->getPathHelper(false)->setAction('unread')->addParameter(RedirectParameter::fromPath($this->getPathHelper(false)->getPath())));
        $readButton->setAccept(new BooleanValueFieldAccept('FormData_Read', false));
        $this->getToolbar()->push($readButton);

        $readButton = new CheckButton();
        $readButton->setTooltip($this->translate('formdata.read.true.button'));
        $readButton->setStyle(CheckButton::STYLE_SUCCESS);
        $readButton->setPath($this->getPathHelper(false)->setAction('read')->addParameter(RedirectParameter::fromPath($this->getPathHelper(false)->getPath())));
        $readButton->setAccept(new BooleanValueFieldAccept('FormData_Read', true));
        $this->getToolbar()->push($readButton);

    }


    protected function getIndexController(): string
    {
        return 'form';
    }

    protected function getIndexIdFields(): array
    {
        return ['Form_ID'];
    }

    protected function getEditController(): string
    {
        return 'formdata';
    }


    protected function getEditIdFields(): array
    {
        return ['FormData_ID'];
    }
}
