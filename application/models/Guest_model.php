<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Guest_model extends CI_Model {

    private function join_products() {
        $this->db->select('
            p.product_id as id,
            p.slug as slug,
            pd.product_name as name,
            pd.product_image as image,
            pd.selling_price as price,
            pc.slug as slug_categories,
            ')
                ->from('products as p')
                ->join('product_details as pd', 'p.product_id = pd.product_id')
                ->join('product_categories as pc', 'p.categories_id = pc.categories_id');
    }

    public function get_products($limit, $offset, $search, $count)
	{
		$this->join_products();
        if($search){
            $keyword = $search['search'];
			$categories = $search['categories'];
			$price = $search['price'];

            if ($keyword) {
                $this->db->where("pd.product_name LIKE '%$keyword%'");
            }
            if ($categories) {
                $this->db->where("pc.slug", $categories);
            }
            if ($price) {
                $max_min = $price == "max" ? "ASC" : "DESC";
                $this->db->order_by("pd.selling_price", $max_min);
            }
        }
		if($count){
			return $this->db->count_all_results();
		}
		else {
			$this->db->limit($limit, $offset);
			$query = $this->db->get();
			if($query->num_rows() > 0) {
				return $query->result();
			}
		}
		return array();
	}

    public function get_more_products(){
        $this->join_products();
        return $this->db->limit(5)
                    ->order_by('product_id','RANDOM')
                    ->get()
                    ->result();
        }

    public function get_categories(){
        return $this->db->select('
            pc.categories_id as id,
            pc.slug,
            pc.categories_name as name,
            ')
                ->from('product_categories as pc')
                ->get()
                ->result();
    }

    public function get_product_by_slug($where){
        return $this->db->select('
            p.product_id as id, 
            p.slug, 
            p.sku,
            p.is_active as active,
            pd.product_name as name,
            pd.product_desc as description,
            pd.product_image as image,
            pd.product_weight as weight,
            pd.current_stock as stock,
            pd.selling_price as price,
            pd.storage_type as type,
            pd.storage_period as period,
            pd.storage_conditions as conditions,
            pc.categories_name as category,
            pu.unit_name as unit,
            pu.unit_type
            ')
                ->from('products'. ' as p')
                ->join('product_categories as pc', 'p.categories_id = pc.categories_id')
                ->join('product_details as pd', 'p.product_id = pd.product_id')
                ->join('product_units as pu', 'p.unit_id = pu.unit_id')
                ->where($where)
                ->get()
                ->row();
    }

    public function get_banner(){
        return $this->db->select('banner_name as name, banner_image as banner')
                        ->from('banner')
                        ->where('is_active', 1)
                        ->order_by('banner_id', 'DESC')
                        ->get()
                        ->result();
    }

}

/* End of file Guest_model.php */
