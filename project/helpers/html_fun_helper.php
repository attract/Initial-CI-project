<?php
define('MEDIA_PATH', "media/");

function js_tag($rpath, $with_base_url=false, $full=false)
{
    if($full)
    {
        return '<script src="'.$rpath.'" type="text/javascript"></script>';
    }
    else
    {
        $with_base_url = ($with_base_url)?base_url():TO_APP_FOLDER;
        return '<script src="'.$with_base_url.MEDIA_PATH.'js/'.$rpath.'" type="text/javascript"></script>';
    }
}

function css_tag($rpath, $with_base_url = false, $full=false)
{
	if($full)
    {
        return '<link rel="stylesheet" type="text/css" href="'.$rpath.'">';
    }
    else
    {
        $with_base_url = ($with_base_url)? base_url():TO_APP_FOLDER;
        return '<link rel="stylesheet" type="text/css" href="'.$with_base_url.MEDIA_PATH.'css/'.$rpath.'">';
    }
}

function img_path($img, $full = false)
{
    $full = ($full)? base_url():TO_APP_FOLDER;
    return $full.MEDIA_PATH.'images/'.$img;
}

function load_view($name_view)
{
    $oCI = & get_instance();
    foreach ((array)$name_view as $view)
    {
        $oCI->load->view($view);
    }
    return TRUE;
}


function logged_out()
{
    $oCI = & get_instance();
    $oCI->ST->update_data_tabl('user',array('date_last_online'=>date('Y-m-d H:i:s')),array('id_user'=>$oCI->user['id_user']));
    $oCI->session->unset_userdata('user');
}
function isLogin($redirect=false)
{
    $oCI = & get_instance();

    $table = "user";
    $login_field = "email_user";
    $password_field = "password_user";
    $verification = 'verif_user';

    $user = $oCI->session->userdata($table);

    $oCI->user = $oCI->data['user'] = false;
    if((!empty($user))&&($user[$login_field]!="")&&($user[$password_field]!=""))
    {
        $where = array(
            $login_field=>$user[$login_field],
            $verification=>1);

        $result =
            $oCI->db->select('*')
                ->from($table)
                ->where($where)
                ->get()->row();

        if(count($result)==1 && decrypt($user[$password_field])==decrypt($result->password_user))
        {
            $oCI->user = $oCI->data['user'] = $result;
            return true;
        }
    }
    ($redirect)?redirect(site_url()):null;
    return false;
}

function get_link_breadcrumbs($title = null)
{
    //prn($title);
    if ($title == "Users") return 'backend/user/list_user/';
    if ($title == "Partners") return 'backend/partner';
    if ($title == "Category") return 'backend/category';
    if ($title == "Sub Category") return 'backend/sub_category';
    return '#';
}

function admin_logged_out()
{
    $oCI = & get_instance();

    $aKeys = array_keys($oCI->session->userdata);

    foreach ($aKeys as $item)
    {
        $data_unset[$item] = "";
    }

    $oCI->session->set_userdata($data_unset);
}

function isAdmin($redirect = false)
{
    $oCI = & get_instance();

    $table = "admin";
    $login_field = "login";
    $password_field = "password";
    $pref = "admin_";

    if (($oCI->session->userdata($pref . $login_field) != "") && ($oCI->session->userdata($pref . $password_field) != ""))
    {
        $where = array($login_field => $oCI->session->userdata($pref . $login_field), $password_field => $oCI->session->userdata($pref . $password_field));
        $result = $oCI->ST->get_data_tabl($table, $where);

        if (count($result) == 1)
        {
            return true;
        }
    }
    ($redirect) ? redirect(site_url('backend/login')) : "";
    return false;
}

function get_admin_id($redirect = false)
{
    $oCI = & get_instance();
    if ($oCI->data['id_admin'])
    {
        return $oCI->data['id_admin'];
    } else
    {
        ($redirect) ? redirect(site_url('backend/login')) : "";
        return false;
    }
}

/*
* DEBUG
*/
function prn($content)
{
    echo '<pre style="background: lightgray; border: 1px solid black;">';
    print_r($content);
    echo '</pre>';
}

/*
* DEBUG & die !
*/
function prd($content)
{
    echo '<pre style="background: lightgray; border: 1px solid black;">';
    print_r($content);
    echo '</pre>';
    die();
}

/*
     *
     */
function get_extension($string)
{

    // разбиваем строку делиметром "."
    $result_array = explode('.', $string);

    // возвращаем последний элемент массива
    return $result_array[count($result_array) - 1];

}

function get_last_id($table)
{

    $oCI = & get_instance();

    $oCI->db->select('id_' . $table);
    $oCI->db->order_by('id_' . $table, 'DESC');
    $oCI->db->limit(1);
    $db_result = $oCI->db->get($table);

    $result = $db_result->row();

    return empty($result) ? 0 : $result->id_product;

}

function translitIt($str)
{
    $tr = array("А" => "A", "Б" => "B", "В" => "V", "Г" => "G", "Д" => "D", "Е" => "E", "Ж" => "J", "З" => "Z", "И" => "I", "Й" => "Y", "К" => "K", "Л" => "L", "М" => "M", "Н" => "N", "О" => "O", "П" => "P", "Р" => "R", "С" => "S", "Т" => "T", "У" => "U", "Ф" => "F", "Х" => "H", "Ц" => "TS", "Ч" => "CH", "Ш" => "SH", "Щ" => "SCH", "Ъ" => "", "Ы" => "YI", "Ь" => "", "Э" => "E", "Ю" => "YU", "Я" => "YA", "а" => "a", "б" => "b", "в" => "v", "г" => "g", "д" => "d", "е" => "e", "ж" => "j", "з" => "z", "и" => "i", "й" => "y", "к" => "k", "л" => "l", "м" => "m", "н" => "n", "о" => "o", "п" => "p", "р" => "r", "с" => "s", "т" => "t", "у" => "u", "ф" => "f", "х" => "h", "ц" => "ts", "ч" => "ch", "ш" => "sh", "щ" => "sch", "ъ" => "y", "ы" => "yi", "ь" => "", "э" => "e", "ю" => "yu", "я" => "ya", " " => "_");
    return strtr($str, $tr);
}

