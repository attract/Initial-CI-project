<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Twitter Bootstrap -->
    <?=css_tag('backend/bootstrap.min.css')?>
    <?//=css_tag('backend/bootstrap-responsive.min.css')?>
    <?//=css_tag('backend/fullcalendar.css')?>
    <?=css_tag('backend/unicorn.main.css')?>
    <?=css_tag('backend/unicorn.grey.css')?>
    <?//=css_tag('backend/jquery.jgrowl.css')?>


    <?=js_tag('jquery-1.8.2.min.js')?>
    <?=js_tag('jquery.uniform.min.js')?>
    <?//=js_tag('backend/jquery.ui.custom.js')?>

    <?=js_tag('backend/bootstrap.min.js')?>


    <?php if( !empty($extra_head) ) echo $extra_head; ?>

</head>
<body>
<script>
    var SITE_URL = "<?=site_url()?>";
    var BASE_URL = "<?=base_url()?>";
    var NAME_PAGE = "<?=(!empty($NAME_PAGE))?$NAME_PAGE:''?>";
</script>
<div id="header">
    <h1>TAXI LOGO</h1>
</div>

<div id="content" style="margin-left: 0!important; text-align: center">


    <div class="container-fluid" style="text-align: left">

        <div class="row-fluid">


            <?
            if(!empty($result))
            {
                if($result=='wrong_login' || $result=='wrong_pass'){ $text='Не верный логин или пароль';}
                if($result=='wrong_verif')$text='Not activating account';
            }?>
            <?if(!empty($text))echo '<div class="alert alert-error">'.$text.'</div>';?>

            <form action="<?=site_url('backend/user/login')?>" method="POST" id="login_form">
                <?
                $wrappers = array(
                    'form_field_wrappers'=>"<div class='control-group'></div>",
                    'field_wrappers'=>"<div class='controls'></div>",
                    'error_wrappers'=>"<p class='text-error'></p>",
                    'label_wrappers'=>'<label class="control-label" ></label>');

                render_form('login_backend', $wrappers)?>
                <?=form_input(array('name' => 'valid_me', 'value' => 'valid_me', 'type' => 'hidden'))?>
                <button class="btn btn-primary" onclick="$('#login_form').submit();">Войти</button>

            </form>


        </div>
        <!--Footer-->
        <?$this->load->view('backend/footer')?>
    </div>
    <?php load_view('backend/modals')?>
    <?php load_view('backend/static_text')?>
</body>
</html>
