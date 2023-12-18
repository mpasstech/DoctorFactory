<?php

/****************************************************************************
 * Cakephp AutocachePlugin
 * Nicholas de Jong - http://nicholasdejong.com - https://github.com/ndejong
 * 
 * @author Nicholas de Jong
 * @link https://github.com/ndejong/CakephpAutocachePlugin
 ****************************************************************************/

/**
 * AutocacheSource
 */
class AutocacheSource extends DataSource {

	/**
	 * __construct
	 * 
	 * @param array $config 
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	/**
	 * isConnected
	 * 
	 * @return bool 
	 */
	function isConnected() {
		return true;
	}

}
