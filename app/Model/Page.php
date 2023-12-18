<?php 
class Page extends AppModel
{
	public $name = 'Page';
	//public $belongsTo = array('User');
	var $validate = array(
		
	    
		'page_title' => array(
			'rule'=>'notEmpty',
			'message'=>"Page Title can not be blank"),
		'menu_title' => array(
			'rule'=>'notEmpty',
			'message'=>"Menu Title can not be blank"),
		'content' => array(
			'rule'=>'notEmpty',
			'message'=>"Content can not be blank"),
		
	    );
	
}
?>
