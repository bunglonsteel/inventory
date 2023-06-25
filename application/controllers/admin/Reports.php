<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
        $this->load->model('Reports_model', 'reports');
    }


    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $result      = $this->reports->result_data();
            // var_dump($result);die;
            $sales   = [];
            $grand_total = 0;
            foreach ($result as $res) {
                $grand_total += $res->total;
                $row = [];
                $row[] = '📅 ' . date('d M Y', strtotime($res->date));
                $row[] = "Rp. " . htmlspecialchars(number_format($res->total, 0, ',', '.'));
                $sales[] = $row;
            }
            // var_dump($sales);die;
            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->reports->count_all_result(),
                "recordsFiltered" => $this->reports->count_filtered(),
                "data"            => $sales,
                "grand_total"     => $grand_total,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }

        $data['sales_by_month']   = $this->reports->sales('month');
        $data['sales_by_year']    = $this->reports->sales('year');
        $data['expense_by_month'] = $this->reports->expenses('month');
        $data['expense_by_year']  = $this->reports->expenses('year');
        // var_dump(json_encode($data['sales_by_month']));die;
        $data['title'] = "Laporan Penjualan";
        render_template_admin('admin/reports/sales', $data);
    }

    public function stock()
    {
        if ($this->input->is_ajax_request()) {
            $result        = $this->reports->result_data('stock');
            $product_stock = [];
            foreach ($result as $res) {
                $class = $res->current_stock < 3 ? "text-danger" : "text-green";
                $row = [];
                $row[] = htmlspecialchars($res->product_name);
                $row[] = '<strong class="' . $class . '">' . htmlspecialchars($res->current_stock) . '</strong>';
                $product_stock[] = $row;
            }
            // var_dump($product_stock);die;
            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->reports->count_all_result('stock'),
                "recordsFiltered" => $this->reports->count_filtered('stock'),
                "data"            => $product_stock,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = "Laporan Stock";
        render_template_admin('admin/reports/stock', $data);
    }

    public function send_whatsapp()
    {
        $this->load->helper('settings_helper');
        $this->load->model('Settings_model', 'settings');

        $this->form_validation->set_rules(
            'whatsapp',
            'No. Whatsapp',
            'trim|required|numeric|min_length[11]|regex_match[/08[0-9]+$/]',
        );
        $stocks = $this->db->select('product_name, current_stock')->from('product_details')->order_by('current_stock', 'ASC')->get()->result();

        if ($this->form_validation->run() == false) {
            $output = [
                'error'     => 'true',
                'message'   => validation_errors('', ''),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {
            $no_whatsapp = $this->input->post('whatsapp');
            $token       = $this->settings->find(['option_name' => 'whatsapp_api'])->option_value;
            $message     = '';

            foreach ($stocks as $stock) {
                $message .= $stock->product_name . ": ( " . $stock->current_stock . " ),\n";
            }

            $respoonse = json_decode(send_whastapp_message($token, $no_whatsapp, $message));

            if ($respoonse->status) {
                $output = [
                    'success'   => 'true',
                    'message'   => 'Laporan berhasil terkirim.',
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => $respoonse->reason,
                    'csrf_hash' => $this->security->get_csrf_hash(),
                ];
            }
        }

        echo json_encode($output);
    }
}

/* End of file Report.php */
