<?php

/*
 * $file - filename.png
 * $path - AVATAR_PATH
 * $size - array(85,90) - (Width,Heigth) OR FALSE - (origin size)
 *
 * <?=get_img('samolet_1.jpg',NEWS_PATH,array(122,188),TRUE)?>
 */
function get_img($file=FALSE,$path=FALSE,$size= FALSE,$html = false, $need_crop = 1,$center = 1)
{
    $is_no_image = TRUE;
    $file_final = 'undefined_img';
    $main_path_final = 'undefined_path';

    if (($file !== FALSE) && ($path !== FALSE)) {

        $file_path = realpath('.') . $path . $file;

        if (is_file($file_path)) {
            $is_no_image = FALSE;
            if (($size !== FALSE) && (is_array($size)) && (count($size) == 2)) {
                $file_create = $size[0] . '_' . $size[1] . '_' . $file;
                $file_path_create = realpath('.') . $path . $file_create;

                if (is_file($file_path_create)) {
                    $main_path_final = $path;
                    $file_final = $file_create;
                } else {
                    $filesize = @getimagesize($file_path);
                    //prn($filesize);

                    if (($filesize[0] > $size[0]) || ($filesize[1] > $size[1])) {
                        $oCI = & get_instance();
                        $oCI->load->library('uploadfile');
                        $oCI->uploadfile->imageResize($file_path, $file_path_create, $size);

                        $main_path_final = $path;
                        $file_final = $file_create;

                        $real_path_final = realpath('.') . $path . $file_create;

                    } else {
                        // Picture Size is small
                        $main_path_final = $path;
                        $file_final = $file;

                        $real_path_final = realpath('.') . $path . $file;
                    }
                }
            } else {
                // return original
                $main_path_final = $path;
                $file_final = $file;

                $real_path_final = realpath('.') . $path . $file;
            }
        }
    }

    if($is_no_image)
    {
        $file_no_image = 'nologo.png';
        $path_no_image = '/'.MEDIA_PATH.'upload/no_image/';
        $file_no_image_create = $size[0].'_'.$size[1].'_'.$file_no_image;

        $file_path_no_image = realpath('.').$path_no_image.$file_no_image;
        $file_path_no_image_create = realpath('.').$path_no_image.$file_no_image_create;

        if(is_file($file_path_no_image_create))
        {
            $main_path_final = $path_no_image;
            $file_final = $file_no_image_create;

            $real_path_final = realpath('.').$path_no_image.$file_no_image_create;
        }
        else
        {
            $filesize = getimagesize($file_path_no_image);

            if (($filesize[0] > $size[0]) || ($filesize[1] > $size[1])) {
                $oCI = & get_instance();
                $oCI->load->library('uploadfile');
                $oCI->uploadfile->imageResize($file_path_no_image, $file_path_no_image_create, $size);

                $main_path_final = $path_no_image;
                $file_final = $file_no_image_create;
                $real_path_final = realpath('.'). $path_no_image . $file_no_image_create;
            } else {
                // Picture Size is small
                $main_path_final = $path_no_image;
                $file_final = $file_no_image;
                $real_path_final = realpath('.'). $path_no_image . $file_no_image;
            }
        }
    }

    if($need_crop){
        $img_param = getimagesize(realpath('.').$main_path_final.$file_final);
        $img_width = $img_param[0];
        $img_height = $img_param[1];
        $block_width = $size[0];
        $block_height = $size[1];

        $crop_info = (object) array('x'=>0,'y'=>0,'x2'=>0,'y2'=>0);

        if($center){
            if($img_width==$block_width){
                if($img_height!=$block_height){
                    $crop_info->x = 0;
                    $crop_info->x2 = $block_width;

                    $crop_info->y = $img_height/2 - $block_height/2;
                    $crop_info->y2 = $img_height/2 + $block_height/2;
                }
            }
            if($img_height==$block_height){
                if($img_width!=$block_width){
                    $crop_info->y = 0;
                    $crop_info->y2 = $block_height;

                    $crop_info->x = $img_width/2 - $block_width/2;
                    $crop_info->x2 = $img_width/2 + $block_width/2;
                }
            }
        }
        else{
            $crop_info->x = 0;
            $crop_info->y = 0;
            $crop_info->x2 = $block_width;;
            $crop_info->y2 = $block_height;
        }

        if(($crop_info->x2!=0)&&($crop_info->y2!=0)){
            $crop_info->x = (int)$crop_info->x;
            $crop_info->y = (int)$crop_info->y;
            $crop_info->x2 = (int)$crop_info->x2;;
            $crop_info->y2 = (int)$crop_info->y2;
            $crop_error = image_crop(realpath('.').$main_path_final,$file_final,'croped_'.$file_final,$crop_info);
            if(empty($crop_error)){
                unlink(realpath('.').$main_path_final.$file_final);
                rename(realpath('.').$main_path_final.'croped_'.$file_final,realpath('.').$main_path_final.$file_final);
                //move_uploaded_file(realpath('.').$main_path_final.'croped_'.$file_final,realpath('.').$main_path_final.$file_final);
            }
        }
    }
    $path_final = base_url().$main_path_final.$file_final;

    if ($html) {
        if ($size[0] < $size[1]) {
            return "<img width='" . $size[0] . "' src='" . $path_final . "' id='id_img_" . substr(md5($path_final), 0, 5) . "' >";
        } else {
            return "<img heigth='" . $size[1] . "' src='" . $path_final . "' id='id_img_" . substr(md5($path_final), 0, 5) . "' >";
        }
    } else {
        return $path_final;
    }
}

