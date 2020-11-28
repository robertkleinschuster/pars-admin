<?php


namespace Pars\Admin\Cms\Paragraph;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsParagraphTypeFieldFormat implements FieldFormatInterface
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
        return $this->getTranslator()->translate('cmsparagraphtype.code.' . $bean->get('CmsParagraphType_Code'), 'admin');
    }

}
