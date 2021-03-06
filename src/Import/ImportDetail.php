<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\BaseDetail;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Toolbar\ConfigureButton;
use Pars\Component\Base\Toolbar\RunButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

class ImportDetail extends BaseDetail
{
    protected function initName()
    {
        $this->setName('{Import_Name}');
    }

    protected function initialize()
    {
        parent::initialize();
        $this->initConfigureButton();
        $this->initRunButton();
    }

    protected function initConfigureButton()
    {
        $editPathHelper = clone $this->generateEditPathHelper();
        $this->getToolbar()->push(new ConfigureButton(
            $editPathHelper
                ->setController($this->getIndexController())
                ->setAction('configure')
                ->getPath()
        ));
    }

    protected function initRunButton()
    {
        $editPathHelper = clone $this->getPathHelper()
            ->setController($this->getEditController())
            ->setAction($this->getEditAction())
            ->setId(IdParameter::fromMap($this->getEditIdFields()));

        $redirect = $editPathHelper->setController($this->getIndexController())
            ->setAction('detail');
        if ($this->hasCurrentContext()) {
            $redirect->addParameter($this->getCurrentContext());
        }
        $redirect = (new RedirectParameter())
            ->setPath($redirect->getPath());
        $this->getToolbar()->push(new RunButton(
            $editPathHelper
                ->setController($this->getIndexController())
                ->setAction('run')
                ->addParameter($redirect)->getPath()
        ));
    }

    protected function initFields()
    {
        parent::initFields();
        $this->addSpan('Import_Name', $this->translate('import.name'));
        $this->addSpan('Import_Day', $this->translate('import.day'))
            ->setFormat(new ImportDayFieldFormat($this->getTranslator()));
        $this->addSpan('Import_Hour', $this->translate('import.hour'))
            ->setFormat(new ImportHourFieldFormat($this->getTranslator()));
        $this->addSpan('Import_Minute', $this->translate('import.minute'))
            ->setFormat(new ImportMinuteFieldFormat($this->getTranslator()));

        $active = new Badge('{Import_Active}');
        $active->setLabel($this->translate('import.active'));
        $active->setFormat(new ImportActiveFieldFormat($this->getTranslator()));
        $this->pushField($active);
        $this->addSpan('ImportData_IntValue1_AVG', $this->translate('import.avg1'));
        $this->addSpan('ImportData_IntValue2_AVG', $this->translate('import.avg2'));
        $this->addSpan('ImportData_IntValue3_AVG', $this->translate('import.avg3'));
        $this->addLastUpdateField();
    }

    protected function addLastUpdateField()
    {
        $this->addSpan('Import_Data[last_update]', $this->translate('import.data.last_update'))->setFormat(
            new class implements FieldFormatInterface {
                public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
                {
                    if ($bean->isset('Import_Data')) {
                        $data = $bean->get('Import_Data');
                        if (isset($data['last_update'])) {
                            $date = new \DateTime(
                                $data['last_update']['date'],
                                new \DateTimeZone($data['last_update']['timezone']
                                )
                            );
                            return $date->format('d.m.Y H:i:s');
                        }
                    }
                    return '';
                }
            }
        );
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
