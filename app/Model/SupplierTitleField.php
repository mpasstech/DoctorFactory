<?php
class SupplierTitleField extends AppModel
{
    public $useTable = 'supplier_title_fields';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp');
	public $hasMany = array("SupplierCheckboxRadioField");
}