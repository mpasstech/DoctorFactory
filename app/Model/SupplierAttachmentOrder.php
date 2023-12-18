<?php
class SupplierAttachmentOrder extends AppModel
{
    public $useTable = 'supplier_attachment_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp');
	
}