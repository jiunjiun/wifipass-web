<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ip_locator extends CI_Model {
	private $Url;
	public function __construct() {
		parent::__construct();
		$this->Url = 'http://www.geobytes.com/IpLocator.htm?GetLocation&template=php3.txt&IpAddress=';
	}

	public function getParams($ip) {
		return get_meta_tags($this->Url.$ip);
	}
}