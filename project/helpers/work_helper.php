<?php

function sendEmailView($aUser,$view,$data)
{
    if(!empty($aUser))
    {
        foreach($aUser as $item)
        {
            list($subject,$body,$from_email)=get_mail_setting($view,$data,$item->lang_id);
            $aEmail=Array($item->email_user);
            sendEmail($subject,$body,$from_email,$aEmail);
        }
    }
}

function sendEmail($subject,$body,$from,$aTo,$aAttach=Array())
{
    $oCI=get_instance();
    $oCI->load->library('email');
    $oCI->email->clear(TRUE);
    $oCI->email->from($from);
    $oCI->email->to($aTo);
    $oCI->email->subject($subject);
    $oCI->email->message($body);
    $oCI->email->mailtype='html';
    if(!empty($aAttach))
    {
        foreach($aAttach as $file)
        {
            if(file_exists($file))
            {
                $oCI->email->attach($file);
            }
        }
    }
    return $oCI->email->send();
}

function encrypt($str)
{
    $oCI=get_instance();
    $oCI->load->library('encrypt');
    return $oCI->encrypt->encode($str);
}

function decrypt($str)
{
    $oCI=get_instance();
    $oCI->load->library('encrypt');
    return $oCI->encrypt->decode($str);
}

function parse($view,$data=Array())
{
    $oCI=get_instance();
    $oCI->load->library('Parser');
    return $oCI->parser->parse($view,$data,true);
}

function get_mail_setting($key,$data,$lang)
{
    $oCI=get_instance();
    $pre=
        $oCI->db->select('subject_'.$lang.' as subject ,body_'.$lang.' as body,from_mail')
            ->from('mail')
            ->where('key_mail',$key)
            ->get()->row();
    if(count($pre))
    {
        return Array($pre->subject,parse_string($pre->body,$data),(($pre->from_mail)?$pre->from_mail:$oCI->aSetting['FROM_EMAIL']));
    }
    else
    {
        return Array(false,false,false);
    }
}

function parse_string($str,$data=Array())
{
    $oCI=get_instance();
    $oCI->load->library('Parser');
    return $oCI->parser->parse_string($str,$data,true);
}

function form_validation($rules)
{
    $oCI=get_instance();
    $oCI->load->library('form_validation');
    $oCI->form_validation->set_error_delimiters('<p>','</p>');
    $oCI->form_validation->set_message('greater_than','Должно быть число больше 0');
    $oCI->form_validation->set_message('valid_url','Поле должно содержать коректный адрес');
    $oCI->form_validation->set_message('_phone','Поле должно содержать коректный телефон (только цифры и "-")');
    $oCI->form_validation->set_message('_login','Поле Никнейм может содержать только буквы русского и английского алфавита, подчеркивания, тире и пробелы');
    $oCI->form_validation->set_message('matches','Пароли не совпадают');

    return $oCI->form_validation->run($rules);
}

function post($key)
{
    $oCI=get_instance();
    return $oCI->input->post($key);
}

function get($key)
{
    $oCI=get_instance();
    return $oCI->input->get($key);
}

function get_validation_vars($rules)
{
    $oCI=get_instance();
    $oCI->load->config('form_validation');
    $conf=$oCI->config->item($rules);
    $return=Array();
    for($i=0;$i<count($conf);$i++)
    {
        $return[$conf[$i]['field']]=post($conf[$i]['field']);
    }

    return $return;
}

function move_file_from_temp($new_path,$aFile)
{
    if(!empty($aFile))
    {
        $oldpath=realpath('.').'/'.TEMP_FILE;
        $new_path=realpath('.').'/'.$new_path;
        for($i=0;$i<count($aFile);$i++)
        {
            list($real,$im)=explode('_%_',$aFile[$i]);
            if(file_exists($oldpath.$real))
            {
                rename($oldpath.$real,$new_path.$real);
            }
        }
    }
}

function wrap_validation_delimiter($str)
{
    $oCI=get_instance();
    $oCI->load->library('form_validation');
    $pref=$oCI->form_validation->{'_error_prefix'};
    $suf=$oCI->form_validation->{'_error_suffix'};

    return $pref.$str.$suf;
}

function make_link($url,$text='')
{
    if($text=='')
    {
        $text=$url;
    }
    return '<a href="'.$url.'">'.$text.'</a>';
}

function convert_date($date)
{
    $date = date_create_from_format('d.m.Y', $date);
    if($date)
    {
        return date_format($date, 'Y-m-d');
    }
    else
    {
        return '0000-00-00';
    }
}

function num_format($num)
{
    return number_format($num,2,'.',' ');
}
?>