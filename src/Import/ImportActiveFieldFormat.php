<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\StyleAwareInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

/**
 * Class ImportActiveFieldFormat
 * @package Pars\Admin\Import
 */
class ImportActiveFieldFormat implements FieldFormatInterface
{
    use ParsTranslatorAwareTrait;

    /**
     * UserStateFieldFormat constructor.
     */
    public function __construct(ParsTranslator $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean->get('Import_Active') == 'true') {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SUCCESS);
            }
            return $this->getTranslator()->translate('import.active.true');
        } else {
            if ($field instanceof StyleAwareInterface) {
                $field->setStyle(StyleAwareInterface::STYLE_SECONDARY);
            }
            return $this->getTranslator()->translate('import.active.false');
        }
    }
}
