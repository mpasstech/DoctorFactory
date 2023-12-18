<?php
class TeethAttachment extends AppModel
{
    public $useTable = 'teeth_attachments';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),		
    );
}