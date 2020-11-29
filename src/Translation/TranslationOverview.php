<?php


namespace Pars\Admin\Translation;


use Pars\Admin\Base\BaseOverview;

class TranslationOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.translation'));

        $this->addField('Translation_Code', $this->translate('translation.code'));
        $this->addField('Translation_Text', $this->translate('translation.text'));
        $this->addField('Translation_Namespace', $this->translate('translation.namespace'));

        parent::initialize();
    }

    protected function getController(): string
    {
        return 'translation';
    }

    protected function getDetailIdFields(): array
    {
       return [
           'Translation_ID'
       ];
    }

}
