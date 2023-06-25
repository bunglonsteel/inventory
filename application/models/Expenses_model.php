<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Expenses_model extends CI_Model
{

    private $table = ['ex' => 'expenses', 'ec' => 'expense_categories'];
    private $order = ['expense_cat_name', 'expense_cat_name', 'expense_amount', 'expense_notes', 'expense_date'];

    private function _get_query($table)
    {
        $this->db->from($this->table[$table]);
        if ($table == 'ex') {
            $this->db->join($this->table['ec'], $this->table[$table] . '.expense_cat_id = ' . $this->table['ec'] . '.expense_cat_id');
        }
        if (strip_tags(htmlspecialchars($_POST['search']['value']))) {
            $this->db->like($this->order[0], strip_tags(htmlspecialchars($_POST['search']['value'])));
            if ($table == 'ex') {
                $this->db->or_like($this->order[4], strip_tags(htmlspecialchars($_POST['search']['value'])));
            }
        }

        if ($_POST['order'][0]['column']) {
            $this->db->order_by($this->order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            if ($table == "ex") {
                $order_by = 'expense_id';
            } else {
                $order_by = 'expense_cat_id';
            }
            $this->db->order_by($order_by, 'DESC');
        }
    }

    public function result_data($table)
    {
        $this->_get_query($table);
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered($table)
    {
        $this->_get_query($table);
        return $this->db->get()->num_rows();
    }

    public function count_all_result($table)
    {
        $this->_get_query($table);
        return $this->db->count_all_results();
    }

    public function get()
    {
        $this->db->select('expense_cat_id as id, expense_cat_name as name');
        if ($this->input->post('select_search', TRUE)) {
            $this->db->like($this->order[1], $this->input->post('select_search', TRUE));
        }
        return $this->db->get($this->table['ec'])->result();
    }

    public function get_by_id($table, $where, $join = false)
    {
        if ($join) {
            if ($table == "ex") {
                $this->db->join('expense_categories', 'expense_categories.expense_cat_id = expenses.expense_cat_id');
            } else {
                $this->db->join('expenses', 'expense_categories.expense_cat_id = expenses.expense_cat_id');
            }
        }
        return $this->db->get_where($this->table[$table], $where)->first_row();
    }

    public function insert($table, $data)
    {
        $this->db->insert($this->table[$table], $data);
    }

    public function update($table, $where, $data)
    {
        $this->db->update($this->table[$table], $data, $where);
    }

    public function remove($table, $where)
    {
        $this->db->delete($this->table[$table], $where);
    }

    public function count_amount_of_expenditures($type = 'day')
    {
        $this->db->select('SUM(amount) as amount')->from('payments');
        if ($type == 'day') {
            $this->db->where('DATE(payment_date)', date('Y-m-d'));
        } else if ($type == "last_day") {
            $this->db->where('DATE(payment_date) <', date('Y-m-d'));
            $this->db->where('DATE(payment_date) >=', date('Y-m-d', strtotime('-1 day')));
        } else if ($type == "last_week") {
            $this->db->where('DATE(payment_date) <', date('Y-m-d'));
            $this->db->where('DATE(payment_date) >=', date('Y-m-d', strtotime('-7 day')));
        }
        return $this->db->get()->row();
        // $this->db->last_query();
    }
}

/* End of file Expenses_model.php */
