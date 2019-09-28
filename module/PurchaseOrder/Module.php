<?php
namespace PurchaseOrder;

use PurchaseOrder\Model\PurchaseOrderMapper;
use PurchaseOrder\Model\PurchaseOrderItemMapper;
use PurchaseOrder\Model\PurchaseOrderItemBidMapper;

class Module
{
    public function getConfig()
    {
      return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
      return array(
        'Zend\Loader\StandardAutoloader' => array(
          'namespaces' => array(
            __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
          ),
        ),
      );
    }

    public function getServiceConfig()
    {
        return array(
          'factories' => array(
            'PurchaseOrderMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new PurchaseOrderMapper($dbAdapter);
              return $mapper;
            },
            'PurchaseOrderItemMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new PurchaseOrderItemMapper($dbAdapter);
              return $mapper;
            },
            'PurchaseOrderItemBidMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new PurchaseOrderItemBidMapper($dbAdapter);
              return $mapper;
            },
          ),
        );
    }

    public function getViewHelperConfig() {
    return array(
      'factories' => array(
        'getBid' => function($sm){
          return new \PurchaseOrder\View\Helper\GetBid($sm->getServiceLocator());
        },
        'getCountBid' => function($sm){
          return new \PurchaseOrder\View\Helper\GetCountBid($sm->getServiceLocator());
        },
        'getQRCode' => function($sm){
          return new \PurchaseOrder\View\Helper\GetQRCode($sm->getServiceLocator());
        },
      )
    );
  }
}
