<?php


namespace Pars\Admin\Cms\Page;


use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Progress;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\Form\Form;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Article\ArticleBean;
use Pars\Mvc\View\HtmlElement;

class CmsPagePollDetail extends BaseDetail implements BeanAwareInterface
{
    use BeanAwareTrait;

    protected ?string $token = null;

    protected function initialize()
    {
        $this->setShowDelete(false);
        $this->setShowBack(false);
        $this->setShowEdit(false);
        $paragraphList = $this->getBean()->get('CmsParagraph_BeanList');
        if ($paragraphList instanceof BeanListInterface) {
            $id = new IdParameter();
            foreach ($this->getEditIdFields() as $editIdField) {
                $id->addId($editIdField);
            }
            $this->push(new HtmlElement('h3.mb-3', 'Ergebnis'));
            $toolbar = new Form();
            $toolbar->addOption('btn-toolbar');
            $toolbar->addOption('mb-4');
            $button = new Button('', Button::STYLE_WARNING);
            $button->setType('submit');
            $button->setName(SubmitParameter::name());
            $button->setValue((new SubmitParameter())->setMode('reset_poll'));
            $button->push(new Icon(Icon::ICON_TRASH));
            $toolbar->push($button);

            $button = new Button('', Button::STYLE_SUCCESS);
            $button->setType('submit');
            $button->setName(SubmitParameter::name());
            $button->setValue((new SubmitParameter())->setMode('show_poll'));
            $button->push(new Icon(Icon::ICON_EYE));
            $toolbar->addHidden(RedirectParameter::name(), (new RedirectParameter())->setPath(
                $this->getPathHelper()->setController($this->getIndexController())->setAction('detail')->setId($id)->getPath()
            ));
            if ($this->hasToken()) {
                $toolbar->addHidden('submit_token', $this->getToken());
            }
            $toolbar->push($button);
            $this->push($toolbar);

            $resultMap = [];
            foreach ($paragraphList as $paragraph) {
                if ($paragraph instanceof ArticleBean) {
                    if ($paragraph->getArticle_Data()->exists('poll')) {
                        $resultMap[$paragraph->ArticleTranslation_Name] = $paragraph->getArticle_Data()->get('poll');
                    }
                }
            }
            if (count($resultMap)) {
                $max = max($resultMap);
                foreach ($resultMap as $title => $item) {
                    if ($max > 0 && $item > 0) {
                        $progress = new Progress($item / $max * 100);
                        $progress->setStyle(Progress::STYLE_SUCCESS);
                        $span = new Span($title . ': ' . $item);
                        $this->append($span);
                        $this->append($progress);
                    }
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
