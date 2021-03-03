<?php

namespace Pars\Admin\Role;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class RoleActiveFieldFormat implements FieldFormatInterface
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
        if ($bean->get('UserRole_Active') == 'true') {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
            }
            return $this->getTranslator()->translate('userrole.active.true', 'admin');
        } else {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
            }
            return $this->getTranslator()->translate('userrole.active.false', 'admin');
        }
    }
}
