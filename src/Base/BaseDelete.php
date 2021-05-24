<?php

namespace Pars\Admin\Base;

use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Component\Base\Delete\Delete;
use Pars\Component\Base\Form\Form;
use Pars\Component\Base\Form\Submit;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;

abstract class BaseDelete extends Delete implements CrudComponentInterface
{
    use CrudComponentTrait;

    public ?string $token = null;
    public ?string $tokenName = null;

    protected function initBase()
    {
        parent::initBase();
        $this->setHeading($this->translate('delete.heading'));
        $this->setText($this->translate('delete.text'));
    }


    protected function initFields()
    {
        parent::initFields();
        $form = new Form();
        $form->addSubmit(
            SubmitParameter::name(),
            $this->translate('delete.submit'),
            SubmitParameter::delete(),
            Submit::STYLE_DANGER,
            null
        );
        $form->addHidden(SubmitParameter::name(), SubmitParameter::delete());
        $form->addCancel($this->translate('delete.cancel'), $this->generateIndexPath());
        $form->addHidden(RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH), $this->generateRedirectPath());
        if ($this->hasToken() && $this->hasTokenName()) {
            $form->addHidden($this->getTokenName(), $this->getToken());
        }
        $this->push($form);
    }


    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('delete.title'));
    }


    /**
     * @return string
     */
    protected function generateIndexPath(): string
    {
        if ($this->hasCurrentContext()) {
            return $this->getCurrentContext()->getPath();
        }
        $indexPath = $this->getPathHelper()
            ->setController($this->getRedirectController())
            ->setAction($this->getRedirectAction());
        $indexPath->addParameter(IdParameter::fromMap($this->getRedirectIdFields()));
        return $indexPath->getPath();
    }

    /**
     * @return string
     */
    protected function generateRedirectPath(): string
    {
        if ($this->hasCurrentContext()) {
            return $this->getCurrentContext()->getPath();
        }
        $indexPath = $this->getPathHelper()
            ->setController($this->getRedirectController())
            ->setAction($this->getRedirectAction());
        $indexPath->addParameter(IdParameter::fromMap($this->getRedirectIdFields()));
        return $indexPath->getPath();
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
    public function setToken(string $tokenName, string $token): self
    {
        $this->setTokenName($tokenName);
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
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
    * @param string $tokenName
    *
    * @return $this
    */
    public function setTokenName(string $tokenName): self
    {
        $this->tokenName = $tokenName;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasTokenName(): bool
    {
        return isset($this->tokenName);
    }

}
