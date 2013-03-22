<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wifi extends CI_Controller {
	function __construct() {
        parent::__construct();
    }
	
	public function index() {
		$kind = $this->input->post('k', TRUE);
		switch($kind) {
			case 'save':
				$this->save();
				break;
			case 'renew':
				$this->renew();
				break;
			case 'publicWifi':
				$this->publicWifi();
				break;
		}	
		show_404();
	}
	
	private function save() {
		$this->load->model('db/wifis');
		$wifi = json_decode($this->input->post('wifi', TRUE));
		$this->wifis->Add($wifi);
	}
	
	private function renew() {
		$this->load->config('config.inc');
		$this->load->model('gcm_push');
		$this->load->model('db/users');
		
		$user = json_decode($this->input->post('user', TRUE));
		$user = $this->users->SWhereEmail($user->email);
		$this->gcm_push->push($this->config->item('Kind_StoreWifi'), $user->id);
	}
	
	private function publicWifi() {
		$this->load->model('db/wifis');
		$publicWifi = json_decode($this->input->post('publicWifi', TRUE));
		$publicWifi = json_decode($publicWifi->publicWifi);
		foreach($publicWifi as $wifi) {
			$this->wifis->Add($wifi);
		}
	}
}