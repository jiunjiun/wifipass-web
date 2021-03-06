<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcm_errors extends CI_Model {
	private $tab;
	public function __construct() {
		parent::__construct();
		
		$this->tab = strtolower(get_class($this));
	}
	
	public function Select() {
		return $this->db->get($this->tab);
	}
	
	public function SWhere($user_id) {
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->tab);
	}
		
	public function Add($data) {
		$data->created_at = $data->updated_at = date("Y-m-d H:i:s", time());
		$this->db->insert($this->tab, $data);
	}
	
	public function Update($user_id, $data) {
		$this->db->where('user_id', $user_id);
		$this->db->update($this->tab, $data);
	}
	
	public function Del($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->tab); 
	}
	
	public function verify($user_id) {
		$this->db->where('user_id', $user_id);
		$query =  $this->db->get($this->tab);
		if($query->num_rows() > 0) {
			return false;
		}
		return true;
	}
}