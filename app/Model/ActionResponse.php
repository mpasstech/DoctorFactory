<?php

class ActionResponse extends AppModel {

	public $actsAs = array('Containable');
	public $belongsTo = array(
		'ActionQuestion',
		'User',
		'ActionType',
		'QuestionChoice',
	);


	}
