<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Helps extends MY_Controller {

    public function __construct()
    {
        parent :: __construct();
    }

    function ajax_city()
    {
        $id_country = (int)$this->input->post('id_country');
        $result = $this->ST->get_data_tabl('city', array('country_id'=>$id_country), 'city_order');

        $response = array();
        $response['result'] = '<option value="0">Выберите город</option>';
        if(!empty($result))
        {
            foreach($result as $item)
            {
                $response['result'] .= '<option value="'.$item->id_city.'">'.$item->city.'</option>';
            }
        }
        echo json_encode($response);
    }

    function ajax_metro()
    {
        $id_city = (int)$this->input->post('id_city');
        $result = $this->ST->get_data_tabl('station', array('city_id'=>$id_city), 'name_station');

        $response = array();
        $response['result'] = '<option value="0">Выберите станцию</option>';
        if(!empty($result))
        {
            foreach($result as $item)
            {
                $response['result'] .= '<option value="'.$item->id_station.'">'.$item->name_station.'</option>';
            }
        }
        echo json_encode($response);
    }

}