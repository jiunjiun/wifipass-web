<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcm_Push extends CI_Model {
	private $id, $gps;
	public function __construct() {
		parent::__construct();
		$this->load->model('db/gcms');
		$this->load->model('db/gcm_errors');
		$this->load->model('db/wifis');
		$this->load->config('config.inc');
	}

	public function push($Kind, $id, $gps) {
		$this->id	= $id;
		$this->gps	= $gps;
		$limitData 	= $this->config->item('LimitData');
		$wifi_arr	= array();
		$gcm_reg 	= $this->getReg($id);
		$wifis		= $this->wifis->SWRadius($gps, $this->config->item('Radius'));
		if($wifis->num_rows() > 0) {
			if($wifis->num_rows() <= $limitData) {
				$wifi_arr = json_encode($wifis->result());
				
				$message 	= array($this->config->item('Kind_key') => $Kind, $this->config->item('Message_key') => $wifi_arr);
				$this->send_notification($gcm_reg, $message);
			} else {
				$i = 1;
				foreach($wifis->result() as $row) {
					$wifi_arr [] = $row;
					if($i++ == $limitData) {
						$wifi_arr 	= json_encode($wifi_arr);
						$message 	= array($this->config->item('Kind_key') => $Kind, $this->config->item('Message_key') => $wifi_arr);
						$this->send_notification($gcm_reg, $message);
						
						$i = 1;
						$wifi_arr = array();
					}
				}
				if(count($wifi_arr) > 0) {
					$wifi_arr 	= json_encode($wifi_arr);
					$message 	= array($this->config->item('Kind_key') => $Kind, $this->config->item('Message_key') => $wifi_arr);
					$this->send_notification($gcm_reg, $message);
				}
			}
		}
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
        $result = json_decode($result);
		// print_r($result);
		if(!empty($result->results[0]->error)) {
			$data = (Object) array("user_id"=> $this->id, "gps"=> json_encode($this->gps), "message"=> $result->results[0]->error);
			$this->gcm_errors->add($data);			
		}
    }
}