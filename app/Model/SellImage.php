<?php
class SellImage extends AppModel
{
    public $useTable = 'sell_images';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'SellItem' => array(
            'className' => 'SellItem',
            'foreignKey' => 'sell_item_id',
        ),		
    );
}