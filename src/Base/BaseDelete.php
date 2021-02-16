<?php


namespace Pars\Admin\Base;


use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
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

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initialize()
    {
        $this->setHeading($this->translate('delete.heading'));
        $this->setText($this->translate('delete.text'));
        parent::initialize();
        $form = new Form();
        $form->addSubmit(
            SubmitParameter::name(),
            $this->translate('delete.submit'),
            SubmitParameter::createDelete(),
            Submit::STYLE_DANGER,
            null,
            1,
            2
        );
        $form->addHidden(SubmitParameter::name(), SubmitParameter::createDelete());
        $form->addCancel($this->translate('delete.cancel'), $this->generateIndexPath());
        $form->addHidden(RedirectParameter::nameAttr(RedirectParameter::ATTRIBUTE_PATH), $this->generateIndexPath());
        if ($this->hasToken()) {
            $form->addHidden('submit_token', $this->getToken());
        }
        $this->push($form);
    }


    /**
     * @return string
     */
    protected function generateIndexPath(): string
    {
        $indexPath = $this->getPathHelper()
            ->setController($this->getRedirectController())
            ->setAction($this->getRedirectAction());
        $id = new IdParameter();
        foreach ($this->getRedirectIdFields() as $key => $value) {
            if (is_string($key)) {
                $id->addId($key, $value);
            } else {
                $id->addId($value);
            }
        }
        $indexPath->addParameter($id);
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
