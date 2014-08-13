<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once('backend.php');

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
 * @property User_lib $user_lib
 * @property User_model $UM
 * @property qqfileuploader $qqfileuploader
 */

class User extends Backend
{

    private $aResult = array('status' => 0);
    private $per_page = 20;

    public function __construct()
    {
        parent :: __construct();

        $this->path .= strtolower(get_parent_class($this)) . "/" . strtolower(__CLASS__) . "/";
        $this->load->library('User_lib');
        $param = array('table'=>'user',
                        'validation_login_name' => 'login_backend',
                        'session_var' => array('email_user', 'password_user'),
                        'set_message' => array('is_unique' => 'This Email is already exist',
                                                '_check_confirm_field' => 'Repeat password is required',
                                                'is_unique_only_one' => 'This Email is already exist',
                                                '_noZero' => 'This field is required'));
        $this->user_lib->initialize($param);
        $this->load->model('user_model', 'UM');
        $this->data['LEFT_MENU'] = "Users";
        $this->data['extra_head'] .= js_tag('backend/user.js');
        $this->data['NAME_PAGE'] = 'user';
    }


    function index()
    {

        $this->data['SUB_LEFT_MENU'] = "All users";
        $this->render($this->path . 'index');

    }

    //авторизация
    public function login()
    {
        $this->user_lib->login('backend/main');
        $this->data['result'] = $this->user_lib->result;
        $this->load->view('backend/_login_forma', $this->data);
    }

    //выйти
    function logout()
    {
        $this->user_lib->logout('backend/user/login');

    }

