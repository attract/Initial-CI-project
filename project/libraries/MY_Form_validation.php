<?php
class MY_Form_validation extends CI_Form_validation {

    public  $_error_prefix		= '';
    public  $_error_suffix		= '';

	function _year($str)
	{
		return ( ($str<6) || ($str>100) ) ? FALSE : TRUE;
	}
	function _noZero($str)
	{
		return (intval($str)==0) ? FALSE : TRUE;
	}
	function _noZero_noReq($str)
	{
		if($str!="")
		{
		      return (intval($str)==0) ? FALSE : TRUE;
		}
	}
	function _noNegative($str)
	{
		if($str!="-1")
		{
		    return (($str<0)) ? FALSE : TRUE;
		}
	}
	function _date($str)
	{
		$error = TRUE;
		list($yyyy,$mm,$dd) = explode('-',$str);
		if (!checkdate($mm,$dd,$yyyy)) 
		{
			$error = FALSE;
		}
		return $error;
	}
	function _login($str)
	{
		return ( ! preg_match("/^[a-zA-Z0-9]+$/i", $str)) ? FALSE : TRUE;
	}
	function _passw($str)
	{
		return ( ! preg_match("/^[a-zA-Zа-яА-Я0-9 ]+$/iu", $str)) ? FALSE : TRUE;
	}
	function _check_fio($str)
	{
	      $str = urldecode($str);
	      return ( ! preg_match("/^[a-zA-Zа-яА-Я -]+$/iu", $str)) ? FALSE : TRUE;
	}
	function _check_date($str)
	{
	      return ( ! preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}+$/iu", $str)) ? FALSE : TRUE;
	}
	function valid_url($str)
	{
	      if(preg_match("~^(?:(?:https?|ftp|telnet)://(?:[a-z0-9_-]{1,32}".
	      "(?::[a-z0-9_-]{1,32})?@)?)?(?:(?:[a-z0-9-]{1,128}\.)+(?:com|net|".
	      "org|mil|edu|arpa|gov|biz|info|aero|inc|name|[a-z]{2})|(?!0)(?:(?".
	      "!0[^.]|255)[0-9]{1,3}\.){3}(?!0|255)[0-9]{1,3})(:[0-9]{1,5})?(?:/[a-z0-9.,_@%\(\)\*&".
	      "?+=\~/-]*)?(?:#[^ '\"&<>]*)?$~i", $str))
	      return true;
		else
	      return false;
	}
	function _tel($str){
		return ((strlen($str)<10)||(!preg_match("/^[0-9-+()]+$/i", $str))) ? FALSE : TRUE;
	}
	function _select($str){
        return ((empty($str))||( ! preg_match("/^[0-9]+$/i", $str))) ? FALSE : TRUE;
	}
	function _float_num($str)
	{
		return ( ! preg_match("/^[0-9.,]+$/i", $str)) ? FALSE : TRUE;
	}
	//----------------------------------  проверка капчи
	function _captcha($string)
	{
		$oCI = & get_instance();
		$string = urldecode($string);
		$session_id = set_value('captcha_id', 0);
		$oCI->load->model('w_model',"Cm",TRUE);

		if ($oCI->Cm->check($session_id, $string) === false)
		{
			return false;
		}
		return true;
	}
    function _unique_email($str){
        // правило на уникальность email
        $oCI = & get_instance();
        $db_result = $oCI->ST->get_data_tabl('user', array('email' => $str));
        if (count($db_result)) return FALSE;
        else return TRUE;
    }
    function _exist_email($str){
        // правило на существование email в базе
        $oCI = & get_instance();
        $db_result = $oCI->ST->get_data_tabl('user', array('email' => $str));
        if (count($db_result)) return TRUE;
        else return FALSE;
    }
    function _activate_account($str){
        // правило на активированый аккаунт поле `verification`
        $oCI = & get_instance();
        $db_result = $oCI->ST->get_data_tabl('user', array('email' => $str));
        if (count($db_result) && $db_result[0]->verification == 1) return TRUE;
        else return FALSE;
    }
    /*
     * функция для редактирования уникального значения в поля.
     * id или уникальное или такое как переданное
    */
    function is_unique_only_one($param=null,$info=null)
    {
        if(!$param || !$info) return false;
        $ar = explode(',',$info);
        $ar2 = explode('.',$ar[1]);

        $oCI = &get_instance();
        $oResult = $oCI->db->query("select * from ".$ar2[0]." where ".$ar2[1]." = '".$param."' and ".$ar[0]."!='".$_POST[$ar[0]]."' ")->result_array();
        //echo $oCI->db->last_query();exit();
        return (empty($oResult))?TRUE:FALSE;
    }
/*
 * Функция для проверки RECAPTHA
 *
 * */
    function check_recaptcha($val)
    {
        $oCI = &get_instance();
        $oCI->load->library('recaptcha');
        if ($oCI->recaptcha->check_answer($oCI->input->ip_address(), $oCI->input->post('recaptcha_challenge_field'), $val))
        {
            return TRUE;
        }
        $oCI->form_validation->set_message('check_captcha', $oCI->lang->line('recaptcha_incorrect_response'));
        return FALSE;
    }
}

?>
