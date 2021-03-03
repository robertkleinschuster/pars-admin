<?php

namespace Pars\Admin\Import;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class ImportHourFieldFormat implements FieldFormatInterface
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
        if ($bean->empty('Import_Hour')) {
            return $this->getTranslator()->translate('import.hour.null', 'admin');
        } else {
            return $bean->get('Import_Hour');
        }
    }
}
