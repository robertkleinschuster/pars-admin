<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class ImportOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setSection($this->translate('section.import'));
        $this->addField('Import_Name', $this->translate('import.name'));
        $active = new Badge('{Import_Active}');
        $active->setFormat(new ImportActiveFieldFormat($this->getTranslator()));
        $this->append($active);
        parent::initialize();
        $span = new Span();
        $span->setLabel($this->translate('import.day'));
        $span->setFormat(new ImportDayFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span();
        $span->setLabel($this->translate('import.hour'));
        $span->setFormat(new ImportHourFieldFormat($this->getTranslator()));
        $this->append($span);
        $span = new Span();
        $span->setLabel($this->translate('import.minute'));
        $span->setFormat(new ImportMinuteFieldFormat($this->getTranslator()));
        $this->append($span);
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
