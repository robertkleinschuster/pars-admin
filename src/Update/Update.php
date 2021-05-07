<?php

namespace Pars\Admin\Update;

use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Admin\Base\BaseEdit;
use Pars\Core\Database\Updater\AbstractDatabaseUpdater;
use Pars\Core\Translation\ParsTranslator;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Path\PathHelper;
use Pars\Model\Authentication\User\UserBean;

class Update extends BaseEdit
{


    public function __construct(ParsTranslator $translator, UserBean $userBean, AbstractDatabaseUpdater $updater = null)
    {
        parent::__construct($translator, $userBean);
        $this->updater = $updater;
    }


    protected function initialize()
    {
        $this->setShowSubmit(false);
        $previewList = $this->updater->getPreviewMap();
        $this->getForm()->addSubmit(SubmitParameter::name(), $this->translate('update.submit'), (new SubmitParameter())->setMode($this->updater->getCode()));
        $this->getForm()->addHidden(RedirectParameter::name(), (new RedirectParameter())->setPath(
            $this->getPathHelper()->setController('update')->setAction($this->updater->getCode())
        ));
        foreach ($previewList as $key => $item) {
            $this->getForm()->addCheckbox($key, '')->setValue('true')
                ->setLabel('<pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>')
            ->setGroup($key);
        }
        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return 'update';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }

    protected function getRedirectAction(): string
    {
        return $this->updater->getCode();
    }
}
