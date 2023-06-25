<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Products_model extends CI_Model
{

    private $table = 'products';
    private $columns = ['product_id', 'slug', 'sku', 'categories_id', 'unit_id', 'user_id', 'is_active'];
    private $order = ['product_name', 'categories_name', 'unit_type', 'purchase_price', 'selling_price', 'current_stock'];

    private function join_table()
    {
        $this->db->select('
            p.product_id, 
            p.slug, 
            p.sku, 
            p.barcode, 
            p.is_active,
            pd.product_details_id,
            pd.product_name,
            pd.product_desc,
            pd.product_image,
            pd.product_weight,
            pd.current_stock,
            pd.selling_price,
            pd.purchase_price,
            pd.storage_type,
            pd.storage_period,
            pd.storage_conditions,
            pc.categories_id,
            pc.categories_name, 
            pu.unit_id, 
            pu.unit_name,
            pu.unit_type,
            sup.supplier_id,
            sup.supplier_name
            ')
            ->from($this->table . ' as p')
            ->join('product_categories as pc', 'p.categories_id = pc.categories_id')
            ->join('product_details as pd', 'p.product_id = pd.product_id')
            ->join('product_units as pu', 'p.unit_id = pu.unit_id')
            ->join('supplier as sup', 'p.supplier_id = sup.supplier_id');
    }

    private function _get_query()
    {
        $this->join_table();
        if (strip_tags(htmlspecialchars($_POST['search']['value']))) {
            $this->db->like('pd.' . $this->order[0], strip_tags(htmlspecialchars($_POST['search']['value'])));
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

    public function count_all_result_value()
    {
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function get_product_id($id)
    {
        $this->join_table();
        return $this->db->where('p.' . $this->columns[0], $id)
            ->get()
            ->row();
    }

    public function get_products($limit, $offset, $search, $count)
    {
        $this->db->select('
            p.product_id as id,
            pd.product_name as item,
            pd.product_image as image,
            pd.current_stock as stock,
            pd.selling_price as price,
            ')
            ->from('products as p')
            ->join('product_details as pd', 'p.product_id = pd.product_id')
            ->join('product_categories as pc', 'p.categories_id = pc.categories_id');
        if ($search) {
            $keyword = $search['keyword'];
            $categories = $search['categories'];
            if ($keyword) {
                $this->db->where("pd.product_name LIKE '%$keyword%'");
            }

            if ($categories) {
                $this->db->where("pc.categories_id", $categories);
            }
        }
        if ($count) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $offset);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->result();
            }
        }

        return array();
    }

    public function get()
    {
        $this->db->select('
            p.product_id as id,
            pd.product_name as name,
            ')
            ->from('products as p')
            ->join('product_details as pd', 'p.product_id = pd.product_id');

        if ($this->input->post('select_search', TRUE)) {
            $this->db->like('pd.product_name', $this->input->post('select_search', TRUE));
        }
        return $this->db->get()->result();
    }

    public function insert_data($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function update_data($table, $where, $data)
    {
        $this->db->update($table, $data, $where);
    }

    public function remove_data($table, $where)
    {
        $this->db->delete($table, $where);
    }

    public function count_stock_limit()
    {
        return $this->db->select('SUM(if(current_stock > 0 AND current_stock < 3, 1, 0)) as stock_limit, SUM(if(current_stock <= 0, 1, 0)) as out_of_stock')
            ->from('product_details')
            ->where('current_stock <', 3)
            ->get()
            ->row();
    }

    public function count_product_sold()
    {
        return $this->db->select('product_name as name, product_image as image, SUM(qty) as soldout')
            ->from('product_details')
            ->join('order_items as oi', 'product_details.product_id = oi.product_id')
            ->join('orders as od', 'oi.order_id = od.order_id')
            ->where('month(oi.created_at)', date('m'))
            ->where('year(oi.created_at)', date('Y'))
            ->where('payment_status', 'PAID')
            ->group_by('product_name')
            ->order_by('qty', "DESC")
            ->limit(4)
            ->get()
            ->result();
    }
}
