<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends CI_Model
{

    private $table   = 'supplier';
    private $columns = ['supplier_id', 'supplier_name', 'company', 'contact', 'address'];
    private $order   = ['supplier_id', 'supplier_name', 'company', 'contact'];

    private function _get_query()
    {
        $this->db->from($this->table);
        if (strip_tags(htmlspecialchars($_POST['search']['value']))) {
            $this->db->like($this->columns[1], strip_tags(htmlspecialchars($_POST['search']['value'])));
            $this->db->or_like($this->columns[2], strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        if ($_POST['order'][0]['column']) {
            $this->db->order_by($this->order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by($this->order[0], 'DESC');
        }
    }

    public function result_data()
    {
        $this->_get_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_result()
    {
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function get()
    {
        $this->db->select('supplier_id as id, supplier_name as name');
        if ($this->input->post('select_search', TRUE)) {
            $this->db->like($this->columns[1], $this->input->post('select_search', TRUE));
        }
        return $this->db->get($this->table)->result();
    }

    public function get_supplier_id($id)
    {
        return $this->db->get_where($this->table, [$this->columns[0] => $id])->row();
    }

    public function get_products_by_supplier($id)
    {
        return $this->db->get_where('products', [$this->columns[0] => $id])->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->update($this->table, $data, [$this->columns[0] => $id]);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, [$this->columns[0] => $id]);
    }
}

/* End of file Supplier_model.php */
