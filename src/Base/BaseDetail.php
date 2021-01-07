<?php


namespace Pars\Admin\Base;


use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Toolbar\Toolbar;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\HtmlElement;

abstract class BaseDetail extends Detail
{
    use AdminComponentTrait;

    public ?string $indexPath = null;
    public bool $showEdit = true;
    public bool $showDelete = true;
    public bool $showBack = true;

    protected function initialize()
    {
        $indexPath = $this->getPathHelper()->setController($this->getIndexController())->setAction($this->getIndexAction());
        $id = new IdParameter();
        foreach ($this->getIndexIdFields() as $key => $value) {
            if (is_string($key)) {
                $id->addId($key, $value);
            } else {
                $id->addId($value);
            }
        }
        $indexPath->addParameter($id);
        $this->setIndexPath($indexPath);
        $this->addOption('ajax');

        $toolbar = $this->getToolbar();

        if ($this->hasIndexPath() && $this->isShowBack()) {
            $button = new Button(null, Button::STYLE_SECONDARY);
            $button->addIcon(Icon::ICON_ARROW_LEFT_CIRCLE);
            $button->setPath($this->getIndexPath());
            $toolbar->push($button);
        }
        if ($this->isShowEdit()) {
            $button = new Button(null, Button::STYLE_WARNING);
            $button->addIcon(Icon::ICON_EDIT_2);
            $id = new IdParameter();
            foreach ($this->getEditIdFields() as $key => $value) {
                if (is_string($key)) {
                    $id->addId($key, $value);
                } else {
                    $id->addId($value);
                }
            }
            $button->setPath($this->getPathHelper()->setController($this->getEditController())->setAction($this->getEditAction())->setId($id));
            $toolbar->push($button);
        }
        if ($this->isShowDelete()) {
            $button = new Button(null, Button::STYLE_DANGER);
            $button->addIcon(Icon::ICON_TRASH);
            $id = new IdParameter();
            foreach ($this->getEditIdFields() as $key => $value) {
                if (is_string($key)) {
                    $id->addId($key, $value);
                } else {
                    $id->addId($value);
                }
            }
            $button->setPath($this->getPathHelper()->setController($this->getEditController())->setAction('delete')->setId($id));
            $toolbar->push($button);
        }
        parent::initialize();
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
     * @return BaseDetail
     */
    public function setShowDelete(bool $showDelete): BaseDetail
    {
        $this->showDelete = $showDelete;
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
     * @return BaseDetail
     */
    public function setShowEdit(bool $showEdit): BaseDetail
    {
        $this->showEdit = $showEdit;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowBack(): bool
    {
        return $this->showBack;
    }

    /**
     * @param bool $showBack
     * @return BaseDetail
     */
    public function setShowBack(bool $showBack): BaseDetail
    {
        $this->showBack = $showBack;
        return $this;
    }


    abstract protected function getIndexController(): string;

    abstract protected function getEditIdFields(): array;

    protected function getEditController(): string
    {
        return $this->getIndexController();
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }


    protected function getIndexAction(): string
    {
        return 'index';
    }

    protected function getIndexIdFields(): array
    {
        return [];
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
