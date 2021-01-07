<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Delete\Delete;
use Pars\Component\Base\Form\Form;
use Pars\Component\Base\Form\Submit;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;

abstract class BaseDelete extends Delete
{
    use AdminComponentTrait;

    public ?string $indexPath = null;
    public ?string $token = null;

    protected function initialize()
    {

        $indexPath = $this->getPathHelper()->setController($this->getRedirectController())->setAction($this->getRedirectAction());
        $id = new IdParameter();
        foreach ($this->getRedirectIdFields() as $key => $value) {
            if (is_string($key)) {
                $id->addId($key, $value);
            } else {
                $id->addId($value);
            }
        }
        $indexPath->addParameter($id);
        $this->setIndexPath($indexPath);
        $this->addOption('ajax');

        $this->setHeading($this->translate('delete.heading'));
        $this->setText($this->translate('delete.text'));
        parent::initialize();
        $form = new Form();
        $form->addSubmit(SubmitParameter::name(), $this->translate('delete.submit'), SubmitParameter::createDelete(), Submit::STYLE_DANGER, null, 1, 2);

        if ($this->hasIndexPath()) {
            $form->addCancel($this->translate('delete.cancel'), $this->getIndexPath(), 1, 1);
            $form->addHidden(RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH), $this->getIndexPath());
        }
        if ($this->hasToken()) {
            $form->addHidden('submit_token', $this->getToken());
        }
        $this->push($form);
    }

    abstract protected function getRedirectController(): string;

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasToken(): bool
    {
        return isset($this->token);
    }


    /**
     * @return string
     */
    public function getIndexPath(): string
    {
        return $this->indexPath;
    }

    /**
     * @param string $indexPath
     *
     * @return $this
     */
    public function setIndexPath(string $indexPath): self
    {
        $this->indexPath = $indexPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasIndexPath(): bool
    {
        return isset($this->indexPath);
    }


}
