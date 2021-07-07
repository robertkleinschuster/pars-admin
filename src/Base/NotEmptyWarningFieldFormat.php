<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;
use Pars\Mvc\View\ViewElement;

class NotEmptyWarningFieldFormat implements FieldFormatInterface
{

    protected string $field;

    /**
     * NotEmptyWarningFieldFormat constructor.
     * @param string $field
     */
    public function __construct(string $field)
    {
        $this->field = $field;
    }


    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if (!$bean->empty($this->field)) {
            if ($field instanceof ViewElement) {
                $field->getInput()->addOption('border-warning');
            }
        }
        return $value;
    }
}
