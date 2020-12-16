<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;

class ImportOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.import'));
        $this->addField('Import_Name', $this->translate('import.name'));
        $this->addField('Import_Day', $this->translate('import.day'));
        $this->addField('Import_Hour', $this->translate('import.hour'));
        $this->addField('Import_Minute', $this->translate('import.minute'));
        $active = new Badge('{Import_Active}');
        $active->setFormat(new ImportActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        parent::initialize();
    }


    protected function getController(): string
    {
        return 'import';
    }

    protected function getDetailIdFields(): array
    {
        return ['Import_ID'];
    }

}
