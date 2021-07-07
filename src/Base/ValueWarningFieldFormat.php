<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;
use Pars\Mvc\View\ViewElement;

class ValueWarningFieldFormat implements FieldFormatInterface
{

    protected string $field;
    protected $value;

    /**
     * NotEmptyWarningFieldFormat constructor.
     * @param string $field
     * @param $value
     */
    public function __construct(string $field, $value)
    {
        $this->field = $field;
        $this->value = $value;
    }


    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if (!$bean->empty($this->field) && $bean->get($this->field) == $this->value) {
            if ($field instanceof ViewElement) {
               $field->getInput()->addOption('border-warning');
            }
        }
        return $value;
    }
}
