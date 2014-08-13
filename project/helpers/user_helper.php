<?php

function getAdmin($redirect = null)
{
    $oCI = & get_instance();
    $array_in_session = 'admin';
    $table = "user";
    $login_field = "email_user";
    $password_field = "password_user";
    $aAdmin_session = $oCI->session->userdata($array_in_session);

    $ses_login = (isset($aAdmin_session[$login_field]))?$aAdmin_session[$login_field]:'';
    $ses_password = (isset($aAdmin_session[$password_field]))?$aAdmin_session[$password_field]:'';

    if (($ses_login != "") && ($ses_password != ""))
    {
        $where = array($login_field => $ses_login, $password_field => $ses_password);

        $result = $oCI->db->from($table)->where($where)->get()->row();
        if (count($result) == 1)
        {
            return empty($result) ? false : $result;
        }

    }
    $redirect = trim((string)$redirect);
    $current = trim((string)uri_string());
    if ($redirect && $redirect !== $current) redirect(site_url($redirect));

    return false;
}

function getUser($redirect = null)
{

    $oCI = & get_instance();

    $table = "user";
    $login_field = "email_user";
    $password_field = "pass_user";

    if (($oCI->session->userdata($login_field) != "") && ($oCI->session->userdata($password_field) != ""))
    {
        $where = array($login_field => $oCI->session->userdata($login_field), $password_field => $oCI->session->userdata($password_field));

        $result = $oCI->db->from($table)->where($where)->get()->row();

        if (count($result) == 1)
        {

            return empty($result) ? false : $result;
        }

    }
    $redirect = trim((string)$redirect);
    $current = trim((string)uri_string());
    if ($redirect && $redirect !== $current) redirect(site_url($redirect));

    return false;

}

//
function hasAccess($array_type_id = array(), $redirect = false)
{
    $oCI = &get_instance();
    if ((!empty($oCI->data['GROUP_ID'])) && (in_array($oCI->data['GROUP_ID'], $array_type_id)))
    {
        return true;
    } else
    {
        $redirect = trim((string)$redirect);
        $current = trim((string)uri_string());
        if ($redirect && $redirect !== $current) redirect(site_url($redirect));
        return false;
    }


}

function get_user_avatar($avatar, $size = "122")
{
    if (!empty($size))
    {
        $size = $size . "_";
    }

    $abs_img_path = realpath(".") . AVATAR_PATH . $size . $avatar;

    if (is_file($abs_img_path))
    {
        return base_url() . AVATAR_PATH . $size . $avatar;
    } else
    {
        return base_url() . AVATAR_PATH . $size . 'noavatar.jpg';
    }
}

function isUser($redirect = false)
{
    $oCI = &get_instance();
    if (!empty($oCI->data['user']['id_user']))
    {
        return $oCI->data['user']['id_user'];
    } else
    {
        $redirect = trim((string)$redirect);
        $current = trim((string)uri_string());
        if ($redirect && $redirect !== $current) redirect(site_url($redirect));
        return false;
    }
}

function isReviewer()
{
    if(hasAccess(array(3))){
        return true;
    }
    else{
        return false;
    }
}

function lock_info($field = null, $aHide = null)
{
    if (!$field || !$aHide || !is_array($aHide)) return FALSE;
    return array_key_exists($field, $aHide) ? TRUE : FALSE;

}

/*****************СКлонятор***********************************/
function inflect($name)
{
    $url = 'http://export.yandex.ru/inflect.xml?name=' . urlencode($name);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Opera/9.80 (Windows NT 6.1; U; ru) Presto/2.6.30 Version/10.61');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    $cases = array();
    preg_match_all('#\<inflection\s+case\=\"([0-9]+)\"\>(.*?)\<\/inflection\>#si', $result, $m);
    if (count($m[0]))
    {
        foreach ($m[1] as $i => &$id)
        {
            $cases[(int)$id] = $m[2][$i];
        }
        unset ($id);
    } else return null;
    if (count($cases) > 1) return $cases; else return false;
}

function get_name_in_padej($name = null, $padej = 1)
{
    if (!$name) return false;
    $a = inflect($name);
    if (is_array($a) && !empty($a[$padej]))
    {
        return $a[$padej];
    }
    return FALSE;
}

function _set_remember($iID)
{
    $oCI = &get_instance();
    $save_on_site = 1;//(int)$this->input->post('save_on_site');
    if($save_on_site==1)
    {
        $oCI->config->set_item('sess_expiration',9000000); // 3-и месяца
        $is_remember = 1;
    }
    else
    {
        $oCI->config->set_item('sess_expiration',7200);
        $is_remember = 0;
    }

    $oCI->session->__construct();
    $oCI->ST->update_data_tabl('user',array('remember'=>$is_remember), array('id_user'=>$iID));
}

?>
