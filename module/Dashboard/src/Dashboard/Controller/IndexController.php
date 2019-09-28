<?php

namespace Dashboard\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
  public function getDashboardMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('DashboardMapper');
  }

  public function indexAction()
  {
    return new ViewModel([

    ]);
  }
}
