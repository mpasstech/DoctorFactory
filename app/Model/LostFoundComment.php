<?php

class LostFoundComment extends AppModel
{
    public $actsAs = array('Containable');
    public $belongsTo = array('Thinapp','LoseObject','User');



}