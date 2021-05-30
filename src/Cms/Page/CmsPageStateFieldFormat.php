<?php

namespace Pars\Admin\Cms\Page;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class CmsPageStateFieldFormat implements FieldFormatInterface
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
        $field->setTooltip($this->getTranslator()->translate('cmspagestate.code.' . $bean->get('CmsPageState_Code')));
        switch ($bean->get('CmsPageState_Code')) {
            case 'active':
                if ($field instanceof StyleAwareInterface) {
                    $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
                }
                return Icon::eye(true);
            case 'inactive':
                if ($field instanceof StyleAwareInterface) {
                    $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
                }
                return Icon::eye(false);
        }
    }
}
