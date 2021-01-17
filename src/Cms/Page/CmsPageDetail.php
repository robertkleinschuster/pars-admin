<?php


namespace Pars\Admin\Cms\Page;


use Pars\Admin\Article\ArticleDetail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Headline;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Span;
use Pars\Model\Article\DataBean;

class CmsPageDetail extends ArticleDetail
{

    protected function initialize()
    {
        $this->setSection($this->translate('section.page'));
        $span = new Badge('{CmsPageState_Code}');
        $span->setLabel($this->translate('cmspagestate.code'));
        $span->setFormat(new CmsPageStateFieldFormat($this->getTranslator()));
        $this->append($span, 2, 1);
        $span = new Span('{CmsPageType_Code}', $this->translate('cmspagetype.code'));
        $span->setFormat(new CmsPageTypeFieldFormat($this->getTranslator()));
        $this->append($span, 3, 2);
        $span = new Span('{CmsPageLayout_Code}', $this->translate('cmspagelayout.code'));
        $span->setFormat(new CmsPageLayoutFieldFormat($this->getTranslator()));
        $this->append($span, 3, 1);
        $data = $this->getBean()->get('Article_Data');
        if ($data instanceof DataBean) {
            foreach ($data as $key => $value) {
                if (!is_array($value) && strpos($key, '__class') === false) {
                    $this->addField("Article_Data[$key]", $this->translate('article.data.' . $key));
                }
            }
        }
        parent::initialize();

    }


    protected function getIndexController(): string
    {
        return 'cmspage';
    }

    protected function getEditIdFields(): array
    {
        return [
            'CmsPage_ID'
        ];
    }


}
