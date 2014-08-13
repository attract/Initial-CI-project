<?php

class qqFileUploader
{

    private $allowedExtensions = array();
    private $sizeLimit = 104857600; // 100M
    private $file = false;
    private $uploadDirectory='';
    private $make_unique_name=false;
    private $first_unique_prefix=1;
    private $unique_name='';
    // сохранять в папку tmp в указанной папке
    public $save_in_tmp = TRUE;

    function __construct($config= array())
    {
        $oCI = $this->_get_inst();
        $oCI->load->library('image_lib');
        $oCI->lang->load('error_auten');

        if (count($config) > 0)
        {
            $this->initialize($config);
        }

        $this->checkServerSettings();

        if(isset($_GET['qqfile']))
        {
            $this->file = new qqUploadedFileXhr();
        }elseif(isset($_FILES['qqfile']))
        {
            $this->file = new qqUploadedFileForm();
        }else
        {
            $this->file = false;
        }
    }

    /**
     * Initialize preferences
     *
     * @access	public
     * @param	array
     * @return	void
     */
    public function initialize($config = array())
    {
        foreach ($config as $key => $val)
        {

            if (isset($this->$key))
            {
                $method = 'set_'.$key;

                if (method_exists($this, $method))
                {
                    $this->$method($val);
                }
                else
                {
                    $this->$key = $val;
                }
            }
        }
        return $this;
    }

    public function set_allowedExtensions($ext=array())
    {
        $this->allowedExtensions = array_map("strtolower", $ext);
        return $this;
    }

    public function set_uploadDirectory($dir='/media/upload/')
    {
        $this->uploadDirectory = realpath('.').$dir;
        return $this;
    }


    protected function _get_inst()
    {
        $inst = & get_instance();
        return $inst;
    }


    private function checkServerSettings()
    {
        /* $postSize = $this->toBytes(ini_get('post_max_size'));
      $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

      if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
          $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
          die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
      }  */
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last)
        {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }


    /*
   * Upload File (image,video,audio,txt. ect)
   * @param string $main_patch
   * @param string $file_name
   * return array
   * */
    function UploadFile($main_path = false, $file_name = false)
    {
        $path_length = strlen($main_path);
        if($main_path[$path_length-1]!='/')
        {
            $main_path .= '/';
        }

        if (!$main_path || (!is_dir(realpath('.').$main_path)) || (!is_writable(realpath('.').$main_path)))
        {
            echo "No directory". realpath('.').$main_path  ."or no 777";
            return false;
        }

        if($this->save_in_tmp)
        {
            $main_path =$main_path . 'tmp/';
            if (!is_dir(realpath('.').$main_path)){

                mkdir(realpath('.').$main_path, 0777);
            }
        }

        $pathinfo = pathinfo($this->file->getName());
        $ext = $pathinfo['extension'];
        $this->uploadDirectory = realpath('.') . $main_path;

        if (!$file_name)
        {
            $file_name = md5(time() . uniqid());
            $path_real_file = $this->uploadDirectory . $file_name . '.' . $ext;
            $file_name .= $ext;
        }
        else
        {
            $path_real_file = $this->uploadDirectory . $file_name;
        }


        $save_result = $this->file->save($path_real_file); //save

        if($save_result)
        {
            return array('file_name' => $file_name, 'main_path' => $main_path);
        }
        return false;

    }
    function MyUploadFile($file_name=false)
    {
        $response = array('status'=>'error');
        $error = $this->CheckErrors();
        if(!$error)
        {
            if($this->save_in_tmp)
            {
                $this->uploadDirectory = $this->uploadDirectory . 'tmp/';
                if(!is_dir($this->uploadDirectory)){
                    mkdir($this->uploadDirectory, 0777);
                }
            }

            $pathinfo = pathinfo($this->file->getName());
            $ext = $pathinfo['extension'];

            if(!$file_name)
            {
                $file_name = md5(time() . uniqid());
                $path_real_file = $this->uploadDirectory . $file_name . '.' . $ext;
                $file_name .= '.'.$ext;
            }
            else
            {
                $path_real_file = $this->uploadDirectory . $file_name;
            }
            // создавать приставку к имени (1), (2)... для уникальности
            if($this->make_unique_name)
            {
                $this->unique_name = $file_name;
                $this->f_make_unique_name($file_name);
                $file_name = $this->unique_name;
                $path_real_file = $this->uploadDirectory . $file_name;
            }

            $save_result = $this->file->save($path_real_file); //save

            if($save_result)
            {
                $response['status']='success';
                $response['data']=array('file_name'=>$file_name, 'main_path'=>base_url().str_replace(realpath('.'), '', $this->uploadDirectory),'ext'=>$ext);
            }
            else
            {
                $response['data'] = 'ошибка загрузки';
            }
        }
        else
        {
            $response = array('status'=>'error', 'data'=>$error['error']);
        }
        return $response;
    }

