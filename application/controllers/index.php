<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->config('config.inc');
    }
	
	public function index() {
		echo 'Hello!!';
		$this->load->model('gcm_push');
		$this->gcm_push->push($this->config->item('Kind_StoreWifi'), 30);
	}
}