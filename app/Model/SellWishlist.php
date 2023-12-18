<?php
class SellWishlist extends AppModel
{
		public $useTable = 'sell_wishlists';
		public $actsAs = array('Containable');
		public $belongsTo = array(
			'SellItem' => array(
				'className' => 'SellItem',
				'foreignKey' => 'sell_item_id',
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'user_id',
			),			
		);
}