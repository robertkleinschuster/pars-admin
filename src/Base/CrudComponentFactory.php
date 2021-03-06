<?php

namespace Pars\Admin\Base;

use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareInterface;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Helper\Path\PathHelper;
use Pars\Helper\Path\PathHelperAwareInterface;
use Pars\Helper\Path\PathHelperAwareTrait;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\Exception\MvcException;
use Pars\Mvc\View\AbstractComponent;

class CrudComponentFactory implements ParsTranslatorAwareInterface
{
    protected const CLASS_EDIT = 'edit';
    protected const CLASS_DETAIL = 'detail';
    protected const CLASS_DELETE = 'delete';
    protected const CLASS_OVERVIEW = 'overview';

    use ParsTranslatorAwareTrait;

    protected UserBean $userBean;
    protected string $controller;

    protected array $class_Map = [];

    /**
     * CrudComponentFactory constructor.
     * @param UserBean $userBean
     * @param ParsTranslator $translator
     */
    public function __construct(UserBean $userBean, ParsTranslator $translator)
    {
        $this->userBean = $userBean;
        $this->setTranslator($translator);
    }

    /**
     * @return UserBean
     */
    protected function getUserBean(): UserBean
    {
        return $this->userBean;
    }

    /**
     * @param string $key
     * @return string
     * @throws MvcException
     */
    protected function getClass(string $key): string
    {
        if (isset($this->class_Map[$key])) {
            return $this->class_Map[$key];
        }
        throw new MvcException('No component class for ' . $key);
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setOverview(string $class): self
    {
        $this->class_Map[self::CLASS_OVERVIEW] = $class;
        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setDetail(string $class): self
    {
        $this->class_Map[self::CLASS_DETAIL] = $class;
        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setDelete(string $class): self
    {
        $this->class_Map[self::CLASS_DELETE] = $class;
        return $this;
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setEdit(string $class): self
    {
        $this->class_Map[self::CLASS_EDIT] = $class;
        return $this;
    }

    /**
     * @param string $class
     * @return CrudComponentInterface|AbstractComponent
     */
    public function create(string $class)
    {
        return new $class($this->getTranslator(), $this->getUserBean());
    }

    /**
     * @return BaseOverview
     * @throws MvcException
     */
    public function createOverview(): BaseOverview
    {
        return $this->create($this->getClass(self::CLASS_OVERVIEW));
    }

    /**
     * @return BaseDelete
     * @throws MvcException
     */
    public function createDelete(): BaseDelete
    {
        return $this->create($this->getClass(self::CLASS_DELETE));
    }

    /**
     * @return BaseEdit
     * @throws MvcException
     */
    public function createEdit(): BaseEdit
    {
        return $this->create($this->getClass(self::CLASS_EDIT));
    }

    /**
     * @return BaseDetail
     * @throws MvcException
     */
    public function createDetail(): BaseDetail
    {
        return $this->create($this->getClass(self::CLASS_DETAIL));
    }
}
