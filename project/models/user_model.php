<?php
class User_model extends CI_Model
{

    function get_list($group_id = null, $no_moder = null,$where=null, $page=null,$per_page=null,$order_by=null)
    {
        $this->db->select('SQL_CALC_FOUND_ROWS user.*',FALSE);
        //$this->db->select('group.group');
        $this->db->from('user');
        //$this->db->join('group', 'group.id_group = user.group_id', 'left');
        /*if ($group_id)
        {
            //$group_id = str_replace(array('{', '}'), '', $group_id);
            $ar = explode(',',$group_id);
            if(!in_array(0,$ar))
            if (strlen($group_id)) $this->db->where_in('group_id', explode(',',$group_id));
        }*/
        if ($per_page!==null || $page!==null)
        {
            $this->db->limit($per_page, $page);
        }

        if (!empty($where))
        {
            $this->db->where($where);
        }
        if($order_by)
        {
            $this->db->order_by($order_by);
        }
        if ($no_moder) $this->db->where('is_moder', '0');
        return $this->db->get()->result();
    }


    function get_user($id_user)
    {
        //$this->db->select('user.*');
        //$this->db->select('type_user.*');
        $this->db->from('user');
        //$this->db->join('group', 'group.id_group = user.group_id', 'left');
        $this->db->where('id_user', $id_user);
        $this->db->limit(1, 0);
        $uInfo = (array)$this->db->get()->row();

        return (object)$uInfo;

    }

    function get_city($id_country)
    {

        $where = array('country_id' => $id_country);

        $this->db->from('city');
        $this->db->where($where);
        $this->db->order_by('city');

        return $this->db->get()->result_array();
    }
    function get_search($table = null, $where)
    {
        if ($table)
        {
            $this->db->from($table);
            $this->db->where($where);
            $this->db->limit(10);


            return $this->db->get()->result_array();

        }
    }

    public  function hide_all_info($id=null)
    {
        if(!$id) return;
        $sql = "INSERT INTO `user_hide_info` (`user_id`, `field_name`) VALUES (".$id.", 'phone_user'), (".$id.", 'work_user'), (".$id.",'email_user'), (".$id.",'country_id'),(".$id.",'social')";
        $this->db->query($sql);
    }

    function get_user_edit_setting($id)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('id_user = '.$id);

        return $this->db->get()->result_array();
    }
/* мои функции */
    function get_company_users($where=false, $order_field=null, $page=null, $per_page=null)
    {
        $this->db->select('user.*, company_user.*, company.is_buyer');
        $this->db->from('user');
        $this->db->join('company_user', 'user.id_user = company_user.user_id', 'left');
        $this->db->join('company', 'company.id_company = company_user.company_id', 'left');

        if(!empty($where)){ $this->db->where($where); }
        if(!empty($order_field)){ $this->db->order_by($order_field); }
        if((isset($per_page))&&(isset($page))){$this->db->limit($per_page, $page);}
        return $this->db->get()->result();
    }

}

?>