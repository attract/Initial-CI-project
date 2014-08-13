<?
    if(!isSet($only_options))
    {
        $only_options = false;
    }

    if(!$only_options)
    {
?>
    <select name='<?=isSet($select_name)?$select_name:$text?>' <?=(isSet($extra_select)?$extra_select:'')?>>
<?
    }

    if(isSet($select_first)&&(is_array($select_first)))
    {
?>
        <option value='<?=key($select_first)?>' <?=(isSet($extra_option)?$extra_option:'')?> ><?=$select_first[key($select_first)]?></option>
<?php
    }

     if(!empty($select))
     {
        foreach($select as $item)
        {
            $sel = '';
            if(isSet($_GET[$select_name]) && ($_GET[$select_name] == $item->$value))
            {
                $sel = 'selected';
            }
            elseif(isset($selected_item))
            {
                $sel = ($selected_item == $item->$value)?'selected':'';
            }

?>
            <option value='<?=$item->$value?>' <?=(isSet($extra_option)?$extra_option:'')?> <?=$sel?> ><?=$item->$text?></option>
<?
        }
     }
?>

<?
    if(!$only_options)
    {
?>
    </select>
<?
    }

?>