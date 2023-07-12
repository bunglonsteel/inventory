<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Supplier extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Supplier_model', 'supplier');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result    = $this->supplier->result_data();
            $temp_data = [];
            $start     = $_POST['start'];

            foreach ($result as $res) {
                $row = [];
                $row[] = ++$start . ".";
                $row[] = htmlspecialchars($res->supplier_name);
                $row[] = htmlspecialchars($res->company);
                $row[] = $res->contact ? htmlspecialchars($res->contact) : '-';
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->supplier_id . '">
                                <i class="tf-icons bx bx-edit-alt"></i>Ubah
                            </button>
                            <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->supplier_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $temp_data[] = $row;
            }

            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->supplier->count_all_result(),
                "recordsFiltered" => $this->supplier->count_filtered(),
                "data"            => $temp_data,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Supplier';
        render_template_admin('admin/supplier', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();

            $payload = [
                "id"       => strip_tags(htmlspecialchars($this->input->post('target', TRUE) ?? '')),
                "supplier" => strip_tags(htmlspecialchars($this->input->post('supplier', TRUE) ?? '')),
                "company"  => strip_tags(htmlspecialchars($this->input->post('company', TRUE) ?? '')),
                "contact"  => strip_tags(htmlspecialchars($this->input->post('contact', TRUE) ?? '')),
                "address"  => strip_tags(htmlspecialchars($this->input->post('address', TRUE) ?? '')),
            ];

            if ($type == "add") {
                $message = $this->_add_supplier($payload);
            }

            if ($type == "edit") {
                $message = $this->_update_supplier($payload);
            }

            if ($type == "delete") {
                $message = $this->_remove_supplier($payload['id']);
            }

            echo json_encode($message);
        }
    }

    private function _add_supplier($payload)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $data = [
                'supplier_name' => $payload['supplier'],
                'company'       => $payload['company'],
                'contact'       => $payload['contact'],
                'address'       => $payload['address'],
            ];

            $this->supplier->insert($data);
            return [
                'success' => 'true',
                'message' => 'Supplier berhasil ditambahkan, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _update_supplier($payload)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors'    => 'true',
                'message'   => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check = $this->supplier->get_supplier_id($payload['id']);
            if (!$check) {
                return [
                    'error'     => 'true',
                    'message'   => 'Supplier tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $data = [
                    'supplier_name' => $payload['supplier'],
                    'company'       => $payload['company'],
                    'contact'       => $payload['contact'],
                    'address'       => $payload['address'],
                ];

                $this->supplier->update($payload['id'], $data);
                return [
                    'success'   => 'true',
                    'message'   => 'Supplier berhasil diperbarui, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _remove_supplier($id)
    {
        $check = $this->supplier->get_supplier_id($id);
        if (!$check) {
            return [
                'error'     => 'true',
                'message'   => 'Supplier tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check_products = $this->supplier->get_products_by_supplier($id);
            if ($check_products) {
                return [
                    'error'     => 'true',
                    'message'   => 'Supplier tidak bisa dihapus karena ada produk yang menggunakan supplier ini.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $this->supplier->delete($id);
                return [
                    'success'   => 'true',
                    'message'   => 'Supplier berhasil dihapus, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _rules()
    {
        $this->form_validation->set_rules(
            'supplier',
            'Supplier',
            'trim|required'
        );
        $this->form_validation->set_rules(
            'company',
            'Tipe Perusahaan',
            'trim|required'
        );
        $this->form_validation->set_rules(
            'contact',
            'Kontak',
            'trim|numeric'
        );
        $this->form_validation->set_rules(
            'address',
            'Alamat',
            'trim'
        );
    }

    public function get_supplier(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id ?? ''));
            $result = $this->supplier->get_supplier_id($target);
            if (!$result) {
                $message = [
                    'errors'    => 'true',
                    'message'   => 'Data tidak ditemukan',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $message = [
                    'success'   => 'true',
                    'message'   => 'Data berhasil ditemukan',
                    'data'      => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($message);
        }
    }

    public function select_supplier()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->supplier->get();
            if (!$result) {
                $message = [
                    'error'     => 'true',
                    'message'   => 'Data tidak ditemukan / kosong.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $message = [
                    'success'   => 'true',
                    'message'   => 'Data berhasil ditemukan',
                    'data'      => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($message);
        }
    }
}

/* End of file Supplier.php */