    //список
    public function list_user($group_id = 0, $no_moder = 0, $page = 0)
    {

        $this->data['extra_head'] .= js_tag('backend/unicorn.form_common.js');
        $this->data['SUB_LEFT_MENU'] = "All users";

        $this->data['page'] = (int)$page;
        $this->data['keyword'] = (strlen($this->input->get('keyword'))) ? trim(urldecode($this->input->get('keyword'))) : '';
        $this->data['current_company'] = (int)$this->input->get('company');

        $this->data['aCompany'] = $this->ST->get_fields_data_tabl('company','id_company,name_company');

        $where = (strlen($this->data['keyword'])) ? '(user.first_name LIKE "%' . $this->data['keyword'] . '%" OR user.last_name LIKE "%' . $this->data['keyword'] . '%")' : null;
        /**************************************************************************/
        $this->load->library(array("pagination", "conf_paginator"));

        $uri_seg = 6;
        $page = intval($page);
        if ($page < 0)
        {
            $page = 0;
        }
        list($Class, $Method) = explode("::", __METHOD__);
        $base_url = site_url('backend/' . strtolower(__CLASS__) . "/" . strtolower($Method) . "/" . $group_id . "/" . $no_moder);

        $this->data['result'] = $this->UM->get_list($group_id, $no_moder, $where, $page, $this->per_page, 'user.date_create_user DESC,  user.id_user DESC');
        $count_users = count($this->data['result']);

        $this->data['found_count'] = $this->ST->get_query_tabl('SELECT FOUND_ROWS();', true);
        $this->data['found_count'] = $this->data['found_count'][0]['FOUND_ROWS()'];
        //prd($this->data['found_count']);
        $conf = $this->conf_paginator->get_conf_backend($base_url, $uri_seg, $this->data['found_count'], $this->per_page);
        $this->pagination->initialize($conf);

        $this->data['pages'] = $this->pagination->create_links();


        $query = 'SELECT company_user.*,company.name_company
                    FROM company_user
                        LEFT JOIN company ON company_user.company_id = company.id_company
                        WHERE user_id IN (';
        for($i=0; $i<$count_users; $i++){
            $query.= $this->data['result'][$i]->id_user.', ';

        }

        $aUser_companies_list = array();
        $aUser_companies_list_id = array();
        if($count_users){
            $query = substr($query,0,strlen($query)-2).')';

            $aUser_company = $this->ST->get_query_tabl($query);
            $count_rec = count($aUser_company);
                // get_companies list for each user
            for($i=0; $i<count($aUser_company); $i++){
                if(!isset($aUser_companies_list[$aUser_company[$i]->user_id])){
                    $aUser_companies_list[$aUser_company[$i]->user_id] = '';
                    $aUser_companies_list_id[$aUser_company[$i]->user_id] = ':';
                }
                if($aUser_company[$i]->type_user==1){
                    // if company admin
                    $company_name = '<b>'.$aUser_company[$i]->name_company.'</b>(account owner)';
                }
                else{
                    if($aUser_company[$i]->type_user==2){
                        $company_name = $aUser_company[$i]->name_company.'(team member)';
                    }
                    else{
                        $company_name = $aUser_company[$i]->name_company.'(client)';
                    }
                }
                $aUser_companies_list[$aUser_company[$i]->user_id] .= $company_name.', ';
                $aUser_companies_list_id[$aUser_company[$i]->user_id] .= $aUser_company[$i]->company_id.':';

            }
            //prn($aUser_companies_list_id);
            //prn($aUser_companies_list);
                // add property to main array
            for($i=0; $i<$count_users; $i++){
                $cur_user = $this->data['result'][$i]->id_user;
                if($this->data['current_company']>0){
                        // check company if company filter selected
                    //prn('strpos '.$this->data['current_company'].' in '.$aUser_companies_list_id[$cur_user]);
                    $cur_pos = strpos($aUser_companies_list_id[$cur_user],':'.$this->data['current_company'].':');
                    //prn($cur_pos);
                    if($cur_pos===false){
                        //prn('not founded');
                        unset($this->data['result'][$i]);
                        continue;
                    }
                }

                if(!empty($aUser_companies_list[$cur_user])){
                    $sComp_list = substr($aUser_companies_list[$cur_user],0,strlen($aUser_companies_list[$cur_user])-2);
                    $this->data['result'][$i]->sCompany_list = $sComp_list;
                }
            }

            //prd($this->data['result']);
        }

        //$this->data['aGroup'] = get_array('id_group', 'group', $this->ST->get_data_tabl('group'));
        //$this->data['group_id'] = $group_id;
        $this->data['NAME_PAGE'] .= '_list_user';

        if (IS_AJAX)
        {
            $this->load->view('backend/user/_list_list_user', $this->data);
        } else
            $this->render($this->path . '_list');
    }

    public function change_block_status_user()
    {
        $id_user = $this->input->post('id_user');
        $new_status = (int)$this->input->post('new_status');
        if($new_status){
            $new_status = 1;
        }
        else{
            $new_status = 0;
        }
        if(ADMIN_ID!=$id_user){
            echo json_encode(array('status' => (int)
                    $this->ST->update_data_tabl('user',
                        array('is_blocked_user'=>$new_status),array('id_user'=>$id_user))));
            //echo json_encode(array('status' => (int)$this->user_lib->delete($this->input->post('id_user'))));
        }
    }
    public function change_user_rights()
    {
        $id_user = (int)$this->input->post('id_user');
        $new_status = (int)$this->input->post('new_status');
        if($new_status==1||$new_status==2){
            $aCompany_user = $this->ST->get_data_tabl('company_user',array('user_id'=>$id_user));
            if(count($aCompany_user)==1){
                echo json_encode(array('status' =>
                    (int)$this->ST->update_data_tabl('company_user',
                            array('type_user'=>$new_status),array('id_company_user'=>$aCompany_user[0]->id_company_user))));
                return;
            }
            //echo json_encode(array('status' => (int)$this->user_lib->delete($this->input->post('id_user'))));
        }
        echo json_encode(array('status'=>0));
        return;
    }

    public function activate_user()
    {
        $id_user = $this->input->post('id_user');

        echo json_encode(array('status' => (int)
                $this->ST->update_data_tabl('user',
                    array('verification'=>1),array('id_user'=>$id_user))));
            //echo json_encode(array('status' => (int)$this->user_lib->delete($this->input->post('id_user'))));

    }

    public function login_as_user()
    {
        $answer = array('success'=>0);
        $id_user = (int)$this->input->post('id_user');
        if(isAdmin()){
            $this->load->helper('user_helper');
            $activate_result = activate_user_frontend($id_user);
            $answer['success'] = $activate_result;
        }

        echo json_encode($answer);
        return;
    }


    //просмотр пользователя
    public function view_user($id_user = null)
    {
        $this->data['SUB_LEFT_MENU'] = "View user";
        $id_user = (int)$id_user;
        if ($id_user)
        {
            $this->data['uInfo'] = $this->UM->get_user($id_user);
        }
        $this->data['NAME_PAGE'] .= '_view_user';
        $this->render($this->path . '_view');

    }

    //добавление
    public function add_user()
    {

        $this->data['result'] = 0;
        $this->data['SUB_LEFT_MENU'] = "Add user";
        $this->data['validate_name'] = 'add_user_backend';
        $param = $this->user_lib->validate($this->data['validate_name']);
        //prd($param);
        if ($param)
        {
            $company_id = $param['company'];
            $is_admin_company = $param['is_admin_company'];

            unset($param['is_admin_company']);
            unset($param['company']);

            unset($param['pass2_user']);
            $real_pass = $param['password'];
            $param['password'] = md5($param['password']);
            $param['verification'] = 1;
            $this->user_lib->table = 'user';
            $this->user_lib->initialize();
            $this->data['id_user'] = $this->user_lib->add($param);
                // save user company info
            $values['company_id'] = $company_id;
            $values['type_user'] = ($is_admin_company)?1:2;
            $values['user_id'] = $this->data['id_user'];
            $this->ST->insert_data_tabl('company_user',$values);

            $this->load->model('message_model','MM',true);
            $this->MM->check_users_chat_visible('company_partners');

            //prd($values);
            $this->data['result'] = 1;

        }

        $this->data['aCompany_list'] = $this->ST->get_fields_data_tabl('company','id_company, name_company',null,'name_company');
        $count_companies = count($this->data['aCompany_list']);
        $aCompany_list_normal = array();
        for($i=0; $i<$count_companies; $i++){
            $aCompany_list_normal[$this->data['aCompany_list'][$i]->id_company] = $this->data['aCompany_list'][$i]->name_company;
        }
        $this->data['aCompany_list'] = $aCompany_list_normal;
        if (IS_AJAX)
        {
            $result['status'] = $this->data['result'];
            $result['data'] = $this->load->view('backend/user/_add_edit', $this->data, TRUE);
            echo json_encode($result);
        } else
        {
            $this->render('backend/user/_add_edit');
        }


    }

    //редактирование
    public function edit_user($id_user = null)
    {
        $this->data['result'] = 0;
        $this->data['id_user'] = (int)$id_user;
        $this->data['validate_name'] = 'edit_user_backend';

        $param = $this->user_lib->validate($this->data['validate_name']);
        if ($param)
        {
            unset($param['id_user']);
            unset($param['pass2_user']);
            if (strlen($param['password'])) $param['password'] = md5($param['password']); else unset($param['password']);
            $this->user_lib->table = 'user';
            $this->user_lib->initialize();
            $this->data['result'] = (int)$this->user_lib->edit($this->data['id_user'], $param);
            //prn($id_user);
        }
        //$this->data['aGroup'] = get_array('id_group', 'group', $this->ST->get_data_tabl('group', null, 'id_group ASC'));
        $this->data['oDefault'] = $this->UM->get_user($this->data['id_user']);
        if (IS_AJAX)
        {
            //prd($this->data);
            $result['status'] = $this->data['result'];
            $result['data'] = $this->load->view('backend/user/_add_edit', $this->data, TRUE);
            echo json_encode($result);
        } else
        {
            $this->render('backend/user/_add_edit');
        }


    }

    /*--Ajax multiupload-*/
    //загрузка картинок
    public function do_multiupload()
    {
        // Список поддерживаемых расширений, ex. array("jpeg", "xml", "bmp")
        $allowedExtensions = array("jpg", "png", "gif", "jpeg");

        // Максимально допустимый размер файла, в байтах
        $sizeLimit = 3 * 1024 * 1024; //3.1Мб
        //сама  библиотека

        $this->load->library("qqfileuploader", array('allowedExtensions' => $allowedExtensions, 'sizeLimit' => $sizeLimit));

        $result = $this->qqfileuploader->UploadImage($this->data['UPLOAD_PATH'], null, $this->data['thumb']);
        echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);

    }

