<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Products_model', 'products');
        $this->load->model('Categories_model', 'categories');
        $this->load->model('Units_model', 'units');
    }


    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->products->result_data();
            $temp_data = [];
            // var_dump($result);die;
            foreach ($result as $res) {
                $row = [];
                $row[] = '  <div class="d-flex align-items-center flex-wrap flex-lg-nowrap">
                                <img class="w-100 h-100 me-2 mb-2 mb-sm-0 rounded" src="' . base_url('public/image/products/') . $res->product_image . '" style="max-width:80px;max-height: 50px;object-fit: cover;"></img>
                                <span>' . htmlspecialchars($res->product_name) . '</span>
                            </div>';
                $row[] = htmlspecialchars($res->categories_name);
                $row[] = htmlspecialchars($res->unit_type);
                $row[] = "Rp." . htmlspecialchars(number_format($res->purchase_price, 0, ',', '.'));
                $row[] = "Rp." . htmlspecialchars(number_format($res->selling_price, 0, ',', '.'));
                $row[] = $res->current_stock <= 0 ? '<span class="badge bg-danger" style="width:33px">' . htmlspecialchars($res->current_stock) . '</span>' : '<span class="badge bg-info" style="width:33px">' . htmlspecialchars($res->current_stock) . '</span>';
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn preview btn-icon btn-sm btn-primary me-2" data-id="' . $res->product_id . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                <i class="bx bx-detail"></i>
                            </button>
                            <button type="button" class="d-flex btn edit btn-icon btn-sm btn-secondary me-2" data-id="' . $res->product_id . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="tf-icons bx bx-edit-alt"></i>
                            </button>
                            <button type="button" class="d-flex btn delete btn-icon btn-sm btn-danger" data-id="' . $res->product_id . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                <i class="tf-icons bx bx-trash"></i>
                            </button>
                        </div>';
                $temp_data[] = $row;
            }
            // var_dump($temp_data);die;
            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->products->count_all_result_value(),
                "recordsFiltered" => $this->products->count_filtered(),
                "data"            => $temp_data,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        // echo $this->products->table;
        $data['title'] = 'Semua Produk';
        render_template_admin('admin/products/product', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();
            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '500';
            $config['upload_path']      = './public/image/products/';
            $config['file_ext_tolower'] = TRUE;
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);
            // Slug handle
            $slug        = strip_tags(htmlspecialchars($this->input->post('slug', TRUE) ?? ''));
            $result_slug = url_title($slug, 'dash', true);

            $data = [
                "image"              => isset($_FILES['image']['name']) ? $_FILES['image']['name'] : null,
                "target"             => strip_tags(htmlspecialchars($this->input->post('target', TRUE) ?? '')),
                "name"               => strip_tags(htmlspecialchars($this->input->post('name', TRUE) ?? '')),
                'slug'               => $result_slug,
                "sku"                => strip_tags(htmlspecialchars($this->input->post('sku', TRUE) ?? '')),
                "barcode"            => strip_tags(htmlspecialchars($this->input->post('barcode', TRUE) ?? '')),
                "supplier"           => strip_tags(htmlspecialchars($this->input->post('supplier', TRUE) ?? '')),
                "category"           => strip_tags(htmlspecialchars($this->input->post('category', TRUE) ?? '')),
                "unit"               => strip_tags(htmlspecialchars($this->input->post('unit', TRUE) ?? '')),
                "stock"              => strip_tags(htmlspecialchars($this->input->post('stock', TRUE) ?? '')),
                "purchase"           => strip_tags(htmlspecialchars($this->input->post('purchase', TRUE) ?? '')),
                "selling"            => strip_tags(htmlspecialchars($this->input->post('selling', TRUE) ?? '')),
                "product_weight"     => strip_tags(htmlspecialchars($this->input->post('product_weight', TRUE) ?? '')),
                "storage_type"       => strip_tags(htmlspecialchars($this->input->post('storage_type', TRUE) ?? '')),
                "storage_period"     => strip_tags(htmlspecialchars($this->input->post('storage_period', TRUE) ?? '')),
                "storage_conditions" => strip_tags(htmlspecialchars($this->input->post('storage_conditions', TRUE) ?? '')),
                "desc"               => strip_tags(htmlspecialchars($this->input->post('description', TRUE) ?? '')),
            ];
            if ($type == "add") {
                $message = $this->_add_product($data);
            }

            if ($type == "edit") {
                $message = $this->_update_product($data);
            }

            if ($type == "delete") {
                $message = $this->_remove_product($data['target']);
            }

            echo json_encode($message);
        }
    }

    private function _add_product($data)
    {

        $this->form_validation->set_rules('slug', 'Slug (url)', 'trim|required|is_unique[products.slug]');
        if ($this->form_validation->run() == FALSE) {
            $message =  [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            // field products
            $last_product = $this->db->query("SHOW TABLE STATUS WHERE name='products'")->row_array();
            $product = [
                'slug'          => $data['slug'],
                'sku'           => $data['sku'],
                'barcode'       => $data['barcode'],
                'supplier_id'   => $data['supplier'],
                'categories_id' => $data['category'],
                'unit_id'       => $data['unit']
            ];
            $product_detail = [
                'product_id'         => $last_product['Auto_increment'],
                'product_name'       => $data['name'],
                'product_desc'       => $data['desc'],
                'product_image'      => 'default.jpg',
                'product_weight'     => $data['product_weight'],
                'current_stock'      => $data['stock'],
                'purchase_price'     => $data['purchase'],
                'selling_price'      => $data['selling'],
                'storage_type'       => $data['storage_type'],
                'storage_period'     => $data['storage_period'],
                'storage_conditions' => $data['storage_conditions'],
            ];

            if ($data['image']) {
                if ($this->upload->do_upload('image')) {
                    $new_image                       = $this->upload->data('file_name');
                    $product_detail['product_image'] = $new_image;

                    $this->products->insert_data('products', $product);
                    $this->products->insert_data('product_details', $product_detail);
                    $message =  [
                        'success'   => 'true',
                        'message'   => 'Produk berhasil ditambahkan, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $message =  [
                        'error'     => 'true',
                        'message'   => $this->upload->display_errors('', ''),
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $this->products->insert_data('products', $product);
                $this->products->insert_data('product_details', $product_detail);
                $message =  [
                    'success'   => 'true',
                    'message'   => 'Produk berhasil ditambahkan, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        return $message;
    }

    private function _update_product($data)
    {
        if ($this->form_validation->run() == FALSE) {
            $message =  [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            // field products
            $product = [
                'slug'          => $data['slug'],
                'sku'           => $data['sku'],
                'supplier_id'   => $data['supplier'],
                'categories_id' => $data['category'],
                'unit_id'       => $data['unit'],
            ];
            $product_detail = [
                'product_name'       => $data['name'],
                'product_desc'       => $data['desc'],
                'product_desc'       => $data['desc'],
                'current_stock'      => $data['stock'],
                'purchase_price'     => $data['purchase'],
                'selling_price'      => $data['selling'],
                'storage_type'       => $data['storage_type'],
                'storage_period'     => $data['storage_period'],
                'storage_conditions' => $data['storage_conditions'],
            ];

            $check = $this->products->get_product_id($data['target']);

            if ($data['slug'] != $check->slug) {
                $check_slug = $this->db->get_where('products', ['slug' => $data['slug']])->row_array();
                if ($check_slug) {
                    $product['slug'] = $data['slug'] . rand(0, 10000);
                }
            }

            if ($check) {
                if ($data['image']) {
                    if ($this->upload->do_upload('image')) {
                        if ($check->product_image != 'default.jpg') {
                            unlink(FCPATH . 'public/image/products/' . $check->product_image);
                        }

                        $new_image = $this->upload->data('file_name');
                        $product_detail['product_image'] = $new_image;

                        $this->products->update_data('products', ['product_id' => $check->product_id], $product);
                        $this->products->update_data('product_details', ['product_details_id' => $check->product_details_id], $product_detail);
                        $message =  [
                            'success'   => 'true',
                            'message'   => 'Produk berhasil diperbarui, terimakasih.',
                            'csrf_hash' => $this->security->get_csrf_hash(),
                        ];
                    } else {
                        $message =  [
                            'error'     => 'true',
                            'message'   => $this->upload->display_errors('', ''),
                            'csrf_hash' => $this->security->get_csrf_hash(),
                        ];
                    }
                } else {
                    $this->products->update_data('products', ['product_id' => $check->product_id], $product);
                    $this->products->update_data('product_details', ['product_details_id' => $check->product_details_id], $product_detail);
                    $message =  [
                        'success'   => 'true',
                        'message'   => 'Produk berhasil diperbarui, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $message =  [
                    'error'     => 'true',
                    'message'   => 'Gagal produk tidak ada / tidak bisa dirubah.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        return $message;
    }

    private function _remove_product($id)
    {
        $check = $this->products->get_product_id($id);
        if (!$check) {
            return [
                'error'     => 'true',
                'message'   => 'Product yang ingin dihapus tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($check->product_image != 'default.jpg') {
                unlink(FCPATH . 'public/image/products/' . $check->product_image);
            }
            $this->products->remove_data('products', ['product_id' => $check->product_id]);
            $this->products->remove_data('product_details', ['product_details_id' => $check->product_details_id]);
            return [
                'success'   => 'true',
                'message'   => 'Produk berhasil dihapus, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _rules()
    {
        $this->form_validation
            ->set_rules('name', 'Nama produk', 'trim|required')
            ->set_rules('sku', 'No. SKU', 'trim|required')
            ->set_rules('barcode', 'Barcode', 'trim|required')
            ->set_rules('supplier', 'Supplier', 'trim|required|callback_handle_select')
            ->set_rules('category', 'Kategori', 'trim|required|callback_handle_select')
            ->set_rules('unit', 'Unit', 'trim|required|callback_handle_select')
            ->set_rules('stock', 'Stok awal', 'trim|required')
            ->set_rules('purchase', 'Harga beli', 'trim|required')
            ->set_rules('product_weight', 'Berat produk', 'trim')
            ->set_rules('storage_type', 'Jenis Penyimpanan', 'trim')
            ->set_rules('storage_period', 'Masa Penyimpanan', 'trim')
            ->set_rules('storage_conditions', 'Kondisi Penyimpanan', 'trim')
            ->set_rules('description', 'Deskripsi produk', 'trim|required');
    }

    function handle_select($str)
    {
        if ($str == 0) {
            $this->form_validation->set_message('handle_select', '{field} tidak boleh kosong.');
            return FALSE;
        }
        return TRUE;
    }

    public function get_product(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id ?? ''));
            $result = $this->products->get_product_id($target);

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

    public function select_product()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->products->get();
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

/* End of file Products.php */
