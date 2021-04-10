<?php

namespace Pars\Admin\Translation;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;

class TranslationOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.translation'));
    }


    protected function initialize()
    {
        $this->setShowOrder(true);
        $badge = new Badge();
        $badge->setFormat(new TranslationStateFieldFormat($this->getTranslator()));
        $this->append($badge);
        $this->addFieldOrderable('Translation_Code', $this->translate('translation.code'));
        $this->addFieldOrderable('Translation_Text', $this->translate('translation.text'));
        $this->addFieldOrderable('Locale_Name', $this->translate('locale.name'));
        $this->addFieldOrderable('Translation_Namespace', $this->translate('translation.namespace'));
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
