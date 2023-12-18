<?php
class SellItem extends AppModel
{
    public $useTable = 'sell_items';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
        ),
		'Thinapp' => array(
            'className' => 'Thinapp',
            'foreignKey' => 'thinapp_id',
        ),
		'SellItemCategory' => array(
            'className' => 'SellItemCategory',
            'foreignKey' => 'sell_item_category_id',
        ),
		'Channel' => array(
            'className' => 'Channel',
            'foreignKey' => 'channel_id',
        ),		
    );
	public $hasMany = array(
		'SellImage' => array(
            'className' => 'SellImage',
            'foreignKey' => 'sell_item_id',
        ),
		'SellAction' => array(
			'className' => 'MessageAction',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"MessageAction.list_message_type" =>"SELL"
			)
		)
	);
	public $hasOne = array(
		'CoverImage' => array(
            'className' => 'SellImage',
            'foreignKey' => 'sell_item_id',
			'conditions' => array('CoverImage.is_cover_image'=>'YES')
        ),
		'SellStatic' => array(
			'className' => 'MessageStatic',
			'foreignKey' => 'message_id',
			'conditions'=>array(
				"SellStatic.list_message_type" =>"SELL"
			)
		)
	);
}