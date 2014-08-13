<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property CI_DB_active_record $db
 * @property CI_DB_forge $dbforge
 * @property CI_Benchmark $benchmark
 * @property CI_Calendar $calendar
 * @property CI_Cart $cart
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Encrypt $encrypt
 * @property CI_Exceptions $exceptions
 * @property CI_Form_validation $form_validation
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Input $input
 * @property CI_Lang $lang
 * @property CI_Loader $load
 * @property CI_Log $log
 * @property CI_Model $model
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Profiler $profiler
 * @property CI_Router $router
 * @property CI_Session $session
 * @property CI_Sha1 $sha1
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Typography $typography
 * @property CI_Unit_test $unit_test
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $user_agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Xmlrpcs $xmlrpcs
 * @property CI_Zip $zip
 * @property CI_Javascript $javascript
 * @property CI_Jquery $jquery
 * @property CI_Utf8 $utf8
 * @property CI_Security $security
 */

abstract class Backend extends CI_Controller {

    public $data = array();
    public $path = 'backend/';

    public function __construct()
    {
        parent :: __construct();
        $this->path .= strtolower(get_called_class().'/');
        $this->data['path'] = $this->path;

        $this->load->model('standart_model','ST');
        $this->load->helper(array('html_form_helper', 'user_helper', 'html_fun_helper'));

        /*проверка авторизации*/
        $this->data['login_admin'] = $this->data = (array)getAdmin('backend/user/login');

        $this->data = (array_change_key_case($this->data, CASE_UPPER));

        /*проверка авторизации*/
        $this->data['extra_head']= "";
        $this->data['LEFT_MENU']="";
        $this->data['SUB_LEFT_MENU']="";
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
        $this->load->view("backend/index", $this->data);
    }

    /**
     * validate (что бы прошла валидация - обязательный параметр в форме $_POST['valid_me'])
     *
     * @access    public
     * @param    string $rules
     * @param    array $set_message - массив для текстов ошибок для собственных функций('название_функции'=>'текст ошибки')
     * @return    boolean(FALSE)/array
     */
    protected function validate($rules = null, $set_message = array())
    {
        $valid_me = $this->input->post('valid_me');

        if (!$valid_me || !$rules)
        {

            //echo  'Нет переменной $valid_me или не указанны правила $rules';
            return FALSE;
        }
        $this->load->library(array('form_validation'));


        //установка своих текстов для ошибок
        if (!empty($set_message) && is_array($set_message))
        {
            foreach ($set_message as $key => $value)
            {
                $this->form_validation->set_message($key, $value);
            }

        }

        if ($this->form_validation->run($rules))
        {
            return $this->_get_form_array($rules);
        }
        return FALSE;
    }

    private function _get_form_array($rules = null)
    {
        $this->config->load('form_validation', true);
        $config_form_validation = $this->config->item("form_validation");
        if (!$rules)
        {
            return FALSE;
        }

        foreach ($config_form_validation[$rules] as $item)
        {
            $form_array[$item['field']] = set_value($item['field']);
        }
        return $form_array;
    }


    /* установка ошибок */
    protected function set_form_errors($validate_name)
    {
        $this->config->load('form_validation', TRUE);
        $config_form_validation = $this->config->item($validate_name, "form_validation");
        $response = array();
        foreach ($config_form_validation as $item) {
            $response[$item['field']] = form_error($item['field']);
        }
        return $response;
    }
    protected function get_posts($array = array())
    {
        $response = array();

        foreach ($array as $item) {
            $response[$item] = $this->input->post($item);
        }
        return $response;
    }

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

    /* Make Js for Grocery Crud */
    protected function make_extra($output)
    {
        if(!empty($output['css_files']))
        {
            foreach($output['css_files'] as $item)
            {
                $this->data['extra_head'][sha1($item)] = css_tag($item, false, true);
            }
        }

        if(!empty($output['js_files']))
        {
            foreach($output['js_files'] as $item)
            {
                $this->data['extra_head'][sha1($item)] = js_tag($item, false, true);
            }
        }
    }
}


?>
