<?php

class AppStackTrace extends AppModel
{
    public $useTable = 'app_stack_traces';
	public $actsAs = array('Containable');
}