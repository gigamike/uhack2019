<?php
namespace Supplier;

use Supplier\Model\SupplierPortalMapper;

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
            'SupplierPortalMapper' => function ($sm) {
              $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
              $mapper = new SupplierPortalMapper($dbAdapter);
              return $mapper;
            },
          ),
        );
    }
}
