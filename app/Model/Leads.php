<?php

class Leads extends AppModel
{
    public $useTable = 'customer_lead';
    public $primaryKey = 'customer_id';

	 public $belongsTo = array(
         'User',
        'Support' => array(
            'className' => 'User',
            'foreignKey' => 'support_admin_id',
        )
    );

    public $hasMany = array(
        'AppQueries' => array(
            'className' => 'AppQueries',
            'foreignKey' => 'customer_lead_id',
            'associatedKey'   => 'customer_id',
			"order"=>"AppQueries.id DESC"
        ),
    );

	
    var $validate = array(
        'app_id' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'App id can not be empty'),
            'isUnique' => array ('rule' => 'isUnique','message' => 'This id cannot user.'),
        ),
        'app_name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'App name can not be empty')
        ),
        'app_theme' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select them for app')
        ),
        'app_logo' => array(
            'extention'=>array(
                'rule' => array('chkImageExtension'),
                'message' => 'Please supply a valid image with png extension only.'
            )
        )
    );


    public function chkImageExtension($data) {
        $return = true;
        if(isset($data['app_logo']['name']) && $data['app_logo']['name'] != ''){
            $fileData   = pathinfo($data['app_logo']['name']);
            $ext        = $fileData['extension'];
            $allowExtension = array('png');

            if(in_array($ext, $allowExtension)) {
                $return = true;
            } else {
                $return = false;
            }
        }

        return $return;
    }

    function identicalFieldValues( $field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];
            if($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }

    function MobileCheck($mobile = null)
    {
        //pr($this->data); die;
        $mobile = substr($mobile['phone'], -10);;
        $user_id = CakeSession::read("Auth.User.id");
        //echo $user_id ; die;
        if(isset($user_id)){
            $user = $this->find('first',array('conditions'=>array('id !='=>$user_id,'RIGHT(mobile,10)'=>$mobile),'recursive'=>-1));
        }else{
            $user = $this->find('first',array('conditions'=>array('RIGHT(mobile,10)'=>$mobile),'recursive'=>-1));
        }
        if(isset($user) && !empty($user)){
            return false;
        }
        return true;

    }



}