<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('backend.php');

class Main extends Backend {
	
	public function __construct()
	{
		parent :: __construct();
	}
	
	function index()
	{
        $this->render(__FUNCTION__);
	}

}
