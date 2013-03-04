<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gps_locator extends CI_Model {
	private $Url;
	public function __construct() {
		parent::__construct();
		$this->Url = 'http://www.geoplugin.net/extras/location.gp?format=json&';
	}

	public function getParams($lat, $long) {
		return json_decode(file_get_contents($this->Url.$lat.'&'.$long));
	}
}