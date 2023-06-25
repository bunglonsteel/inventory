<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Users_model extends CI_Model
{

    private $table   = 'users';
    private $columns = ['user_id', 'name', 'contact', 'user_type', 'is_login', 'is_active'];
    private $order   = ['name', 'user_type', 'contact', 'is_login', 'is_active'];

    private function _get_query()
    {
        $this->db->from($this->table);
        if (strip_tags(htmlspecialchars($_POST['search']['value']))) {
            $this->db->like('pd.' . $this->order[0], strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        $this->db->where('user_type !=', 'superadmin');

        if ($_POST['order'][0]['column']) {
            $this->db->order_by($this->order[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by('user_id', 'DESC');
        }
    }

    public function result()
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

    public function get($type = "all")
    {
        $this->db->select('TO_BASE64(uuid) as id, name')
            ->where('is_active', 1);
        if ($type != 'all') {
            $this->db->where('user_type', $type);
            $this->db->where('is_active', 1);
        }

        if ($this->input->post('select_search', TRUE)) {
            $this->db->like($this->columns[1], $this->input->post('select_search', TRUE));
        }
        return $this->db->get($this->table)->result();
    }

    public function find($array)
    {
        return $this->db->get_where($this->table, $array)->row();
    }

    public function findId($array)
    {
        $this->db->select('TO_BASE64(uuid) as id, name, email, contact, user_type as role, is_login as login, is_active as active');
        return $this->db->get_where($this->table, $array)->row();
    }

    public function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->update($this->table, $data, ['user_id' => $id]);
    }

    public function delete($id)
    {
        $this->db->delete($this->table, ['user_id' => $id]);
    }
}

/* End of file Users_model.php */
