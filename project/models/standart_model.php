<?php
/*
get_fields_data_tabl() получение данных
get_query_tabl($sql,$result_array=false)-- Выполнение sql запроса и получение результата
get_data_tabl($table_name,$where='',$order_field=0,$result_array=false)-- Получение данных таблицы
insert_data_tabl($table_name,$values)-- Добавление данных
update_data_tabl($table_name,$values,$id_values,$id_field=null)-- Обновление данных
delete_data_tabl($table_name,$id_values=null,$where=null)-- Удаление данных
*/
class Standart_model extends CI_Model {

protected $table_description="field_description";
protected $table_field_id="field";

/* получение данных */
function get_fields_data_tabl($table_name,$fields='*',$where=null, $order_field=null,$result_array=false,$per_page=null,$page=0)
{
    $this->db->select($fields);
    $this->db->from($table_name);
    if(isset($where)){ $this->db->where($where); }
    if(isset($order_field)){ $this->db->order_by($order_field); }
    if((isset($per_page))&&(isset($page))){$this->db->limit($per_page, $page);}

    return ($result_array)?$this->db->get()->result_array():$this->db->get()->result();
}

/* получение данных таблицы SQL запросом */
function get_query_tabl($sql,$result_array=false)
{
    $query = $this->db->query($sql);
    if ($result_array)
        return $query->result_array();
    else
        return $query->result();
}
/* получение данных таблицы */
function get_data_tabl($table_name, $where='', $order_field=null, $result_array=false, $page=null, $per_page=null)
{
    $this->db->select('*');
    $this->db->from($table_name);
    if(!empty($where)){ $this->db->where($where); }
    if(!empty($order_field)){ $this->db->order_by($order_field); }
    if((isset($per_page))&&(isset($page))){$this->db->limit($per_page, $page);}
    return ($result_array)?$this->db->get()->result_array():$this->db->get()->result();
}

/* добавление данных в таблицу */
function insert_data_tabl($table_name,$values)
{
    if($this->db->insert($table_name, $values)){
         return $this->db->insert_id();
    }else{
         return false;
    }
}

/* обновление данных в таблице */
function update_data_tabl($table_name, $values, $where)
{
    return ($this->db->update($table_name, $values, $where)) ? true : false;
}

/* удаление записи с таблицы */
function delete_data_tabl($table_name, $where=null)
{
      return $this->db->delete($table_name, $where);
}

}

?>
