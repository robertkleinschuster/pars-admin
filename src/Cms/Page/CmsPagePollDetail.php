<?php


namespace Pars\Admin\Cms\Page;


use Niceshops\Bean\Type\Base\BeanAwareInterface;
use Niceshops\Bean\Type\Base\BeanAwareTrait;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Progress;
use Pars\Component\Base\Field\Span;

class CmsPagePollDetail extends BaseDetail implements BeanAwareInterface
{
    use BeanAwareTrait;

    protected ?string $token = null;

    protected array $resultData = [];

    protected function initialize()
    {
        $this->setShowDelete(false);
        $this->setShowBack(false);
        $this->setShowEdit(false);
        if (count($this->getResultData())) {
            $max = max($this->getResultData());
            foreach ($this->getResultData() as $title => $item) {
                if ($max > 0 && $item > 0) {
                    $progress = new Progress($item / $max * 100);
                    $progress->setStyle(Progress::STYLE_SUCCESS);
                    $span = new Span($title . ': ' . $item);
                    $this->append($span);
                    $this->append($progress);
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

    /**
     * @return array
     */
    public function getResultData(): array
    {
        return $this->resultData;
    }

    /**
     * @param array $data
     */
    public function setResultData(array $data): void
    {
        $this->resultData = $data;
    }




}
