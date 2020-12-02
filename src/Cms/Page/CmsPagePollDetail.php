<?php


namespace Pars\Admin\Cms\Page;


use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Niceshops\Bean\Type\Base\BeanListInterface;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Field\Progress;
use Pars\Component\Base\Field\Span;
use Pars\Model\Article\ArticleBean;
use Pars\Mvc\View\HtmlElement;

class CmsPagePollDetail extends Detail implements BeanAwareInterface
{
    use BeanAwareTrait;

    private ?string $resetPath = null;
    private ?string $showPath = null;

    protected function initialize()
    {

        $paragraphList = $this->getBean()->get('CmsParagraph_BeanList');
        if ($paragraphList instanceof BeanListInterface) {
            $this->push(new HtmlElement('h3.mb-3', 'Ergebnis'));
            $toolbar = new HtmlElement('div.btn-toolbar.mb-4');

            if ($this->hasResetPath()) {
                $button = new Button('', Button::STYLE_WARNING);
                $button->push(new Icon(Icon::ICON_TRASH));
                $button->setPath($this->getResetPath());
                $toolbar->push($button);
            }
            if ($this->hasShowPath()) {
                $button = new Button('', Button::STYLE_SUCCESS);
                $button->push(new Icon(Icon::ICON_EYE));
                $button->setPath($this->getShowPath());
                $toolbar->push($button);
            }

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

    /**
     * @return string
     */
    public function getResetPath(): string
    {
        return $this->resetPath;
    }

    /**
     * @param string $resetPath
     *
     * @return $this
     */
    public function setResetPath(string $resetPath): self
    {
        $this->resetPath = $resetPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasResetPath(): bool
    {
        return isset($this->resetPath);
    }

    /**
     * @return string
     */
    public function getShowPath(): string
    {
        return $this->showPath;
    }

    /**
     * @param string $showPath
     *
     * @return $this
     */
    public function setShowPath(string $showPath): self
    {
        $this->showPath = $showPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasShowPath(): bool
    {
        return isset($this->showPath);
    }


}
