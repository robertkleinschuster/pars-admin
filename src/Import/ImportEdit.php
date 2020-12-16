<?php


namespace Pars\Admin\Import;


use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Import\Type\ImportTypeBeanList;

class ImportEdit extends BaseEdit
{
    protected ?array $importTypeOptions = null;

    protected function initialize()
    {
        $this->getForm()->addText('Import_Name', '{Import_Name}', $this->translate('import.name'), 1);
        if (null !== $this->getImportTypeOptions()) {
            $this->getForm()->addSelect('ImportType_Code', $this->getImportTypeOptions() ,'{ImportType_Code}', $this->translate('importtype.code'));
        }
        $this->getForm()->addCheckbox('Import_Active', '{Import_Active}', $this->translate('import.active'), 2);
        $days = [];
        $days[null] = $this->translate('import.day.null');
        for ($i = 1; $i <= 7; $i++) {
            $days[$i] = $i;
        }
        $hours = [];
        $hours[null] = $this->translate('import.hour.null');
        for ($i = 0; $i <= 23; $i++) {
            $hours[$i] = $i;
        }
        $minutes = [];
        $minutes[null] = $this->translate('import.minute.null');
        for ($i = 0; $i <= 59; $i++) {
            $minutes[$i] = $i;
        }
        $this->getForm()->addSelect('Import_Day', $days, '{Import_Day}', $this->translate('import.day'), 3, 1);
        $this->getForm()->addSelect('Import_Hour', $hours, '{Import_Hour}', $this->translate('import.hour'), 3, 2);
        $this->getForm()->addSelect('Import_Minute', $minutes, '{Import_Minute}', $this->translate('import.minute'), 3, 3);
        parent::initialize();
    }

    /**
     * @return array|null
     */
    public function getImportTypeOptions(): ?array
    {
        return $this->importTypeOptions;
    }

    /**
     * @param array|null $importTypeOptions
     */
    public function setImportTypeOptions(?array $importTypeOptions): void
    {
        $this->importTypeOptions = $importTypeOptions;
    }




    protected function getRedirectController(): string
    {
        return 'import';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Import_ID'];
    }

}
