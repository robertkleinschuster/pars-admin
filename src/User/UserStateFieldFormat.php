<?php


namespace Pars\Admin\User;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class UserStateFieldFormat implements FieldFormatInterface
{
    use TranslatorAwareTrait;


    /**
     * UserStateFieldFormat constructor.
     */
    public function __construct(TranslatorInterface $translator)
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
                    return $this->getTranslator()->translate('userstate.code.active', 'admin');
                case 'inactive':
                    if ($field instanceof StyleAwareInterface) {
                        $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
                    }
                    return $this->getTranslator()->translate('userstate.code.inactive', 'admin');
                case 'locked':
                    if ($field instanceof StyleAwareInterface) {
                        $field->setStyle(StyleAwareInterface::STYLE_DANGER);
                    }
                    return $this->getTranslator()->translate('userstate.code.locked', 'admin');
            }
        }
        return $value;
    }
}
