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
class FranchiseHelper extends AppHelper {




    public function splitAfterDecimal($doctor_id,$decimal=2){
        return Custom::splitAfterDecimal($doctor_id,$decimal);
    }
    public function getFranchiseRoleLabel($string){
        return Custom::getFranchiseRoleLabel($string);
    }

    Public function getAssociateAppListOption($mediator_id){
        $query = "SELECT t.id, t.name AS app_name FROM booking_convenience_fee_details AS bcfd JOIN thinapps AS t ON t.id = bcfd.thinapp_id where  ( bcfd.primary_owner_id = $mediator_id OR bcfd.secondary_owner_id = $mediator_id OR bcfd.primary_mediator_id = $mediator_id OR bcfd.secondary_mediator_id = $mediator_id) AND bcfd.`status` = 'ACTIVE' ORDER BY t.name asc ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQL_ASSOC),'app_name','id');
        }
        return false;
    }





}
