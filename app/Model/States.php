<?php

class States extends AppModel
{

    public $belongsTo = array('Country');
    public $hasMany = array('City');

}