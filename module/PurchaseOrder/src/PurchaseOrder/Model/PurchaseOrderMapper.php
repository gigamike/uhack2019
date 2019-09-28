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
use PurchaseOrder\Model\PurchaseOrderEntity;

class PurchaseOrderMapper
{
	protected $tableName = 'purchase_order';
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

		if(isset($filter['supplier_id'])){
			$where->equalTo("supplier_id", $filter['supplier_id']);
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
		    $entityPrototype = new PurchaseOrderEntity();
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

		    $entityPrototype = new PurchaseOrderEntity();
		    $hydrator = new ClassMethods();
		    $resultset = new HydratingResultSet($hydrator, $entityPrototype);
		    $resultset->initialize($results);
		}

		return $resultset;
	}

	public function save(PurchaseOrderEntity $purchaseOrder)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($purchaseOrder);

		if ($purchaseOrder->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $purchaseOrder->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();

		if (!$purchaseOrder->getId()) {
			$purchaseOrder->setId($result->getGeneratedValue());
		}
		return $result;
	}

	public function getPurchaseOrder($id)
	{
		$select = $this->sql->select();
		$select->where(array('id' => $id));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		if (!$result) {
			return null;
		}

		$hydrator = new ClassMethods();
		$purchaseOrder = new PurchaseOrderEntity();
		$hydrator->hydrate($result, $purchaseOrder);

		return $purchaseOrder;
	}

	public function delete($id)
	{
    $delete = $this->sql->delete();
    $delete->where(array('id' => $id));

    $statement = $this->sql->prepareStatementForSqlObject($delete);
    return $statement->execute();
	}

	public function getPurchaseOrders($paginated=false, $filter = array(), $order=array(), $limit = null)
	{
    $select = $this->sql->select();
		$select->columns(array(
				'id',
				'status',
				'created_datetime',
				'created_user_id',
		));
		$select->join(
      'user',
      $this->tableName . ".created_user_id = user.id",
      array(
				'email',
				'first_name',
				'last_name',
			),
      $select::JOIN_INNER
    );
		$select->join(
      'purchase_order_item',
      $this->tableName . ".id = purchase_order_item.purchase_order_id",
      array(
				'amount' => new \Zend\Db\Sql\Expression("IF(SUM(quantity*unit_price) IS NULL, 0, SUM(quantity*unit_price))"),
			),
      $select::JOIN_LEFT
    );

    $where = new \Zend\Db\Sql\Where();

		if(isset($filter['id'])){
		    $where->equalTo($this->tableName . ".id", $filter['id']);
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

		$select->group(array($this->tableName . ".id"));

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
