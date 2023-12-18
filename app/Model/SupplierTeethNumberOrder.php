<?php
class SupplierTeethNumberOrder extends AppModel
{
    public $useTable = 'supplier_teeth_number_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','SupplierOrder','SupplierTeethNumber','SupplierOrderDetail');
}