<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');
include (WWW_ROOT."webservice".DS."ConnectionUtil.php");
include (WWW_ROOT."webservice".DS."QueueManagementWebService.php");
/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class QueueManagementController extends AppController
{

    /**
     * This controller does not use a model
     *
     * @var array
     */

    public $uses = array();

    function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('web_app_dashboard','index','get_chatbot_url');
    }

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     * @throws NotFoundException When the view file could not be found
     *    or MissingViewException in debug mode.
     */
    public function index($doctor = null)
    {

    	
        
        $doctor_id = $this->request->query['t'];
    	$doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
        $wa = @$this->request->query['wa'];
   		$pm = @$this->request->query['pm'];
    	if(!empty($doctor_data)){
        	$t = base64_encode($doctor_data['thinapp_id']);
        	$actual_link = SITE_PATH."tracker/fortis?sh=y&t=".$t."&pm=".$pm;
        	header("Location: $actual_link");
        	die;   
    
        }
        
    
        
        if(empty($doctor_data['doctor_code'])){
            $res = Custom::setup_ivr_data($doctor_data['thinapp_id'], base64_decode($doctor_id),'',true);
            $doctor_data = Custom::get_doctor_info(base64_decode($doctor_id));
        }
        $department_search = isset($this->request->query['d']) ? $this->request->query['d'] : 'NO';
        $this->layout = false;

        //pr($doctor_data); die;
        if (!empty($doctor_data)) {

            $thin_app_id = $doctor_data['thinapp_id'];
            /* add to home icon functionality start*/
            $dir = false;
            $icon_folder = LOCAL_PATH . 'app/webroot/add_home_screen/' . $doctor_data['doctor_id'] . "/icons";
            $manifest = LOCAL_PATH . 'app/webroot/add_home_screen/' . $doctor_data['doctor_id'] . "/manifest.json";
            $default_manifest = LOCAL_PATH . 'app/webroot/add_home_screen/doctorapps/manifest.json';
            if (!is_dir($icon_folder)) {
                if (Custom::createIconFolder($icon_folder)) {
                    $dir = true;
                }
            } else {
                $dir = true;
            }
            if ($dir) {
                if (!file_exists($manifest)) {
                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    $json_data = json_decode(file_get_contents($default_manifest), true);
                    $json_data['name'] = $doctor_data['app_name'];
                    $json_data['short_name'] = $doctor_data['app_name'];
                    $json_data['start_url'] = $actual_link;
                    $json_data['theme_color'] = "#0952EE";
                    $json_data['background_color'] = "#ffffff";
                    $has_manifest = file_put_contents($manifest, json_encode($json_data));
                    $logo = $doctor_data['logo'];
                    $array = array("72", "96", "128", "144", "152", "192", "384", "512");
                    foreach ($array as $key => $value) {
                        $res = Custom::download_image_from_url($logo, $value, $value, $icon_folder . "/icon-$value" . "x" . "$value.png");
                    }
                }
            }
            /* add to home icon functionality end*/
            $this->set(compact('pm','category_name', 'thin_app_id', 'wa', 'department_search', 'doctor_id', 'doctor_data'));

        } else {
            exit();
        }

    }

    public function web_app_dashboard()
    {
        $this->layout = 'ajax';
        if ($this->request->is('ajax')) {
            $post['app_key'] = APP_KEY;
            $thin_app_id = base64_decode($this->request->data['ti']);
            $doctor_id = base64_decode($this->request->data['di']);
            $doctor_data = Custom::get_doctor_info($doctor_id);
            $this->set(compact('doctor_data','channel_id','appointment_data','show_add_banner','thin_app_id','doctor_id'));
            $this->render('web_app_dashboard', 'ajax');
        } else {
            exit();
        }
    }

    public function get_chatbot_url()
    {

        $this->layout = 'ajax';
        $data=array();
        if ($this->request->is('ajax') && !empty($this->request->data)) {
            $mobile = base64_decode($this->request->data['m']);
            $mobile = Custom::create_mobile_number($mobile);
            $thin_app_id = base64_decode($this->request->data['ti']);
            $doctor_id = base64_decode($this->request->data['di']);
            echo Custom::createChatBoatLink($thin_app_id,$mobile,$doctor_id,false);die;
        }
    }


}