<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Sales_model', 'sales');
        $this->load->model('Products_model', 'products');
    }

    public function index()
    {
        $data['amount_now']        = $this->sales->count_amount_sales('day');
        $data['amount_last_day']   = $this->sales->count_amount_sales('last_day');
        $data['amount_month']      = $this->sales->count_amount_sales('month');
        $data['amount_last_month'] = $this->sales->count_amount_sales('last_month');
        $data['stock_limit']       = $this->products->count_stock_limit();
        $data['top_product_sold']  = $this->products->count_product_sold();
        $data['sales']             = $this->sales->count_sales();
        // var_dump($data['sales']);
        // die;
        $data['title']   = 'Dashboard';
        render_template_admin('admin/dashboard', $data);
    }
}

/* End of file Dashboard.php */
