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
    protected function initialize()
    {
        $id = (new IdParameter());
        foreach ($this->getDetailIdFields() as $idField) {
            $id->addId($idField);
        }
        $redirect = $this->getPathHelper()->setController($this->getRedirectController())->setAction($this->getRedirectAction())->getPath();
        $this->append(new ConfigureButton($this->getPathHelper()->setController($this->getController())->setAction('configure')->setId($id)->getPath()));
        $this->append(new RunButton($this->getPathHelper()
            ->setController($this->getController())->setAction('run')
            ->setId($id)->addParameter((new RedirectParameter())->setPath(
                $redirect
            ))->getPath()));
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
