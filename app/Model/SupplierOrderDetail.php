<?php
class SupplierOrderDetail extends AppModel
{
    public $useTable = 'supplier_order_details';
	public $actsAs = array('Containable');
	public $belongsTo = array('SupplierOrder');
    public $hasMany = array(
        "SupplierAttachmentOrder",
        "SupplierCheckboxRadioOrder",
        "SupplierTeethNumberOrder",
        "SupplierTextTextareaFieldOrder"

    );
	
}