<?php


namespace Pars\Admin\Base;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;
use Pars\Mvc\View\HtmlElement;

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
            if ($field instanceof HtmlElement) {
                $field->addOption('border');
                $field->addOption('text-danger');
                $field->addOption('bg-light');
                $field->addOption('p-1');
            }
        }
        return $value;
    }

}
