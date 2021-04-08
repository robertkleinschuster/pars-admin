<?php

namespace Pars\Admin\Config;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldAcceptInterface;
use Pars\Mvc\View\FieldInterface;

class ConfigValueInfoFieldAccept implements FieldAcceptInterface
{
    public function __invoke(FieldInterface $field, ?BeanInterface $bean = null): bool
    {
        return $bean->empty('Config_Value');
    }
}
