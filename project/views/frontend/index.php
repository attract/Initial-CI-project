<!DOCTYPE HTML>
<html>
<head>
    <title><?=(!empty($seo_title))?$seo_title:$_SERVER['HTTP_HOST'];?></title>
    <!-- Meta Tags -->
    <meta name="keywords" content="<?=(!empty($seo_keywords))?$seo_keywords:null?>" />
    <meta name="description" content="<?=(!empty($seo_description))?$seo_description:null?>" />

    <meta charset="utf-8" />
    <?=css_tag('style.css')?>
    <?=css_tag('slava.css')?>

    <?=js_tag('jquery-1.8.2.min.js')?>
    <?
        if(isset($extra_head))
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
    var ID_USER = '<?=(!empty($user)?$user->id_user:-1)?>';
    var SITE_URL = "<?=site_url()?>";
    var BASE_URL = "<?=base_url()?>";
</script>
    <div id="page_content" class="content">
        <?php if(!empty($view)){ load_view($view); } ?>
    </div>
</body>
</html>