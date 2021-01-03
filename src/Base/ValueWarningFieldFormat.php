<?php


namespace Pars\Admin\Base;


use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Form\Input;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;
use Pars\Mvc\View\HtmlElement;

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
