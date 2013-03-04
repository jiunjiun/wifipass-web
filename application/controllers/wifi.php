<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wifi extends CI_Controller {
	function __construct() {
        parent::__construct();
    }
	
	public function index() {
		if($this->input->post('k', TRUE) == 'save')
			$this->save();
		show_404();
	}
	
	private function save() {
		$this->load->model('db/wifis');
		$wifi = json_decode($this->input->post('wifi', TRUE));
		$this->wifis->Add($wifi);
	}
	
	private function fund() {
	
	}
}