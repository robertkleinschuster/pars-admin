<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\BaseDetail;
use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Toolbar\DropdownEditButton;
use Pars\Component\Base\Toolbar\EditTextButton;
use Pars\Component\Base\Toolbar\PreviewButton;
use Pars\Helper\Parameter\EditLocaleParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;

abstract class ArticleDetail extends BaseDetail
{
    protected ?string $previewPath = null;
    use ArticleComponentTrait;
    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    protected function initialize()
    {
        parent::initialize();
        $this->initEditTextButton();
        $this->initPreviewButton();
    }

    protected function initName()
    {
        $this->setName('{ArticleTranslation_Name}');
    }

    protected function addFieldText(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.text');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Text');
        }
        $span = $this->addSpan('ArticleTranslation_Text', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldName(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.name');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Name');
        }
        $span = $this->addSpan('ArticleTranslation_Name', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldView(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.view');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_View');
        }
        $span = $this->addSpan('Article_View', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldRead(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.read');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Read');
        }
        $span = $this->addSpan('Article_Read', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldTeaser(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.teaser');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Teaser');
        }
        $span = $this->addSpan('ArticleTranslation_Teaser', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldPath(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.path');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Path');
        }
        $span = $this->addSpan('ArticleTranslation_Path', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldFooter(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.footer');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Footer');
        }
        $span = $this->addSpan('ArticleTranslation_Footer', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldKeywords(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.keywords');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Keywords');
        }
        $span = $this->addSpan('ArticleTranslation_Keywords', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldSubHeading(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.subheading');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_SubHeading');
        }
        $span = $this->addSpan('ArticleTranslation_SubHeading', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldHeading(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.heading');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Heading');
        }
        $span = $this->addSpan('ArticleTranslation_Heading', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldTitle(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.title');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Title');
        }
        $span = $this->addSpan('ArticleTranslation_Title', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldActive(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.active');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Active');
        }
        $span = $this->addSpan('ArticleTranslation_Active', $label);
        $span->setGroup($group);
        return $span;
    }

    protected function addFieldHost(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.host');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Host');
        }
        $span = $this->addSpan('ArticleTranslation_Host', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldCodeUrl(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.code');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Code');
        }
        $span = $this->addSpan('ArticleTranslation_Code', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldCodeInternal(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.code');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Code');
        }
        $span = $this->addSpan('Article_Code', $label);
        $span->setGroup($group);
        return $span;

    }

    protected function addFieldContactEmail(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.data.contact_email');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Data[contact_email]');
        }
        $span = $this->addSpan("Article_Data[contact_email]", $label);
        $span->setGroup($group);
        return $span;

    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initEditTextButton()
    {
       /* if ($this->isShowEdit()) {
            $button = new EditTextButton();
            $button->setModal(true);
            $button->setModalTitle($this->translate('edit.title'));
            $button->setPath($this->generateEditTextPath());
            $dropdown = new DropdownEditButton($button);
            if ($this->hasLocale_List()) {
                foreach ($this->getLocale_List() as $locale) {
                    $button = new Button();
                    $button->setModal(true);
                    $button->setModalTitle($this->translate('edit.title'));
                    $button->setContent($locale->get('Locale_Name'));
                    $button->setPath($this->generateEditTextPath($locale->get('Locale_UrlCode')));
                    $dropdown->getDropdownList()->push($button);
                }
            }
            $this->getToolbar()->push($dropdown);
        }*/
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
            $path->addParameter($this->getNextContext());
        }
        if ($locale_UrlCode) {
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
            $this->getToolbar()->push((new PreviewButton($this->getPreviewPath()))
                ->setTarget('_blank')
                ->setTooltip($this->translate('preview'))
            );
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
