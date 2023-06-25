<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Pages extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Guest_model', 'guest');
    }

    public function index()
    {
        $data['socmed']     = json_decode($this->db->get_where("settings", ['option_name' => "socmed"])->row()->option_value);
        $data['categories'] = $this->guest->get_categories();
        // var_dump($data['socmed']);
        // die;
        $data['banners']    = $this->guest->get_banner();
        $data['title']      = 'Monitoring';
        render_template('pages/home', $data);
    }

    public function product_details(String $slug)
    {
        $check = $this->guest->get_product_by_slug(['p.slug' => strip_tags(htmlspecialchars($slug))]);
        if (!$check) {
            show_404();
        } else {
            $this->load->model('Settings_model', 'settings');
            $data['ecommerce']     = json_decode($this->settings->find(['option_name' => "ecommerce"])->option_value);
            // var_dump($data['ecommerce']);
            // die;
            $data['title']         = $check->name;
            $data['product']       = $check;
            $data['more_products'] = $this->guest->get_more_products();
            render_template('pages/product_details', $data);
        }
    }

    public function product()
    {
        if ($this->input->is_ajax_request()) {
            $search = [
                "search"     => trim(htmlspecialchars($this->input->post('search', TRUE))),
                "categories" => trim(htmlspecialchars($this->input->post('categories', TRUE))),
                "price"      => trim(htmlspecialchars($this->input->post('price', TRUE)))
            ];

            $this->load->library('pagination');

            $limit = 12;
            $offset = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

            $config['base_url']    = site_url('pages/product');
            $config['total_rows']  = $this->guest->get_products($limit, $offset, $search, $count = true);
            $config['per_page']    = $limit;
            $config['uri_segment'] = 3;

            $config['full_tag_open']    = '<nav class="mt-3" aria-label="Page navigation"><ul class="pagination flex flex-wrap items-center text-gray-400">';
            $config['full_tag_close']   = '</ul></nav>';

            $config['num_tag_open']     = '<li class="px-3 py-1 border bg-white mx-1 rounded-md">';
            $config['num_tag_close']    = '</li>';
            $config['cur_tag_open']     = '<li class="px-3 py-1 shadow-md bg-rose-500 text-white rounded-md">';
            $config['cur_tag_close']    = '</li>';
            $config['next_tag_open']    = '<li class="px-3 py-1 next">';
            $config['next_tag_close']   = '</li>';
            $config['prev_tag_open']    = '<li class="px-3 py-1 prev">';
            $config['prev_tag_close']   = '</li>';
            $config['first_tag_open']   = '<li class="px-3 py-1 first">';
            $config['first_tag_close']  = '</li>';
            $config['last_tag_open']    = '<li class="px-3 py-1 last">';
            $config['last_tag_close']   = '</li>';

            $config['next_link']        = '<i class="tf-icon bx bx-chevrons-right"></i>';
            $config['prev_link']        = '<i class="tf-icon bx bx-chevrons-left"></i>';
            $config['first_link']       = '<i class="tf-icon bx bx-chevron-left"></i>';
            $config['last_link']        = '<i class="tf-icon bx bx-chevron-right"></i>';

            $this->pagination->initialize($config);
            $data['products'] = $this->guest->get_products($limit, $offset, $search, $count = false);
            $data['paginate'] = $this->pagination->create_links();

            $result = [
                'status' => 'success',
                'data' => [
                    'products'   => $data['products'],
                    'pagination' => $data['paginate'],
                ],
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
        $data['categories'] = $this->guest->get_categories();
        $data['title']      = 'Monitoring';
        render_template('pages/products', $data);
    }

    public function about()
    {
        $data['title'] = 'Tentang kami';
        render_template('pages/about', $data);
    }
}

/* End of file Home.php */
