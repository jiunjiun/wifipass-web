<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller {
	function __construct() {
        parent::__construct();
		$this->load->config('config.inc');
		$this->load->model('db/wifis');
    }
	
	public function index() {
		// echo 'Hello!!';
		// $this->load->model('gcm_push');
		// $this->gcm_push->push($this->config->item('Kind_StoreWifi'), 30);
		
		
		/***	PHP and MySQL: Calculating Distance		**/
		$parames['wifis'] = $this->wifis->Select();
		
		$lat		=	"22.7299811";
		$lon		=	"120.3297569";
		
		$gps 		= (Object)array("lat"=> $lat, "lon"=> $lon);
		$radius 	= $this->wifis->SWRadius($gps, $this->config->item('Radius'));
		
		$this->load->model('gcm_push');
		$this->gcm_push->push($this->config->item('Kind_StoreWifi'), 30, $gps);
		
		// $parames['rang'] = $rang;
		$this->load->view('index', $parames);
	}
}