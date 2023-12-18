<?php
class SupplierAttachmentField extends AppModel
{
    public $useTable = 'supplier_attachment_fields';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp');
}