<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Stock_model', 'stock');
    }


    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->stock->result_data();
            // var_dump($result);die;
            $temp_data = [];

            foreach ($result as $res) {
                $type = ($res->stock_type == "in") ? '<span class="text-green fw-bold">+' . $res->qty . '</span>' : '<span class="text-danger fw-bold">-' . $res->qty . '</span>';
                $row = [];
                $row[] = '  <div class="d-flex align-items-center flex-wrap flex-lg-nowrap">
                                <img class="w-100 h-100 me-2 mb-2 mb-sm-0 rounded" src="' . base_url('public/image/products/') . $res->product_image . '" style="max-width:80px;max-height: 50px;object-fit: cover;"></img>
                                <span>' . htmlspecialchars($res->product_name) . '</span>
                            </div>';
                $row[] = $type;
                $row[] = date('d M Y - H:i', strtotime($res->created_at));
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->stock_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $temp_data[] = $row;
            }
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->stock->count_all_result_value(),
                "recordsFiltered" => $this->stock->count_filtered(),
                "data" => $temp_data,
                "csrf_hash" => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = "Stock";
        render_template_admin('admin/products/stock', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();

            $payload = [
                'target_id'     => strip_tags(htmlentities($this->input->post('target', TRUE))),
                'product_id'    => strip_tags(htmlentities($this->input->post('product', TRUE))),
                'qty'           => strip_tags(htmlentities($this->input->post('qty', TRUE))),
                'stock_type'    => strip_tags(htmlentities($this->input->post('type', TRUE))),
                'stock_notes'   => strip_tags(htmlentities($this->input->post('notes', TRUE))),
            ];

            if ($type == "add") {
                $message = $this->_add_stock($payload);
            }

            if ($type == "delete") {
                $message = $this->_remove_stock($payload['target_id']);
            }

            echo json_encode($message);
        }
    }

    private function _add_stock($payload)
    {

        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $data = [
                'product_id'    => $payload['product_id'],
                'qty'           => $payload['qty'],
                'stock_type'    => $payload['stock_type'],
                'order_type'    => 'adjustment',
                'stock_notes'   => $payload['stock_notes'],
                'created_at'    => date('Y-m-d H:i:s'),
            ];

            $this->stock->insert($data);
            return [
                'success' => 'true',
                'message' => 'Stock berhasil ditambahkan, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _remove_stock($stock_id)
    {
        $check = $this->stock->get_stock_id(['stock_id' => $stock_id]);
        if (!$check) {
            return [
                'error' => 'true',
                'message' => 'Stock yang ingin dihapus tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $this->stock->remove($check);
            return [
                'success' => 'true',
                'message' => 'Stock berhasil dihapus, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _rules()
    {
        $this->form_validation->set_rules(
            'product',
            'Produk',
            'trim|required',
            ['required' => '%s tidak boleh kosong.']
        );
        $this->form_validation->set_rules(
            'qty',
            'Quantity',
            'trim|required',
            ['required' => '%s tidak boleh kosong.']
        );
        $this->form_validation->set_rules(
            'type',
            'Type',
            'trim|required',
            ['required' => '%s tidak boleh kosong.']
        );
        $this->form_validation->set_rules(
            'notes',
            'Catatan',
            'trim|max_length[255]',
            ['max_length' => '%s tidak boleh lebih dari {param} huruf.']
        );
    }
}

/* End of file Stock.php */
