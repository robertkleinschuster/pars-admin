<?php

namespace Pars\Admin\User\RolePermission;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class RolePermissionDescriptionFieldFormat implements FieldFormatInterface
{
    use ParsTranslatorAwareTrait;


    /**
     * CmsPageTypeFieldFormat constructor.
     */
    public function __construct(ParsTranslator $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        return $this->getTranslator()->translate('userpermission.code.' . $bean->get('UserPermission_Code'));
    }
}
