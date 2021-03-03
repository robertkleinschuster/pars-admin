<?php

namespace Pars\Admin\Translation;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class TranslationStateFieldFormat implements FieldFormatInterface
{
    use TranslatorAwareTrait;

    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean->empty('Translation_Text') || $bean->get('Translation_Text') == $bean->get('Translation_Code')) {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_WARNING);
            }
            return $this->getTranslator()->translate('translation.state.missing', 'admin');
        } else {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
            }
            return $this->getTranslator()->translate('translation.state.complete', 'admin');
        }
    }
}
