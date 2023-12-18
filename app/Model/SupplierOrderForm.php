<?php
class SupplierOrderForm extends AppModel
{
    public $useTable = 'supplier_order_forms';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','SupplierTextTextareaField','SupplierTitleField','SupplierAttachmentField');
	
}