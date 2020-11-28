<?php


namespace Pars\Admin\Locale;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;

class LocaleDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setHeadline('{Locale_Name}');
        $this->addField('Locale_Code', $this->translate('locale.code'));
        $this->addField('Locale_UrlCode', $this->translate('locale.urlcode'));
        $active = new Badge('{Locale_Active}');
        $active->setLabel($this->translate('locale.active'));
        $active->setFormat(new LocaleActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        parent::initialize(); // TODO: Change the autogenerated stub
    }
    protected function getIndexController(): string
    {
        return 'locale';
    }

    protected function getEditController(): string
    {
        return 'locale';
    }



    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getEditIdFields(): array
    {
       return ['Locale_Code'];
    }


}
