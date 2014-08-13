<?
//$delimiters - разделитель для полей формы. array('<div class="form">', '</div>')
/*
 * генератор формы
 * string $form_settings - массив в  config/form_validation.php
 * array $wrappers массив с обертками для полей формыю
 * $wrappers['form_field_wrappers']='<div class="item"></div>';//обертка всего поля формы(label+input+error)
 * $wrappers['label_wrappers']='<label class="label"></label>';//вид элемента формы label. В обертку будет подставлен атрибут  for
 * $wrappers['field_wrappers']='<span class="item-field"></span>';//обертка только для элемента формы(input,textarea,select)
 * $wrappers['error_wrappers']='<div class="error"></div>';//обертка всего поля формы блока с ощибкой. В оберту будет подставлен
 *  id="{имя элемента формы}" и class ="error_wrapper"
 * array $data - массив значений для постоения формы (пока что для селектов)
 * object/array $default - объект/массив со значениями по умолчанию
 * */
function render_form($form_settings = null, $wrappers = array(), $data = array(), $default = null)
{
    $default = ($default) ? (array)$default : null;
    if (!$form_settings)
    {
        echo "Не указанна таблица с настройками при вызове функции";
        return;
    }

    $oCI = &get_instance();
    $oCI->config->load('form_validation', true);
    $config_form_validation = $oCI->config->item("form_validation");
    $form_settings = $config_form_validation[$form_settings];

    if (!$form_settings || !is_array($form_settings))
    {
        echo 'Нет параметров для построения формы';
        return;
    }

    foreach ($form_settings as $key => $value)
    {


        if (empty($value['type']))
        {
            echo "Не указан тип поля!";
            continue;
        }

        $param = get_data_to_param($value, $default);
        //устанавливаю настройки для обертки или из form_validation или из массива
        $tmp_wrappers = (!empty($value['wrappers'])) ? $value['wrappers'] : $wrappers;
        $form_wrapper = get_field_wrapper('form_field_wrappers', $value['field'], $tmp_wrappers);
        echo  $form_wrapper['start'];
        switch ($value['type'])
        {
            case'input[type=text]':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                echo form_input($param);
                echo $field_wrapper['finish'];
                break;
                }
            case'input[type=password]':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                echo form_password($param);
                echo $field_wrapper['finish'];
                break;
                }
            case'input[type=hidden]':
                {
                //echo form_hidden($param['name'],$param['value']);
                $param['type']='hidden';
                echo form_input($param);
                break;
                }
            case'input[type=file]':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                echo form_upload($param);
                echo $field_wrapper['finish'];
                break;
                }
            case'select':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                $arSelect = (!empty($data[$param['key_with_array']])) ? $data[$param['key_with_array']] : array();
                    if (empty($param['unset_zero']))
                        $arSelect[0]='Укажите значение';
                    echo form_dropdown($param['name'], $arSelect, $param['value'], $param['string_param_for_select']);

                echo $field_wrapper['finish'];
                break;
                }
            case'input[type=checkbox]':
                {
                $checkbox_wrappers = (isset($value['wrappers']['form_wrappers'])) ? $value['wrappers'] : $tmp_wrappers;
                $checkbox_form_wrapper = get_field_wrapper('form_wrappers', $value['field'], $checkbox_wrappers, FALSE);
                echo $checkbox_form_wrapper['start'];


                $checkbox_wrappers = (isset($value['wrappers']['field_wrappers'])) ? $value['wrappers'] : $tmp_wrappers;
                $checkbox_field_wrappers = get_field_wrapper('field_wrappers', $value['field'], $checkbox_wrappers, FALSE);
                echo $checkbox_field_wrappers['start'];
                echo form_checkbox($param);
                echo $checkbox_field_wrappers['finish'];
                $checkbox_label = (isset($value['wrappers']['label_wrappers'])) ? $value['wrappers'] : $tmp_wrappers;
                echo get_label_wrapper($value['label'], $value['field'], $checkbox_label, FALSE);


                echo $checkbox_form_wrapper['finish'];
                break;
                }
            case'input[type=radio]':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                if (!empty($value['array_radio']) && is_array($value['array_radio']))
                {
                    foreach ($value['array_radio'] as $item)
                    {
                        $radio_wrappers = (isset($item['wrappers']['form_wrappers'])) ? $item['wrappers'] : $tmp_wrappers;
                        //prn($radio_wrappers);
                        $radio_form_wrapper = get_field_wrapper('form_wrappers', $item['field'], $radio_wrappers, FALSE);
                        echo $radio_form_wrapper['start'];
                        $param = get_data_to_param($item, $default);

                        $radio_wrappers = (isset($item['wrappers']['field_wrappers'])) ? $item['wrappers'] : $tmp_wrappers;
                        $radio_field_wrapper = get_field_wrapper('field_wrappers', $item['field'], $radio_wrappers, FALSE);
                        echo $radio_field_wrapper['start'];
                        echo form_radio($param);
                        echo $radio_field_wrapper['finish'];
                        $radio_wrappers = (isset($item['wrappers']['label_wrappers'])) ? $item['wrappers'] : $tmp_wrappers;
                        echo get_label_wrapper($item['label'], $value['field'], $radio_wrappers, FALSE);
                        echo $radio_form_wrapper['finish'];
                    }

                }
                echo $field_wrapper['finish'];
                break;
                }
            case'textarea':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                echo form_textarea($param);
                echo $field_wrapper['finish'];
                break;
                }
            //для одной картинки
            case'qquploader':
                {
                echo get_label_wrapper($value['label'], $value['field'], $tmp_wrappers);
                $field_wrapper = get_field_wrapper('field_wrappers', $value['field'], $tmp_wrappers);
                echo $field_wrapper['start'];
                echo $param['html_button'];
                echo $param['html_result'];
                $param['value'] = (!empty($param['value'])) ? $param['value'] : '';
                echo form_hidden($param['name'], $param['value']);
                echo $field_wrapper['finish'];
                break;
                }
            default:
                echo "Не указан/не известный тип поля!";
        }
        echo get_error_wrapper($param['name'], $tmp_wrappers);
        echo  $form_wrapper['finish'];
        echo "\n\n";
    }
}

