<?php

class UserEnabledFunPermission extends AppModel {

    public $actsAs = array('Containable');
    public $belongsTo = array('UserFunctionalityType');

}
 ?>
