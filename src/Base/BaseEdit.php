<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Edit\Edit;
use Pars\Helper\Validation\ValidationHelperAwareInterface;
use Pars\Helper\Validation\ValidationHelperAwareTrait;

class BaseEdit extends Edit implements ValidationHelperAwareInterface
{
    use AdminComponentTrait;
    use ValidationHelperAwareTrait;

    public ?string $token = null;

    protected function initialize()
    {
        parent::initialize();
        if ($this->hasToken()) {
            $this->getForm()->addHidden('submit_token', $this->getToken());
        }
        foreach ($this->getForm()->getFormGroupList() as $formGroup) {
            if ($this->getValidationHelper()->hasError($formGroup->getName())) {
                $formGroup->setError($this->getValidationHelper()->getSummary($formGroup->getName()));
            }
        }
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

}
