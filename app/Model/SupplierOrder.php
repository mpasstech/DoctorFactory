<?php
class SupplierOrder extends AppModel
{
    public $useTable = 'supplier_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','SupplierHospital','Supplier');
	public $hasMany = array(
	    "SupplierOrderDetail" => array("order"=>"sort_no ASC")
    );
}