<?php
class SupplierTextTextareaField extends AppModel
{
    public $useTable = 'supplier_text_textarea_fields';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp');
}