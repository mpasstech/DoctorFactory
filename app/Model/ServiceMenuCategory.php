<?php
class ServiceMenuCategory extends AppModel
{
    
	public $actsAs = array('Containable');

	var $validate = array(
		'name' => array(
			'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter category.'),
			'isUnique' => array ('rule' => array('checkUnique','id'),'message' => 'This category already exists.')
		)
	);

	function checkUnique($data,$id)
	{
		$thinapp_id = CakeSession::read("Auth.User.User.thinapp_id");
		$user = $this->find('first',array('conditions'=>array('thinapp_id'=>$thinapp_id,'name'=>$data['name'])));
		if(!empty($user)){
			if(isset($this->data['ServiceMenuCategory']['id'])){
				if($this->data['ServiceMenuCategory']['id'] == $user['ServiceMenuCategory']['id']){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		return true;
	}
}