<?php
class QuestCategory extends AppModel
{
    public $useTable = 'quest_categories';
	public $actsAs = array('Containable');
	public $hasOne = array(
		'Quest' => array(
            'className' => 'Quest',
            'foreignKey' => 'quest_category_id'
        )
	);
	
}