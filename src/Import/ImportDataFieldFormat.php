<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class ImportDataFieldFormat implements FieldFormatInterface
{
    public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
    {
        return '<pre>' . json_encode($bean->get('Import_Data')['data'], JSON_PRETTY_PRINT) . '</pre>';
    }
}
