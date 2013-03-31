<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
	function __construct() {
        parent::__construct();
    }
	
	public function index() {		
		if($this->input->post('k', TRUE) == 'reg')
			$this->register();
		// show_404();
	}
	
	private function register() {
		$this->load->model('db/users');
		
		$reg = json_decode($this->input->post('reg', TRUE));
		$reg->gps = json_decode($reg->gps);
		$this->users->Add($reg);
	}
}