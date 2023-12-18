<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Helper', 'View');
App::uses('SessionComponent', 'Controller/Component');



/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class SupportAdminHelper extends AppHelper {

    public function getOrgName($id)
    {
        $user = ClassRegistry::init('OrganizationType');
        $user_data = $user->find('first',array('conditions'=>array('OrganizationType.org_type_id'=>$id)));
        
        $user_image = $user_data['OrganizationType']['org_type'];
        return $user_image;
    }


    public function get_public_channel()
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('list',array('fields'=>array('Channel.id','Channel.channel_name'),'conditions'=>array('Channel.channel_status'=>"PUBLIC")));
        return $user_data;
    }

    

   public function getAllThinappDropdwon()
    {
        $user = ClassRegistry::init('Thinapps');
        $user_data = $user->find('list',array('fields'=>array('Thinapps.id','Thinapps.name'),'conditions'=>array('Thinapps.status'=>"ACTIVE"),'order'=>array('Thinapps.name'=>'asc')));
        return $user_data;
    }
   public function appUserCount($thin_app_id)
    {
        $user = ClassRegistry::init('Users');
        $user_data = $user->find('count',array('conditions'=>array('Users.role_id'=>1,'Users.thinapp_id'=>$thin_app_id)));
        return $user_data;
    }

    public function getSubscriptionMonthInterval()
    {
        $type = ClassRegistry::init('Thinapp')->getColumnType('subscription_interval');
        preg_match_all("/'(.*?)'/", $type, $enums);
        $enum_array=array();
        foreach ($enums[1] as $key => $val){
            $enum_array[$val]=$val;
        }
        return $enum_array;
    }


    public function getStorageEnum()
    {
        $type = ClassRegistry::init('Thinapp')->getColumnType('cloud_storage');
        preg_match_all("/'(.*?)'/", $type, $enums);
        $enum_array=array();
        foreach ($enums[1] as $key => $val){
            $enum_array[$val]=$val;
        }
        return $enum_array;
    }

    public function isMainSupportAdmin()
    {


        $session  = new SessionComponent(new ComponentCollection());
        if ($user = $session->read('Auth.User')) {

            if($user['role_id'] == 4 && $user['mobile'] == MAIN_SUPPORT_ADMIN_MOBILE){
                return true;
            }
        }return false;
    }




    
}
