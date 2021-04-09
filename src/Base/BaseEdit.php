<?php

namespace Pars\Admin\Base;

use Pars\Pattern\Mode\ModeAwareInterface;
use Pars\Pattern\Mode\ModeAwareTrait;
use Pars\Component\Base\Edit\Edit;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Validation\ValidationHelperAwareInterface;
use Pars\Helper\Validation\ValidationHelperAwareTrait;

abstract class BaseEdit extends Edit implements ValidationHelperAwareInterface, ModeAwareInterface, CrudComponentInterface
{
    use CrudComponentTrait;
    use ValidationHelperAwareTrait;
    use ModeAwareTrait;

    public ?string $token = null;
    public bool $create = false;
    public bool $createBulk = false;
    public bool $showSubmit = true;

    /**
     *
     */
    protected function initialize()
    {
        parent::initialize();
        $this->setName($this->translate('edit.title'));
        $this->initToken();
        $this->initFieldErrors();
        $this->initSubmitButton();
    }

    /**
     *
     */
    protected function initToken()
    {
        if ($this->hasToken()) {
            $this->getForm()->addHidden('submit_token', $this->getToken());
        }
    }

    /**
     *
     */
    protected function initFieldErrors()
    {
        foreach ($this->getForm()->getFormGroupList() as $formGroup) {
            if ($this->getValidationHelper()->hasError($formGroup->getName())) {
                $formGroup->setError($this->getValidationHelper()->getSummary($formGroup->getName()));
            }
        }
    }

    /**
     * @throws \Pars\Pattern\Exception\AttributeExistsException
     * @throws \Pars\Pattern\Exception\AttributeLockException
     */
    protected function initSubmitButton()
    {
        if ($this->isShowSubmit()) {
            if ($this->isCreateBulk()) {
                $this->getForm()->addSubmit(
                    SubmitParameter::name(),
                    $this->translate('edit.submit'),
                    (new SubmitParameter())->setCreateBulk(),
                    null,
                    '',
                    50
                );
                $this->getForm()->addHidden(
                    SubmitParameter::name(),
                    (new SubmitParameter())->setCreateBulk()
                );
            } elseif ($this->isCreate()) {
                $this->getForm()->addSubmit(
                    SubmitParameter::name(),
                    $this->translate('edit.submit'),
                    (new SubmitParameter())->setCreate(),
                    null,
                    '',
                    50
                );
                $this->getForm()->addHidden(
                    SubmitParameter::name(),
                    (new SubmitParameter())->setCreate()
                );
            } elseif ($this->hasMode()) {
                $this->getForm()->addSubmit(
                    SubmitParameter::name(),
                    $this->translate('edit.submit'),
                    (new SubmitParameter())->setMode($this->getMode()),
                    null,
                    '',
                    50
                );
                $this->getForm()->addHidden(
                    SubmitParameter::name(),
                    (new SubmitParameter())->setMode($this->getMode())
                );
            } else {
                $this->getForm()->addSubmit(
                    SubmitParameter::name(),
                    $this->translate('edit.submit'),
                    (new SubmitParameter())->setSave(),
                    null,
                    '',
                    50
                );
                $this->getForm()->addHidden(
                    SubmitParameter::name(),
                    (new SubmitParameter())->setSave()
                );
            }
            if ($this->isCreate()) {
                $this->getForm()->addCancel(
                    $this->translate('edit.cancel'),
                    $this->generateCreateRedirectPath(),
                    50,
                    2
                );
                $this->getForm()->addHidden(
                    RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH),
                    $this->generateCreateRedirectPath()
                );
            } else {
                $this->getForm()->addCancel(
                    $this->translate('edit.cancel'),
                    $this->generateIndexPath(),
                    50,
                    2
                );
                $this->getForm()->addHidden(
                    RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH),
                    $this->generateIndexPath()
                );
            }
        }
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
        $indexPath->setId(IdParameter::fromMap($this->getRedirectIdFields()));
        return $indexPath->getPath();
    }

    /**
     * @return string
     */
    protected function generateCreateRedirectPath(): string
    {
        if ($this->hasCurrentContext()) {
            return $this->getCurrentContext()->getPath();
        }
        $indexPath = $this->getPathHelper()
            ->setController($this->getRedirectController())
            ->setAction($this->getCreateRedirectAction());
        $indexPath->setId(IdParameter::fromMap($this->getCreateRedirectIdFields()));
        return $indexPath->getPath();
    }

    /**
     * @return bool
     */
    public function isCreateBulk(): bool
    {
        return $this->createBulk;
    }

    /**
     * @param bool $createBulk
     */
    public function setCreateBulk(bool $createBulk): void
    {
        $this->createBulk = $createBulk;
    }

    /**
     * @return bool
     */
    public function isShowSubmit(): bool
    {
        return $this->showSubmit;
    }

    /**
     * @param bool $showSubmit
     */
    public function setShowSubmit(bool $showSubmit): void
    {
        $this->showSubmit = $showSubmit;
    }

    /**
     * @return string
     */
    abstract protected function getRedirectController(): string;

    /**
     * @return string
     */
    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    /**
     * @return string
     */
    protected function getCreateRedirectAction(): string
    {
        return 'index';
    }

    /**
     * @return array
     */
    protected function getCreateRedirectIdFields(): array
    {
        return [];
    }

    /**
     * @return array
     */
    abstract protected function getRedirectIdFields(): array;

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
     * @return bool
     */
    public function isCreate(): bool
    {
        return $this->create;
    }

    /**
     * @param bool $create
     * @return self
     */
    public function setCreate(bool $create): self
    {
        $this->create = $create;
        return $this;
    }
}
