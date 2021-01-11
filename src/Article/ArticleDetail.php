<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Toolbar\PreviewButton;

abstract class ArticleDetail extends BaseDetail
{
    protected ?string $previewPath = null;

    protected function initialize()
    {
        $this->setSection($this->translate('section.article'));
        $this->setHeadline('{ArticleTranslation_Name}');
        $this->addField('Article_Code', $this->translate('article.code'), 1, 1);
        $this->addField('ArticleTranslation_Code', $this->translate('articletranslation.code'), 1,2);
        $this->addField('ArticleTranslation_Title', $this->translate('articletranslation.title'), 5, 1);
        $this->addField('ArticleTranslation_Keywords', $this->translate('articletranslation.keywords'), 5, 2);
        $this->addField('ArticleTranslation_Heading', $this->translate('articletranslation.heading'), 7, 1);
        $this->addField('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'), 7, 2);
        $this->addField('ArticleTranslation_Path', $this->translate('articletranslation.path'), 8, 1);
        $this->addField('ArticleTranslation_Host', $this->translate('articletranslation.host'), 0,1);
        $this->addField('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'), 8, 2);
        $this->addField('ArticleTranslation_Footer', $this->translate('articletranslation.footer'), 9, 1);
        $this->addField('ArticleTranslation_Text', $this->translate('articletranslation.text'), 10, 1);
        parent::initialize();
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
