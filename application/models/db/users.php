<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {
	private $tab;
	public function __construct() {
		parent::__construct();
		$this->tab = strtolower(get_class($this));
		
		$this->load->model('db/gcms');
		$this->load->model('gps_locator');
		$this->load->config('config.inc');
	}
	
	public function Select() {
		return $this->db->get($this->tab);
	}
	
	public function SWhere($id) {
		$this->db->where('id', $id);
		$query =  $this->db->get($this->tab);
		foreach($query->result() as $row) {
			return $row;
		}
	}
	
	public function SWhereEmail($email) {
		$this->db->where('email', $email);
		$query =  $this->db->get($this->tab);
		foreach($query->result() as $row) {
			return $row;
		}
	}
		
	public function Add($data) {
		try {
			$gcmData = (object)array();
			$gcmData->registrarId = $data->registrarId;
			
			if($this->verify($data->email)) {
				if (filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
					unset($data->registrarId);
					
					# get gps(lat, long) seach country code.
					$mGps_locator = $this->gps_locator->getParams($data->gps->lat, $data->gps->long);
					$data->country = $mGps_locator->geoplugin_countryCode;
					
					$data->created_at = $data->updated_at = date("Y-m-d H:i:s", time());
					$this->db->insert($this->tab, $data);
				}
			} 
		
			$gcmData->user_id = $this->SWhereEmail($data->email)->id;
			if(!empty($gcmData->user_id)) {
				$this->gcms->Add($gcmData);
			}
			/**	GCM	**/
			$this->load->model('gcm_push');
			$this->gcm_push->push($this->config->item('Kind_StoreWifi'), $gcmData->user_id, $data->gps);
		} catch(Exception $e) {
		
		}
	}
	
	public function Update($id, $data) {
		$this->db->where('id', $id);
		$this->db->update($this->tab, $data);
	}
	
	public function Del($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->tab); 
	}
	
	private function verify($email) {
		$this->db->where('email', $email);
		$query =  $this->db->get($this->tab);
		if($query->num_rows() > 0) {
			return false;
		}
		return true;
	}
}