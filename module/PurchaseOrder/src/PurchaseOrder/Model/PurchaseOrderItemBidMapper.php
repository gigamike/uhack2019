<?php
namespace PurchaseOrder\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;
use Zend\Db\Sql\Expression;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\ResultSet;
use PurchaseOrder\Model\PurchaseOrderItemBidEntity;

class PurchaseOrderItemBidMapper
{
	protected $tableName = 'purchase_order_item_bid';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function fetch($paginated=false, $filter = array(), $order=array(), $limit = null)
	{
		$select = $this->sql->select();
		$where = new \Zend\Db\Sql\Where();

		if(isset($filter['id'])){
			$where->equalTo("id", $filter['id']);
		}

		if(isset($filter['purchase_order_id'])){
			$where->equalTo("purchase_order_id", $filter['purchase_order_id']);
		}

		if(isset($filter['supplier_id'])){
			$where->equalTo("supplier_id", $filter['supplier_id']);
		}

		if(isset($filter['item'])){
		    $where->equalTo("item", $filter['item']);
		}

		if(isset($filter['item_keyword'])){
			$where->addPredicate(
					new \Zend\Db\Sql\Predicate\Like("item", "%" . $filter['item_keyword'] . "%")
			);
		}

		if (!empty($where)) {
			$select->where($where);
		}

		if(count($order) > 0){
		    $select->order($order);
		}

		if(!is_null($limit)){
			$select->limit($limit);
		}

		// echo $select->getSqlString($this->dbAdapter->getPlatform());exit();

		if($paginated) {
		    $entityPrototype = new PurchaseOrderItemBidEntity();
		    $hydrator = new ClassMethods();
		    $resultset = new HydratingResultSet($hydrator, $entityPrototype);

			$paginatorAdapter = new DbSelect(
					$select,
					$this->dbAdapter,
					$resultset
			);
			$paginator = new Paginator($paginatorAdapter);
			return $paginator;
		}else{
		    $statement = $this->sql->prepareStatementForSqlObject($select);
		    $results = $statement->execute();

		    $entityPrototype = new PurchaseOrderItemBidEntity();
		    $hydrator = new ClassMethods();
		    $resultset = new HydratingResultSet($hydrator, $entityPrototype);
		    $resultset->initialize($results);
		}

		return $resultset;
	}

	public function save(PurchaseOrderItemBidEntity $purchaseOrderItemBidBid)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($purchaseOrderItemBidBid);

		if ($purchaseOrderItemBidBid->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $purchaseOrderItemBidBid->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$purchaseOrderItemBidBid->getId()) {
			$purchaseOrderItemBidBid->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function getPurchaseOrderItemBid($id)
	{
		$select = $this->sql->select();
		$select->where(array('id' => $id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		if (!$result) {
			return null;
		}

		$hydrator = new ClassMethods();
		$purchaseOrderItemBidBid = new PurchaseOrderItemBidEntity();
		$hydrator->hydrate($result, $purchaseOrderItemBidBid);

		return $purchaseOrderItemBidBid;
	}

	public function delete($id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('id' => $id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}

	public function deleteByPurchaseOrderId($purchase_order_id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('purchase_order_id' => $purchase_order_id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}

	public function getCountBid($filter = array())
	{
    $select = $this->sql->select();
    $select->columns(array(
      'count_id' => new \Zend\Db\Sql\Expression("COUNT(DISTINCT(" . $this->tableName . ".supplier_id))"),
    ));

    $where = new \Zend\Db\Sql\Where();

		if(isset($filter['purchase_order_id'])){
			$where->equalTo("purchase_order_id", $filter['purchase_order_id']);
		}

    if (!empty($where)) {
        $select->where($where);
    }

    // echo $select->getSqlString($this->dbAdapter->getPlatform()) . "<br />";

    $statement = $this->sql->prepareStatementForSqlObject($select);
    $result = $statement->execute()->current();
    if (!$result) {
        return null;
    }

    return $result;
	}

	public function getPurchaseOrderItemBids($paginated=false, $filter = array(), $order=array(), $limit = null)
	{
    $select = $this->sql->select();
		$select->columns(array(
			'supplier_id',
			'amount' => new \Zend\Db\Sql\Expression("IF(SUM(" . $this->tableName . ".quantity * " . $this->tableName . ".unit_price) IS NULL, 0, SUM(" . $this->tableName . ".quantity * " . $this->tableName . ".unit_price))"),
		));
		$select->join(
      'user',
      $this->tableName . ".supplier_id = user.id",
      array(
				'company_name',
			),
      $select::JOIN_INNER
    );

    $where = new \Zend\Db\Sql\Where();

		if(isset($filter['id'])){
		    $where->equalTo($this->tableName . ".id", $filter['id']);
		}

		if(isset($filter['purchase_order_id'])){
			$where->equalTo("purchase_order_id", $filter['purchase_order_id']);
		}

    if (!empty($where)) {
        $select->where($where);
    }

    if(count($order) > 0){
			$select->order($order);
    }

		if(!is_null($limit)){
	    $select->limit($limit);
		}

		$select->group(array($this->tableName . ".supplier_id"));

    // echo $select->getSqlString($this->dbAdapter->getPlatform()) . "<br>"; exit();

    if($paginated) {
        $paginatorAdapter = new DbSelect(
            $select,
            $this->dbAdapter
        );
        $paginator = new Paginator($paginatorAdapter);
        return $paginator;
    }else{
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new ResultSet;
            $resultSet->initialize($result);
        }
    }

    return $resultSet;
	}
}