function image_crop($main_real_path,$src_image,$dest_image,$crop_info = array()){

    $oCI = &get_instance();

    // new start
    $crop_image_path = $main_real_path.$dest_image;

    if(is_file($crop_image_path)){
        unlink($crop_image_path);
    }

    $image_src_path = $main_real_path.$src_image;
    if(is_file($image_src_path))
    {
        copy($image_src_path, $crop_image_path);
        //if(is_file($image))prd('doesIs');
    }else
    {
        $response['errors'] = 'No such file';
        return $response;
    }

    $aImageParams = getimagesize($crop_image_path);
    $x1 = $crop_info->x;
    $y1 = $crop_info->y;
    $x1 = (empty($x1)) ? 0 : $x1;
    $y1 = (empty($y1)) ? 0 : $y1;
    $x2 = (int)$crop_info->x2;
    $y2 = (int)$crop_info->y2;
    $x2 = (empty($x2)) ? $aImageParams[0] : $x2;
    $y2 = (empty($y2)) ? $aImageParams[1] : $y2;


    $oCI->load->library('image_moo');
    $oCI->image_moo->load($crop_image_path);
    $oCI->image_moo->crop($x1, $y1, $x2, $y2);
    //$oCI->image_moo->crop(0, 0, 100, 100);
    $oCI->image_moo->save_pa('', '', true);
    $response =  $oCI->image_moo->display_errors('<h1>', '</h1>');
    return $response;
    // new end
}



function get_margin_for_photo($pic_name, $pic_module, $window_width, $window_height, $place_center = 0, $return_type = 'only_margin')
{
    // функция формирует стили для фото для цетрирования его в блоке по вертикали и горизонтали
    $aMargin = array("top" => 0, "left" => 0);
    $pic_path = "";
    $no_margin = false;
    if ($pic_module == "avatar") {
        $pic_path = realpath(".") . AVATAR_PATH . $pic_name;
    }
    if ($pic_module == "photo") {
        $pic_path = realpath(".") . THUMBS_PHOTO_BANK_UPLOAD_PATH . $pic_name;
    }
    if ($pic_module == "original_photo") {
        $pic_path = realpath(".") . PHOTO_BANK_UPLOAD_PATH . $pic_name;
    }
    if ($pic_module == "photo_album") {
        $pic_path = $pic_name;
    }
    if ($pic_module == "one_photo") {
        $pic_path = $pic_name;
    }
    if ($pic_module == "real_path") {
        $pic_path = $pic_name;
    }
    if ($pic_module == "video") {
        $pic_path = realpath(".") . VIDEO_PATH . $pic_name;
    }
    if ($pic_module == "partner") {
        $pic_path = realpath(".") . PARTNER_PATH . $pic_name;
    }
    $size_style_img = array('resize_dim' => 'none', 'size_style' => '');
    if (is_file($pic_path)) {
        $aSize = getimagesize($pic_path);
        $pic_w = $aSize[0];
        $pic_h = $aSize[1];
        if ($return_type == 'full_img_style') {
            $size_style_img = get_size_style_img($pic_path, $window_width, $window_height, 1);
            // если фотку уменьшили до одной из координат, находим вторую координату, и расчитываем для них
            if ($size_style_img['resize_dim'] == 'height') {
                $pic_w = $window_height * $pic_w / $pic_h;
                $pic_h = $window_height;
                // если одну ось уменьшили до нужной, но вторая ось стала меньшенужной блоку
                if ($pic_w < $window_width) {
                    $no_margin = true;
                }
            }
            if ($size_style_img['resize_dim'] == 'width') {
                $pic_h = $pic_h * $window_width / $pic_w;
                $pic_w = $window_width;
                if ($pic_h < $window_height) {
                    $no_margin = true;
                }
            }
        }
        //prn('pic_h = '.$pic_h.' pic_w = '.$pic_w);
        // вычисляем пропорции картинки и окна в кот. выводить
        $window_koef = $window_width / $window_height;
        if ($pic_w && $pic_h) {
            $pic_koef = $pic_w / $pic_h;
            if ($pic_koef < $window_koef) {
                //if($pic_w<$window_width){
                $pic_h = ($window_width / $pic_w) * $pic_h;
                //}

                $aMargin["top"] = "-" . (string)($pic_h / 2 - $window_height / 2);
            }
            if ($pic_koef > $window_koef) {
                if ($pic_h < $window_height) {
                    $pic_w = ($window_height / $pic_h) * $pic_w;
                }
                $aMargin["left"] = "-" . (string)($pic_w / 2 - $window_width / 2);
            }
            if ($place_center != 0) {
                if ($window_width > $aSize[0]) {
                    $aMargin["left"] = ($window_width - $aSize[0]) / 2;
                }

                if ($window_height > $aSize[1]) {
                    $aMargin["top"] = ($window_height - $aSize[1]) / 2;
                }
            }
        }
    } else {
        if ($return_type == 'full_img_style') {
            return 'min-width:' . $window_width . 'px; min-height:' . $window_height . 'px;';
        } else {
            $aMargin = array("top" => 0, "left" => 0, "error" => "no file");
        }
    }
    if ($return_type == 'full_img_style') {
        if ($no_margin) {
            return $size_style_img['size_style'];
        }
        return 'margin-left:' . $aMargin['left'] . 'px; margin-top:' . $aMargin['top'] . 'px; ' . $size_style_img['size_style'];
    }
    return $aMargin;
}

