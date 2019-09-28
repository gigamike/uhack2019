<?php

namespace Supplier\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Supplier\Model\SupplierEntity;

use Gumlet\ImageResize;

class IndexController extends AbstractActionController
{
  public function getSupplierPortalMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('SupplierPortalMapper');
  }

  public function indexAction()
  {
    return new ViewModel([

    ]);
  }

  public function portalAction()
  {
    $filter = array();
    $order = array(
      'name',
    );
    $suppliers = $this->getSupplierPortalMapper()->fetch(false, $filter, $order);
    return new ViewModel([
      'suppliers' => $suppliers,
    ]);
  }
}
