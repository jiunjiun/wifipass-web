<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gcms extends CI_Model {
	private $tab;
	public function __construct() {
		parent::__construct();
		
		$this->tab = strtolower(get_class($this));
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
		if($this->verify($data->registrarId)) {
			$data->created_at = $data->updated_at = date("Y-m-d H:i:s", time());
			$this->db->insert($this->tab, $data);
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
	
	public function verify($registrarId) {
		$this->db->where('registrarId', $registrarId);
		$query =  $this->db->get($this->tab);
		if($query->num_rows() > 0) {
			return false;
		}
		return true;
	}
}