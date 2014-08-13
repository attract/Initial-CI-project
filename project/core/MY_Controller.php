<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');
/**
 *@property Standart_model $ST
 *
 */
class MY_Controller extends CI_Controller {

    public $data = array();
    public $path = 'frontend/';
    public $user = array();

    public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);

        $this->data['extra_head']=array();
        $this->path .= strtolower(get_called_class().'/');
        $this->data['path'] = $this->path;

        isLogin();
    }

    protected function render($view = null)
    {
        if(!empty($view))
        {
            if(!is_array($view))
            {
                $this->data['view'] = $this->path.$view;
            }
        }
        $this->data['user'] = $this->user;
        $this->load->view("frontend/index", $this->data);
    }

    protected function prerender($view, $val)
    {
        $this->data[$val] = $this->load->view($view, $this->data, true);
    }

    /*
     * $item - Array OR String
     */
    protected function js($item)
    {
        if(is_array($item))
        {
            foreach($item as $i)
            {
                $this->data['extra_head'][sha1($i)] = js_tag($i);
            }
        }
        else
        {
            $this->data['extra_head'][sha1($item)] = js_tag($item);
        }
    }

    /*
     * $item - Array OR String
     */
    protected function css($item)
    {
        if(is_array($item))
        {
            foreach($item as $i)
            {
                $this->data['extra_head'][sha1($i)] = css_tag($i);
            }
        }
        else
        {
            $this->data['extra_head'][sha1($item)] = css_tag($item);
        }
    }

    protected function get_id($array, $field)
    {
        $response = array();
        if(!empty($array))
        {
            foreach($array as $item)
            {
                $response[] = $item->$field;
            }
        }
        return $response;
    }
}

?>