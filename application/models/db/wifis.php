<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wifis extends CI_Model {
	private $tab;
	public function __construct() {
		parent::__construct();
		
		$this->tab = strtolower(get_class($this));
		$this->load->model('gps_locator');
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
		
	public function Add($data) {
		try {
			# get gps(lat, long) seach country code.
			$gps = json_decode($data->gps);
			$mGps_locator = $this->gps_locator->getParams($gps->lat, $gps->long);
			$data->country = $mGps_locator->geoplugin_countryCode;
			
			$type = $this->verify($data->MAC);
			switch(gettype($type)) {
				case 'string':
					$data->updated_at = date("Y-m-d H:i:s", time());
					$this->Update($type, $data);
					break;
				case 'boolean':
					$data->created_at = $data->updated_at = date("Y-m-d H:i:s", time());
					$this->db->insert($this->tab, $data);
					break;
			}
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
	
	private function verify($MAC) {
		$this->db->where('MAC', $MAC);
		$query =  $this->db->get($this->tab);
		if($query->num_rows() > 0) {
			foreach($query->result() as $row)
				return $row->id;
		}
		return true;
	}
}