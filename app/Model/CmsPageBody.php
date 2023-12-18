<?php

class CmsPageBody extends Model {

    public $useTable = 'cms_pages_body';
    public $belongsTo = array('CmsPage');
    public $actsAs = array('Containable');



}
