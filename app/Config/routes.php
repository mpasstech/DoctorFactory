<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */

Router::connect('/', array('controller' => 'homes', 'action' => 'index'));

Router::connect('/doctor_home', array('controller' => 'homes', 'action' => 'home'));
Router::connect('/dti/*', array('controller' => 'homes', 'action' => 'dti'));

Router::connect('/l/*', array('controller' => 'homes', 'action' => 'r'));
Router::connect('/s/*', array('controller' => 'homes', 'action' => 'r'));

Router::connect('/reset/*', array('controller' => 'users', 'action' => 'reset_password'));
Router::connect('/qutot', array('controller' => 'qutot', 'action' => 'index'));

Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'index'));
Router::connect('/signup', array('controller' => 'users', 'action' => 'register_organization'));
Router::connect('/register-org', array('controller' => 'users', 'action' => 'register_organization'));
Router::connect('/contact', array('controller' => 'homes', 'action' => 'contact_us'));
Router::connect('/contact_us_ajax', array('controller' => 'homes', 'action' => 'contact_us_ajax'));
Router::connect('/enquiry', array('controller' => 'homes', 'action' => 'app_enquiry'));
Router::connect('/price', array('controller' => 'homes', 'action' => 'pricing'));
Router::connect('/features', array('controller' => 'homes', 'action' => 'features'));
Router::connect('/support', array('controller' => 'homes', 'action' => 'support'));
Router::connect('/usecase', array('controller' => 'homes', 'action' => 'usecase'));
Router::connect('/term', array('controller' => 'homes', 'action' => 'term'));
Router::connect('/privacy', array('controller' => 'homes', 'action' => 'privacy'));
Router::connect('/earn', array('controller' => 'homes', 'action' => 'earn'));
Router::connect('/blog', array('controller' => 'homes', 'action' => 'blog'));
Router::connect('/refund', array('controller' => 'homes', 'action' => 'refund'));
Router::connect('/career', array('controller' => 'homes', 'action' => 'career'));
Router::connect('/campaign', array('controller' => 'homes', 'action' => 'campaign'));
// Router::connect('/create-ticket', array('controller' => 'homes', 'action' => 'create_ticket'));




Router::connect('/request-call', array('controller' => 'homes', 'action' => 'request_call'));
Router::connect('/patient_engagement', array('controller' => 'homes', 'action' => 'doctor'));
Router::connect('/success', array('controller' => 'homes', 'action' => 'success'));
Router::connect('/vshare', array('controller' => 'homes', 'action' => 'vshare'));
Router::connect('/skype', array('controller' => 'homes', 'action' => 'skype'));
Router::connect('/patient_engagement', array('controller' => 'homes', 'action' => 'doctor'));
Router::connect('/success', array('controller' => 'homes', 'action' => 'success'));
Router::connect('/login', array('controller' => 'users', 'action' => 'org_login'));
Router::connect('/org-login', array('controller' => 'app_admin', 'action' => 'org_login'));
Router::connect('/forgot', array('controller' => 'users', 'action' => 'forgot_pasword'));
Router::connect('/franchise', array('controller' => 'franchise', 'action' => 'login'));
Router::connect('/lt/*', array('controller' => 'homes', 'action' => 'lt'));




Router::connect('/admin', array('controller' => 'admin', 'action' => 'login', 'admin' => true));

Router::connect('/supplier', array('controller' => 'supplier', 'action' => 'login'));


Router::connect('/get', array('controller' => 'homes', 'action' => 'app_store'));
Router::connect('/get/', array('controller' => 'homes', 'action' => 'app_store'));

Router::connect('/polls/*', array('controller' => 'polls', 'action' => 'index'));

Router::connect('/quests/*', array('controller' => 'quests', 'action' => 'index'));
Router::connect('/folder/consent/*', array('controller' => 'folder', 'action' => 'consent'));
Router::connect('/folder/refral_user_static/*', array('controller' => 'folder', 'action' => 'refral_user_static'));
Router::connect('/folder/rerefer', array('controller' => 'folder', 'action' => 'rerefer'));
Router::connect('/folder/get_refer_detail', array('controller' => 'folder', 'action' => 'get_refer_detail'));
Router::connect('/folder/verify/*', array('controller' => 'folder', 'action' => 'verify'));
Router::connect('/folder/lab_request/*', array('controller' => 'folder', 'action' => 'lab_request'));
Router::connect('/folder/verify_lab_request/*', array('controller' => 'folder', 'action' => 'verify_lab_request'));
Router::connect('/folder/verify_share/*', array('controller' => 'folder', 'action' => 'verify_share'));
Router::connect('/folder/send_otp/*', array('controller' => 'folder', 'action' => 'send_otp'));
Router::connect('/folder/record/*', array('controller' => 'folder', 'action' => 'record'));
Router::connect('/folder/add_file_notify/*', array('controller' => 'folder', 'action' => 'add_file_notify'));
Router::connect('/folder/upload_file/*', array('controller' => 'folder', 'action' => 'upload_file'));
Router::connect('/folder/load_records/*', array('controller' => 'folder', 'action' => 'load_records'));


Router::connect('/folder/*', array('controller' => 'folder', 'action' => 'index'));

//Router::connect('/doctor', array('controller' => 'homes', 'action' => 'home'));
/*Router::connect('/doctor/get_staff_info', array('controller' => 'doctor', 'action' => 'get_staff_info'));
Router::connect('/doctor/load_doctor_time_slot', array('controller' => 'doctor', 'action' => 'load_doctor_time_slot'));
Router::connect('/doctor/send_otp', array('controller' => 'doctor', 'action' => 'send_otp'));
Router::connect('/doctor/verify_and_book_appointment', array('controller' => 'doctor', 'action' => 'verify_and_book_appointment'));
Router::connect('/doctor/load_doctor_blog', array('controller' => 'doctor', 'action' => 'load_doctor_blog'));
Router::connect('/doctor/doctor_list', array('controller' => 'doctor', 'action' => 'doctor_list'));
Router::connect('/doctor/load_doctor_list', array('controller' => 'doctor', 'action' => 'load_doctor_list'));
Router::connect('/doctor/check_availability/*', array('controller' => 'doctor', 'action' => 'check_availability'));
Router::connect('/doctor/blog/*', array('controller' => 'doctor', 'action' => 'blog'));*/
//Router::connect('/doctor/*', array('controller' => 'doctor', 'action' => 'index'));

Router::connect('/fo/*', array('controller' => 'quests', 'action' => 'index'));
/* this router work for redirection url */
Router::connect('/:slug', array('controller' => 'app_admin', 'action' => 'login'));
//Router::connect('/docs', array('controller' => 'apis'));
//Router::connect('/docs/', array('controller' => 'apis'));
//echo $_SERVER['REQUEST_URI']; die;
if (preg_match("/docs/", $_SERVER['REQUEST_URI'])) {
    if (!preg_match("/admin/", $_SERVER['REQUEST_URI'])) {
        Router::connect('/:docs/:slug', array('controller' => 'docs', 'action' => 'index'), array('pass' => array('slug')));
    }
}



/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE . 'Config' . DS . 'routes.php';
