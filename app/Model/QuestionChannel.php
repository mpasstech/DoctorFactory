<?php

class QuestionChannel extends AppModel {

	public $actsAs = array('Containable');
	public $belongsTo = array(
		'ActionQuestion',
		'Channel',
		'ThinApp'
	);


}
