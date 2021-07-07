<?php

namespace Pars\Admin\Import\Tesla;

use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Span;
use Pars\Import\Tesla\Authentication\OAuth2\TeslaOAuth2ClientProvider;

class TeslaImportConfigure extends BaseEdit
{
    protected function initialize()
    {
        $this->getForm()->setUseColumns(false);
        $this->getForm()->addText('tesla_authcode', '', $this->translate('tesla.authcode'));
        #$this->getForm()->addPassword('tesla_password', '', $this->translate('tesla.password'));
        $this->setMode('configure');
        $this->getForm()->setUseEvents(false);
        $provider = new TeslaOAuth2ClientProvider();
        $this->getForm()->pushField(new Span($provider->getAuthorizationUrl()));
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
