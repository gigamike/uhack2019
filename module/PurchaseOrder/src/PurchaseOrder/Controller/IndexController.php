<?php

namespace PurchaseOrder\Controller;

use Zend\Db\Adapter\Adapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message as Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Transport\Sendmail as Sendmail;

use PurchaseOrder\Model\PurchaseOrderEntity;
use PurchaseOrder\Model\PurchaseOrderItemEntity;
use PurchaseOrder\Model\PurchaseOrderItemBidEntity;

class IndexController extends AbstractActionController
{
  public function getPurchaseOrderMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('PurchaseOrderMapper');
  }

  public function getPurchaseOrderItemMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('PurchaseOrderItemMapper');
  }

  public function getPurchaseOrderItemBidMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('PurchaseOrderItemBidMapper');
  }

  public function getUserMapper()
  {
    $sm = $this->getServiceLocator();
    return $sm->get('UserMapper');
  }

  public function indexAction()
  {
    $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
    $search_by = $this->params()->fromRoute('search_by') ? $this->params()->fromRoute('search_by') : '';

    $searchFilter = array();
    if (!empty($search_by)) {
      $searchFilter = (array) json_decode($search_by);
    }

    $order = array('purchase_order.created_datetime DESC');
    $paginator = $this->getPurchaseOrderMapper()->getPurchaseOrders(true, $searchFilter, $order);
    $paginator->setCurrentPageNumber($page);
    $paginator->setItemCountPerPage(9);

    return new ViewModel([
      'searchFilter' => $searchFilter,
      'paginator' => $paginator,
      'search_by' => $search_by,
      'page' => $page,
      'searchFilter' => $searchFilter,
    ]);
  }

  public function searchAction()
	{
		$request = $this->getRequest();
		if ($request->isPost()) {
			$formdata = (array) $request->getPost();
			$search_data = array();
			foreach ($formdata as $key => $value) {
				if ($key != 'submit') {
					if (!empty($value)) {
						$search_data[$key] = $value;
					}
				}
			}

			if (!empty($search_data)) {
				$search_by = json_encode($search_data);
				return $this->redirect()->toRoute('purchase-order', array('search_by' => $search_by));
			}else{
				return $this->redirect()->toRoute('purchase-order');
			}
		}else{
			return $this->redirect()->toRoute('purchase-order');
		}
	}

  public function addAction()
  {
    $request = $this->getRequest();
    if ($request->isPost()) {
      $items = $request->getPost('items');
      $quanitities = $request->getPost('quanitities');
      $prices = $request->getPost('prices');
      $timeline_datetime = $request->getPost('timeline_datetime');
      $comments = $request->getPost('comments');

      $validItems = array();
      foreach ($items as $key => $value) {
        $item = trim($items[$key]);
        $quanitity = trim($quanitities[$key]);
        $price = trim($prices[$key]);

        if($item != '' && is_numeric($quanitity) && is_numeric($price)){
          $validItems[] = array(
            'item' => $item,
            'quanitity' => $quanitity,
            'price' => $price,
          );
        }
      }

      if(count($validItems) > 0){
        $authService = $this->serviceLocator->get('auth_service');

        $purchaseOrder = new PurchaseOrderEntity();
        $purchaseOrder->setStatus('pending');
        $purchaseOrder->setTimelineDatetime($timeline_datetime);
        $purchaseOrder->setComments($comments);
        $purchaseOrder->setCreatedUserId($authService->getIdentity()->id);
        $this->getPurchaseOrderMapper()->save($purchaseOrder);

        foreach ($validItems as $row) {
          $purchaseOrderItem = new PurchaseOrderItemEntity();
          $purchaseOrderItem->setPurchaseOrderId($purchaseOrder->getId());
          $purchaseOrderItem->setItem($row['item']);
          $purchaseOrderItem->setQuantity($row['quanitity']);
          $purchaseOrderItem->setUnitPrice($row['price']);
          $this->getPurchaseOrderItemMapper()->save($purchaseOrderItem);
        }

        $this->flashMessenger()->setNamespace('success')->addMessage('Purchase Order successfully.');
        return $this->redirect()->toRoute('purchase-order');
      }else{
        $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Items.');
        return $this->redirect()->toRoute('purchase-order', array('action' => 'add',));
      }
    }

    /*
    $searchFilterUser = array(
      'role' => 'supplier',
    );
    $orderUser = array(
      'company_name',
      'first_name',
      'last_name',
    );
    $suppliers = $this->getUserMapper()->fetch(false, $searchFilterUser, $orderUser);
    */

    return new ViewModel([
      // 'suppliers' => $suppliers,
    ]);
  }

  public function quotationAction()
  {
    $id = (int)$this->params('id');

    if (!$id) {
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $purchaseOrder = $this->getPurchaseOrderMapper()->getPurchaseOrder($id);
    if(!$purchaseOrder){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $authService = $this->serviceLocator->get('auth_service');
    $supplierId = $authService->getIdentity()->id;

    $supplier = $this->getUserMapper()->getUser($supplierId);
    if(!$supplier){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $createdBy = $this->getUserMapper()->getUser($purchaseOrder->getCreatedUserId());
    if(!$createdBy){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $items = $request->getPost('items');
      $quanitities = $request->getPost('quanitities');
      $prices = $request->getPost('prices');
      $comments = $request->getPost('comments');

      $validItems = array();
      foreach ($items as $key => $value) {
        $item = trim($items[$key]);
        $quanitity = trim($quanitities[$key]);
        $price = trim($prices[$key]);

        if($item != '' && is_numeric($quanitity) && is_numeric($price)){
          $validItems[] = array(
            'item' => $item,
            'quanitity' => $quanitity,
            'price' => $price,
          );
        }
      }

      if(count($validItems) > 0){
        $authService = $this->serviceLocator->get('auth_service');

        foreach ($validItems as $row) {
          $purchaseOrderItemBid = new PurchaseOrderItemBidEntity();
          $purchaseOrderItemBid->setPurchaseOrderId($purchaseOrder->getId());
          $purchaseOrderItemBid->setSupplierId($supplierId);
          $purchaseOrderItemBid->setItem($row['item']);
          $purchaseOrderItemBid->setQuantity($row['quanitity']);
          $purchaseOrderItemBid->setUnitPrice($row['price']);
          $this->getPurchaseOrderItemBidMapper()->save($purchaseOrderItemBid);
        }

        $this->flashMessenger()->setNamespace('success')->addMessage('Bid/Quotation successfully posted.');
        return $this->redirect()->toRoute('purchase-order');
      }else{
        $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Items.');
        return $this->redirect()->toRoute('purchase-order', array('action' => 'quotation', 'id' => $purchaseOrder->getId(),));
      }
    }

    $filter = array(
      'purchase_order_id' => $purchaseOrder->getId(),
    );
    $order = array();
    $purchaseOrderItems = $this->getPurchaseOrderItemMapper()->fetch(false, $filter, $order);

    return new ViewModel([
      'purchaseOrder' => $purchaseOrder,
      'purchaseOrderItems' => $purchaseOrderItems,
      'supplier' => $supplier,
      'createdBy' => $createdBy,
    ]);
  }

  public function bidAction()
  {
    $id = (int)$this->params('id');

    if (!$id) {
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $purchaseOrder = $this->getPurchaseOrderMapper()->getPurchaseOrder($id);
    if(!$purchaseOrder){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $createdBy = $this->getUserMapper()->getUser($purchaseOrder->getCreatedUserId());
    if(!$createdBy){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $filter = array(
      'purchase_order_id' => $purchaseOrder->getId(),
    );
    $order = array();
    $purchaseOrderItems = $this->getPurchaseOrderItemMapper()->fetch(false, $filter, $order);

    $filter = array(
      'purchase_order_id' => $purchaseOrder->getId(),
    );
    $order = array();
    $purchaseOrderItemBids = $this->getPurchaseOrderItemBidMapper()->getPurchaseOrderItemBids(false, $filter, $order);

    $authService = $this->serviceLocator->get('auth_service');
    $userId = $authService->getIdentity()->id;

    $user = $this->getUserMapper()->getUser($userId);
    if(!$user){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $request = $this->getRequest();
    if ($request->isPost()) {
      $this->flashMessenger()->setNamespace('success')->addMessage('Payment successfull.');
      return $this->redirect()->toRoute('purchase-order', ['action' => 'bid', 'id' => $id]);
    }

    return new ViewModel([
      'purchaseOrder' => $purchaseOrder,
      'createdBy' => $createdBy,
      'purchaseOrderItems' => $purchaseOrderItems,
      'purchaseOrderItemBids' => $purchaseOrderItemBids,
      'user' => $user,
    ]);
  }

  public function viewAction()
  {
    $id = (int)$this->params('id');

    if (!$id) {
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $purchaseOrder = $this->getPurchaseOrderMapper()->getPurchaseOrder($id);
    if(!$purchaseOrder){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    return new ViewModel([

    ]);
  }

  public function deleteAction()
  {
    $id = (int)$this->params('id');

    if (!$id) {
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $purchaseOrder = $this->getPurchaseOrderMapper()->getPurchaseOrder($id);
    if(!$purchaseOrder){
      $this->flashMessenger()->setNamespace('error')->addMessage('Invalid Purchase Order.');
      return $this->redirect()->toRoute('purchase-order');
    }

    $this->getPurchaseOrderItemMapper()->deleteByPurchaseOrderId($id);
    $this->getPurchaseOrderMapper()->delete($id);

    $this->flashMessenger()->setNamespace('success')->addMessage('Purchase Order deleted successfully.');
    return $this->redirect()->toRoute('purchase-order');
  }
}
