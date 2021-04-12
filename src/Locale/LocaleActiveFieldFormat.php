<?php

namespace Pars\Admin\Locale;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class LocaleActiveFieldFormat implements FieldFormatInterface
{
    use ParsTranslatorAwareTrait;


    /**
     * UserStateFieldFormat constructor.
     */
    public function __construct(ParsTranslator $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean->get('Locale_Active') == 'true') {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
            }
            return $this->getTranslator()->translate('locale.active.true');
        } else {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
            }
            return $this->getTranslator()->translate('locale.active.false');
        }
    }
}
