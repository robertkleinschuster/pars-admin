<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Form\Input;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Toolbar\CreateNewButton;
use Pars\Component\Base\Toolbar\DeleteButton;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Mvc\View\HtmlElement;

abstract class BaseOverview extends Overview
{
    use AdminComponentTrait;

    public bool $showEdit = true;
    public bool $showEditBulk = false;
    public bool $showDelete = true;
    public bool $showDeleteBulk = true;
    public bool $showDetail = true;
    public bool $showCreate = true;
    public bool $showCreateNew = false;
    public bool $showMove = false;
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
        $moveRedirectIdParameter = new IdParameter();
        foreach ($this->getRedirectIdFields() as $key => $value) {
            if (is_string($key)) {
                $moveRedirectIdParameter->addId($key, $value);
            } else {
                $moveRedirectIdParameter->addId($value);
            }
        }
        $moveredirectPath->setId($moveRedirectIdParameter);
        $moveredirectParameter->setPath($moveredirectPath->getPath());

        $redirect = new Input(Input::TYPE_HIDDEN);
        $redirect->setName(RedirectParameter::name());
        $redirect->setValue($moveredirectParameter);
        $this->push($redirect);


        if ($this->hasSection()) {
            $this->setName($this->getSection());
        }

        $id = (new IdParameter());
        foreach ($this->getDetailIdFields() as $key => $value) {
            if (is_string($key)) {
                $id->addId($key, $value);
            } else {
                $id->addId($value);
            }
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

        $createid = (new IdParameter());
        foreach ($this->getCreateIdFields() as $key => $value) {
            if (is_string($key)) {
                $createid->addId($key, $value);
            } else{
                $createid->addId($value);
            }
        }
        $createPath = $this->getPathHelper()->setController($this->getController())->setAction('create')->setId($createid)->getPath();
        if ($this->isShowCreate()) {
            $this->getToolbar()->setCreatePath($createPath);
        }
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', $createPath);

        $createPath = $this->getPathHelper()->setController($this->getController())->setAction('create_new')->setId($createid)->getPath();
        if ($this->isShowCreateNew()) {
            $this->getToolbar()->push(new CreateNewButton($createPath));
        }



        $this->setBulkFieldName(IdListParameter::name());
        $idList = new IdListParameter();
        foreach ($this->getDetailIdFields() as $key => $value) {
            if (is_string($key)) {
                $idList->addId($key, $value);
            } else {
                $idList->addId($value);
            }
        }
        $this->setBulkFieldValue($idList);


        if ($this->isShowDeleteBulk()) {

            $id = (new IdParameter());
            foreach ($this->getDetailIdFields() as $key => $value) {
                if (is_string($key)) {
                    $id->addId($key, $value);
                } else {
                    $id->addId($value);
                }
            }
            $button = new DeleteButton(SubmitParameter::name(), SubmitParameter::createDeleteBulk());
            $button->setConfirm($this->translate('delete_bulk.message'));
            $button->addOption('d-none');
            $this->getToolbar()->push($button);
            $this->setTag('form');
        }


        if ($this->isShowMove()) {
            $id = new IdParameter();
            foreach ($this->getDetailIdFields() as $key => $value) {
                if (is_string($key)) {
                    $id->addId($key, $value);
                } else {
                    $id->addId($value);
                }
            }
            $this->setMoveUpPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
                ->setId($id)
                ->addParameter((new MoveParameter())->setUp())
                ->addParameter($moveredirectParameter));
            $this->setMoveDownPath($this->getPathHelper()
                ->setController($this->getController())
                ->setAction('edit')
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

    abstract protected function getController(): string;

    abstract protected function getDetailIdFields(): array;

    protected function getCreateIdFields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->getName();
    }

    /**
     * @param string $section
     *
     * @return $this
     */
    public function setSection(string $section): self
    {
        return $this->setName($section);
    }

    /**
     * @return bool
     */
    public function hasSection(): bool
    {
        return $this->hasName();
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

    /**
     * @return bool
     */
    public function isShowCreateNew(): bool
    {
        return $this->showCreateNew;
    }

    /**
     * @param bool $showCreateNew
     */
    public function setShowCreateNew(bool $showCreateNew): void
    {
        $this->showCreateNew = $showCreateNew;
    }



}
