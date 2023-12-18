<?php

class CmsPage extends Model {


    public $belongsTo = array('Thinapp');
    public $hasMany = array('CmsPageBody');
    public $actsAs = array('Containable');

    var $validate = array(
        'title' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Title can not be empty')
        )


    );


    public function checkUnique($data=null) {
        $thinapp_id = CakeSession::read("Auth.User");;
        $slug = $data['slug'];
        $condition = array(
            'CmsPage.thinapp_id' => $thinapp_id['User']['thinapp_id'],
            'CmsPage.slug' => trim($slug)
        );
        if (isset($this->data["CmsPage"]["id"])) {
            $condition["CmsPage.id <>"] = $this->data["CmsPage"]["id"];
        }
        $result = $this->find("count", array("conditions" => $condition));
        return ($result == 0);
    }

	
}
