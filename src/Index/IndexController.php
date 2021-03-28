<?php

namespace Pars\Admin\Index;

use Pars\Admin\Base\BaseController;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Progress;
use Pars\Component\Base\Field\Span;
use Pars\Component\Base\ListGroup\Group;
use Pars\Component\Base\ListGroup\Item;
use Pars\Core\Config\ParsConfig;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\Data\ArticleDataBeanFinder;
use Pars\Mvc\View\HtmlElement;

/**
 * Class IndexController
 * @package Pars\Admin\Index
 * @method IndexModel getModel()
 */
class IndexController extends BaseController
{
    public function indexAction()
    {
        $detail = new Detail();
        $detail->setHeading($this->translate('index.headline'));
        $alert = new Alert();
        $alert->setHeading($this->translate('index.alert.headline.incomplete'));
        $alert->setStyle(Alert::STYLE_WARNING);
        #detail->getJumbotron()->getElementList()->push($alert);
        $progress = new Progress(10);
        $progress->addOption('my-3');
        $progress->setStyle(Progress::STYLE_DANGER);
        $span = new Span();
        $score = 0;


        if (!$this->getModel()->hasStartpage()) {
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.startpage'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 20;
            $heading = new HtmlElement('h5.mr-1.d-inline.mb-1');
            $heading->push(new Badge($this->translate('index.active.startpage'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasPage()) {
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.page'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.page'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }


        if (!$this->getModel()->hasBlock()) {
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsblock'));
            $heading->push(new Badge($this->translate('index.no.active.block'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.block'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasMenu()) {
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsmenu'));

            $heading->push(new Badge($this->translate('index.no.active.menu'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.menu'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasLocale()) {
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('locale'));
            $heading->push(new Badge($this->translate('index.no.additional.locale'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.mr-1.d-inline');
            $heading->push(new Badge($this->translate('index.additional.locale'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }


        if ($this->getModel()->getConfig()) {
            $count = count($this->getModel()->getConfig());
            $factor = 40;
            if ($count > 0) {
                $factor = $factor / $count;
            }
            foreach ($this->getModel()->getConfig() as $key => $value) {
                if (empty($value)) {
                    $heading = new HtmlElement('h5.mr-1.d-inline');
                    $heading->push(new Badge($this->translate('index.config.empty') . ': ' . $key, Badge::STYLE_DANGER));
                    $heading->setPath($this->getPathHelper()->setController('config'));
                    $span->push($heading);
                } else {
                    $score += $factor;
                    $heading = new HtmlElement('h5.mr-1.d-inline');
                    $heading->push(new Badge($this->translate('index.config.ok') . ': ' . $key, Badge::STYLE_SUCCESS));
                    $span->push($heading);
                }
            }
        }
        $progress->setValue($score);
        $span->push($progress);

        if ($score <= 40) {
            $progress->setStyle(Progress::STYLE_DANGER);
        } elseif ($score <= 60) {
            $progress->setStyle(Progress::STYLE_WARNING);
        } elseif ($score < 99) {
            $progress->setStyle(Progress::STYLE_INFO);
        } elseif ($score >= 99) {
            $span->getElementList()->clear();
            $progress->setStyle(Progress::STYLE_SUCCESS);
            $heading = new HtmlElement('h5.mt-3');
            $heading->push(new Badge($this->translate('index.success'), Badge::STYLE_SUCCESS));
            #  $span->push($heading);
            $alert->setHeading($this->translate('index.alert.headline.complete'));
            $alert->setStyle(Alert::STYLE_SUCCESS);
        }

        if ($span->getElementList()->count()) {
            $detail->append($span);
        }

        $group = new Group();
        $item = new Item();
        $item->setContent($this->translate('index.create.page'));
        $item->setPath($this->getPathHelper()->setController('cmspage')->setAction('create'));
        $group->push($item);

        $item = new Item();
        $item->setContent($this->translate('index.edit.page'));
        $item->setPath($this->getPathHelper()->setController('cmspage')->setAction('index'));
        $group->push($item);

        $item = new Item();
        $item->setContent($this->translate('index.create.block'));
        $item->setPath($this->getPathHelper()->setController('cmsblock')->setAction('create'));
        $group->push($item);

        $item = new Item();
        $item->setContent($this->translate('index.create.menu'));
        $item->setPath($this->getPathHelper()->setController('cmsmenu')->setAction('create'));
        $group->push($item);

        $messages = new Detail();
        $messages->setHeading($this->translate('index.messages'));
        $articleDataFinder = new ArticleDataBeanFinder($this->getModel()->getDbAdpater());
        $articleDataFinder->order(['Timestamp_Create' => ArticleDataBeanFinder::ORDER_MODE_DESC]);
        $articleDataFinder->limit(5, 0);
        foreach ($articleDataFinder->getBeanListDecorator() as $item) {
            $data = $item->get('ArticleData_Data');
            if (isset($data['email'])) {
                $messages->addField('', empty($data['name']) ? $data['email'] : $data['name'] . ' - ' . $data['email'])->setContent(empty($data['subject']) ? $data['message'] : $data['subject'])->setPath(
                    $this->getPathHelper()->setController('articledata')->setAction('detail')->setId((new IdParameter())->addId('ArticleData_ID', $item->get('ArticleData_ID')))
                        ->addParameter(ContextParameter::fromPath($this->getPathHelper(true)->getPath()))->getPath()
                );
            }
        }

        $this->getView()->append($detail);

        if ($messages->getJumbotron()->getFieldList()->count()) {
            $this->getView()->append($messages);
        }

        $this->getView()->append($group);
    }

    public function tasksAction()
    {
        return $this->unauthorized();
        $config = $this->getModel()->getApplicationConfig();
        if (isset($config['task'])) {
            foreach ($config['task'] as $class => $taskConfig) {
                /**
                 * @var $task \Pars\Core\Task\Base\AbstractTask
                 */
                $task = new $class($taskConfig, new \DateTime(), $this->getLogger(), $this->getModel()->getDbAdpater());
                $task->execute();
                echo 'Task: ' . $class . '<br>';
            }
        }
        exit;
    }
}
