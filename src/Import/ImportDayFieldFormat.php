<?php

namespace Pars\Admin\Import;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class ImportDayFieldFormat implements FieldFormatInterface
{
    use TranslatorAwareTrait;


    /**
     * UserStateFieldFormat constructor.
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->setTranslator($translator);
    }

    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        if ($bean->empty('Import_Day')) {
            return $this->getTranslator()->translate('import.day.null', 'admin');
        } else {
            return $bean->get('Import_Day');
        }
    }
}
