<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Overview\Overview;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\HtmlElement;

abstract class BaseOverview extends Overview
{
    use AdminComponentTrait;

    public ?string $createPath = null;
    public ?string $orderField = null;
    public bool $showEdit = true;
    public bool $showDelete = true;
    public bool $showDetail = true;
    public bool $showCreate = true;
    public ?string $section = null;

    protected function initialize()
    {
        if ($this->hasSection()) {
            $this->push(new HtmlElement('h3.mb-3', $this->getSection()));
        }

        $id = (new IdParameter());
        foreach ($this->getDetailIdFields() as $idField) {
            $id->addId($idField);
        }

        if ($this->showDetail) {
            $this->setDetailPath($this->getPathHelper()->setController($this->getController())->setAction('detail')->setId($id));
        }
        if ($this->isShowEdit()) {
            $this->setEditPath($this->getPathHelper()->setController($this->getController())->setAction('edit')->setId($id));
        }
        if ($this->showDelete) {
            $this->setDeletePath($this->getPathHelper()->setController($this->getController())->setAction('delete')->setId($id));
        }
        if ($this->isShowCreate()) {
            $id = (new IdParameter());
            foreach ($this->getCreateIdFields() as $idField) {
                $id->addId($idField);
            }

            $this->setCreatePath($this->getPathHelper()->setController($this->getController())->setAction('create')->setId($id));
        }

        if ($this->hasCreatePath()) {
            $toolbar = new HtmlElement('div.btn-toolbar.mb-4');
            $button = new Button($this->translate('create.title'), Button::STYLE_SUCCESS);
            $button->setPath($this->getCreatePath());
            $toolbar->push($button);
            $this->push($toolbar);
        }

        $moveredirectParameter = new RedirectParameter();
        $moveredirectPath = $this->getPathHelper()->setController($this->getMoveRedirectController())->setAction($this->getMoveRedirectAction());
        $moveRedirectIdParamter = new IdParameter();
        foreach ($this->getMoveRedirectIdFields() as $moveRedirectIdField) {
            $moveRedirectIdParamter->addId($moveRedirectIdField);
        }
        $moveredirectPath->setId($moveRedirectIdParamter);
        $moveredirectParameter->setPath($moveredirectPath->getPath());

        if ($this->hasOrderField()) {
            $this->setMoveUpPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
                ->setId((new IdParameter())->addId($this->getOrderField()))
                ->addParameter((new MoveParameter())->setUp($this->getOrderField()))
                ->addParameter($moveredirectParameter));
            $this->setMoveDownPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
                ->setId((new IdParameter())->addId($this->getOrderField()))
                ->addParameter((new MoveParameter())->setDown($this->getOrderField()))
                ->addParameter($moveredirectParameter));
        }
        parent::initialize();
    }

    protected function getMoveRedirectController(): string
    {
        return $this->getController();
    }

    protected function getMoveRedirectAction(): string
    {
        return 'index';
    }

    protected function getMoveRedirectIdFields(): array
    {
        return [
        ];
    }

    /**
    * @return string
    */
    public function getSection(): string
    {
        return $this->section;
    }

    /**
    * @param string $section
    *
    * @return $this
    */
    public function setSection(string $section): self
    {
        $this->section = $section;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasSection(): bool
    {
        return isset($this->section);
    }


    abstract protected function getController(): string;
    abstract protected function getDetailIdFields(): array;
    protected function getCreateIdFields(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isShowCreate(): bool
    {
        return $this->showCreate;
    }

    /**
     * @param bool $showCreate
     * @return BaseOverview
     */
    public function setShowCreate(bool $showCreate): BaseOverview
    {
        $this->showCreate = $showCreate;
        return $this;
    }


    /**
    * @return string
    */
    public function getOrderField(): string
    {
        return $this->orderField;
    }

    /**
    * @param string $orderField
    *
    * @return $this
    */
    public function setOrderField(string $orderField): self
    {
        $this->orderField = $orderField;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasOrderField(): bool
    {
        return isset($this->orderField);
    }


    /**
     * @return bool
     */
    public function isShowDelete(): bool
    {
        return $this->showDelete;
    }

    /**
     * @param bool $showDelete
     * @return BaseOverview
     */
    public function setShowDelete(bool $showDelete): BaseOverview
    {
        $this->showDelete = $showDelete;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowDetail(): bool
    {
        return $this->showDetail;
    }

    /**
     * @param bool $showDetail
     * @return BaseOverview
     */
    public function setShowDetail(bool $showDetail): BaseOverview
    {
        $this->showDetail = $showDetail;
        return $this;
    }


    /**
     * @return bool
     */
    public function isShowEdit(): bool
    {
        return $this->showEdit;
    }

    /**
     * @param bool $showEdit
     * @return BaseOverview
     */
    public function setShowEdit(bool $showEdit): BaseOverview
    {
        $this->showEdit = $showEdit;
        return $this;
    }


    /**
    * @return string
    */
    public function getCreatePath(): string
    {
        return $this->createPath;
    }

    /**
    * @param string $createPath
    *
    * @return $this
    */
    public function setCreatePath(string $createPath): self
    {
        $this->createPath = $createPath;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasCreatePath(): bool
    {
        return isset($this->createPath);
    }


}
