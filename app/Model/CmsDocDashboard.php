<?php

class CmsDocDashboard extends Model {


    public $belongsTo = array('Thinapp');
	
	

    var $validate = array(
        'title' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Title can not be empty')
        ),
        'description' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter page content.')
        ),
        'image' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please upload image.')
        )
    );


    public function checkUnique($data=null) {
        $thinapp_id = CakeSession::read("Auth.User");;
        $slug = $data['slug'];
        $condition = array(
            'CmsDocDashboard.thinapp_id' => $thinapp_id['User']['thinapp_id'],
            'CmsDocDashboard.slug' => trim($slug)
        );
        if (isset($this->data["CmsDocDashboard"]["id"])) {
            $condition["CmsDocDashboard.id <>"] = $this->data["CmsDocDashboard"]["id"];
        }
        $result = $this->find("count", array("conditions" => $condition));
        return ($result == 0);
    }

	
}
