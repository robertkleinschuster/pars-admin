<?php

namespace Pars\Admin\User;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class UserStateFieldFormat implements FieldFormatInterface
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
        if (null !== $bean) {
            $field->setIconField(true);
            switch ($bean->get('UserState_Code')) {
                case 'active':
                    $field->setTooltip($this->getTranslator()->translate('userstate.code.active'));
                    return new Icon(Icon::ICON_USER_CHECK, Icon::STYLE_SUCCESS);
                case 'inactive':
                    $field->setTooltip( $this->getTranslator()->translate('userstate.code.inactive'));
                    return new Icon(Icon::ICON_USER, Icon::STYLE_SECONDARY);
                case 'locked':
                    $field->setTooltip( $this->getTranslator()->translate('userstate.code.locked'));
                    return new Icon(Icon::ICON_USER_X, Icon::STYLE_DANGER);
            }
        }
        return $value;
    }
}
