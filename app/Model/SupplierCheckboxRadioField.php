<?php
class SupplierCheckboxRadioField extends AppModel
{
    public $useTable = 'supplier_checkbox_radio_fields';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','SupplierTitleField');
}