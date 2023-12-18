<?php

class PrescriptionTag extends AppModel {

    var $validate = array(
        'name' => array(
            'notEmpty' => array (
                'rule' => 'notEmpty',
                'message' => 'Please enter tage name')
            ,'emailRule-3' =>array(
                'rule' => array('checkUnique'),
                'message' => 'Tag already exist.'),
        ),
        'description' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter description.')
        )
    );


    public function checkUnique($data=null) {

        if(CakeSession::read("Auth.User.User")){
            $login_data = CakeSession::read("Auth.User.User");;
        }else{
            $login_data = CakeSession::read("Auth.User");;

        }

        $thin_app_id = ($login_data['role_id']==5)?$login_data['thinapp_id']:0;


        $name = $data['name'];

        $data = $this->data['PrescriptionTag'];
        if($data['type'] == "PRESCRIPTION_TAG"){
            $condition = array(
                'PrescriptionTag.thinapp_id' => $thin_app_id,
                'PrescriptionTag.name' => trim($name),
                'PrescriptionTag.type' => $data['type']
            );

        }else{
            $condition = array(
                'PrescriptionTag.thinapp_id' => $thin_app_id,
                'PrescriptionTag.name' => trim($name),
                'PrescriptionTag.gender' => $data['gender'],
                'PrescriptionTag.type' => $data['type'],
            );

        }

        if (isset($this->data["PrescriptionTag"]["id"])) {
            $condition["PrescriptionTag.id <>"] = $this->data["PrescriptionTag"]["id"];
        }



        $result = $this->find("count", array("conditions" => $condition));
        return ($result == 0);
    }


}
