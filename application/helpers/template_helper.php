<?php

if (!function_exists('render_template')) {

	function render_template($view, $data = array()) {
		$ci = &get_instance();
		$ci->load->view('layouts/front/header', $data);
		$ci->load->view($view, $data);
		$ci->load->view('layouts/front/footer', $data);
		return true;
	}

	function render_template_admin($view, $data = array()) {
		$ci = &get_instance();
		$ci->load->view('layouts/admin/header', $data);
		$ci->load->view('layouts/admin/sidebar', $data);
		$ci->load->view($view, $data);
		$ci->load->view('layouts/admin/footer', $data);
		return true;
	}
}