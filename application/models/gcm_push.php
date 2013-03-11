<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcm_Push extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('db/gcms');
		$this->load->model('db/users');
		$this->load->model('db/wifis');
		$this->load->config('config.inc');
	}

	public function push($Kind, $id) {
		$user 		= $this->users->SWhere($id);
		$wifis 		= json_encode($this->wifis->SWhereCountry($user->country)->result());
		$gcm_reg 	= $this->getReg($id);
		
		$message 	= array($this->config->item('Kind_key') => $Kind, $this->config->item('Message_key') => $wifis);
		$this->send_notification($gcm_reg, $message);
	}

	
	private function getReg($id) {
		$gcm_reg = array();
		$gcm = $this->gcms->SWhere($id);
		if($gcm->num_rows > 0) {
			foreach($gcm->result() as $row) {
				$gcm_reg[] = $row->registrarId;
			}
		}
		return $gcm_reg;
	}
	
	/**
     * Sending Push Notification
     */
    private function send_notification($registatoin_ids, $message) { 
        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';
 
        $fields = array(
            'registration_ids' 	=> $registatoin_ids,
            'data' 				=> $message,
        );
        $headers = array(
            'Authorization: key=' . $this->config->item('GOOGLE_API_KEY'),
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        // echo $result;
    }
}