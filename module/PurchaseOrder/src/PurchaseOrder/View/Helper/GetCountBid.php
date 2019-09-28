<?php

namespace PurchaseOrder\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetCountBid extends AbstractHelper
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
    $countBid = $this->getPurchaseOrderItemBidMapper()->getCountBid($filter);

    return $countBid['count_id'];
	}
}
