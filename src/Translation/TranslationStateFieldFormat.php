<?php

namespace Pars\Admin\Translation;


use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class TranslationStateFieldFormat implements FieldFormatInterface
{
    use ParsTranslatorAwareTrait;

    public function __construct(ParsTranslator $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        $field->setIconField(true);
        if ($bean->empty('Translation_Text') || $bean->get('Translation_Text') == $bean->get('Translation_Code')) {
            $field->setTooltip($this->getTranslator()->translate('translation.state.missing'));
            return new Icon(Icon::ICON_ALERT_TRIANGLE, Icon::STYLE_WARNING);
        } else {
            $field->setTooltip( $this->getTranslator()->translate('translation.state.complete'));
            return new Icon(Icon::ICON_CHECK_CIRCLE, Icon::STYLE_SUCCESS);
        }
    }
}
