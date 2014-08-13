<?php
/*----------class-----------*/

class Uploadfile {
	
	private $login="";
	private $folder="";
	private $width=129;
	private $height=83;
	private $Max_width=1024;
	private $Max_height=768;
	public $file_deatail=FALSE;
	
	function __construct()
	{
	   $oCI = $this->_get_inst();
	   $oCI->lang->load('error_auten');
	}
	
	// загрузка аватаров
	function set_login($login)
	{
	    $this->login = $login;
	}
	
	// загрузка фото проекта
	function set_folder($folder)
	{
	    $this->folder = $folder;
	}

	function set_resize($w,$h)
	{
	    $this->width = $w; 
	    $this->height = $h;
	}

	function set_resize_max($w,$h)
	{
	    $this->Max_width = $w; 
	    $this->Max_height = $h;
	}
	
	function upload($path_to_upload = AVATAR_PATH,$bResize = FALSE)
	{
	      $oCI = $this->_get_inst();
	      $path=realpath('.').$path_to_upload;
	      $tmp_folder = "tmp";
	      
	      $config['upload_path'] = $path.$tmp_folder;
	      $config['allowed_types'] = 'gif|jpg|png';
	      $config['max_size'] = '5024';    // 5 Мб
	      $config['max_width']  = '2000';
	      $config['max_height']  = '2000';
	      $config['overwrite'] = TRUE;          // перезапись
	      $config['encrypt_name'] = TRUE;
	      
	      $oCI->load->library('upload', $config);
      
	      if (!$oCI->upload->do_upload())
	      {
		    return array('result'=>false,'message'=>mysql_real_escape_string(htmlspecialchars(strip_tags($oCI->upload->display_errors()))));
	      }
          else
		  {
		    
            // Успешная загрузка
		    // Удаление старых аватаров
		    $file_ext = array("gif","jpg","png");
		    foreach($file_ext as $item):
		    
			if(is_file($path . "/" . $this->login .".". $item))
			      @unlink($path . "/" . $this->login .".". $item);
			
		    endforeach;
		    
		    $mas=$oCI->upload->data();
		    $this->file_deatail = $mas;
		    $uplFile = $path . $tmp_folder . "/" . $mas['file_name'];
		    $config = array();
		    $config['image_library'] = 'gd2'; // выбираем библиотеку
		    $config['source_image'] = $uplFile; 
		    $config['maintain_ratio'] = TRUE; // сохранять пропорции
		    // и задаем размеры
		    
		    if($bResize)
		    {
				$config['width'] = $this->width; 
				$config['height'] = $this->height;
			}
			
		    $config['new_image']= $path . "/" . $this->login . $mas['file_ext'];

		    $oCI->load->library('image_lib', $config); // загружаем библиотеку 
		    $oCI->image_lib->resize(); // и вызываем функцию 
		    
		    // Удаление временного файла
		    unlink($uplFile);
		    
		    return array('result'=>true,'file_name'=>$this->login.$mas['file_ext'],'url_path'=>(base_url().$path_to_upload.$this->login.$mas['file_ext']),'file_deatail'=>$mas);
            
		  }
	}

	function imageResize($uplFile ,$newFile, $config_image = array(), $type_resize = 'outside')
	{
		    list($width,$height) = $config_image;
		    
		    $config = array();
		    $config['image_library'] = 'gd2'; // выбираем библиотеку
		    $config['source_image'] = $uplFile; 
		    $config['maintain_ratio'] = TRUE; // сохранять пропорции

            if(!empty($width))
		    {
				$config['width'] = $width; // и задаем размеры
			}
			
			if(!empty($height))
			{
				$config['height'] = $height;
			}

            $img_param = getimagesize($uplFile);
            // если ширина > высоты
            //(при таких настройках обрезка работает по принципу достигли одной координаты, перестали уменьшать)
            // если поменять условие, то будет работать по принципу чтоб обе координаты вмещались в заданую область
            if ($type_resize == 'outside')
            {
                if ($img_param[0] > $img_param[1])
                {
                    $config['master_dim'] = 'height';
                } else
                {
                    $config['master_dim'] = 'width';
                }
            }
            elseif($type_resize == 'inside')
            {
                if($img_param[0] < $img_param[1]){
                    $config['master_dim'] = 'height';
                } else{
                    $config['master_dim'] = 'width';
                }

            }

		    $config['new_image']= $newFile;
		    
		    $oCI = $this->_get_inst();
		    
		    $oCI->load->library('image_lib', $config); // загружаем библиотеку 
		    $oCI->image_lib->initialize($config); 
		    $oCI->image_lib->resize(); // и вызываем функцию 		  
		    
		    if(!$oCI->image_lib->resize())
			{
				echo $oCI->image_lib->display_errors();
			}
	}

