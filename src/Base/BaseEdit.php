<?php


namespace Pars\Admin\Base;


use Niceshops\Core\Mode\ModeAwareInterface;
use Niceshops\Core\Mode\ModeAwareTrait;
use Pars\Component\Base\Edit\Edit;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Validation\ValidationHelperAwareInterface;
use Pars\Helper\Validation\ValidationHelperAwareTrait;

abstract class BaseEdit extends Edit implements ValidationHelperAwareInterface, ModeAwareInterface
{
    use AdminComponentTrait;
    use ValidationHelperAwareTrait;
    use ModeAwareTrait;

    public ?string $token = null;
    public ?string $indexPath = null;
    public bool $create = false;
    public bool $createBulk = false;

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

        parent::initialize();
        if ($this->hasToken()) {
            $this->getForm()->addHidden('submit_token', $this->getToken());
        }
        foreach ($this->getForm()->getFormGroupList() as $formGroup) {
            if ($this->getValidationHelper()->hasError($formGroup->getName())) {
                $formGroup->setError($this->getValidationHelper()->getSummary($formGroup->getName()));
            }
        }
        if ($this->isCreateBulk()) {
            $this->getForm()->addSubmit(SubmitParameter::name(), $this->translate('edit.submit'), (new SubmitParameter())->setCreateBulk(), null, '', 50, 1);
            $this->getForm()->addHidden(SubmitParameter::name(), (new SubmitParameter())->setCreateBulk());
        } elseif ($this->isCreate()) {
            $this->getForm()->addSubmit(SubmitParameter::name(), $this->translate('edit.submit'), (new SubmitParameter())->setCreate(), null, '', 50, 1);
            $this->getForm()->addHidden(SubmitParameter::name(), (new SubmitParameter())->setCreate());
        } elseif ($this->hasMode()) {
            $this->getForm()->addSubmit(SubmitParameter::name(), $this->translate('edit.submit'), (new SubmitParameter())->setMode($this->getMode()), null, '', 50, 1);
            $this->getForm()->addHidden(SubmitParameter::name(), (new SubmitParameter())->setMode($this->getMode()));
        } else {
            $this->getForm()->addSubmit(SubmitParameter::name(), $this->translate('edit.submit'), (new SubmitParameter())->setSave(), null, '', 50, 1);
            $this->getForm()->addHidden(SubmitParameter::name(), (new SubmitParameter())->setSave());
        }
        if ($this->hasIndexPath()) {
            if ($this->isCreate()) {
                $id = new IdParameter();
                foreach ($this->getCreateRedirectIdFields() as $key => $value) {
                    if (is_string($key)) {
                        $id->addId($key, $value);
                    } else {
                        $id->addId($value);
                    }
                }
                $indexPath = $this->getPathHelper()->setController($this->getRedirectController())->setAction($this->getCreateRedirectAction());
                $indexPath->addParameter($id);
                $this->getForm()->addCancel($this->translate('edit.cancel'), $indexPath, 50, 2);
                $this->getForm()->addHidden(RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH), $indexPath);

            } else {
                $this->getForm()->addCancel($this->translate('edit.cancel'), $this->getIndexPath(), 50, 2);
                $this->getForm()->addHidden(RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH), $this->getIndexPath());

            }
        }
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


    abstract protected function getRedirectController(): string;

    protected function getRedirectAction(): string
    {
        return 'detail';
    }

    protected function getCreateRedirectAction(): string
    {
        return 'index';
    }

    protected function getCreateRedirectIdFields(): array
    {
        return [];
    }

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
