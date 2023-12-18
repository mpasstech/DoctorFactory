<?php

class AppEnableFunctionality extends AppModel {

    public $actsAs = array('Containable');
  //public $belongsTo = array('AppFunctionalityType');
   public $hasMany = array('UserEnabledFunPermission');
}
 ?>
