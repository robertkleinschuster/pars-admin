<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Paragraph;

abstract class ArticleEdit extends BaseEdit
{
    public ?array $fileOptions = null;

    protected function initialize()
    {
        $this->initFields();
        parent::initialize();
        $this->push(new Paragraph($this->translate('article.paragraph.hint')));
    }

    protected function initFields()
    {
        $this->getForm()->addText('Article_Code', '{Article_Code}', $this->translate('article.code'), 1, 1);
        $this->getForm()->addText('ArticleTranslation_Code', '{ArticleTranslation_Code}', $this->translate('articletranslation.code'), 1, 2)
            ->setHint($this->translate('articletranslation.code.hint'));
        $this->getForm()->addText('ArticleTranslation_Name', '{ArticleTranslation_Name}', $this->translate('articletranslation.name'), 4, 1);
        $this->getForm()->addText('ArticleTranslation_Title', '{ArticleTranslation_Title}', $this->translate('articletranslation.title'), 4, 2);
        $this->getForm()->addText('ArticleTranslation_Keywords', '{ArticleTranslation_Keywords}', $this->translate('articletranslation.keywords'), 7, 2)
        ->setHint($this->translate('articletranslation.keywords.hint'));
        $this->getForm()->addText('ArticleTranslation_Heading', '{ArticleTranslation_Heading}', $this->translate('articletranslation.heading'), 5, 1);
        $this->getForm()->addText('ArticleTranslation_SubHeading', '{ArticleTranslation_SubHeading}', $this->translate('articletranslation.subheading'), 5, 2);
        $this->getForm()->addUrl('ArticleTranslation_Path', '{ArticleTranslation_Path}', $this->translate('articletranslation.path'), 6, 1);
        $this->getForm()->addWysiwyg('ArticleTranslation_Teaser', '{ArticleTranslation_Teaser}', $this->translate('articletranslation.teaser'), 8, 1);
        $this->getForm()->addWysiwyg('ArticleTranslation_Text', '{ArticleTranslation_Text}', $this->translate('articletranslation.text'), 9, 1);
        $this->getForm()->addWysiwyg('ArticleTranslation_Footer', '{ArticleTranslation_Footer}', $this->translate('articletranslation.footer'), 8, 2);
        if ($this->hasFileOptions()) {
            $this->getForm()->addSelect('File_ID', $this->getFileOptions(), '{File_ID}', $this->translate('file.id'), 9, 1);
        }
        $this->getForm()->addHidden('Locale_Code', $this->getTranslator()->getLocale());
    }


    /**
     * @return array
     */
    public function getFileOptions(): array
    {
        return $this->fileOptions;
    }

    /**
     * @param array $fileOptions
     *
     * @return $this
     */
    public function setFileOptions(array $fileOptions): self
    {
        $this->fileOptions = $fileOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFileOptions(): bool
    {
        return isset($this->fileOptions);
    }

}
