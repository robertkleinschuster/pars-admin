<?php


namespace Pars\Admin\Import\Tesla;


use Pars\Admin\Base\BaseEdit;

class TeslaImportConfigure extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->addText('tesla_username', '', $this->translate('tesla.username'));
        $this->getForm()->addPassword('tesla_password', '', $this->translate('tesla.password'));
        $this->setMode('configure');
        parent::initialize();
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
