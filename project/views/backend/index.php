<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <?=js_tag('jquery-1.8.2.min.js')?>

    <!-- Twitter Bootstrap -->
    <?=css_tag('backend/bootstrap.min.css')?>
    <?=css_tag('backend/backend.css')?>
    <?=js_tag('backend/bootstrap.min.js')?>
    <?=js_tag('backend/backend.js')?>
    <? //=js_tag('backend/jquery.jgrowl.js')?>

    <?=css_tag('backend/slava_backend.css')?>

    <?=css_tag('backend/unicorn.main.css')?>
    <?=css_tag('backend/unicorn.grey.css')?>
    <? //=css_tag('backend/jquery.jgrowl.css')?>

    <?=js_tag('backend/unicorn.js')?>

    <?php
    if(!empty($extra_head))
    {
        foreach($extra_head as $item)
        {
            echo $item;
        }
    }
    ?>

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
<?$this->load->view('backend/header')?>
<?$this->load->view('backend/left_menu')?>

<div id="content">
    <div id="content-header">
        <h1><?=$LEFT_MENU?></h1>
    </div>
    <div id="breadcrumb">
        <a href="<?=site_url('backend');?>" title="Главная" class="tip-bottom"><i class="icon-home"></i> Main</a>
        <?
        if(!empty($LEFT_MENU)){
            $link_level_1 = get_link_breadcrumbs($LEFT_MENU);
            if($link_level_1!='#'){
                $link_level_1 = site_url($link_level_1);
            }
            ?>
            <a href="<?=$link_level_1?>" class="current">
                <?=$LEFT_MENU?>
            </a>
            <?      }
        if(!empty($SUB_LEFT_MENU)){
            $link_level_2 = get_link_breadcrumbs($SUB_LEFT_MENU);
            if($link_level_2!='#'){
                $link_level_2 = site_url($link_level_2);
            }
            ?>
            <a href="<?=$link_level_2; ?>" class="current">
                <?=$SUB_LEFT_MENU?>
            </a>
            <?
        }
        if(!empty($SUB_LEFT_MENU2)){
            ?>
            <a href="#" class="current"><?=$SUB_LEFT_MENU2?></a>
            <?
        }
        ?>
    </div>
    <div class="container-fluid">

        <div class="row-fluid" id="index-content">
            <!--Content-->
            <?php if (!empty($view)) load_view($view)?>

        </div>
        <!--Footer-->
        <?$this->load->view('backend/footer')?>
    </div>
    <?php load_view('backend/modals')?>
    <?php load_view('backend/static_text')?>
</body>
</html>