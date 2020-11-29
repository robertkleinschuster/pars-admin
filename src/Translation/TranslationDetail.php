<?php


namespace Pars\Admin\Translation;


use Pars\Admin\Base\BaseDetail;

class TranslationDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.translation'));

        $this->addField('Translation_Code', $this->translate('translation.code'));
        $this->addField('Translation_Text', $this->translate('translation.text'));
        $this->addField('Translation_Namespace', $this->translate('translation.namespace'));
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'translation';
    }

    protected function getEditIdFields(): array
    {
        return [
            'Translation_ID'
        ];
    }

}