/*
* функция нужна, что бы создать массив параметров для элемента формы
* */
function get_data_to_param($data = null, $oDefault = null)
{
    //prn($data);
    if (!$data || !is_array($data)) return false;
    if (empty($data['data'])) $data['data'] = array();
    if (empty($data['type'])) $data['type'] = '';


    if ($data['type'] == 'input[type=checkbox]')
    {
        $data['data']['value'] = (!empty($data['value'])) ? $data['value'] : 0;
        $default = ((!empty($oDefault[$data['field']])) && ($oDefault[$data['field']] == $data['data']['value'])) ? TRUE : FALSE;
        $data['data']['checked'] = (set_checkbox($data['field'], $data['data']['value'], $default)) ? 'checked' : '';
    }
    if ($data['type'] == 'input[type=radio]')
    {
        $data['data']['value'] = (!empty($data['value'])) ? $data['value'] : 0;
        $default = ((!empty($oDefault[$data['field']])) && ($oDefault[$data['field']] == $data['data']['value'])) ? TRUE : FALSE;
        $data['data']['checked'] = (set_radio($data['field'], $data['data']['value'], $default)) ? 'checked' : '';
        // prn($data['field']);
        // prn($oDefault);

    }
    if (in_array($data['type'], array('input[type=text]', 'input[type=hidden]', 'textarea')))
    {
        $default = ((!empty($oDefault[$data['field']]))) ? $oDefault[$data['field']] : '';
        $data['data']['value'] = set_value($data['field'], $default);


    }

    if ($data['type'] == 'qquploader')
    {
        $data['data']['html_button'] = $data['html_button'];
        $data['data']['html_result'] = $data['html_result'];
        $default = ((!empty($oDefault[$data['field']]))) ? $oDefault[$data['field']] : '';
        $data['data']['value'] = set_value($data['field'], $default);
        //вставляю картинку  и кропку в результ
        if (strlen($data['data']['value']))
        {
            $Headers = @get_headers($data['path_to_thumb'] . $data['pref_to_thumb'] . $data['data']['value']);
            if (!empty($Headers[0]) && strpos($Headers[0],'200'))
            {
                //prn($Headers);
                $img = '<div class="cont_multiupl"><img src="' . $data['path_to_thumb'] . $data['pref_to_thumb'] . $data['data']['value'] . '"/>';
                $img .= str_replace('%FILE%', $data['data']['value'], $data['html_delete_img']) . "</div>";
                preg_match('/<(.+?)>/', $data['html_result'], $match1);
                preg_match('/<\/(.+?)>/', $data['html_result'], $match2);
                $data['data']['html_result'] = '<' . $match1[1] . '>' . $img . '</' . $match2[1] . '>';
            } else
                $data['data']['value'] = '';

        }
    }

    if ($data['type'] == 'select')
    {

        //конверттирую  массив параметров в строку. так как селект работает только со строкой
        $tmp='';
        if (!empty($data['data']))
        {
            $tmp = ' ';
            foreach ($data['data'] as $key => $value)
            {
                $tmp .= $key . '="' . $value . '" ';
            }

        }
        $data['data']['string_param_for_select'] = $tmp . ' id="' . $data['field'] . '"';
        $data['data']['key_with_array'] = (!empty($data['key_with_array'])) ? $data['key_with_array'] : 0;
        $data['data']['unset_zero'] = (!empty($data['unset_zero'])) ? $data['unset_zero'] : 0;


        $oCI = &get_instance();
        $default = $oCI->input->post($data['field']);
        if (empty($default)) $default = (!empty($oDefault[$data['field']])) ? $oDefault[$data['field']] : 0;
        $data['data']['value'] = $default;

    }

    return array_merge(array('name' => $data['field'], 'id' => $data['field']), $data['data']);
}

