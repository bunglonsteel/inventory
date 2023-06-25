<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Banner extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Banner_model', 'banner');
    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result = $this->banner->result_data();
            $result_data = [];

            foreach ($result as $res) {
                $row   = [];
                $row[] = '  <div class="d-flex align-items-center flex-wrap flex-lg-nowrap">
                                <img class="w-100 h-100 me-2 mb-2 mb-sm-0 rounded" src="' . base_url('public/image/banner/') . $res->banner_image . '" style="max-width:80px;max-height: 50px;object-fit: cover;"></img>
                                <span>' . htmlspecialchars($res->banner_name) . '</span>
                            </div>';
                $row[] = $res->is_active == 1 ? 'Ya' : 'Tidak';
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn edit btn-sm btn-secondary me-2" data-id="' . $res->banner_id . '">
                                <i class="tf-icons bx bx-edit-alt"></i>Ubah
                            </button>
                            <button type="button" class="d-flex btn delete btn-sm btn-danger" data-id="' . $res->banner_id . '">
                                <i class="tf-icons bx bx-trash"></i>Hapus
                            </button>
                        </div>';
                $result_data[] = $row;
            }
            $output = [
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->banner->count_all_result(),
                "recordsFiltered" => $this->banner->count_filtered(),
                "data" => $result_data,
                "csrf_hash" => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Banner';
        render_template_admin('admin/banner', $data);
    }

    public function action($type = "add")
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $this->form_validation->set_rules('banner_name', 'Judul banner', 'trim|required');
            $this->form_validation->set_rules('active', 'Tampilkan Banner', 'trim|required');

            $config['allowed_types']    = 'jpg|png|jpeg';
            $config['max_size']         = '500';
            $config['upload_path']      = './public/image/banner/';
            $config['file_ext_tolower'] = TRUE;
            $config['encrypt_name']     = TRUE;

            $this->load->library('upload', $config);
            $payload = [
                "target" => strip_tags(htmlspecialchars($this->input->post('target', TRUE))),
                "name"   => strip_tags(htmlspecialchars($this->input->post('banner_name', TRUE))),
                "active" => strip_tags(htmlspecialchars($this->input->post('active', TRUE))),
            ];

            if ($type == "add") {
                $message = $this->_add_banner($payload);
            }

            if ($type == "edit") {
                $message = $this->_update_banner($payload);
            }

            if ($type == "delete") {
                $message = $this->_remove_banner($payload['target']);
            }

            echo json_encode($message);
        }
    }

    private function _add_banner($payload)
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
            if ($this->upload->do_upload('image')) {
                $data = [
                    'banner_name'  => $payload['name'],
                    'banner_image' => $this->upload->data('file_name'),
                    'is_active'    => $payload['active'],
                ];
                $this->banner->insert($data);
                $message =  [
                    'success'   => 'true',
                    'message'   => 'Banner berhasil ditambahkan, terimakasih.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $message =  [
                    'error'     => 'true',
                    'message'   => $this->upload->display_errors('', ''),
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }
        return $message;
    }

    private function _update_banner($payload)
    {
        if ($this->form_validation->run() == FALSE) {
            $message =  [
                'errors' => 'true',
                'message' => validation_errors(
                    '<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">',
                    '<button type="button" class="btn-alert btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                ),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $check = $this->db->get_where('banner', ['banner_id' => $payload['target']])->row();
            if ($check) {
                $data = [
                    'banner_name' => $payload['name'],
                    'is_active'   => $payload['active'],
                ];

                if (!$this->input->post('image')) {
                    if ($this->upload->do_upload('image')) {

                        unlink(FCPATH . 'public/image/banner/' . $check->banner_image);
                        $data['banner_image'] = $this->upload->data('file_name');

                        $this->banner->update(['banner_id' => $check->banner_id], $data);
                        $message =  [
                            'success'   => 'true',
                            'message'   => 'Banner berhasil diperbarui, terimakasih.',
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
                    $this->banner->update(['banner_id' => $check->banner_id], $data);
                    $message =  [
                        'success'   => 'true',
                        'message'   => 'Banner berhasil diperbarui, terimakasih.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $message =  [
                    'error'     => 'true',
                    'message'   => 'Gagal banner tidak ada / tidak bisa dirubah.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        return $message;
    }

    private function _remove_banner($id)
    {
        $check = $this->db->get_where('banner', ['banner_id' => $id])->row();
        if (!$check) {
            $message = [
                'error'     => 'true',
                'message'   => 'Banner yang ingin dihapus tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            unlink(FCPATH . 'public/image/banner/' . $check->banner_image);
            $this->banner->delete(['banner_id' => $check->banner_id]);
            $message = [
                'success'   => 'true',
                'message'   => 'Banner berhasil dihapus, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }
        return $message;
    }

    public function get_banner($id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id));
            $result = $this->db->get_where('banner', ['banner_id' => $target])->row();
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

/* End of file Banner.php */
