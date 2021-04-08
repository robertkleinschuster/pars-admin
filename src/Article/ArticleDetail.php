<?php

namespace Pars\Admin\Article;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Toolbar\DropdownEditButton;
use Pars\Component\Base\Toolbar\EditTextButton;
use Pars\Component\Base\Toolbar\PreviewButton;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\EditLocaleParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;

abstract class ArticleDetail extends BaseDetail
{
    protected ?string $previewPath = null;

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    protected function initialize()
    {
        $this->initSection();
        $this->initFields();
        $this->initDataFields();
        parent::initialize();
        $this->initEditTextButton();
        $this->initPreviewButton();
    }

    /**
     *
     */
    protected function initSection()
    {
        $this->setSection('{ArticleTranslation_Name}');
    }


    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        $this->addField('Article_Code', $this->translate('article.code'), 1, 1);
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'), 1, 2);
        $this->addField('ArticleTranslation_Host', $this->translate('articletranslation.host'), 1, 3);
        $this->addField('ArticleTranslation_Title', $this->translate('articletranslation.title'), 2, 1);
        $this->addField('ArticleTranslation_Heading', $this->translate('articletranslation.heading'), 2, 2);
        $this->addField('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'), 2, 3);
        $this->addField('ArticleTranslation_Keywords', $this->translate('articletranslation.keywords'), 3, 1);
        $this->addField('ArticleTranslation_Footer', $this->translate('articletranslation.footer'), 3, 3);
        $this->addField('ArticleTranslation_Path', $this->translate('articletranslation.path'), 4, 1);
        $this->addField('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'), 3, 2);
        $this->addField('ArticleTranslation_Text', $this->translate('articletranslation.text'), 10, 1);
    }

    /**
     * @throws BeanException
     */
    protected function initDataFields()
    {
        if (!$this->getBean()->empty('Article_Data')) {
            $data = $this->getBean()->get('Article_Data');
            if ($data instanceof DataBean) {
                foreach ($data as $key => $value) {
                    if (!is_array($value) && strpos($key, '__class') === false && isset($value)) {
                        switch ($data->type($key)) {
                            case DataBean::DATA_TYPE_BOOL:
                                if ($value === true) {
                                    $icon = new Icon(Icon::ICON_CHECK);
                                    $icon->addOption('text-success');
                                    $icon->setLabel($this->translate('article.data.' . $key));
                                    $this->append($icon);
                                } else {
                                    $icon = new Icon(Icon::ICON_X);
                                    $icon->addOption('text-danger');
                                    $icon->setLabel($this->translate('article.data.' . $key));
                                    $this->append($icon);
                                }
                                break;
                            case DataBean::DATA_TYPE_FLOAT:
                            case DataBean::DATA_TYPE_INT:
                            case DataBean::DATA_TYPE_STRING:
                                if ($value) {
                                    $this->addField("Article_Data[$key]", $this->translate('article.data.' . $key));
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initEditTextButton()
    {
        if ($this->isShowEdit()) {
            $button = new EditTextButton();
            $button->setModal(true);
            $button->setModalTitle($this->translate('edit.title'));
            $button->setPath($this->generateEditTextPath());
            $dropdown = new DropdownEditButton($button);
            foreach ($this->getLocale_List() as $locale) {
                $button = new Button();
                $button->setModal(true);
                $button->setModalTitle($this->translate('edit.title'));
                $button->setContent($locale->get('Locale_Name'));
                $button->setPath($this->generateEditTextPath($locale->get('Locale_UrlCode')));
                $dropdown->getDropdownList()->push($button);
            }
            $this->getToolbar()->push($dropdown);
        }
    }

    /**
     * @param string|null $locale_UrlCode
     * @return string
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function generateEditTextPath(string $locale_UrlCode = null): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getEditController())
            ->setAction('edit_text')
            ->setId(IdParameter::fromMap($this->getEditIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }        if ($locale_UrlCode) {
            $path->addParameter(new EditLocaleParameter($locale_UrlCode));
        }
        return $path->getPath();
    }

    /**
     * @throws BeanException
     */
    protected function initPreviewButton()
    {
        if ($this->hasPreviewPath()) {
            $this->getToolbar()->push((new PreviewButton($this->getPreviewPath()))->setTarget('_blank'));
        }
    }


    /**
     * @return string
     */
    public function getPreviewPath(): string
    {
        return $this->previewPath;
    }

    /**
     * @param string $previewPath
     *
     * @return $this
     */
    public function setPreviewPath(string $previewPath): self
    {
        $this->previewPath = $previewPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPreviewPath(): bool
    {
        return isset($this->previewPath);
    }
}
