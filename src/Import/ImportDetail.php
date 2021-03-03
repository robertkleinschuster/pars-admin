<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Toolbar\ConfigureButton;
use Pars\Component\Base\Toolbar\RunButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;

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

        $id = (new IdParameter());
        foreach ($this->getEditIdFields() as $idField) {
            $id->addId($idField);
        }

        $redirect = $this->getPathHelper()->setController($this->getIndexController())->setAction('detail')->setId($id)->getPath();

        $this->getToolbar()->push(new ConfigureButton(
            $this->getPathHelper()
                ->setController($this->getIndexController())
                ->setAction('configure')
                ->setId($id)
                ->getPath()
        ));
        $this->getToolbar()->push(new RunButton(
            $this->getPathHelper()
                ->setController($this->getIndexController())
                ->setAction('run')
                ->setId($id)
                ->addParameter((new RedirectParameter())->setPath($redirect))->getPath()
        ));
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
