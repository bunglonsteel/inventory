<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
    }

    public function index()
    {
        $data['socmed']         = json_decode($this->settings->find(['option_name' => 'socmed'])->option_value);
        $data['ecommerce']      = json_decode($this->settings->find(['option_name' => 'ecommerce'])->option_value);
        $data['whatsapp_token'] = $this->settings->find(['option_name' => 'whatsapp_api'])->option_value;
        $data['title']  = 'Settings';
        render_template_admin('admin/settings', $data);
    }

    public function update(string $type = "general", $target = null)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            switch ($type) {
                case 'general':
                    $output = $this->_general();
                    break;
                case 'account':
                    $output = $this->_account();
                    break;
                case 'socmed':
                    $output = $this->_socmed($target);
                    break;
                case 'ecommerce':
                    $output = $this->_ecommerce($target);
                    break;
                case 'token':
                    $output = $this->_token();
                    break;
                default:
                    break;
            }
            echo json_encode($output);
        }
    }

    public function create(string $type)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            if ($type == "socmed") {
                $output = $this->_socmed();
            } else if ($type == "ecommerce") {
                $output = $this->_ecommerce();
            }
            echo json_encode($output);
        }
    }

    public function delete(string $type, string $id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            if ($type == "socmed") {
                $socmed = json_decode($this->settings->find(['option_name' => "socmed"])->option_value) ?
                    json_decode($this->settings->find(['option_name' => "socmed"])->option_value) :
                    [];
                $result = array_filter($socmed, fn ($v) => $v->key != $id);
                $this->settings->update(json_encode($result), "socmed");
                $output =  [
                    'success'   => 'true',
                    'message'   => 'Social media berhasil dihapus, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $socmed = json_decode($this->settings->find(['option_name' => "ecommerce"])->option_value) ?
                    json_decode($this->settings->find(['option_name' => "ecommerce"])->option_value) :
                    [];
                $target = array_filter($socmed, fn ($v) => $v->key == $id);
                $result = array_filter($socmed, fn ($v) => $v->key != $id);

                unlink(FCPATH . 'public/image/general/' . $target[array_key_first($target)]->image);
                $this->settings->update(json_encode($result), "ecommerce");
                $output =  [
                    'success'   => 'true',
                    'message'   => 'Ecommerce berhasil dihapus, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
            echo json_encode($output);
        }
    }

    private function _general()
    {
        $payload = [
            "logo"        => isset($_FILES['image']['name']) ? $_FILES['image']['name'] : null,
            "site"        => trim(htmlentities($this->input->post('site_title', TRUE))),
            "keywords"    => trim(htmlentities($this->input->post('keywords', TRUE))),
            "desc"        => trim(htmlentities($this->input->post('desc', TRUE))),
            "bank_an"     => trim(htmlentities($this->input->post('bank_an', TRUE))),
            "bank_number" => trim(htmlentities($this->input->post('bank_number', TRUE))),
            "number"      => trim(htmlentities($this->input->post('number', TRUE))),
            "address"     => trim(htmlentities($this->input->post('address', TRUE))),
        ];

        $this->form_validation->set_rules('site_title', 'Judul situs', 'trim|required');
        $this->form_validation->set_rules('keywords', 'Kata kunci', 'trim|required');
        $this->form_validation->set_rules('desc', 'Deskripsi', 'trim|required');
        $this->form_validation->set_rules('bank_an', 'Atas Nama Bank', 'trim|required');
        $this->form_validation->set_rules('bank_number', 'Rek BCA', 'trim|required|numeric');
        $this->form_validation->set_rules('number', 'No. telepon', 'trim|required');
        $this->form_validation->set_rules('address', 'Alamat', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $output = [
                'errors'     => 'true',
                'message'   =>  validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            $data = [
                "site_title"   => $payload['site'],
                "logo"         => $this->general->logo,
                "keywords"     => $payload['keywords'],
                "description"  => $payload['desc'],
                "bank_an"      => $payload['bank_an'],
                "bank_number"  => $payload['bank_number'],
                "number_phone" => $payload['number'],
                "address"      => $payload['address'],
            ];
            // var_dump($data);die;
            if ($payload['logo']) {

                $this->_validate_upload();

                if ($this->upload->do_upload('image')) {
                    if ($this->general->logo != 'logo.jpg') {
                        unlink(FCPATH . 'public/image/general/' . $this->general->logo);
                    }
                    $data['logo'] = $this->upload->data('file_name');
                    $this->settings->update(json_encode($data), "general");
                    $output =  [
                        'success'   => 'true',
                        'message'   => 'Pengaturan berhasil diperbarui, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $output =  [
                        'error'     => 'true',
                        'message'   => $this->upload->display_errors('', ''),
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $this->settings->update(json_encode($data), "general");
                $output =  [
                    'success'   => 'true',
                    'message'   => 'Pengaturan berhasil diperbarui, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        return $output;
    }

    private function _account()
    {
        $payload = [
            "old_password" => trim(htmlentities($this->input->post('old_password', TRUE))),
            "new_password" => trim(htmlentities($this->input->post('old_password', TRUE))),
            "re_password"  => trim(htmlentities($this->input->post('old_password', TRUE))),
        ];

        $this->form_validation->set_rules('old_password', 'Password Lama', 'trim|required');
        $this->form_validation->set_rules('new_password', 'Passoword Baru', 'trim|required|matches[new_password]|min_length[8]');
        $this->form_validation->set_rules('re_password', 'Ulangi Password baru', 'trim|required|matches[re_password]');

        if ($this->form_validation->run() == FALSE) {
            $output = [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            $check_admin = $this->db->get_where('users', ['user_type' => 'superadmin'])->first_row();
            if (!$check_admin) {
                $output =  [
                    'error'     => 'true',
                    'message'   => 'Akun tidak ada atau tidak ditemukan.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                if (!password_verify($payload['old_password'], $check_admin->password)) {
                    $output =  [
                        'error'     => 'true',
                        'message'   => 'Password lama salah',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {

                    $this->db->update('users', ['password' => password_hash($payload['new_password'], PASSWORD_DEFAULT)], ['user_type' => 'superadmin']);
                    $output =  [
                        'success'   => 'true',
                        'message'   => 'Password berhasil diperbarui, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            }
        }

        return $output;
    }

    private function _socmed($target = "")
    {
        $payload = [
            "name" => trim(htmlentities($this->input->post('name', TRUE))),
            "icon" => trim(htmlentities($this->input->post('icon', TRUE))),
            "url"  => trim(htmlentities($this->input->post('url', TRUE))),
        ];

        $this->form_validation->set_rules('name', 'Nama social media', 'trim|required');
        $this->form_validation->set_rules('icon', 'Icon', 'trim|required');
        $this->form_validation->set_rules('url', 'Link', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $output = [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            $socmed   = json_decode($this->settings->find(['option_name' => "socmed"])->option_value) ?
                json_decode($this->settings->find(['option_name' => "socmed"])->option_value) :
                [];
            if (!$target) {
                $is_check = FALSE;
                if ($socmed) {
                    foreach ($socmed as $value) {
                        if (strtolower($value->name) == strtolower($payload['name'])) {
                            $is_check = TRUE;
                        }
                    }
                }
                if ($is_check) {
                    $output =  [
                        'error'     => 'true',
                        'message'   => 'Social media ini sudah ada.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $socmed[] = (object) [
                        'key'  => substr(md5(mt_rand()), 0, 8),
                        'name' => $payload['name'],
                        'icon' => $payload['icon'],
                        'link' => $payload['url'],
                    ];
                    $this->settings->update(json_encode($socmed), "socmed");
                    $output =  [
                        'success'   => 'true',
                        'message'   => 'Social media berhasil ditambahkan.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $is_check      = FALSE;
                $result_update = [];
                foreach ($socmed as $value) {
                    if ($value->key == $target) {
                        $is_check = TRUE;
                        $value->name = $payload['name'];
                        $value->icon = $payload['icon'];
                        $value->link = $payload['url'];
                    }

                    $result_update[] = $value;
                }

                if (!$is_check) {
                    $output =  [
                        'error'     => 'true',
                        'message'   => 'Gagal untuk mengupdate.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $this->settings->update(json_encode($socmed), "socmed");
                    $output =  [
                        'success'   => 'true',
                        'message'   => 'Social media berhasil diperbarui.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            }
        }

        return $output;
    }

    private function _ecommerce($target = "")
    {
        $payload = [
            "image"    => isset($_FILES['image']['name']) ? $_FILES['image']['name'] : NULL,
            "platform" => trim(htmlentities($this->input->post('platform', TRUE))),
            "url"      => trim(htmlentities($this->input->post('url', TRUE))),
        ];

        $this->form_validation->set_rules('platform', 'Platform', 'trim|required');
        $this->form_validation->set_rules('url', 'Link', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $output = [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            $this->_validate_upload();
            $ecommerce = json_decode($this->settings->find(['option_name' => "ecommerce"])->option_value) ?
                json_decode($this->settings->find(['option_name' => "ecommerce"])->option_value) :
                [];
            if (!$target) {
                $is_check = FALSE;
                if ($ecommerce) {
                    foreach ($ecommerce as $value) {
                        if (strtolower($value->platform) == strtolower($payload['platform'])) {
                            $is_check = TRUE;
                        }
                    }
                }
                if ($is_check) {
                    $output =  [
                        'error'     => 'true',
                        'message'   => 'Social media ini sudah ada.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    if (!$this->upload->do_upload('image')) {
                        $output =  [
                            'error'     => 'true',
                            'message'   => $this->upload->display_errors('', ''),
                            'csrf_hash' => $this->security->get_csrf_hash(),
                        ];
                    } else {
                        $ecommerce[] = (object) [
                            'key'      => substr(md5(mt_rand()), 0, 8),
                            'platform' => $payload['platform'],
                            'image'    => $this->upload->data('file_name'),
                            'link'     => $payload['url'],
                        ];
                        $this->settings->update(json_encode($ecommerce), "ecommerce");
                        $output =  [
                            'success'   => 'true',
                            'message'   => 'E-Commerce berhasil ditambahkan.',
                            'csrf_hash' => $this->security->get_csrf_hash(),
                        ];
                    }
                }
            } else {
                $is_check      = FALSE;
                $result_update = [];
                foreach ($ecommerce as $value) {
                    if ($value->key == $target) {
                        $is_check        = TRUE;
                        $value->platform = $payload['platform'];
                        if ($payload['image']) {
                            if (!$this->upload->do_upload('image')) {
                                return [
                                    'error'     => 'true',
                                    'message'   => $this->upload->display_errors('', ''),
                                    'csrf_hash' => $this->security->get_csrf_hash(),
                                ];
                            } else {
                                unlink(FCPATH . 'public/image/general/' . $value->image);
                                $value->image = $this->upload->data('file_name');
                            }
                        }
                        $value->link = $payload['url'];
                    }

                    $result_update[] = $value;
                }

                if (!$is_check) {
                    $output =  [
                        'error'     => 'true',
                        'message'   => 'Gagal untuk mengupdate.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $this->settings->update(json_encode($ecommerce), "ecommerce");
                    $output =  [
                        'success'   => 'true',
                        'message'   => 'E-Commerce berhasil diperbarui.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            }
        }

        return $output;
    }

    private function _token()
    {
        $no_whatsapp = trim(htmlentities($this->input->post('no_whatsapp', TRUE)));
        $token       = trim(htmlentities($this->input->post('token_whatsapp', TRUE)));

        $this->form_validation->set_rules('no_whatsapp', 'Token', 'trim|required|numeric');
        $this->form_validation->set_rules('token_whatsapp', 'Token', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $output = [
                'errors'  => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            $response = json_decode(send_whastapp_message($token, $no_whatsapp, 'Test Activation Whatsapp'));
            if ($response->status) {
                $output = [
                    'error'     => 'true',
                    'message'   => "Terimakasih, Whatsapp API anda sudah terhubung",
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
                $this->settings->update($token, "whatsapp_api");
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => $response->reason,
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }
        }

        return $output;
    }

    private function _validate_upload()
    {
        $config['allowed_types']    = 'jpg|png|jpeg';
        $config['max_size']         = '500';
        $config['upload_path']      = './public/image/general/';
        $config['file_ext_tolower'] = TRUE;
        $config['encrypt_name']     = TRUE;

        $this->load->library('upload', $config);
    }
}
// [{"key":"1","name":"Facebook","icon":"bxl-facebook","link":"https:\/\/facebook.com"},{"key":"2","name":"Instagram","icon":"bxl-instagram","link":"https:\/\/instagram.com"},{"key":"3","name":"Tiktok","icon":"bxl-tiktok","link":"https:\/\/tiktok.com"},{"key":"4","name":"Whatsapp","icon":"bxl-whatsapp","link":"https:\/\/whatsapp.com"},{"key":"a067e7e7","name":"sfdf","icon":"sdfs","link":"afsdf"}]
/* End of file Settings.php */
