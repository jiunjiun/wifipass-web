<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gps_locator extends CI_Model {
	private $Url;
	public function __construct() {
		parent::__construct();
		$this->Url = 'http://www.geoplugin.net/extras/location.gp?format=json&';
	}

	public function getParams($lat, $long) {
		$curl = curl_init($this->Url.$lat.'&'.$long);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		return json_decode($result);
	}
}