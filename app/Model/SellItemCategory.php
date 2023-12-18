<?php
class SellItemCategory extends AppModel
{
		public $useTable = 'sell_item_categories';
		public $actsAs = array('Containable');
		public $hasMany = array(
			'SellItem' => array(
				'className' => 'SellItem',
				'foreignKey' => 'sell_item_category_id',
			),
		);
}