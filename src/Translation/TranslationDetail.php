<?php

namespace Pars\Admin\Translation;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseDetail;

class TranslationDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{Translation_Code}');
    }


    protected function initialize()
    {

        $this->addSpan('Translation_Code', $this->translate('translation.code'));
        $this->addSpan('Translation_Text', $this->translate('translation.text'));
        $this->addSpan('Translation_Namespace', $this->translate('translation.namespace'));
        $this->addSpan('Locale_Name', $this->translate('locale.name'));
        parent::initialize();
    }


    protected function beforeRender(BeanInterface $bean = null)
    {
        if ($bean && !$bean->empty('Translation_Text')) {
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
