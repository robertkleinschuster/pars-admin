<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;

class ImportDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.import'));
        $this->addField('Import_Name', $this->translate('import.name'));
        $this->addField('Import_Day', $this->translate('import.day'))
            ->setFormat(new ImportDayFieldFormat($this->getTranslator()));
        $this->addField('Import_Hour', $this->translate('import.hour'))
            ->setFormat(new ImportHourFieldFormat($this->getTranslator()));
        $this->addField('Import_Minute', $this->translate('import.minute'))
            ->setFormat(new ImportMinuteFieldFormat($this->getTranslator()));
        $active = new Badge('{Import_Active}');
        $active->setLabel($this->translate('import.active'));
        $active->setFormat(new ImportActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'import';
    }

    protected function getEditIdFields(): array
    {
        return ['Import_ID'];
    }

}
