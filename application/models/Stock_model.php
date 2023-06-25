<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model {

    private $table = 'product_stock';
    private $order = ['product_id', 'qty', 'created_at'];

    private function join_table() {
        $this->db->select('
            ps.stock_id,
            ps.qty,
            ps.stock_type,
            ps.created_at,
            p.product_id,
            pd.product_name,
            pd.product_image,
            ')
                ->from($this->table. ' as ps')
                ->join('products as p', 'ps.product_id = p.product_id')
                ->join('product_details as pd', 'ps.product_id = pd.product_id');
    }

    private function _get_query(){
        $this->join_table();
        if(strip_tags(htmlspecialchars($_POST['search']['value']))){
            $this->db->like('product_name', strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        if($_POST['order'][0]['column']){
            $this->db->order_by($this->order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by('stock_id', 'DESC');
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

    public function count_all_result_value(){
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function get_stock_id($where){
        return $this->db->get_where($this->table, $where)->row();
    }

    public function insert($data){
        $this->db->insert($this->table, $data);
        if ($data['stock_type'] == 'in') {
            $count = 'current_stock+'.$data["qty"];
        } else {
            $count = 'current_stock-'.$data["qty"];
        }
        $this->db->set('current_stock', $count , FALSE);
        $this->db->where('product_id', $data['product_id']);
        $this->db->update('product_details');
    }

    public function remove($data){
        $this->db->delete($this->table, ['stock_id'=>$data->stock_id]);
        if ($data->stock_type == 'out') {
            $count = 'current_stock+'.$data->qty;
        } else {
            $count = 'current_stock-'.$data->qty;
        }
        $this->db->set('current_stock', $count , FALSE);
        $this->db->where('product_id', $data->product_id);
        $this->db->update('product_details');
    }
}

/* End of file Stock_model.php */
