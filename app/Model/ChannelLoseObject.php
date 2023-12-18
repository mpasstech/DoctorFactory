<?php

class ChannelLoseObject extends Model {

    public $actsAs = array('Containable');
    public $hasMany=array();
    public $belongsTo=array('LoseObject','Channel');


}
