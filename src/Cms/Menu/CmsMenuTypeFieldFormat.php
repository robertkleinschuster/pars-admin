<?php

namespace Pars\Admin\Cms\Menu;


use Pars\Bean\Type\Base\BeanInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsMenuTypeFieldFormat implements FieldFormatInterface
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
        return $this->getTranslator()->translate('cmsmenutype.code.' . $bean->get('CmsMenuType_Code'));
    }
}
