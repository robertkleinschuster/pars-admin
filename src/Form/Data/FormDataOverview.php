<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\BooleanValueFieldAccept;
use Pars\Component\Base\Field\Icon;

class FormDataOverview extends BaseOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('formdata.overview'));
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowCreate(false);
        $this->setShowEdit(false);
    }

    protected function initFields()
    {
        parent::initFields();
        $icon = new Icon(Icon::ICON_MAIL);
        $icon->setStyle(Icon::STYLE_INFO);
        $icon->setAccept(new BooleanValueFieldAccept('FormData_Read', true));
        $this->pushField($icon);
    }


    protected function getController(): string
    {
        return 'formdata';
    }

    protected function getDetailIdFields(): array
    {
        return ['FormData_ID'];
    }

    protected function getCreateIdFields(): array
    {
        return $this->getControllerRequest()->getId()->getAttributes();
    }
}
