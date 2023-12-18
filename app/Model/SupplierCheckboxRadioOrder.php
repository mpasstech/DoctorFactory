<?php
class SupplierCheckboxRadioOrder extends AppModel
{
    public $useTable = 'supplier_checkbox_radio_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array('Thinapp','SupplierOrder','SupplierTitleField','SupplierOrderDetail','SupplierCheckboxRadioField');

}