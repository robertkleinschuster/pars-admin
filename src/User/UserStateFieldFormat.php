<?php

namespace Pars\Admin\User;

use Pars\Bean\Type\Base\BeanInterface;
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
            switch ($bean->get('UserState_Code')) {
                case 'active':
                    if ($field instanceof StyleAwareInterface) {
                        $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
                    }
                    return $this->getTranslator()->translate('userstate.code.active');
                case 'inactive':
                    if ($field instanceof StyleAwareInterface) {
                        $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
                    }
                    return $this->getTranslator()->translate('userstate.code.inactive');
                case 'locked':
                    if ($field instanceof StyleAwareInterface) {
                        $field->setStyle(StyleAwareInterface::STYLE_DANGER);
                    }
                    return $this->getTranslator()->translate('userstate.code.locked');
            }
        }
        return $value;
    }
}
