<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MY_Controller {

    public function __construct()
    {
        parent :: __construct();
    }

    function index()
    {
        $this->render(__FUNCTION__);
    }
}
?>