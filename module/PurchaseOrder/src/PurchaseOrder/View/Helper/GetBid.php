<?php

namespace PurchaseOrder\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetBid extends AbstractHelper
{
  private $_sm;

  public function __construct(\Zend\ServiceManager\ServiceManager $sm) {
      $this->_sm = $sm;
  }

  public function getSm() {
      return $this->_sm;
  }

  public function getPurchaseOrderItemBidMapper()
  {
      return $this->getSm()->get('PurchaseOrderItemBidMapper');
  }

	public function __invoke($filter)
	{
    $purchaseOrderItemBidMapper = $this->getPurchaseOrderItemBidMapper()->fetch(false, $filter);

    return $purchaseOrderItemBidMapper;
	}
}
