<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Categories extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Categories_model', 'categories');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result    = $this->categories->result_data();
            $temp_data = [];
            $start     = $_POST['start'];

            foreach ($result as $res) {
                $row = [];
                $row[] = ++$start . ".";
                $row[] = htmlspecialchars($res->categories_name);
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->categories_id . '">
                                <i class="tf-icons bx bx-edit-alt"></i>Ubah
                            </button>
                            <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->categories_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $temp_data[] = $row;
            }

            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->categories->count_all_result_value(),
                "recordsFiltered" => $this->categories->count_filtered(),
                "data"            => $temp_data,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Kategori';
        render_template_admin('admin/products/categories', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();

            $cat_id = strip_tags(htmlspecialchars($this->input->post('target', TRUE) ?? ''));
            $cat_name = strip_tags(htmlspecialchars($this->input->post('category_name', TRUE) ?? ''));
            // Slug handle
            $slug = strip_tags(htmlspecialchars($this->input->post('slug', TRUE) ?? ''));
            $cat_slug = url_title($slug, 'dash', true);

            if ($type == "add") {
                $message = $this->_add_categories($cat_name, $cat_slug);
            }

            if ($type == "edit") {
                $message = $this->_update_categories($cat_id, $cat_name, $cat_slug);
            }

            if ($type == "delete") {
                $message = $this->_remove_categories($cat_id);
            }

            echo json_encode($message);
        }
    }

    private function _add_categories($cat_name, $cat_slug)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $data = [
                'categories_name' => $cat_name,
                'slug' => $cat_slug,
            ];

            $this->categories->insert_data($data);
            return [
                'success' => 'true',
                'message' => 'Kategori berhasil ditambahkan, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _update_categories($cat_id, $cat_name, $cat_slug)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check = $this->categories->get_category_id($cat_id);
            if (!$check) {
                return [
                    'error' => 'true',
                    'message' => 'Kategori yang anda ingin rubah tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $data = [
                    'categories_name' => $cat_name,
                    'slug' => $cat_slug,
                ];

                $this->categories->update_data($cat_id, $data);
                return [
                    'success' => 'true',
                    'message' => 'Kategori berhasil diperbarui, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _remove_categories($cat_id)
    {
        $check = $this->categories->get_category_id($cat_id);
        if (!$check) {
            return [
                'error' => 'true',
                'message' => 'Kategori yang ingin dihapus tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check_products = $this->categories->get_products_by_category($cat_id);
            if ($check_products) {
                return [
                    'error' => 'true',
                    'message' => 'Kategori tidak bisa dihapus karena ada produk yang menggunakan kategori ini.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $this->categories->remove_data($cat_id);
                return [
                    'success' => 'true',
                    'message' => 'Kategori berhasil dihapus, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _rules()
    {
        $this->form_validation->set_rules(
            'category_name',
            'Nama Kategori',
            'trim|required|is_unique[product_categories.categories_name]',
            ['is_unique' => '%s ini sudah ada silahkan dicek.']
        );
        $this->form_validation->set_rules(
            'slug',
            'Slug',
            'trim|required|is_unique[product_categories.slug]',
            ['is_unique' => '%s ini sudah ada silahkan dicek.']
        );
    }

    public function get_categories(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id ?? ''));
            $result = $this->categories->get_category_id($target);
            if (!$result) {
                $message = [
                    'errors' => 'true',
                    'message' => 'Data tidak ditemukan',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $message = [
                    'success' => 'true',
                    'message' => 'Data berhasil ditemukan',
                    'data' => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($message);
        }
    }

    public function select_categories()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->categories->get();
            if (!$result) {
                $message = [
                    'errors' => 'true',
                    'message' => 'Data tidak ditemukan',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $message = [
                    'success' => 'true',
                    'message' => 'Data berhasil ditemukan',
                    'data' => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($message);
        }
    }
}
