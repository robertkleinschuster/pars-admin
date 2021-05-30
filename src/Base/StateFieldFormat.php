<?php


namespace Pars\Admin\Base;


use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class StateFieldFormat implements FieldFormatInterface
{
    protected string $field;
    protected string $activeText;
    protected string $inactiveText;

    /**
     * StateFieldFormat constructor.
     * @param string $field
     * @param string $activeText
     * @param string $inactiveText
     */
    public function __construct(string $field, string $activeText, string $inactiveText)
    {
        $this->field = $field;
        $this->activeText = $activeText;
        $this->inactiveText = $inactiveText;
    }

    /**
     * @param FieldInterface $field
     * @param string $value
     * @param BeanInterface|null $bean
     * @return string
     */
    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        $field->setIconField(true);
        if ($bean->isset($this->field) && in_array($bean->get($this->field), ['active', true, 'true', 1])) {
            $field->setTooltip($this->activeText);
            return Icon::eye(true);
        } else {
            $field->setTooltip($this->inactiveText);
            return Icon::eye(false);
        }
    }


}