function get_size_style_img($img_real_path, $block_width = 0, $block_height = 0, $with_resize_dim = false)
{
    // функция расчитывает стиль высоты и широты для фотки,
    // которая больше или меньше блока, в который будет вписана
    $sSize_style = '';
    $resize_dim = 'none';

    if (is_file($img_real_path)) {
        $aSize = getimagesize($img_real_path);
        $pic_w = $aSize[0];
        $pic_h = $aSize[1];
        $koef_window = $block_width / $block_height;
        $koef_pic = $pic_w / $pic_h;

        if ($pic_w < $pic_h) {
            $resize_dim = 'width';
            // $sSize_style = 'width:'.$block_width.'px;';

            // проверяем пропорции сторон картинки и блока(ширина к высоте)
            if ($block_width < $block_height) {
                if ($koef_window > $koef_pic) {
                    $resize_dim = 'height';
                    // $sSize_style = 'height:'.$block_height.'px;';
                }
            }
        } else {
            $resize_dim = 'height';
            //$sSize_style = 'height:'.$block_height.'px;';

            // проверяем пропорции сторон картинки и блока(ширина к высоте)
            if ($block_width > $block_height) {
                if ($koef_window > $koef_pic) {
                    $resize_dim = 'width';
                    // $sSize_style = 'width:'.$block_width.'px;';
                }
            }
        }
        if ($resize_dim == 'width') {
            $sSize_style = 'width:' . $block_width . 'px;  height:auto;';
        } else {
            $sSize_style = 'height:' . $block_height . 'px; width:auto;';
        }
    }
    $sSize_style .= ' min-width:' . $block_width . 'px; min-height:' . $block_height . 'px;';
    if ($with_resize_dim) {
        return array('size_style' => $sSize_style, 'resize_dim' => $resize_dim);
    }
    return $sSize_style;
}

function avatar_jobs($obj, $size)
{
    if (!empty($obj->jobs_avatar)) {
        $avatar = get_img($obj->jobs_avatar, AVATAR_JOBS_PATH, $size);
    } elseif (!empty($obj->avatar_company)) {
        $avatar = get_img($obj->avatar_company, AVATAR_COMPANY_PATH, $size);
    } else {
        $avatar = get_img($obj->avatar, AVATAR_PATH, $size);
    }
    return $avatar;
}


/*функции от денчика*/
/**
 * проверка и создание  папки
 * @param null $path
 * @return null
 */
function checkAndMakeMediaDir($path = null)
{
    if ($path && !is_dir(realpath('.') . '/' . $path)) {

        mkdir(realpath('.') . '/' . $path, 0777);
    }
    return $path;
}

function removeCopy($path_to_dir = null, $name_parent_photo = '')
{
    $mainDir = realpath('.') . '/' . $path_to_dir;
    if ($path_to_dir && strlen($name_parent_photo) &&  is_dir($mainDir)) {
        $aFiles = scandir($mainDir);
        foreach ($aFiles as $key => $value) {
            if (strpos($value, $name_parent_photo) !== FALSE) {
                    unlink($mainDir.'/'.$value);
            }
        }

    }
}

?>