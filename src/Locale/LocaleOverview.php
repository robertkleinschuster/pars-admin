<?php


namespace Pars\Admin\Locale;


use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;

class LocaleOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.locale'));

        $active = new Badge('{Locale_Active}');
        $active->setFormat(new LocaleActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        $this->addField('Locale_Name', $this->translate('locale.name'));
        $this->addField('Locale_Code', $this->translate('locale.code'));
        $this->setOrderField('Locale_Order');
        parent::initialize();
    }

    protected function getController(): string
    {
        return 'locale';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'Locale_Code'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [];
    }


}