    /*

     function imageResize($uplFile, $newFile, $width = null, $height = null, $ratio = TRUE, $type_resize = 'outside')
    {
        $width = (int)$width;
        $height = (int)$height;
        if ($width > 0 || $height > 0)
        {
            $config = array();
            $config['image_library'] = 'gd2'; // выбираем библиотеку
            $config['source_image'] = $uplFile;
            $config['maintain_ratio'] = TRUE; // сохранять пропорции

            if ($width > 0)
            {
                $config['width'] = $width;
            }

            if ($height > 0)
            {
                $config['height'] = $height;
            }

            $img_param = getimagesize($uplFile);

            // если ширина > высоты
            //(при таких настройках обрезка работает по принципу достигли одной координаты, перестали уменьшать)
            // если поменять условие, то будет работать по принципу чтоб обе координаты вмещались в заданую область
            if ($type_resize == 'inside')
            {
                if ($img_param[0] > $img_param[1])
                {
                    $config['master_dim'] = 'height';
                } else
                {
                    $config['master_dim'] = 'width';
                }
            } else
            {
                if($type_resize=='outside_full'){
                        // картинка будет обрезаться до большей координаты из указанных для обрезания
                            //при  этом учитываются соотношение сторон самой картинки
                    if ($img_param[0] < $img_param[1]){
                        $config['master_dim'] = 'width';
                    } else{
                        $config['master_dim'] = 'height';
                    }
                }
                else{
                    if ($img_param[0] < $img_param[1]){
                        $config['master_dim'] = 'height';
                    } else{
                        $config['master_dim'] = 'width';
                    }
                }
            }

            $config['new_image'] = $newFile;

            $oCI = $this->_get_inst();

            $oCI->load->library('image_lib', $config); // загружаем библиотеку
            $oCI->image_lib->initialize($config);

            if (!$oCI->image_lib->resize())
            {
                echo $oCI->image_lib->display_errors();
            }

            $oCI->image_lib->clear();


        }
    }
    */
	
   
    
	function uploadFoto($sPathUpl = PROJECT_PATH)
	{
	      $oCI = $this->_get_inst();
	      $path=realpath('.').$sPathUpl;
	      $tmp_folder = $this->folder."/";
	      
	      if(!is_dir($path.$tmp_folder))
	      {
		  @mkdir($path.$tmp_folder,0777);
	      }
	      
	      $iNumberFoto = 1;
	    
	      $map = directory_map($path.$tmp_folder, TRUE);

	      if(isset($map)&&(!empty($map)))
	      {
		      for($i=0; $i<count($map); $i++)
		      {
			      $img=explode(".", $map[$i]);
			      $aNumber[]=(int)$img[0];
		      }
		      $iNumberFoto = max($aNumber)+1;
	      }		      
	      
	      $config['upload_path'] = $path.$tmp_folder;
	      $config['allowed_types'] = 'gif|jpg|png';
	      $config['max_size'] = '5024';    // 5 Мб
	      $config['overwrite'] = TRUE;          // перезапись
	      $config['encrypt_name'] = TRUE;
	      
	      $oCI->load->library('upload', $config);
	      
	      if($iNumberFoto>6)
	      {
		  return "0 | Максимальное количество фото 6";
	      }
	      
	      if (!$oCI->upload->do_upload())
	      {
		    return "0 | Ошибка загрузки файла ".$config['upload_path']. " " .iconv("windows-1251","utf-8",$oCI->upload->display_errors());
	      }else
		  {
		    // Успешная загрузка
		    
		    $mas=$oCI->upload->data();
		    $uplFile = $path . $tmp_folder . $mas['file_name'];
		    $config = array();
		    $config['image_library'] = 'gd2'; // выбираем библиотеку
		    $config['source_image'] = $uplFile; 
		    $config['maintain_ratio'] = TRUE; // сохранять пропорции
		    $config['width'] = $this->width; // и задаем размеры
		    $config['height'] = $this->height;

		    $config['new_image']= $path . $tmp_folder . $iNumberFoto . $mas['file_ext'];

		    $oCI->load->library('image_lib', $config); // загружаем библиотеку 
		    $oCI->image_lib->resize(); // и вызываем функцию 
		    
		    /* -- Original -- */
		    $config['width'] = $this->Max_width; // и задаем размеры
		    $config['height'] = $this->Max_height;

		    $config['new_image']= $path . $tmp_folder . $iNumberFoto ."_orig". $mas['file_ext'];

		    $oCI->image_lib->initialize($config); 
		    $oCI->image_lib->resize(); // и вызываем функцию 

		    // Удаление временного файла
		    unlink($uplFile);
		    
		    return "1 | ".base_url().$sPathUpl.$tmp_folder.$iNumberFoto.$mas['file_ext'];
		  }
		
	}
	
	function deleteFoto($foto_num,$sPathUpl = PROJECT_PATH)
	{
	      $path=realpath('.').$sPathUpl;;
	      $tmp_folder = $this->folder."/";
	      $path .= $tmp_folder;
	      $map = directory_map($path, TRUE);
	      
	      if(count($map)>0)
	      {
		  $numold=1;$inc=false;
		  @sort($map);

		  foreach($map as $item):
		  
		    if(!empty($item))
		    {
			  $num=explode(".",$item);
			  
			  if (!is_Numeric(trim($num[0])))
			  {
			      $nameTmp = explode("_",$num[0]);
			      $num[0] = $nameTmp[0];
			      $prefName = "_".$nameTmp[1];
			  }else
			      {
				  $prefName = "";
			      }
			  
			  if($num[0]==$foto_num)
			  {
			      if(is_file($path.$item))
					    @unlink($path.$item);
			  }
			  
			  if($num[0]>$foto_num)
			  {
			      rename($path.$item, $path."/".intval($num[0]-1).(empty($prefName)?null:$prefName).".".$num[1]);
			  }
			  
		    }
		   endforeach;
	      }
	}

	protected function _get_inst()
	{
	  $inst = & get_instance();
	  return $inst;
	}
}
