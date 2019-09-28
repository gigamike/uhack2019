<?php
namespace PurchaseOrder\Model;

class PurchaseOrderItemBidEntity
{
	protected $id;
	protected $purchase_order_id;
	protected $supplier_id;
	protected $item;
	protected $quantity;
	protected $unit_price;
	protected $created_datetime;

	public function __construct()
	{
		$this->created_datetime = date('Y-m-d H:i:s');
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getPurchaseOrderId()
	{
		return $this->purchase_order_id;
	}

	public function setPurchaseOrderId($value)
	{
		$this->purchase_order_id = $value;
	}

	public function getSupplierId()
	{
		return $this->supplier_id;
	}

	public function setSupplierId($value)
	{
		$this->supplier_id = $value;
	}

	public function getItem()
	{
		return $this->item;
	}

	public function setItem($value)
	{
		$this->item = $value;
	}

	public function getQuantity()
	{
		return $this->quantity;
	}

	public function setQuantity($value)
	{
		$this->quantity = $value;
	}

	public function getUnitPrice()
	{
		return $this->unit_price;
	}

	public function setUnitPrice($value)
	{
		$this->unit_price = $value;
	}

	public function getCreatedDatetime()
	{
		return $this->created_datetime;
	}

	public function setCreatedDatetime($value)
	{
		$this->created_datetime = $value;
	}
}
