<?php
class TeethAttachmentOrder extends AppModel
{
    public $useTable = 'teeth_attachment_orders';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),	
		'TeethOrder' => array(
            'className' => 'TeethOrder',
            'foreignKey' => 'teeth_order_id',
        ),	
		'TeethAttachment' => array(
            'className' => 'TeethAttachment',
            'foreignKey' => 'teeth_attachment_id',
        ),	

		
    );
}