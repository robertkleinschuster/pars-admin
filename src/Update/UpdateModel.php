<?php

namespace Pars\Admin\Update;

use Pars\Admin\Base\BaseModel;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Updater\Database\DataDatabaseUpdater;
use Pars\Model\Updater\Database\SchemaDatabaseUpdater;
use Pars\Model\Updater\Database\SpecialDatabaseUpdater;

class UpdateModel extends BaseModel
{

    public const OPTION_SCHEMA_ALLOWED = 'schema_allowed';
    public const OPTION_DATA_ALLOWED = 'data_allowed';
    public const OPTION_SPECIAL_ALLOWED = 'special_allowed';


    public function getSchemaUpdater()
    {
        return new SchemaDatabaseUpdater($this->getDbAdpater());
    }



    public function getDataUpdater()
    {
        return new DataDatabaseUpdater($this->getDbAdpater());
    }

    public function getSpecialUpdater()
    {
        return new SpecialDatabaseUpdater($this->getDbAdpater());
    }

    public function getConfigValue(string $key = null)
    {
        return null;
    }


    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param array $attribute_List
     * @throws \Pars\Pattern\Exception\AttributeNotFoundException
     */
    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, IdListParameter $idListParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case 'schema':
                if ($this->hasOption(self::OPTION_SCHEMA_ALLOWED)) {
                    $schemaUpdater = new SchemaDatabaseUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'data':
                if ($this->hasOption(self::OPTION_DATA_ALLOWED)) {
                    $schemaUpdater = new DataDatabaseUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'special':
                if ($this->hasOption(self::OPTION_SPECIAL_ALLOWED)) {
                    $schemaUpdater = new SpecialDatabaseUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
        }
    }
}