    function f_make_unique_name($file_name)
    {
        if(is_file($this->uploadDirectory.$file_name))
        {
            $file_name = '('.$this->first_unique_prefix.')'.$this->unique_name;
            $this->first_unique_prefix++;
            $this->f_make_unique_name($file_name);
        }
        else
        {
            $this->unique_name = $file_name;
            return true;
        }
    }

    /*
   * Upload Image (image,video,audio,txt. ect)
   * @param string $main_patch
   * @param string $file_name
   * @param array $array_copy=array('{width}','{height}',{path},{outsize/insize})
   * return array/boolean(TRUE/FALSE)
   * */
    public function UploadImage($main_path = false, $file_name = false, $array_copy = null)
    {
        if (!$main_path) return FALSE;
        $result = $this->UploadFile($main_path, $file_name);
        if($result && is_array($array_copy))
        {
            foreach ($array_copy as $value)
            {
                //prn($array_copy);
                $width = (!empty($value[0]))?$value[0]:'';
                $height = (!empty($value[1]))?$value[1]:'';
                $path = (!empty($value[2]))?$value[2]:'';
                $type_resize = (!empty($value[3]))?$value[3]:'inside';
                $this-> ResizeImage($result['file_name'],$main_path,$width,$height,$path,$type_resize);
            }

        }
        return $result;

    }

    public function ResizeImage($file_name=null,$path_in=null,$width=null,$height=null,$path_out=null,$type_resize = 'inside')
    {


        if((!$file_name)||(!$path_in)||(!is_dir(realpath('.').$path_in)) ||(!$path_out) ||(!is_dir(realpath('.').$path_out))) return FALSE;
        $path_in_file = realpath('.').$path_in.$file_name;
        $pref = ($width)?$width:time();
        $path_out_file = realpath('.').$path_out.$pref.'_'.$file_name;
        $this->imageResize($path_in_file,$path_out_file, $width, $height, TRUE,$type_resize);
        return TRUE;
    }

    public function CheckErrors()
    {
        if (!is_writable($this->uploadDirectory))
        {
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file)
        {
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0)
        {
            return array('error' => 'File is empty');
        }
        if ($size > $this->sizeLimit)
        {
            return array('error' => 'File is too large');
        }

        $pathinfo = pathinfo($this->file->getName());
        if(empty($pathinfo['extension']))
        {
            return array('error' => 'Не указан тип файла');
        }
        $ext = $pathinfo['extension'];

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions))
        {
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of ' . $these . '.');
        }
    }



    //$type_resize = inside||outside
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
                if ($img_param[0] < $img_param[1])
                {
                    $config['master_dim'] = 'height';
                } else
                {
                    $config['master_dim'] = 'width';
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


}

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize())
        {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    function getName()
    {
        return $_GET['qqfile'];
    }

    function getSize()
    {
        if (isset($_SERVER["CONTENT_LENGTH"]))
        {
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else
        {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path))
        {
            return false;
        }
        return true;
    }

    function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    function getSize()
    {
        return $_FILES['qqfile']['size'];
    }
}
