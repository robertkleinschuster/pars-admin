<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldAcceptInterface;
use Pars\Mvc\View\FieldInterface;

/**
 * Class BooleanValueFieldAccept
 * @package Pars\Admin\Base
 */
class BooleanValueFieldAccept implements FieldAcceptInterface
{
    protected string $field;
    protected bool $invert;

    /**
     * BooleanValueFieldAccept constructor.
     * @param string $field
     * @param bool $invert
     */
    public function __construct(string $field, bool $invert = false)
    {
        $this->field = $field;
        $this->invert = $invert;
    }


    /**
     * @param FieldInterface $field
     * @param BeanInterface|null $bean
     * @return bool
     */
    public function __invoke(FieldInterface $field, ?BeanInterface $bean = null): bool
    {
        if ($this->invert) {
            return !$bean->get($this->field);
        } else {
            return $bean->get($this->field);
        }
    }

}
