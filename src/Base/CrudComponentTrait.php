<?php


namespace Pars\Admin\Base;


use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareTrait;
use Pars\Model\Authentication\User\UserBean;

/**
 * Trait CrudComponentTrait
 * @package Pars\Admin\Base
 */
trait CrudComponentTrait
{
    use TranslatorAwareTrait;
    use PathHelperAwareTrait;

    private UserBean $userBean;
    protected ?string $currentContext = null;
    protected ?string $nextContext = null;
    /**
     * MainNavigation constructor.
     * @param PathHelper $pathHelper
     * @param TranslatorInterface $translator
     * @param UserBean $userBean
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    public function __construct(
        PathHelper $pathHelper,
        TranslatorInterface $translator,
        UserBean $userBean
    ) {
        parent::__construct();
        $this->setPathHelper($pathHelper);
        $this->setTranslator($translator);
        $this->userBean = $userBean;
    }

    /**
     * @return UserBean
     */
    public function getUserBean(): UserBean
    {
        return $this->userBean;
    }


    protected function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'admin');
    }

    /**
    * @return string
    */
    public function getCurrentContext(): string
    {
        return $this->currentContext;
    }

    /**
    * @param string $currentContext
    *
    * @return $this
    */
    public function setCurrentContext(string $currentContext)
    {
        $this->currentContext = $currentContext;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasCurrentContext(): bool
    {
        return isset($this->currentContext);
    }

    /**
    * @return string
    */
    public function getNextContext(): string
    {
        return $this->nextContext;
    }

    /**
    * @param string $nextContext
    *
    * @return $this
    */
    public function setNextContext(string $nextContext): self
    {
        $this->nextContext = $nextContext;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasNextContext(): bool
    {
        return isset($this->nextContext);
    }


}
