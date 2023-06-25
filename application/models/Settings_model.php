<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {

    protected $table = 'settings';

    public function find($where){
        return $this->db->get_where($this->table, $where)->row();
    }

    public function update($option, $where){
        $this->db->set('option_value', $option)
                    ->where('option_name', $where)
                    ->update($this->table);
    }

}

/* End of file Settings_model.php */
