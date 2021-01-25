<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Toolbar\PreviewButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;

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
        parent::initialize();
        if ($this->isShowEdit()) {
            $button = new Button(null, Button::STYLE_PRIMARY);
            $button->addOption('my-2');
            $button->addIcon(Icon::ICON_EDIT);
            $id = new IdParameter();
            foreach ($this->getEditIdFields() as $key => $value) {
                if (is_string($key)) {
                    $id->addId($key, $value);
                } else {
                    $id->addId($value);
                }
            }
            $button->setPath($this->getPathHelper()->setController($this->getEditController())->setAction('edit_text')->setId($id));
            $this->getToolbar()->push($button);
        }
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
