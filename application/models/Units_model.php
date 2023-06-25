<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Units_model extends CI_Model {
    
    protected $table = 'product_units';
    protected $primaryKey = 'unit_id';
    protected $columns = ['unit_id', 'unit_name', 'unit_type', 'is_delete'];

    private function _get_query(){
        $this->db->from($this->table);
        if(strip_tags(htmlspecialchars($_POST['search']['value']))){
            $this->db->like($this->columns[1], strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        if($_POST['order'][0]['column']){
            $this->db->order_by($this->columns[   strip_tags(htmlspecialchars($_POST['order'][0]['column']))], 
                                                strip_tags(htmlspecialchars($_POST['order'][0]['dir'])));
        } else {
            $this->db->order_by($this->columns[0], 'DESC');
        }
    }

    public function result_data(){
        $this->_get_query();
        if($_POST['length'] != -1){
            $this->db->limit(   strip_tags(htmlspecialchars($_POST['length'])), 
                                strip_tags(htmlspecialchars($_POST['start'])));
        }
        return $this->db->get()->result();
    }

    public function count_filtered(){
        $this->_get_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_result_value(){
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function get_data(){
        $this->db->from($this->table);
        if($this->input->post('s')){
            $this->db->like($this->columns[2], strip_tags(htmlspecialchars($this->input->post('s'))));
        }
        return $this->db->get()->result();
    }

    public function get(){
        $this->db->select('unit_id as id, unit_name as name , unit_type as short');
        if($this->input->post('select_search', TRUE)){
            $this->db->like($this->columns[1], $this->input->post('select_search', TRUE));
        }
        return $this->db->get($this->table)->result();
    }

    public function get_unit_id($id){
        return $this->db->get_where($this->table, [ $this->columns[0] => $id])->first_row();
    }

    public function insert_data($data){
        $this->db->insert($this->table, $data);
    }

    public function update_data($id, $data){
        $this->db->update($this->table, $data, [ $this->columns[0] => $id ]);
    }

    public function remove_data($id){
        $this->db->delete($this->table, [ $this->columns[0] => $id ]);
    }

}