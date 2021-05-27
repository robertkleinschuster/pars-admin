<?php

namespace Pars\Admin\Index;

use Pars\Admin\Base\BaseController;
use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Collapsable\Collapsable;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Headline;
use Pars\Component\Base\Field\Progress;
use Pars\Component\Base\Field\Span;
use Pars\Core\Database\AbstractDatabaseBeanFinder;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\Data\ArticleDataBeanFinder;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Config\ConfigBeanFinder;
use Pars\Model\Form\FormBeanFinder;
use Pars\Mvc\View\DefaultComponent;
use Pars\Mvc\View\ViewElement;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;

/**
 * Class IndexController
 * @package Pars\Admin\Index
 * @method IndexModel getModel()
 */
class IndexController extends BaseController
{
    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    public function indexAction()
    {
        $headline = new DefaultComponent();
        $headline->push(new Headline($this->translate('index.headline')));
        $this->getView()->pushComponent($headline);

        $detail = new Detail();

        $collapsable = new Collapsable(
            'missing-settings-collapsable',
            $this->getPathHelper(true)->getPath(),
            $this->translate("index.collapse.detail")
        );
        $collapsable->pushComponent($detail);
        $this->getView()->pushComponent($collapsable);

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
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.startpage'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 20;
            $heading = new ViewElement('h5.me-1.d-inline.mb-1');
            $heading->push(new Badge($this->translate('index.active.startpage'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasPage()) {
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmspage'));
            $heading->push(new Badge($this->translate('index.no.active.page'), Badge::STYLE_DANGER));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.page'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }


        if (!$this->getModel()->hasBlock()) {
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsblock'));
            $heading->push(new Badge($this->translate('index.no.active.block'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.block'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasMenu()) {
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('cmsmenu'));

            $heading->push(new Badge($this->translate('index.no.active.menu'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->push(new Badge($this->translate('index.active.menu'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        if (!$this->getModel()->hasLocale()) {
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->setPath($this->getPathHelper()->setController('locale'));
            $heading->push(new Badge($this->translate('index.no.additional.locale'), Badge::STYLE_WARNING));
            $span->push($heading);
        } else {
            $score += 10;
            $heading = new ViewElement('h5.me-1.d-inline');
            $heading->push(new Badge($this->translate('index.additional.locale'), Badge::STYLE_SUCCESS));
            $span->push($heading);
        }

        $configFinder = new ConfigBeanFinder($this->getModel()->getDbAdpater());
        $config = $configFinder->getBeanList()->column('Config_Value', 'Config_Code');

        if ($config) {
            $count = count($config);
            $factor = 40;
            if ($count > 0) {
                $factor = $factor / $count;
            }
            foreach ($config as $key => $value) {
                if (empty($value)) {
                    $heading = new ViewElement('h5.me-1.d-inline');
                    $heading->push(new Badge($this->translate('index.config.empty') . ': ' . $key, Badge::STYLE_DANGER));
                    $heading->setPath($this->getPathHelper()->setController('config'));
                    $span->push($heading);
                } else {
                    $score += $factor;
                    $heading = new ViewElement('h5.me-1.d-inline');
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
            $heading = new ViewElement('h5.mt-3');
            $heading->push(new Badge($this->translate('index.success'), Badge::STYLE_SUCCESS));
            #  $span->push($heading);
            $alert->setHeading($this->translate('index.alert.headline.complete'));
            $alert->setStyle(Alert::STYLE_SUCCESS);
        }

        if ($span->getElementList()->count()) {
            $detail->pushField($span);
        }

        $headline = new DefaultComponent();
        $headline->push(new Headline($this->translate('index.quickactions')));
        $this->getView()->pushComponent($headline);

        $messages = new Detail();
        $messages->setHeading($this->translate('index.messages'));
        $articleDataFinder = new ArticleDataBeanFinder($this->getModel()->getDbAdpater());
        $articleDataFinder->order(['Timestamp_Create' => ArticleDataBeanFinder::ORDER_MODE_DESC]);
        $articleDataFinder->limit(5, 0);
        foreach ($articleDataFinder->getBeanListDecorator() as $item) {
            $data = $item->get('ArticleData_Data');
            if (isset($data['email'])) {
                $messages->addSpan('', empty($data['name']) ? $data['email'] : $data['name'] . ' - ' . $data['email'])->setContent(empty($data['subject']) ? $data['message'] : $data['subject'])->setPath(
                    $this->getPathHelper()->setController('articledata')->setAction('detail')->setId((new IdParameter())->addId('ArticleData_ID', $item->get('ArticleData_ID')))
                        ->addParameter(ContextParameter::fromPath($this->getPathHelper(true)->getPath()))->getPath()
                );
            }
        }

        if ($messages->getJumbotron()->getFieldList()->count()) {
            $this->getView()->pushComponent($messages);
        }
        $finder = new FormBeanFinder($this->getModel()->getDatabaseAdapter());
        $finder->filterValue('Form_IndexInfo', true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $request = $this->pushAction('formdata', 'index', $bean->Form_Code);
            $request->setParameter(IdParameter::fromMap(['Form_ID' => $bean->Form_ID]));
        }

        $finder = new CmsPageBeanFinder($this->getModel()->getDatabaseAdapter());
        $finder->filterLocale_Code($this->getUserBean()->getLocale()->getLocale_Code());
        $finder->filterBlogPage();
        if ($finder->count() === 1) {
            $request = $this->pushAction('cmspage', 'blog', $this->translate('index.quickactions.blog'));
            $request->setParameter(IdParameter::fromMap(['CmsPage_ID' => $finder->getBean()->CmsPage_ID]));
        }


        $this->pushAction('cmspage', 'index', $this->translate('index.quickactions.cmspage'));
        $this->pushAction('cmsblock', 'index', $this->translate('index.quickactions.cmsblock'));
        $this->pushAction('cmsmenu', 'index', $this->translate('index.quickactions.cmsblock'));

    }
}
