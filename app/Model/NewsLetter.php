<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


class NewsLetter extends AppModel {
    public $name = 'NewsLetter';
  //  public $belongsTo = array('User');
    
    var $validate = array(
        
        'from' => array(
			'fromRule-1' => array(
                                'rule'=>array('notEmpty'),
				'message'=>'Email can not be blank'),
			
			'fromRule-3' =>array(
				'rule'=>array('email', 'from' ),
				'message' => 'Please Enter valid email address'),
                ),
        'title' => array(
             'rule' => 'notEmpty',
             'message' => 'Enter first Newsletter title'
        ),
        'letter' => array(
             'rule' => 'notEmpty',
             'message' => 'Enter first Newsletter content'
        )
    );
}