<?php
class SellLike extends AppModel
{

	public $actsAs = array('Containable');
	public $belongsTo = array(
        'SellItem' => array(
            'className' => 'SellItem',
            'foreignKey' => 'sell_item_id',
        ),		
    );
}