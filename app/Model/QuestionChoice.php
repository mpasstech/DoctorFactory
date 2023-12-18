<?php

class QuestionChoice extends AppModel {

	public $actsAs = array('Containable');
		public $belongsTo = array(
			'ActionQuestion',
			'ThinApp'
		);

	}
