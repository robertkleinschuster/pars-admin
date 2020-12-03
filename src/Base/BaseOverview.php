<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Form\Input;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Toolbar\Toolbar;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Mvc\View\HtmlElement;

abstract class BaseOverview extends Overview
{
    use AdminComponentTrait;

    public ?string $createPath = null;
    public bool $showEdit = true;
    public bool $showEditBulk = false;
    public bool $showDelete = true;
    public bool $showDeleteBulk = true;
    public bool $showDetail = true;
    public bool $showCreate = true;
    public bool $showCreateBulk = false;
    public bool $showMove = false;
    public ?string $section = null;
    public ?string $token = null;

    protected function initialize()
    {
        if ($this->hasToken()) {
            $input = new Input(Input::TYPE_HIDDEN);
            $input->setName('submit_token');
            $input->setValue($this->getToken());
            $this->push($input);
        }

        $moveredirectParameter = new RedirectParameter();
        $moveredirectPath = $this->getPathHelper()->setController($this->getRedirectController())->setAction($this->getRedirectAction());
        $moveRedirectIdParamter = new IdParameter();
        foreach ($this->getRedirectIdFields() as $moveRedirectIdField) {
            $moveRedirectIdParamter->addId($moveRedirectIdField);
        }
        $moveredirectPath->setId($moveRedirectIdParamter);
        $moveredirectParameter->setPath($moveredirectPath->getPath());

        $redirect = new Input(Input::TYPE_HIDDEN);
        $redirect->setName(RedirectParameter::name());
        $redirect->setValue($moveredirectParameter);
        $this->push($redirect);

        $this->setBulkFieldName(IdListParameter::name());
        $idList = new IdListParameter();
        foreach ($this->getDetailIdFields() as $idField) {
            $idList->addId($idField);
        }
        $this->setBulkFieldValue($idList);

        if ($this->hasSection()) {
            $this->push(new HtmlElement('h3.mb-3', $this->getSection()));
        }

        $id = (new IdParameter());
        foreach ($this->getDetailIdFields() as $idField) {
            $id->addId($idField);
        }

        if ($this->isShowDetail()) {
            $this->setDetailPath($this->getPathHelper()->setController($this->getController())->setAction('detail')->setId($id));
        }
        if ($this->isShowEdit()) {
            $this->setEditPath($this->getPathHelper()->setController($this->getController())->setAction('edit')->setId($id));
        }
        if ($this->isShowDelete()) {
            $this->setDeletePath($this->getPathHelper()->setController($this->getController())->setAction('delete')->setId($id));
        }
        if ($this->isShowCreate()) {
            $id = (new IdParameter());
            foreach ($this->getCreateIdFields() as $idField) {
                $id->addId($idField);
            }
            $this->setCreatePath($this->getPathHelper()->setController($this->getController())->setAction('create')->setId($id));
        }


        if ($this->isShowCreateBulk()) {
            $id = (new IdParameter());
            foreach ($this->getCreateIdFields() as $idField) {
                $id->addId($idField);
            }
            $toolbar = new HtmlElement('div.btn-toolbar.mb-4');
            $button = new Button($this->translate('create_bulk.button'), Button::STYLE_SUCCESS);
            $button->setPath(
                $this->getPathHelper()->setController($this->getController())->setAction('create_bulk')->setId($id)
            );
            $toolbar->push($button);
            $this->push($toolbar);
        }

        $toolbar = new Toolbar();

        if ($this->hasCreatePath()) {
            $button = new Button($this->translate('create.title'), Button::STYLE_SUCCESS);
            $button->setPath($this->getCreatePath());
            $toolbar->push($button);
        }

        if ($this->isShowDeleteBulk()) {

            $id = (new IdParameter());
            foreach ($this->getDetailIdFields() as $idField) {
                $id->addId($idField);
            }
            $button = new Button($this->translate('delete_bulk.button'), Button::STYLE_DANGER);
            $button->setType('submit');
            $button->setName(SubmitParameter::name());
            $button->setValue(SubmitParameter::createDeleteBulk());
            $button->setConfirmTitle($this->translate('delete.heading'));
            $button->setConfirmCancel($this->translate('delete.cancel'));
            $toolbar->push($button);
            $this->setTag('form');
            $this->setAttribute('method', 'post');
            $this->setAttribute('action', $this->getPathHelper()
                ->setController($this->getController())
                ->setAction('delete')->setId($id)->getPath());
        }

        $this->push($toolbar);


        if ($this->isShowMove()) {
            $id = new IdParameter();
            foreach ($this->getDetailIdFields() as $idField) {
                $id->addId($idField);
            }
            $fragment = 'move-table-' . $this->getController();
            $this->setId($fragment);
            $this->setMoveUpPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
                ->setId($id)
                ->setFragment($fragment)
                ->addParameter((new MoveParameter())->setUp())
                ->addParameter($moveredirectParameter));
            $this->setMoveDownPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
                ->setFragment($fragment)
                ->setId($id)
                ->addParameter((new MoveParameter())->setDown())
                ->addParameter($moveredirectParameter));
        }
        parent::initialize();
    }

    protected function getRedirectController(): string
    {
        return $this->getController();
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
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
     * @return bool
     */
    public function isShowMove(): bool
    {
        return $this->showMove;
    }

    /**
     * @param bool $showMove
     */
    public function setShowMove(bool $showMove): void
    {
        $this->showMove = $showMove;
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

    /**
     * @return bool
     */
    public function isShowCreateBulk(): bool
    {
        return $this->showCreateBulk;
    }

    /**
     * @param bool $showCreateBulk
     */
    public function setShowCreateBulk(bool $showCreateBulk): void
    {
        $this->showCreateBulk = $showCreateBulk;
    }

    /**
     * @return bool
     */
    public function isShowEditBulk(): bool
    {
        return $this->showEditBulk;
    }

    /**
     * @param bool $showEditBulk
     */
    public function setShowEditBulk(bool $showEditBulk): void
    {
        $this->showEditBulk = $showEditBulk;
    }

    /**
     * @return bool
     */
    public function isShowDeleteBulk(): bool
    {
        return $this->showDeleteBulk;
    }

    /**
     * @param bool $showDeleteBulk
     */
    public function setShowDeleteBulk(bool $showDeleteBulk): void
    {
        $this->showDeleteBulk = $showDeleteBulk;
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
