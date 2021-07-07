<?php

namespace Pars\Admin\Translation;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseEdit;

class TranslationEdit extends BaseEdit
{
    protected ?array $localeOptions = null;

    protected function initialize()
    {
        $this->getForm()->setUseColumns(false);

        $this->getForm()->addText('Translation_Code', '{Translation_Code}', $this->translate('translation.code'));
        $this->getForm()->addText('Translation_Text', '{Translation_Text}', $this->translate('translation.text'));
        $options = [
            'frontend' => 'frontend',
            'admin' => 'admin'
        ];
        $this->getForm()->addSelect('Translation_Namespace', $options, '{Translation_Namespace}', $this->translate('translation.namespace'));
        if ($this->hasLocaleOptions()) {
            $this->getForm()->addSelect('Locale_Code', $this->getLocaleOptions(), '{Locale_Code}', $this->translate('locale.code'));
        }
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


    /**
     * @return array
     */
    public function getLocaleOptions(): array
    {
        return $this->localeOptions;
    }

    /**
     * @param array $localeOptions
     *
     * @return $this
     */
    public function setLocaleOptions(array $localeOptions): self
    {
        $this->localeOptions = $localeOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLocaleOptions(): bool
    {
        return isset($this->localeOptions);
    }


    protected function getRedirectController(): string
    {
        return 'translation';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'Translation_ID'
        ];
    }
}