    //удаление картинок
    public function delete_multiupload()
    {
        $del_img = $this->input->post('name_pic');
        if (!$del_img) return false;
        (is_file(realpath('.') . $this->data['UPLOAD_PATH'] . $del_img)) ? unlink(realpath('.') . $this->data['UPLOAD_PATH'] . $del_img) : false;
        foreach ($this->data['thumb'] as $key => $value)
        {
            (is_file(realpath('.') . $this->data['UPLOAD_PATH'] . $value[0] . '_' . $del_img)) ? unlink(realpath('.') . $this->data['UPLOAD_PATH'] . $value[0] . '_' . $del_img) : false;

        }
        echo 'true';


    }


    /**
     *
     */
   public function lost_pass()
    {
        $id_user = (int)$this->input->post('id_user');
        if ($id_user)
        {
            $uInfo = $this->ST->get_data_tabl('user', array('id_user' => $id_user),null,true);
            if (!empty($uInfo))
            {
                $this->load->helper('string');
                $pass = random_string('alnum', 8);
                $this->ST->update_data_tabl('user', array('password' => md5($pass)),array('id_user' => $id_user));

                $this->user_lib->initialize(array('mail_name_from' => 'Administration ' . base_url(), 'mail_subject' => 'New pass!', 'mail_body' => 'Your new pass: ' . $pass, 'mail_email_from' => ADMIN_EMAIL, 'mail_email_to' => $uInfo[0]['email']));
                $this->user_lib->send_mail();
                echo 'yes';
                return;
            }
        }
        echo 'no';
    }
}