/*функция возвращает масси $result[key]=value
для селектов */
/**
 * get_array
 *
 *
 * @param    string
 * @param    string
 * @param    array
 * @return   array (array)
 */
function  get_array($key = null, $value = null, $array = array())
{
    if (!$key || !$value || !$array || !is_array($array)) return FALSE;

    $check_param = (array)($array[0]);
    if (empty($check_param[$key]) || empty($check_param[$value])) return FALSE;

    $result = array();

    foreach ($array as $item)
    {
        $item = (array)$item;
        $result[$item[$key]] = $item[$value];
    }
    return $result;


}

/*
* get field wrapper
* @param    string  $type
* @param    array
* @param    bool $view_tags_label
* @return   array $array('start'=>'[value]','finish'=>'[value]',);
*
* */

function get_field_wrapper($type = '', $name = '', $wrappers = array(), $view_tag_label = TRUE)
{
    $result = array('start' => '<div id="' . $name . '">', 'finish' => '</div>');
    if (!empty($wrappers[$type]))
    {
        $text = $wrappers[$type];
        preg_match('/<(.+?)>/', $text, $match1);
        preg_match('/<\/(.+?)>/', $text, $match2);
        if (!empty($match1[1]) && !empty($match2[1]))
        {
            $id = ($type == 'form_field_wrappers') ? ' id="' . $name . '"' : '';
            $match1[1] = str_replace("'", '"', $match1[1]);
            $result['start'] = '<' . $match1[1] . ' ' . $id . '>';
            $result['finish'] = '</' . $match2[1] . '>';
            return $result;
        }
    }
    // prn($result);
    return ($view_tag_label) ? $result : array('start' => ' ', 'finish' => ' ');

}

/*
* get label wrapper
*@param    string  $label
*@param    string  $name
*@param    array $wrappers
*@param    bool $view_tags_label
*@return   string;
* */
function get_label_wrapper($label = '', $name = '', $wrappers = array(), $view_tag_label = TRUE)
{
    if (!empty($wrappers['label_wrappers']))
    {
        $text = $wrappers['label_wrappers'];
        preg_match('/<(.+?)>/', $text, $match1);
        preg_match('/<\/(.+?)>/', $text, $match2);
        if (!empty($match1[1]) && !empty($match2[1]))
        {
            $match1[1] = str_replace("'", '"', $match1[1]);
            return '<' . $match1[1] . ' for="' . $name . '" >' . $label . '</' . $match2[1] . '>';
        }
    }
    return ($view_tag_label) ? form_label($label, $name) : '&nbsp;' . $label;
}

/*
* get error wrapper
*@param    array $delimiters
*@param    string  $type
*@param    string  $name
*@return   string;
* */
function get_error_wrapper($name = null, $wrappers = array())
{
    if (!$name) return FALSE;
    if (!empty($wrappers['error_wrappers']))
    {
        $text = $wrappers['error_wrappers'];
        preg_match('/<(.+?)>/', $text, $match1);
        preg_match('/<\/(.+?)>/', $text, $match2);
        if (!empty($match1[1]) && !empty($match2[1]))
        {
            $match1[1] = str_replace("'", '"', $match1[1]);
            return '<' . $match1[1] . ' id="error_' . $name . '" class="form_error" >' . form_error($name) . '</' . $match2[1] . '>';
        }
    }
    return "<div id='error_" . $name . "' class='form_error'>" . form_error($name) . "</div>";

}
?>