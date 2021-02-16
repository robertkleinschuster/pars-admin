<?php


namespace Pars\Admin\Update;


use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Admin\Base\BaseEdit;
use Pars\Core\Database\Updater\AbstractUpdater;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Path\PathHelper;
use Pars\Model\Authentication\User\UserBean;

class Update extends BaseEdit
{


    public function __construct(PathHelper $pathHelper, TranslatorInterface $translator, UserBean $userBean, AbstractUpdater $updater = null)
    {
        parent::__construct($pathHelper, $translator, $userBean);
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
                ->setLabel($key . ': <br><pre>' . json_encode($item, JSON_PRETTY_PRINT) . '</pre>');
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
