<?php
class User extends AppModel
{

    public $actsAs = array('Containable');
    public $name = 'User';
    public $belongsTo = array('Thinapp','Role');
    public $hasOne = array('Leads',
        'AppointmentStaff' => array(
            'className' => 'AppointmentStaff',
            'foreignKey' => 'user_id',
            'conditions'=>array('AppointmentStaff.status' => 'ACTIVE')

        )
    );
    public $hasMany = array();
    public $countryCode = '';

    var $validate = array(
        'username' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Name can not be empty'),
            //'isUnique' => array ('rule' => 'isUnique','message' => 'This username already exists.'),
            //'custom' => array ('rule' => array('custom', '/^[A-Za-z0-9,\.-_]*$/i'),'message' => 'The username can only contain letters, numbers.'),
            //'usernameminlimit'=>array('rule'=>array('minLength', '4	'),'message'=>"Username must at least 4 character"),
            //'usernamemaxlimit'=>array('rule'=>array('maxLength', '20'),'message'=>"Username not more than 20 character"),
        ),

        'org_name' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Orgnization name can not be empty')
        ),

        'org_type' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please select orgnization type')
        ),
        'email' => array(
            'emailRule-1' => array(
                'rule'=>array('notEmpty'),
                'message'=>'Email can not be blank'),
            'emailRule-2' => array(
                'rule'=>array('email'),
                'message' => 'Invalid email address')

        ),
        'org_unique_url' => array(
            /*'emailRule-1' => array(
                'rule'=>array('notEmpty'),
                'message'=>'Email can not be blank'),*/
            'emailRule-2' => array(
                'rule'=>array('custom', '/^([a-z0-9]+-)*[a-z0-9]+$/i'),
                'message' => 'Use alphanumeric characters with single hyphen only'),
            'emailRule-3' =>array(
                'rule' => array('isUnique'),
                'message' => 'Sorry you can not use this url'),
        ),

        'password' => array(
            'checkempty'=>array('rule'=>'notEmpty','message'=>"Password can not be blank"),
            'minlimit'=>array('rule'=>array('minLength', '5'),'message'=>"Password must at least 5 character"),
            'maxlimit'=>array('rule'=>array('maxLength', '50'),'message'=>"Password not more than 15 character"),
        ),
        'confirm_password' => array(
            'complex' => array('rule' => array('identicalFieldValues', 'password' ),'message' => 'Passwords does not match.'),
            'notEmpty' => array('rule' => array('notEmpty'),'allowEmpty' => false,'message' => 'Confirm password can not be blank',),
        ),
        'mobile' => array(
            //'rule' => array('phone', null, 'us')
            // 'on' => 'update',
            // 'on' => 'create',
            // 'required' => true,
            'phone_notEmpty'=>array('rule' => 'notEmpty','message' => 'Mobile field can not be blank'),
            /*  'complex' => array('rule' => array('MobileCheck'),'message' => 'Mobile Number already exist.'),*/
            //'isUnique' => array ('rule' => 'isUnique','message' => 'Mobile Number already exists.'),
            //'isNumeric' => array ('rule' => 'isNumeric','message' => 'Phone number can not contain letters.'),
            //'minlimit'=>array('rule'=>array('minLength', '10'),'message'=>"Enter 10 digit mobile number"),
            //'maxlimit'=>array('rule'=>array('maxLength', '13'),'message'=>"Enter 10 digit mobile number"),
        ),

        'code' => array(
            'notEmpty' => array ('rule' => 'notEmpty','message' => 'Please enter verification code.')
        )

        /*  'sms_charge' => array(
              'checkempty'=>array('rule'=>'notEmpty','message'=>"SMS charge can not be blank"),
              'minlimit'=>array('rule'=>array('minLength', '0'),'message'=>"SMS charge must at least 0"),
          ),
          'push_charge' => array(
              'checkempty'=>array('rule'=>'notEmpty','message'=>"Push charge can not be blank"),
              'minlimit'=>array('rule'=>array('minLength', '0'),'message'=>"Push charge must at least 0"),
          ), */

    );

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
        $mobile = substr($mobile['mobile'], -10);;
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
?>
