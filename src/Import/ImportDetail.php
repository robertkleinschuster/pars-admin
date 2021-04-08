<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Toolbar\ConfigureButton;
use Pars\Component\Base\Toolbar\RunButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\FieldFormatInterface;
use Pars\Mvc\View\FieldInterface;

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
        $this->addField('Import_Data[last_update]', $this->translate('import.data.last_update'))->setFormat(
            new class implements FieldFormatInterface {
                public function __invoke(FieldInterface $field, string $value, ?BeanInterface $bean = null): string
                {
                    if ($bean->isset('Import_Data')) {
                        $data = $bean->get('Import_Data');
                        if (isset($data['last_update'])) {
                            $date = new \DateTime(
                                $data['last_update']->date,
                                new \DateTimeZone($data['last_update']->timezone
                                )
                            );
                            return $date->format('d.m.Y H:i:s');
                        }
                    }
                    return '';
                }
            }
        );
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
