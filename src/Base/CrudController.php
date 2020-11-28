<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;

/**
 * Class CrudController
 * @package Pars\Admin\Base
 * @method CrudModel getModel()
 */
abstract class CrudController extends BaseController
{

    public function indexAction()
    {
        $overview = $this->createOverview();
        $overview->setBeanList($this->getModel()->getBeanList());
        $this->getView()->append($overview);
    }

    abstract protected function createOverview(): BaseOverview;

    public function detailAction()
    {
        $detail = $this->createDetail();
        $bean = $this->getModel()->getBean();
        $detail->setBean($bean);
        $this->getView()->append($detail);
        $metaInfo = new Detail();
       # $metaInfo->setSection($this->translate('meta.info'));
        if ($bean->exists('Person_ID_Create') && !$bean->empty('Person_ID_Create')) {
            if ($bean->get('Person_ID_Create') > 0) {
                $user = $this->getModel()->getUserById($bean->get('Person_ID_Create'));
                $metaInfo->append(new Span($user->get('User_Displayname'), $this->translate('user.create')));
            }
        }
        if ($bean->exists('Person_ID_Edit') && !$bean->empty('Person_ID_Edit')) {
            if ($bean->get('Person_ID_Edit') > 0) {
                $user = $this->getModel()->getUserById($bean->get('Person_ID_Edit'));
                $metaInfo->append(new Span($user->get('User_Displayname'), $this->translate('user.edit')));
            }
        }
        if ($bean->exists('Timestamp_Create') && !$bean->empty('Timestamp_Create')) {
            $date = $bean->get('Timestamp_Create');
            if ($date instanceof \DateTime) {
                $metaInfo->append(new Span($date->format('d. M Y H:i:s'), $this->translate('timestamp.create')));
            }
        }
        if ($bean->exists('Timestamp_Edit') && !$bean->empty('Timestamp_Edit')) {
            $date = $bean->get('Timestamp_Edit');
            if ($date instanceof \DateTime) {
                $metaInfo->append(new Span($date->format('d. M Y H:i:s'), $this->translate('timestamp.edit')));
            }
        }
        $this->getView()->append($metaInfo);
    }

    abstract protected function createDetail(): BaseDetail;

    public function createAction()
    {
        $edit = $this->createEdit();
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getEmptyBean($this->getPreviousAttributes()));
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $edit->setCreate(true);
        $this->getView()->append($edit);
        return $edit;
    }

    public function editAction()
    {
        $edit = $this->createEdit();
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getBean());
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $this->getView()->append($edit);
    }

    abstract protected function createEdit(): BaseEdit;

    public function deleteAction()
    {
        $delete = $this->createDelete();
        $delete->setToken($this->generateToken('submit_token'));
        $this->getView()->append($delete);
        $this->detailAction();
        return $delete;
    }

    abstract protected function createDelete(): BaseDelete;
}
