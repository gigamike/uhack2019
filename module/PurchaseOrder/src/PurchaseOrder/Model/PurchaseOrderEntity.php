<?php
namespace PurchaseOrder\Model;

class PurchaseOrderEntity
{
	protected $id;
	protected $status;
	protected $timeline_datetime;
	protected $comments;
	protected $created_datetime;
	protected $created_user_id;

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

	public function getStatus()
	{
		return $this->status;
	}

	public function setStatus($value)
	{
		$this->status = $value;
	}

	public function getTimelineDatetime()
	{
		return $this->timeline_datetime;
	}

	public function setTimelineDatetime($value)
	{
		$this->timeline_datetime = $value;
	}

	public function getComments()
	{
		return $this->comments;
	}

	public function setComments($value)
	{
		$this->comments = $value;
	}

	public function getCreatedDatetime()
	{
		return $this->created_datetime;
	}

	public function setCreatedDatetime($value)
	{
		$this->created_datetime = $value;
	}

	public function getCreatedUserId()
	{
		return $this->created_user_id;
	}

	public function setCreatedUserId($value)
	{
		$this->created_user_id = $value;
	}
}
