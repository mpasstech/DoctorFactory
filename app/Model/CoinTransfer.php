<?php
class CoinTransfer extends AppModel
{
    public $useTable = 'coin_transfers';
	public $actsAs = array('Containable');
	public $belongsTo = array(
        'TransferBy' => array(
            'className' => 'User',
            'foreignKey' => 'transfer_by_user_id',
        ),
		'TransferTo' => array(
            'className' => 'User',
            'foreignKey' => 'transfer_to_user_id',
        ),	
    );
}