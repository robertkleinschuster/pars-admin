<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Overview\ConfigureButton;
use Pars\Component\Base\Overview\RunButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;

class ImportOverview extends BaseOverview
{
    protected function initName()
    {
        $this->setName($this->translate('section.import'));
    }

    protected function initFields()
    {
        $id = (new IdParameter());
        foreach ($this->getDetailIdFields() as $idField) {
            $id->addId($idField);
        }
        $redirect = $this->getPathHelper()->setController($this->getRedirectController())->setAction($this->getRedirectAction())->getPath();
        $this->pushField(new ConfigureButton($this->getPathHelper()->setController($this->getController())->setAction('configure')->setId($id)->getPath()));
        $this->pushField(new RunButton($this->getPathHelper()
            ->setController($this->getController())->setAction('run')
            ->setId($id)->addParameter((new RedirectParameter())->setPath(
                $redirect
            ))->getPath()));
        $this->addField('Import_Name', $this->translate('import.name'));
        $active = new Badge('{Import_Active}');
        $active->setFormat(new ImportActiveFieldFormat($this->getTranslator()));
        $this->pushField($active);
        $span = new Span();
        $span->setLabel($this->translate('import.day'));
        $span->setFormat(new ImportDayFieldFormat($this->getTranslator()));
        $this->pushField($span);
        $span = new Span();
        $span->setLabel($this->translate('import.hour'));
        $span->setFormat(new ImportHourFieldFormat($this->getTranslator()));
        $this->pushField($span);
        $span = new Span();
        $span->setLabel($this->translate('import.minute'));
        $span->setFormat(new ImportMinuteFieldFormat($this->getTranslator()));
        $this->pushField($span);
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