function xmlBodyOpen()
{

    echo '<?xml version="1.0" encoding="utf-8"?>';
    echo '<data>';
}

function xmlBodyClose()
{

    echo '</data>';
}

function xmlTagOpen($tag)
{

    echo '<' . $tag . '>';
}

function xmlTagClose($tag)
{

    echo '</' . $tag . '>';
}

function xmlNode($tag, $inner)
{

    echo '<' . $tag . '>';
    echo $inner;
    echo '</' . $tag . '>';
}

function requestPOST($ch, $url, $post = 0)
{
    //$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // отправляем на
    curl_setopt($ch, CURLOPT_HEADER, 0); // пустые заголовки
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // возвратить то что вернул сервер
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // следовать за редиректами
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); // таймаут4
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/cookie.txt'); // сохранять куки в файл
    curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/cookie.txt');
    curl_setopt($ch, CURLOPT_POST, $post !== 0); // использовать данные в post
    if ($post) curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $data = curl_exec($ch);
    //curl_close($ch);
    return $data;
}

/* Substr Russ Text */
function set_text($source, $count_chars = 20)
{
    $count_chars = (int)$count_chars;

    if (mb_strlen($source) > $count_chars)
    {
        return mb_substr(strip_tags($source), 0, $count_chars) . "...";
    } else
    {
        return strip_tags($source);
    }
}

/*функция проверяет наличие файла на сервере*/
/*
 * $part_of_the_path - часть пути напрример /media/upload/avatar
 * $type - url/abs; url - пусть по браузерной строки проверять будет
 * abs - абсолютный  путь
 * */
function is_my_file($part_of_the_path = null, $type = 'url')
{
    if (!$part_of_the_path) return FALSE;
    if ($type == 'abs')
    {
        $part_of_the_path = ($part_of_the_path{0} != '/') ? '/' . $part_of_the_path : $part_of_the_path;
        return (is_file(realpath('.')) . $part_of_the_path);
    } else
    {
        $part_of_the_path = ($part_of_the_path{0} == '/') ? substr($part_of_the_path, 1) : $part_of_the_path;
        $Headers = @get_headers(base_url().$part_of_the_path);
        if (!empty($Headers[0]) && strpos($Headers[0], '200')) return TRUE; else
            return FALSE;
    }



}

//функция которая переводит первую букву в верхний регистр для кириллицы и UTF-8
if (!function_exists('mb_ucfirst') && function_exists('mb_substr'))
{
    function mb_ucfirst($string)
    {
        $string = mb_ereg_replace("^[\ ]+", "", $string);
        $string = mb_strtoupper(mb_substr($string, 0, 1, "UTF-8"), "UTF-8") . mb_substr($string, 1, mb_strlen($string), "UTF-8");
        return $string;
    }
}

function sub_text($text, $count, $ending = '...'){


    $len = (mb_strlen($text) > $count) ? mb_strripos(mb_substr($text, 0, $count), ' ') : $count;
    $cutStr = mb_substr($text, 0, $len);

    if( !empty($cutStr) ) return close_tags($cutStr) . $ending;

    return false;
}

function close_tags($content)
{
    $position = 0;
    $open_tags = array();
    //теги для игнорирования
    $ignored_tags = array('br', 'hr', 'img');

    while (($position = strpos($content, '<', $position)) !== FALSE)
    {
        //забираем все теги из контента
        if (preg_match("|^<(/?)([a-z\d]+)\b[^>]*>|i", substr($content, $position), $match))
        {
            $tag = strtolower($match[2]);
            //игнорируем все одиночные теги
            if (in_array($tag, $ignored_tags) == FALSE)
            {
                //тег открыт
                if (isset($match[1]) AND $match[1] == '')
                {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]++;
                    else
                        $open_tags[$tag] = 1;
                }
                //тег закрыт
                if (isset($match[1]) AND $match[1] == '/')
                {
                    if (isset($open_tags[$tag]))
                        $open_tags[$tag]--;
                }
            }
            $position += strlen($match[0]);
        }
        else
            $position++;
    }
    //закрываем все теги
    foreach ($open_tags as $tag => $count_not_closed)
    {
        $content .= str_repeat("</{$tag}>", $count_not_closed);
    }

    return $content;
}

// variant1 - пример 7-лет, 5-дней
// variant2 - пример 1-год, 1-день
// variant3 - пример 3-года, 3-дня
function CountToRightStr($count,$variant2,$variant3,$variant1)
{
    if ($count!=0){
        $str='';
        $num=$count>100 ? substr($count, -2) : $count;
        if($num>=5&&$num<=14){
            $str = $variant1;
        }
        else{
            $num=substr($count, -1);
            if($num==0||($num>=5&&$num<=9)){
                $str = $variant1;
            }
            if($num==1){
                $str = $variant2;
            }
            if($num>=2&&$num<=4){
                $str = $variant3;
            }
        }
        return $str;
    }
    else{
        return "0 ".$variant1;
    }
}


?>
