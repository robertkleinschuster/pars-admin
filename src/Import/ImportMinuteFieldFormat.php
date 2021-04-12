<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class ImportMinuteFieldFormat implements FieldFormatInterface
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
        if ($bean->empty('Import_Minute')) {
            return $this->getTranslator()->translate('import.minute.null');
        } else {
            return $bean->get('Import_Minute');
        }
    }
}
