<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Users_model', 'users');
        $this->load->library('uuid');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result    = $this->users->result();
            $temp_data = [];

            foreach ($result as $res) {
                $is_login    = $res->is_login  == 1 ? "Ya" : 'Tidak';
                $is_active   = $res->is_active == 1 ? "Aktif" : 'Nonaktif';
                $classLogin  = $res->is_login  == 1 ? "bg-soft-success text-green" : 'bg-label-danger';
                $classActive = $res->is_active == 1 ? "bg-soft-success text-green" : 'bg-label-danger';

                $row   = [];
                $row[] = '<div class="d-flex align-items-center">
                            <div  class = "avatar me-2 flex-shrink-0">
                                <span class = "avatar-initial bg-primary rounded-circle">' . mb_substr($res->name, 0, 1) . '</span>
                            </div>
                            <span>' . htmlspecialchars($res->name) . '</span>
                        </div>';
                $row[] = ucfirst($res->user_type);
                $row[] = $res->email ? htmlspecialchars($res->email) : '-';
                $row[] = $res->contact ? htmlspecialchars($res->contact) : '-';
                $row[] = '<span class="badge ' . $classLogin . ' px-3 py-2">
                            ' . $is_login . '
                        </span>';
                $row[] = '<span class="badge ' . $classActive . ' px-3 py-2">
                            ' . $is_active . '
                        </span>';
                $row[] = '<div class="d-flex gap-2">
                            <button type="button" class="d-flex btn edit btn-icon btn-sm btn-secondary" data-id="' . base64_encode($res->uuid) . '">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button type="button" class="d-flex btn delete btn-icon btn-sm btn-danger" data-id="' . base64_encode($res->uuid) . '">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>';
                $temp_data[] = $row;
            }

            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->users->count_all_result(),
                "recordsFiltered" => $this->users->count_filtered(),
                "data"            => $temp_data,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Users';
        render_template_admin('admin/users', $data);
    }

    public function action(String $type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->_rules();

            $payload = [
                "id"        => strip_tags(htmlentities($this->input->post('target', TRUE) ?? '')),
                "name"      => strip_tags(htmlentities($this->input->post('name', TRUE) ?? '')),
                "email"     => strip_tags(htmlentities($this->input->post('email', TRUE) ?? '')),
                "password"  => strip_tags(htmlentities($this->input->post('password', TRUE) ?? '')),
                "contact"   => strip_tags(htmlentities($this->input->post('contact', TRUE) ?? '')),
                "role"      => strip_tags(htmlentities($this->input->post('type', TRUE) ?? '')),
                "is_login"  => strip_tags(htmlentities($this->input->post('is_login', TRUE) ?? '')),
                "is_active" => strip_tags(htmlentities($this->input->post('is_active', TRUE) ?? '')),
            ];

            if ($type == "add") {
                $output = $this->_add_user($payload);
            }

            if ($type == "edit") {
                $output = $this->_update_user($payload);
            }

            if ($type == "delete") {
                $output = $this->_remove_user($payload['id']);
            }

            echo json_encode($output);
        }
    }

    private function _add_user($payload)
    {
        if ($this->form_validation->run() == false) {
            $output = [
                'errors'    => 'true',
                'message'   => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($payload['is_login'] == 1 && $payload['email'] == "" && $payload['password'] == "") {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Email dan Password tidak boleh kosong.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $data = [
                    'uuid'      => $this->uuid->v4(),
                    'name'      => $payload['name'],
                    'email'     => $payload['email'],
                    'password'  => password_hash($payload['password'], PASSWORD_DEFAULT),
                    'contact'   => $payload['contact'],
                    'user_type' => $payload['role'],
                    'is_login'  => $payload['is_login'],
                    'is_active' => $payload['is_active'],
                ];

                $this->users->insert($data);
                $output = [
                    'success'   => 'true',
                    'message'   => 'User berhasil ditambahkan, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        return $output;
    }

    private function _update_user($payload)
    {
        if ($this->form_validation->run() == false) {
            $output = [
                'errors'    => 'true',
                'message'   => validation_errors('<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">', '</div>'),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $user = $this->users->find(['uuid' => base64_decode($payload['id'])]);
            if (!$user) {
                $output = [
                    'error'     => 'true',
                    'message'   => 'User tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                if ($payload['is_login'] == 1 && $payload['email'] == "" && $payload['password'] == "") {
                    $output = [
                        'error'     => 'true',
                        'message'   => 'Email dan Password tidak boleh kosong.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $data = [
                        'name'      => $payload['name'],
                        'email'     => $payload['email'],
                        'password'  => password_hash($payload['password'], PASSWORD_DEFAULT),
                        'contact'   => $payload['contact'],
                        'user_type' => $payload['role'],
                        'is_login'  => $payload['is_login'],
                        'is_active' => $payload['is_active'],
                    ];

                    $this->users->update($user->user_id, $data);
                    $output = [
                        'success'   => 'true',
                        'message'   => 'User berhasil diperbarui, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            }
        }

        return $output;
    }

    private function _remove_user($id)
    {
        $user = $this->users->find(['uuid' => base64_decode(htmlspecialchars($id ?? ''))]);
        if (!$user) {
            $output = [
                'error'     => 'true',
                'message'   => 'User tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            if ($user->is_active == 1) {
                $output = [
                    'error'     => 'true',
                    'message'   => 'User ini berstatus aktif tidak boleh dihapus.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                if ($user->user_type  != "superadmin") {
                    $this->users->delete($user->user_id);
                    $output = [
                        'success'   => 'true',
                        'message'   => 'User berhasil dihapus, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            }
        }
        return $output;
    }

    private function _rules()
    {
        $this->form_validation->set_rules(
            'name',
            'nama',
            'trim|required',
        );
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|valid_email',
        );
        $this->form_validation->set_rules(
            'password',
            'Password',
            'trim',
        );
        $this->form_validation->set_rules(
            'contact',
            'No Tlp / Wa',
            'trim|numeric',
        );
        $this->form_validation->set_rules(
            'type',
            'Role',
            'trim|required|alpha',
        );
        $this->form_validation->set_rules(
            'is_login',
            'Izin Login',
            'trim|required|numeric|max_length[1]',
        );
        $this->form_validation->set_rules(
            'is_active',
            'Status',
            'trim|required|numeric|max_length[1]',
        );
    }

    public function select_user(String $type = 'customer')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->users->get($type);
            if (!$result) {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Data tidak ditemukan / kosong.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $output = [
                    'success'   => 'true',
                    'message'   => 'Data berhasil ditemukan',
                    'data'      => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($output);
        }
    }

    public function find(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $result = $this->users->findId(['uuid' => base64_decode($id)]);
            if (!$result) {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Data tidak ditemukan / kosong.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $output = [
                    'success'   => 'true',
                    'message'   => 'Data berhasil ditemukan',
                    'data'      => $result,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($output);
        }
    }
}

/* End of file Users.php */
