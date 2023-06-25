<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Expenses extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Expenses_model', 'expenses');
    }


    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->expenses->result_data('ex');
            // var_dump($result);die;
            $temp_data = [];
            $start = $_POST['start'];
            foreach ($result as $res) {
                $row = [];
                $row[] = ++$start . ".";
                $row[] = htmlspecialchars($res->expense_cat_name);
                $row[] = htmlspecialchars(date('d M Y', strtotime($res->expense_date)));
                $row[] = "Rp. " . htmlspecialchars(number_format($res->expense_amount, 0, ',', '.'));
                $row[] = htmlspecialchars($res->expense_notes);
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn btn-sm btn-secondary me-2 edit" data-id="' . $res->expense_id . '">
                                <i class="tf-icons bx bx-edit-alt"></i>Ubah
                            </button>
                            <button type="button" class="d-flex btn btn-sm btn-danger delete" data-id="' . $res->expense_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $temp_data[] = $row;
            }
            // var_dump($temp_data);die;
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->expenses->count_all_result('ex'),
                "recordsFiltered" => $this->expenses->count_filtered('ex'),
                "data" => $temp_data,
                "csrf_hash" => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }

        $data['title'] = 'Pengeluaran';
        render_template_admin('admin/expenses/expenses', $data);
    }

    public function expense_categories()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->expenses->result_data('ec');
            $temp_data = [];
            $start = $_POST['start'];
            foreach ($result as $res) {
                $row = [];
                $row[] = ++$start . ".";
                $row[] = htmlspecialchars($res->expense_cat_name);
                $row[] = htmlspecialchars($res->expense_cat_desc);
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->expense_cat_id . '">
                                <i class="tf-icons bx bx-edit-alt"></i>Ubah
                            </button>
                            <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->expense_cat_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $temp_data[] = $row;
            }
            // var_dump($temp_data);die;
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->expenses->count_all_result('ec'),
                "recordsFiltered" => $this->expenses->count_filtered('ec'),
                "data" => $temp_data,
                "csrf_hash" => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }

        $data['title'] = 'Kategori Pengeluaran';
        render_template_admin('admin/expenses/expenses_category', $data);
    }

    public function action(String $action, String $type = "ex")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules($type);

            $payload = [
                "target"   => strip_tags(htmlspecialchars($this->input->post('target', TRUE))),
                "category" => strip_tags(htmlspecialchars($this->input->post('category', TRUE))),
                "desc"     => strip_tags(htmlspecialchars($this->input->post('desc', TRUE))),
            ];
            if ($type == "ex") {
                $payload["amount"] = strip_tags(htmlspecialchars($this->input->post('amount', TRUE)));
                $payload["date"]   = strip_tags(htmlspecialchars($this->input->post('date', TRUE)));
            }

            if ($action == "add") {
                $message = $this->_add($type, $payload);
            }

            if ($action == "edit") {
                $message = $this->_update($type, $payload);
            }

            if ($action == "delete") {
                $message = $this->_remove($type, $payload['target']);
            }

            echo json_encode($message);
        }
    }

    private function _add($type, $payload)
    {
        if ($this->form_validation->run() == false) {
            $message = [
                'errors' => 'true',
                'message' => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($type == "ex") {
                $title = "Pengeluaran";
                $data = [
                    'expense_cat_id' => $payload['category'],
                    'expense_date'   => $payload['date'],
                    'expense_amount' => $payload['amount'],
                    'expense_notes'  => $payload['desc'],
                ];
            } else {
                $title = "Kategori";
                $data = [
                    'expense_cat_name' => $payload['category'],
                    'expense_cat_desc' => $payload['desc'],
                ];
            }

            $this->expenses->insert($type, $data);
            $message = [
                'success' => 'true',
                'message' => $title . ' berhasil ditambahkan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }

        return $message;
    }

    private function _update($type, $payload)
    {
        if ($this->form_validation->run() == false) {
            $message = [
                'errors'    => 'true',
                'message'   => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($type == "ex") {
                $check = $this->expenses->get_by_id($type, ['expense_id' => $payload['target']], true);
            } else {
                $check = $this->expenses->get_by_id($type, ['expense_cat_id' => $payload['target']]);
            }

            if (!$check) {
                $message = [
                    'error'     => 'true',
                    'message'   => 'Data tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                if ($type == "ex") {
                    $title = "Pengeluaran";
                    $where = ['expense_id' => $check->expense_id];
                    $data  = [
                        'expense_cat_id' => $payload['category'],
                        'expense_date'   => $payload['date'],
                        'expense_amount' => $payload['amount'],
                        'expense_notes'  => $payload['desc'],
                    ];
                } else {
                    $title = "Kategori";
                    $where = ['expense_cat_id' => $check->expense_cat_id];
                    $data  = [
                        'expense_cat_name' => $payload['category'],
                        'expense_cat_desc' => $payload['desc'],
                    ];
                }
                $this->expenses->update($type, $where, $data);
                $message = [
                    'success'   => 'true',
                    'message'   => $title . ' berhasil diperbarui.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
        return $message;
    }

    private function _remove($type, $id)
    {
        if ($type == "ex") {
            $check = $this->expenses->get_by_id($type, ['expense_id' => $id], true);
        } else {
            $check = $this->expenses->get_by_id($type, ['expense_cat_id' => $id]);
        }

        if (!$check) {
            $message = [
                'error'     => 'true',
                'message'   => 'Data tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($type == "ex") {
                $title = "Pengeluaran";
                $where = ['expense_id' => $check->expense_id];
            } else {
                $check_expenses = $this->expenses->get_by_id($type, ['expenses.expense_cat_id' => $check->expense_cat_id], true);
                if ($check_expenses) {
                    return [
                        'error'     => 'true',
                        'message'   => 'Tidak bisa dihapus karena ada pengeluaran yang menggunakan kategori ini.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                    die;
                }
                $title = "Kategori";
                $where = ['expense_cat_id' => $check->expense_cat_id];
            }
            $this->expenses->remove($type, $where);
            $message = [
                'success'   => 'true',
                'message'   => $title . ' berhasil dihapus, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }

        return $message;
    }

    private function _rules($type)
    {
        if ($type == "ex") {
            $this->form_validation->set_rules(
                'category',
                'Pengeluaran',
                'trim|required'
            );
            $this->form_validation->set_rules(
                'amount',
                'Jumlah',
                'trim|required|numeric'
            );
            $this->form_validation->set_rules(
                'date',
                'Tanggal',
                'trim|required'
            );
            $this->form_validation->set_rules(
                'desc',
                'Catatan',
                'trim'
            );
        } else {
            $this->form_validation->set_rules(
                'category',
                'Pengeluaran',
                'trim|required|is_unique[expense_categories.expense_cat_id]'
            );
            $this->form_validation->set_rules(
                'desc',
                'Deskripsi',
                'trim'
            );
        }
    }

    public function get_id(String $id = '', String $type = 'ex')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id));
            if ($type == "ex") {
                $result = $this->expenses->get_by_id($type, ['expense_id' => $target], true);
            } else {
                $result = $this->expenses->get_by_id($type, ['expense_cat_id' => $target]);
            }

            // var_dump($result);die;
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
            $result = $this->expenses->get();
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

/* End of file Expenses.php */
