<?php

class AppEnquiry extends AppModel
{
    public $useTable = 'app_enquiry';

    public $belongsTo = array(
        'Support' => array(
            'className' => 'User',
            'foreignKey' => 'supp_admin_id',
        )
    );

 //   public $hasOne = array('Thinapp','Leads');
    
	var $validate = array(
        'name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Name can not be empty')
        ),
        'message' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter you message.')
        ),
        'email' => array(
            'emailRule-1' => array(
                'rule'=>array('notEmpty'),
                'message'=>'Email can not be blank'),
            'emailRule-2' => array(
                'rule'=>array('email'),
                'message' => 'invalid email address')

        ),
        'phone' => array(
            'phone_notEmpty'=>array('rule' => 'notEmpty','message' => 'Phone number can not be blank'),
            'minlimit'=>array('rule'=>array('minLength', '10'),'message'=>"Invalid Phone number"),
            'maxlimit'=>array('rule'=>array('maxLength', '13'),'message'=>"Invalid Phone number")
        )
       /* 'attachment' => array(
            'rule' => array('chkImageExtension'),
            'message' => 'Please supply a valid file with docx,doc,pdf file extension only.'
        )*/

    );

    public function chkImageExtension($data) {
        $return = true;
        if(isset($data['attachment']['name']) && $data['attachment']['name'] != ''){
            $fileData   = pathinfo($data['attachment']['name']);
            $ext        = $fileData['extension'];
            $allowExtension = array('docx', 'doc', 'pdf');

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