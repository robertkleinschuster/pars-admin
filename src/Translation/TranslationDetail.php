<?php

namespace Pars\Admin\Translation;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseDetail;

class TranslationDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.translation'));

        $this->addField('Translation_Code', $this->translate('translation.code'));
        $this->addField('Translation_Text', $this->translate('translation.text'));
        $this->addField('Translation_Namespace', $this->translate('translation.namespace'));
        $this->addField('Locale_Name', $this->translate('locale.name'));
        parent::initialize();
    }


    protected function beforeRender(BeanInterface $bean = null)
    {
        if (!$bean->empty('Translation_Text')) {
            $text = $bean->get('Translation_Text');
            $text = str_replace('{', '[', $text);
            $text = str_replace('}', ']', $text);
            $bean->set('Translation_Text', $text);
        }
        parent::beforeRender($bean);
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
