<?php


namespace Pars\Admin\Base;


use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Core\Container\ParsContainer;
use Pars\Core\Container\ParsContainerAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class BooleanValueFieldFormat implements FieldFormatInterface
{

    protected string $field;
    protected string $trueText;
    protected string $falseText;

    /**
     * BooleanValueFieldFormat constructor.
     * @param string $trueText
     * @param string $falseText
     */
    public function __construct(string $field, string $trueText, string $falseText)
    {
        $this->trueText = $trueText;
        $this->falseText = $falseText;
        $this->field = $field;
    }


    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        $field->setIconField(true);
        if ($bean->isset($this->field) && $bean->get($this->field)) {
            $field->setTooltip($this->trueText);
            return new Icon(Icon::ICON_CHECK, Icon::STYLE_SUCCESS);
        } else {
            $field->setTooltip($this->falseText);
            return new Icon(Icon::ICON_X, Icon::STYLE_DANGER);
        }
    }


}
