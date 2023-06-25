<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banner_model extends CI_Model {

    private $table = 'banner';
    private $orderable = ['banner_name'];

    private function _get_query(){
        $this->db->from($this->table);
        if(strip_tags(htmlspecialchars($_POST['search']['value']))){
            $this->db->like('banner_name', strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        if($_POST['order'][0]['column']){
            $this->db->order_by($this->orderable[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by('banner_id', 'DESC');
        }
    }

    public function result_data(){
        $this->_get_query();
        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered(){
        $this->_get_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_result(){
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function insert($data){
        $this->db->insert($this->table, $data);
    }

    public function update($data, $where){
        $this->db->update($this->table, $where, $data);
    }

    public function delete($where){
        $this->db->delete($this->table, $where);
    }

}

/* End of file Banner_model.php */
