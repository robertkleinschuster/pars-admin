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
use Pars\Import\Authentication\OAuth\Configurable\ConfigurableGrant;
use Pars\Import\Authentication\OAuth\Configurable\ConfigurableProvider;
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
        $detail->setHeadline($this->translate('index.headline'));
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
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.startpage'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 20;
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.startpage'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasPage()) {
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.page'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.page'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }


        if (!$this->getModel()->hasParagraph()) {
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsparagraph'));
            $heading->push(new Badge($this->translate('index.no.active.paragraph'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.paragraph'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasMenu()) {
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsmenu'));

            $heading->push(new Badge($this->translate('index.no.active.menu'), Badge::STYLE_WARNING));
            $span->push($heading);
        }  else {
            $score += 10;
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.menu'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasLocale()) {
            $heading = new HtmlElement('h5.m-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('locale'));
            $heading->push(new Badge($this->translate('index.no.additional.locale'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new HtmlElement('h5.m-1.d-inline');
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
                    $heading = new HtmlElement('h5.m-1.d-inline');
                    $heading->push(new Badge($this->translate('index.config.empty') . ': ' . $key, Badge::STYLE_DANGER));
                    $heading->setPath($this->getPathHelper()->setController('config'));
                    $span->push($heading);
                } else {
                    $score += $factor;
                    $heading = new HtmlElement('h5.m-1.d-inline');
                    $heading->push(new Badge($this->translate('index.config.ok') . ': ' . $key, Badge::STYLE_SUCCESS));
                    $span->push($heading);
                }
            }
        }


        if ($score <= 40) {
            $progress->setStyle(Progress::STYLE_DANGER);
        } elseif ($score <= 60) {
            $progress->setStyle(Progress::STYLE_WARNING);
        } elseif ($score < 100) {
            $progress->setStyle(Progress::STYLE_INFO);
        } elseif ($score == 100) {
            $progress->setStyle(Progress::STYLE_SUCCESS);
            $heading = new HtmlElement('h5.mt-3');
            $heading->push(new Badge($this->translate('index.success'), Badge::STYLE_SUCCESS));
            $span->push($heading);
            $alert->setHeading($this->translate('index.alert.headline.complete'));
            $alert->setStyle(Alert::STYLE_SUCCESS);
        }
        $progress->setValue($score);

        $span->push($progress);

        $detail->append($span);


        $group = new Group();
        $item = new Item();
        $item->setContent($this->translate('index.create.page'));
        $item->setPath($this->getPathHelper()->setController('cmspage')->setAction('create'));
        $group->push($item);
        $item = new Item();
        $item->setContent($this->translate('index.create.paragraph'));
        $item->setPath($this->getPathHelper()->setController('cmsparagraph')->setAction('create'));
        $group->push($item);

        $item = new Item();
        $item->setContent($this->translate('index.create.menu'));
        $item->setPath($this->getPathHelper()->setController('cmsmenu')->setAction('create'));
        $group->push($item);
        $this->getView()->append($detail);
        $this->getView()->append($group);
    }
    
}
