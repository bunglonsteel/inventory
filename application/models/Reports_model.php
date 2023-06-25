<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {

    private $order       = ['payment_id', 'amount'];
    private $order_stock = ['product_name', 'current_stock'];

    private function _select_query_sales(){
        $this->db->select('DATE(payment_date) as date, SUM(amount) as total')
                ->from('payments');
    }

    private function _get_query_sales(){
        
        $start_date = $_POST['start_date_filter'] ?? date('Y-m-d', strtotime($_POST['start_date_filter']));
        $end_date   = $_POST['end_date_filter'] ?? date('Y-m-d', strtotime($_POST['end_date_filter']));

        $this->_select_query_sales();
        $this->db->group_by('DATE(payment_date)');
        if($_POST['order'][0]['column']){
            $this->db->order_by($this->order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by($this->order[0], 'DESC');
        }

        if ($start_date && $end_date) {
            $this->db->where("DATE(payment_date) >= '$start_date' AND DATE(payment_date) <= '$end_date'");
        }
    }

    private function _get_query_stock(){
        $this->db->select('product_name, current_stock')
                ->from('product_details');

        if($_POST['order'][0]['column']){
            $this->db->order_by($this->order_stock[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by($this->order_stock[0], 'DESC');
        }
    }

    public function result_data($type = 'sales'){
        if ($type == "sales") {
            $this->_get_query_sales();
        } else {
            $this->_get_query_stock();
        }
        if($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered($type = 'sales'){
        if ($type == "sales") {
            $this->_get_query_sales();
        } else {
            $this->_get_query_stock();
        }
        return $this->db->get()->num_rows();
    }

    public function count_all_result($type = 'sales'){
        if ($type == "sales") {
            $this->_get_query_sales();
        } else {
            $this->_get_query_stock();
        }
        return $this->db->count_all_results();
    }

    public function sales($type){
        $this->_select_query_sales();
        switch ($type) {
            case 'month':
                $this->db->where("MONTH(payment_date) = MONTH(CURRENT_DATE()) AND YEAR(payment_date) = YEAR(CURRENT_DATE())");
                break;
            case 'year':
                $this->db->where("YEAR(payment_date) = YEAR(CURRENT_DATE())");
                break;
            default:
                break;
        }
        return $this->db->get()->row();
    }

    public function expenses($type){
        $this->db->select('DATE(expense_date) as date, SUM(expense_amount) as total')
                ->from('expenses');
        switch ($type) {
            case 'month':
                $this->db->where("MONTH(expense_date) = MONTH(CURRENT_DATE()) AND YEAR(expense_date) = YEAR(CURRENT_DATE())");
                break;
            case 'year':
                $this->db->where("YEAR(expense_date) = YEAR(CURRENT_DATE())");
                break;
            default:
                break;
        }
        return $this->db->get()->row();
    }

}

/* End of file Reports_model.php */
