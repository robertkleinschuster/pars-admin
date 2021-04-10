<?php

namespace Pars\Admin\Base;

use Laminas\I18n\Translator\TranslatorAwareTrait;
use Laminas\I18n\Translator\TranslatorInterface;
use Pars\Bean\Type\Base\BeanException;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
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
    protected ?ContextParameter $currentContext = null;
    protected ?ContextParameter $nextContext = null;
    protected ?PathHelper $pathHelperCurrent = null;

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
        $this->setPathHelper($pathHelper);
        $this->setTranslator($translator);
        $this->userBean = $userBean;
        parent::__construct();
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
    * @return ContextParameter
    */
    public function getCurrentContext(): ContextParameter
    {
        return $this->currentContext;
    }

    /**
    * @param ContextParameter $currentContext
    *
    * @return $this
    */
    public function setCurrentContext(ContextParameter $currentContext)
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
    * @return ContextParameter
    */
    public function getNextContext(): ContextParameter
    {
        return $this->nextContext;
    }

    /**
    * @param ContextParameter $nextContext
    *
    * @return $this
    */
    public function setNextContext(ContextParameter $nextContext)
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

    /**
    * @return PathHelper
    */
    public function getPathHelperCurrent(): PathHelper
    {
        return $this->pathHelperCurrent;
    }

    /**
    * @param PathHelper $pathHelperCurrent
    *
    * @return $this
    */
    public function setPathHelperCurrent(PathHelper $pathHelperCurrent): self
    {
        $this->pathHelperCurrent = $pathHelperCurrent;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasPathHelperCurrent(): bool
    {
        return isset($this->pathHelperCurrent);
    }

}
