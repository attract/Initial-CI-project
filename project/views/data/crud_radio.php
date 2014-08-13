<div class='pretty-radio-buttons'>
    <?
    foreach($data as $item)
    {
        $chk = ($value == $item['value'])?'checked':'';
    ?>
        <label>
        <input id='field-<?=$field_name?>-<?=$item['value']?>' class='radio-uniform'  type='radio'
               name='<?=$field_name?>' value='<?=$item['value']?>' <?=$chk?> /> <?=$item['label']?>
        </label>
    <?
    }
    ?>
</div>