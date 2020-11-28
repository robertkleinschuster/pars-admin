<?php


namespace Pars\Admin\Cms\Menu;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsMenuTypeFieldFormat implements FieldFormatInterface
{
    use TranslatorAwareTrait;


    /**
     * CmsPageTypeFieldFormat constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        return $this->getTranslator()->translate('cmsmenutype.code.' . $bean->get('CmsMenuType_Code'), 'admin');
    }

}
