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


class NewsletterSubscribers extends AppModel {
    public $name = 'NewsletterSubscribers';
  //  public $belongsTo = array('User');
    
    var $validate = array(
        'email'=>array(
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'This email is already listed!'
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please enter valid email!'
            )
        )
    );
}