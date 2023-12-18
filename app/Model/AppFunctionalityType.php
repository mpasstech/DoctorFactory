<?php

class AppFunctionalityType extends AppModel {

  public $actsAs = array('Containable');
  public $hasMany = array('UserFunctionalityType');
  public $hasOne = array('AppEnableFunctionality');


}
 ?>
