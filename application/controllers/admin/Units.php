<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Units extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Units_model', 'units');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->units->result_data();
            $temp_data = [];
            $start = $_POST['start'];

            foreach ($result as $res) {
                $row = [];
                $row[] = ++$start . ".";
                $row[] = htmlspecialchars($res->unit_name);
                $row[] = htmlspecialchars($res->unit_type);
                $row[] = $res->is_delete == 1 ?
                    '<div class="d-flex">
                    <button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->unit_id . '">
                        <i class="tf-icons bx bx-edit-alt"></i>Ubah
                    </button>
                    <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->unit_id . '">
                        <i class="tf-icons bx bx-trash"></i>Hapus
                    </button>
                </div>'
                    : '<button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->unit_id . '">
                    <i class="tf-icons bx bx-edit-alt"></i>Ubah
                </button>';
                $temp_data[] = $row;
            }

            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->units->count_all_result_value(),
                "recordsFiltered" => $this->units->count_filtered(),
                "data" => $temp_data,
                "csrf_hash" => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Satuan';
        render_template_admin('admin/products/units', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();

            $unit_id   = strip_tags(htmlspecialchars($this->input->post('target', TRUE) ?? ''));
            $unit_name = strip_tags(htmlspecialchars($this->input->post('unit_name', TRUE) ?? ''));
            $unit_type = strip_tags(htmlspecialchars($this->input->post('unit_type', TRUE) ?? ''));

            if ($type == "add") {
                $message = $this->_add_unit($unit_name, $unit_type);
            }

            if ($type == "edit") {
                $message = $this->_update_unit($unit_id, $unit_name, $unit_type);
            }

            if ($type == "delete") {
                $message = $this->_remove_unit($unit_id);
            }

            echo json_encode($message);
        }
    }

    private function _add_unit($unit_name, $unit_type)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $data = [
                'unit_name' => $unit_name,
                'unit_type' => $unit_type,
            ];

            $this->units->insert_data($data);
            return [
                'success' => 'true',
                'message' => 'Satuan berhasil ditambahkan, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
    }

    private function _update_unit($unit_id, $unit_name, $unit_type)
    {
        if ($this->form_validation->run() == false) {
            return [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check = $this->units->get_unit_id($unit_id);
            if (!$check) {
                return [
                    'error' => 'true',
                    'message' => 'Satuan yang anda ingin rubah tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $data = [
                    'unit_name' => $unit_name,
                    'unit_type' => $unit_type,
                ];

                $this->units->update_data($unit_id, $data);
                return [
                    'success' => 'true',
                    'message' => 'Satuan berhasil diperbarui, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _remove_unit($unit_id)
    {
        $check = $this->units->get_unit_id($unit_id);
        if (!$check) {
            return [
                'error' => 'true',
                'message' => 'Satuan yang ingin dihapus tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {

            if ($check->is_delete == 0) {
                return [
                    'error' => 'true',
                    'message' => 'Satuan ini tidak bisa dihapus, mohon maaf!.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $this->units->remove_data($unit_id);
                return [
                    'success' => 'true',
                    'message' => 'Satuan berhasil dihapus, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
    }

    private function _rules()
    {
        $this->form_validation->set_rules(
            'unit_name',
            'Nama Satuan',
            'trim|required|is_unique[product_units.unit_name]',
            ['is_unique' => '%s ini sudah ada silahkan dicek.']
        );
        $this->form_validation->set_rules(
            'unit_type',
            'Unit Tipe',
            'trim|required|is_unique[product_units.unit_type]',
            ['is_unique' => '%s ini sudah ada silahkan dicek.']
        );
    }

    public function get_units(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id ?? ''));
            $result = $this->units->get_unit_id($target);
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

    public function select_units()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->units->get();
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

/* End of file Units.php */
