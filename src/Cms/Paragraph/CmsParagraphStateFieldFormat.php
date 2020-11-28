<?php


namespace Pars\Admin\Cms\Paragraph;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsParagraphStateFieldFormat implements FieldFormatInterface
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
        switch ($bean->get('CmsParagraphState_Code')) {
            case 'active':
                if ($field instanceof StyleAwareInterface) {
                    $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
                }
                break;
            case 'inactive':
                if ($field instanceof StyleAwareInterface) {
                    $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
                }
                break;
        }
        return $this->getTranslator()->translate('cmsparagraphstate.code.' . $bean->get('CmsParagraphState_Code'), 'admin');
    }
}
