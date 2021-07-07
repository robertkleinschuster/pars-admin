<?php


namespace Pars\Admin\Form\Data;


use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class FormDataCheckboxFieldFormat implements FieldFormatInterface
{
    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        $data = $bean->get('FormData_Data');
        if (isset($data[$value]) && $data[$value]) {
            $field->setStyle(Icon::STYLE_SUCCESS);
            $field->setName(Icon::ICON_CHECK_SQUARE);
        } else {
            $field->setStyle(Icon::STYLE_DANGER);
            $field->setName(Icon::ICON_X_SQUARE);
        }
        return '';
    }

}
