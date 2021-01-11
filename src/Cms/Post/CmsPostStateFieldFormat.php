<?php


namespace Pars\Admin\Cms\Post;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsPostStateFieldFormat implements FieldFormatInterface
{
    use TranslatorAwareTrait;


    /**
     * CmsPostTypeFieldFormat constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        switch ($bean->get('CmsPostState_Code')) {
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
        return $this->getTranslator()->translate('cmspoststate.code.' . $bean->get('CmsPostState_Code'), 'admin');
    }

}
