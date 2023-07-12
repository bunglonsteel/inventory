<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function index()
	{

		if ($this->session->userdata('email')) {
			redirect('admin/dashboard');
		}

		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');

		if ($this->form_validation->run() == false) {
			$this->load->view('login');
		} else {
			$email    = strip_tags(html_escape($this->input->post('email', TRUE)));
			$password = strip_tags(html_escape($this->input->post('password', TRUE)));
			$user     = $this->db->get_where('users', ['email' => $email])->row_array();

			if ($user && $user['user_type'] == 'superadmin' || $user['user_type'] == 'administrator') {
				if (password_verify($password, $user['password'])) {
					if ($user['is_active'] == 1) {
						if ($user['is_login'] == 1) {
							$data = [
								'email' => $user['email'],
								'name'  => $user['name'],
							];
							$this->session->set_userdata($data);
							redirect('admin/dashboard');
						} else {
							$this->session->set_flashdata(
								'message',
								'<div class="alert alert-warning alert-dismissible fs-7 py-2_5" role="alert">
									Maaf anda tidak diizinkan masuk.
								</div>'
							);
							redirect('auth');
						}
					} else {
						$this->session->set_flashdata(
							'message',
							'<div class="alert alert-warning alert-dismissible fs-7 py-2_5" role="alert">
                                Akun anda belum aktif silahkan hubungi admin.
                            </div>'
						);
						redirect('auth');
					}
				} else {
					$this->session->set_flashdata(
						'message',
						'<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">
							Password salah, ulangi lagi.
						</div>'
					);
					redirect('auth');
				}
			} else {
				$this->session->set_flashdata(
					'message',
					'<div class="alert alert-danger alert-dismissible fs-7 py-2_5" role="alert">
                        Alamat email tidak ada / tidak diizinkan masuk.
                    </div>'
				);
				redirect('auth');
			}
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('email');
		$this->session->set_flashdata(
			'message',
			'<div class="alert alert-primary alert-dismissible fs-7 py-2_5" role="alert">
				Anda berhasil logout.
			</div>'
		);
		redirect('auth');
	}
}

/* End of file Auth.php */
