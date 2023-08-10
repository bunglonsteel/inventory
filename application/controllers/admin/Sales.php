<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Settings_model', 'settings');
        $this->load->model('Products_model', 'products');
        $this->load->model('Sales_model', 'sales');
        $this->load->model('Users_model', 'users');
        $this->load->library('cart');
        $this->load->library('uuid');
        $this->general = json_decode($this->settings->find(['option_name' => "general"])->option_value);
    }


    public function index()
    {
        // var_dump($this->sales->generate_invoice());die;
        if ($this->input->is_ajax_request()) {
            $result = $this->sales->result_data();
            $temp_data = [];

            $info = [
                'PAID'          => 'bg-soft-success text-green',
                'UNPAID'        => 'bg-label-danger',
                'PENDING'       => 'bg-label-warning',
                'RECEIVED'      => 'bg-soft-success text-green',
                'ORDERED'       => 'bg-label-info',
                'CONFIRMED'     => 'bg-soft-success text-green',
                'PROCESSING'    => 'bg-label-secondary',
                'SHIPPING'      => 'bg-label-dark',
                'DELIVERED'     => 'bg-label-primary'
            ];
            foreach ($result as $res) {
                $row = [];
                $row[] = htmlspecialchars($res->invoice);
                $row[] = '  <div class="d-flex align-items-center flex-wrap flex-lg-nowrap">
                                <span class="text-nowrap">' . htmlspecialchars($res->customer) . '</span>
                            </div>';
                $row[] = htmlspecialchars(date('d M Y - H:i', strtotime($res->date)));
                $row[] = '
                            <span class="badge px-3 py-2 ' . $info[$res->order_status] . '">
                                ' . ucfirst(strtolower($res->order_status)) . '
                            </span>
                        ';
                $row[] = "Rp. " . htmlspecialchars(number_format($res->total_amount, 0, ',', '.'));
                $row[] = "Rp. " . htmlspecialchars(number_format($res->total_pay, 0, ',', '.'));
                $row[] = "Rp. " . htmlspecialchars(number_format($res->total_change, 0, ',', '.'));
                $row[] = '
                            <span class="badge px-3 py-2 ' . $info[$res->payment_status] . '">
                                ' . ucfirst(strtolower($res->payment_status)) . '
                            </span>
                        ';
                $row[] = '<div class="d-flex">
                            <button type="button" class="d-flex btn preview btn-icon btn-sm btn-primary me-2" data-id="' . $res->order_id . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
                                <i class="bx bx-detail"></i>
                            </button>
                            <a href="' . base_url('admin/sales/manual/update/') . $res->order_id . '" class="d-flex btn edit btn-icon btn-sm btn-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                <i class="tf-icons bx bx-edit-alt"></i>
                            </a>
                            <button type="button" class="d-flex btn delete btn-icon btn-sm btn-danger" data-id="' . $res->order_id . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                <i class="tf-icons bx bx-trash"></i>
                            </button>
                        </div>';
                $temp_data[] = $row;
            }
            // var_dump($temp_data);die;
            $output = [
                "draw"            => $_POST['draw'],
                "recordsTotal"    => $this->sales->count_all_result_value(),
                "recordsFiltered" => $this->sales->count_filtered(),
                "data"            => $temp_data,
                "csrf_hash"       => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
        $data['title'] = 'Penjualan';
        render_template_admin('admin/sales/sales', $data);
    }

    public function pos()
    {
        if ($this->cart->contents()) {
            $this->cart->destroy();
        }
        if ($this->input->is_ajax_request()) {
            $search = array(
                'keyword' => trim(htmlspecialchars($this->input->post('search_key', TRUE) ?? '')),
                'categories' => trim(htmlspecialchars($this->input->post('categories', TRUE) ?? '')),
            );

            $this->load->library('pagination');

            $limit = 12;
            $offset = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $config['base_url']    = site_url('admin/sales/pos');
            $config['total_rows']  = $this->products->get_products($limit, $offset, $search, $count = true);
            $config['per_page']    = $limit;
            $config['uri_segment'] = 4;

            // Styling
            $config['full_tag_open']   = '<nav class="mt-3" aria-label="Page navigation"><ul class="pagination justify-content-end">';
            $config['full_tag_close']  = '</ul></nav>';
            $config['num_tag_open']    = '<li class="page-item">';
            $config['num_tag_close']   = '</li>';
            $config['cur_tag_open']    = '<li class="page-item active"><a class="page-link">';
            $config['cur_tag_close']   = '</a></li>';
            $config['next_tag_open']   = '<li class="page-item next">';
            $config['next_tag_close']  = '</li>';
            $config['prev_tag_open']   = '<li class="page-item prev">';
            $config['prev_tag_close']  = '</li>';
            $config['first_tag_open']  = '<li class="page-item first">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open']   = '<li class="page-item last">';
            $config['last_tag_close']  = '</li>';
            $config['next_link']       = '<i class="tf-icon bx bx-chevrons-right"></i>';
            $config['prev_link']       = '<i class="tf-icon bx bx-chevrons-left"></i>';
            $config['first_link']      = '<i class="tf-icon bx bx-chevron-left"></i>';
            $config['last_link']       = '<i class="tf-icon bx bx-chevron-right"></i>';

            $this->pagination->initialize($config);

            $data['products'] = $this->products->get_products($limit, $offset, $search, $count = false);
            $data['paginate'] = $this->pagination->create_links();

            $result = [
                'success' => 'true',
                'data'    => [
                    'products'   => $data['products'],
                    'pagination' => $data['paginate'],
                ],
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
            return $this->output->set_content_type('application/json')->set_output(json_encode($result));
        }
        $data['payments'] = $this->sales->get_payment_method();
        $data['title']    = 'Point Of Sales';
        $this->load->view('admin/sales/pos', $data);
    }

    public function pay()
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {

            $this->form_validation->set_rules('customer', 'Customer', 'trim|required|callback_select_null|callback_check_customer')
                ->set_rules('amount_pay', 'Jumlah Bayar', 'trim|required|callback_amount_pay')
                ->set_rules('method_pay', 'Metode Pembayaran', 'trim|required|callback_select_null');

            $payload = [
                'customer'   => strip_tags(htmlspecialchars($this->input->post('customer', TRUE) ?? '')),
                'diskon'     => strip_tags(htmlspecialchars($this->input->post('diskon_all', TRUE) ?? '')),
                'shipping'   => strip_tags(htmlspecialchars($this->input->post('shipping', TRUE) ?? '')),
                'amount_pay' => strip_tags(htmlspecialchars($this->input->post('amount_pay', TRUE) ?? '')),
                'method_pay' => strip_tags(htmlspecialchars($this->input->post('method_pay', TRUE) ?? '')),
                'notes'      => strip_tags(htmlspecialchars($this->input->post('notes', TRUE) ?? '')),
            ];
            if ($this->form_validation->run() == FALSE) {
                $output = [
                    'error'     => 'true',
                    'message'   => validation_errors(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            } else {
                $grand_total = ($this->cart->total() - $payload['diskon']) + $payload['shipping'];
                $user = $this->users->find(['uuid' => base64_decode($payload['customer'])]);
                $data_order = [
                    'unique_id'      => $this->uuid->v4(),
                    'invoice_number' => $this->sales->generate_invoice(),
                    'order_type'     => 'pos',
                    'order_date'     => date('Y-m-d H:i'),
                    'user_id'        => $user->user_id,
                    'discount'       => $payload['diskon'],
                    'cost_shipping'  => $payload['shipping'],
                    'sub_total'      => $this->cart->total(),
                    'grand_total'    => $grand_total,
                    'order_status'   => 7,
                    'payment_status' => 1,
                    'notes'          => $payload['notes'],
                    'total_item'     => count($this->cart->contents()),
                    'total_quantity' => $this->cart->total_items(),
                ];
                $order_id = $this->sales->insert_id('orders', $data_order);

                $order_items  = [];
                $update_stock = [];
                foreach ($this->cart->contents() as $items) {
                    $qty = $items['qty'];
                    $order_items[] = [
                        'order_id'       => $order_id,
                        'product_id'     => $items['id'],
                        'qty'            => $qty,
                        'unit_price'     => $items['price_item'],
                        'total_discount' => $items['diskon'],
                        'sub_total'      => $items['subtotal'],
                    ];

                    $update_stock[] = [
                        'product_id'    => $items['id'],
                        'current_stock' => "current_stock - $qty",
                    ];
                }
                $this->sales->insert_batch('order_items', $order_items);
                $this->sales->update_stock('product_details', $update_stock, 'product_id');

                $data_payments = [
                    'payment_type'    => 'in',
                    'payment_number'  => $this->sales->generate_payment(),
                    'payment_date'    => date('Y-m-d H:i'),
                    'amount'          => $grand_total,
                    'paid_amount'     => $payload['amount_pay'],
                    'change_amount'   => $payload['amount_pay'] - $grand_total,
                    'payment_mode_id' => $payload['method_pay'],
                    'user_id'         => $user->user_id
                ];
                $payment_id = $this->sales->insert_id('payments', $data_payments);

                $data_order_payments = [
                    'payment_id' => $payment_id,
                    'order_id'   => $order_id,
                    'amount'     => $grand_total,
                ];
                $this->sales->insert('order_payments', $data_order_payments);
                $output = [
                    'success'   => 'true',
                    'message'   => 'Pembelian berhasil, Alhamdulillah🤲',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
                $this->cart->destroy();
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function manual($action = "add", $target = "")
    {
        $payload = [
            'target'       => $target,
            'invoice'      => strip_tags(htmlspecialchars($this->input->post('inv', TRUE) ?? '')),
            'customer'     => strip_tags(htmlspecialchars($this->input->post('customer', TRUE) ?? '')),
            'date'         => strip_tags(htmlspecialchars($this->input->post('date', TRUE) ?? '')),
            'discount'     => strip_tags(htmlspecialchars($this->input->post('discount_all', TRUE) ?? '')),
            'shipping'     => strip_tags(htmlspecialchars($this->input->post('shipping', TRUE))),
            'order_status' => strip_tags(htmlspecialchars($this->input->post('order_status', TRUE) ?? '')),
            'method_pay'   => strip_tags(htmlspecialchars($this->input->post('method_pay', TRUE) ?? '')),
            'amount_pay'   => 0,
            'status_pay'   => strip_tags(htmlspecialchars($this->input->post('status_pay', TRUE) ?? '')),
            'notes'        => strip_tags(htmlspecialchars($this->input->post('notes', TRUE) ?? '')),
        ];
        $this->_action($action, $payload);
    }

    private function _action($action,  $payload)
    {
        if (!$this->input->is_ajax_request()) {
            if ($this->cart->contents()) {
                $this->cart->destroy();
            }
            $payment = $this->sales->get_payment_method();
            if ($action == "add") {

                $data['payments'] = $payment;
                $data['title']    = 'Tambah Penjualan';
                render_template_admin('admin/sales/add', $data);
            } else if ($action == "update") {

                $target = $this->sales->get_sales_by_id($payload['target']);
                if ($target) {
                    $this->cart->product_name_rules = '\w \-\.\/\%\:';
                    if (!$this->cart->contents()) {
                        foreach ($target->items as $item) {
                            $data = [
                                "item_id"    => $item->item_id,
                                'id'         => $item->product_id,
                                'name'       => $item->product,
                                'qty'        => $item->quantity,
                                'price'      => $item->price,
                                'price_item' => $item->price - $item->discount,
                                'image'      => base_url('public/image/products/') . $item->image,
                                'diskon'     => $item->discount,
                            ];
                            $this->cart->insert($data);
                        }
                    }
                    $data['payments']     = $payment;
                    $data['target_sales'] = $target;
                    $data['title']        = 'Edit Penjualan';
                    render_template_admin('admin/sales/update', $data);
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        } else {
            if ($action == "add" || $payload['target']) {
                $target = $payload['target'] ? $this->sales->get_sales_by_id($payload['target']) : (object) array("payment_status" => "");;
                if ($target->payment_status == "UNPAID" || $action == "add") {
                    $this->form_validation->set_rules('customer', 'Customer', 'trim|required|callback_select_null|callback_check_customer');
                    $this->form_validation->set_rules('method_pay', 'Metode Pembayaran', 'trim|required|callback_select_null');
                    $this->form_validation->set_rules('status_pay', 'Status Pembayaran', 'trim|required|callback_select_null');
                    $this->form_validation->set_rules('date', 'Tanggal', 'trim|required');
                }
            }

            $this->form_validation->set_rules('order_status', 'Order Status', 'trim|required|callback_select_null');

            if ($this->form_validation->run() == FALSE) {
                $output = [
                    'error'     => 'true',
                    'message'   => validation_errors(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            } else {

                if ($action == 'add') {
                    $output = $this->_add($payload);
                } else {
                    $target = $this->sales->get_sales_by_id($payload['target']);
                    $output = $this->_update($payload, $target);
                }
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    private function _add($payload)
    {

        if (!$this->cart->contents()) {
            $output = [
                'error'     => 'true',
                'message'   => 'Anda harus menambahkan produk terlebih dahulu',
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {

            if ($payload['status_pay'] == "UNPAID" && $payload['order_status'] == "DELIVERED") {
                return [
                    'error'     => 'true',
                    'message'   => 'Order status tidak boleh terkirim jika status pembayaran masih belum dibayar.',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }
            $grand_total = ($this->cart->total() - $payload['discount']) + $payload['shipping'];
            $user        = $this->users->find(['uuid' => base64_decode($payload['customer'])]);
            $data_order  = [
                'unique_id'      => $this->uuid->v4(),
                'invoice_number' => $this->sales->generate_invoice(),
                'order_type'     => 'sales',
                'order_date'     => date('Y-m-d H:i', strtotime($payload['date'])),
                'user_id'        => $user->user_id,
                'discount'       => $payload['discount'],
                'cost_shipping'  => $payload['shipping'],
                'sub_total'      => $this->cart->total(),
                'grand_total'    => $grand_total,
                'order_status'   => $payload['order_status'],
                'payment_status' => $payload['status_pay'],
                'notes'          => $payload['notes'],
                'total_item'     => count($this->cart->contents()),
                'total_quantity' => $this->cart->total_items(),
            ];

            if ($payload['invoice']) {
                $data_order['invoice_number'] = $payload['invoice'];
            }

            $order_id = $this->sales->insert_id('orders', $data_order);

            $order_items = [];
            $update_stock = [];
            foreach ($this->cart->contents() as $items) {
                $qty = $items['qty'];
                $order_items[] = [
                    'order_id'       => $order_id,
                    'product_id'     => $items['id'],
                    'qty'            => $qty,
                    'unit_price'     => $items['price_item'],
                    'total_discount' => $items['diskon'],
                    'sub_total'      => $items['subtotal'],
                ];

                $update_stock[] = [
                    'product_id'    => $items['id'],
                    'current_stock' => "current_stock - $qty",
                ];
            }
            $this->sales->insert_batch('order_items', $order_items);

            $data_payments = [
                'payment_type'    => 'in',
                'payment_number'  => $this->sales->generate_payment(),
                'payment_date'    => date('Y-m-d H:i'),
                'amount'          => $grand_total,
                'payment_mode_id' => $payload['method_pay'],
                'user_id'         => $user->user_id,
            ];

            if ($payload['status_pay'] == "PAID") {
                $data_payments['paid_amount']   = $grand_total;
                $data_payments['change_amount'] = 0;

                $this->sales->update_stock('product_details', $update_stock, 'product_id');
            } else {
                $data_payments['paid_amount']   = 0;
                $data_payments['change_amount'] = 0;
            }

            $payment_id = $this->sales->insert_id('payments', $data_payments);

            $data_order_payments = [
                'payment_id' => $payment_id,
                'order_id'   => $order_id,
                'amount'     => $grand_total,
            ];
            $this->sales->insert('order_payments', $data_order_payments);
            $output = [
                'success'   => 'true',
                'message'   => 'Pembelian berhasil, Alhamdulillah🤲',
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
            $this->cart->destroy();
        }

        return $output;
    }

    private function _update($payload, $target)
    {

        if (!$this->cart->contents()) {
            $output = [
                'error'     => 'true',
                'message'   => 'Anda harus menambahkan produk terlebih dahulu',
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {

            if ($payload['status_pay'] == "UNPAID" && $payload['order_status'] == "DELIVERED") {
                return [
                    'error'     => 'true',
                    'message'   => 'Order status tidak boleh terkirim jika status pembayaran masih belum dibayar.',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }

            $old_items    = array_map(fn ($old_items) => get_object_vars($old_items), $target->items);
            $order_items  = [];
            $new_order    = [];

            $update_stock = [];
            foreach ($this->cart->contents() as $items) {
                $qty  = $items['qty'];
                $item = [
                    'order_id'       => $target->id,
                    'product_id'     => $items['id'],
                    'qty'            => $qty,
                    'unit_price'     => $items['price_item'],
                    'total_discount' => $items['diskon'],
                    'sub_total'      => $items['subtotal'],
                ];

                if (array_key_exists("item_id", $items)) {
                    $item['order_item_id'] = $items['item_id'];
                    array_push($order_items, $item);
                } else {
                    array_push($new_order, $item);
                }
                $update_stock[] = [
                    'product_id'    => $items['id'],
                    'current_stock' => "current_stock - $qty",
                ];
            }

            if ($target->payment_status == "PAID" && $target->order_status != "DELIVERED") {
                $data = [
                    'order_status' => $payload['order_status'],
                    'notes'        => $payload['notes'],
                ];

                if ($payload['order_status'] == "DELIVERED") {
                    $this->sales->update_stock('product_details', $update_stock, 'product_id');
                }
                $this->sales->update('orders', ['order_id' => $target->id], $data);
            } else if ($target->payment_status == "UNPAID") {

                $grand_total = ($this->cart->total() - $payload['discount']) + $payload['shipping'];
                $user        = $this->users->find(['uuid' => base64_decode($payload['customer'])]);
                $data_order  = [
                    'order_date'     => date('Y-m-d H:i', strtotime($payload['date'])),
                    'user_id'        => $user->user_id,
                    'discount'       => $payload['discount'],
                    'cost_shipping'  => $payload['shipping'],
                    'sub_total'      => $this->cart->total(),
                    'grand_total'    => $grand_total,
                    'order_status'   => $payload['order_status'],
                    'payment_status' => $payload['status_pay'],
                    'notes'          => $payload['notes'],
                    'total_item'     => count($this->cart->contents()),
                    'total_quantity' => $this->cart->total_items(),
                ];
                $this->sales->update('orders', ['order_id' => $target->id], $data_order);

                $array_order = array_map(fn ($item) => $item['order_item_id'], $order_items);

                $remove_order = array_filter($old_items, fn ($item) => !in_array($item['item_id'], $array_order));
                $remove_order = array_map(fn ($item) => $item['item_id'], $remove_order);

                $update_order = array_filter($order_items, fn ($item) => !in_array($item['order_item_id'], $remove_order));

                if ($new_order) {
                    $this->sales->insert_batch('order_items', $new_order);
                }

                if ($remove_order) {
                    $this->sales->delete_multiple('order_items', 'order_item_id', $remove_order);
                }

                $this->sales->update_batch('order_items', $update_order, 'order_item_id');

                $data_payments = [
                    'payment_date'    => date('Y-m-d H:i:s'),
                    'amount'          => $grand_total,
                    'payment_mode_id' => $payload['method_pay'],
                    'user_id'         => $user->user_id,
                ];

                if ($payload['status_pay'] == "PAID") {
                    $data_payments['paid_amount']   = $grand_total;
                    $data_payments['change_amount'] = 0;

                    $this->sales->update_stock('product_details', $update_stock, 'product_id');
                } else if ($payload['status_pay'] == "UNPAID") {
                    $data_payments['paid_amount']   = 0;
                    $data_payments['change_amount'] = 0;
                }

                $this->sales->update('payments', ['payment_number' => $target->payment_number], $data_payments);

                $data_order_payments = [
                    'amount'     => $grand_total,
                ];
                $this->sales->update('order_payments', ['order_payment_id' => $target->order_pay_id], $data_order_payments);
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Maaf pesanan tidak bisa dirubah, karena sudah terkirim atau hal lainnya.',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }
            $output = [
                'success'   => 'true',
                'message'   => 'Pembelian berhasil diupdate, Alhamdulillah🤲',
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
            $this->cart->destroy();
        }

        return $output;
    }

    public function delete()
    {
        $id    = $this->input->post('target', TRUE);
        $sales = $this->sales->get_sales_by_id($id);
        if (!$sales) {
            $output = [
                'error'     => 'true',
                'message'   => 'Tidak ditemukan.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        } else {

            $this->sales->delete('orders', 'order_id', $sales->id);
            $index_delete = [];
            $update_stock = [];

            foreach ($sales->items as $item) {
                array_push($index_delete, $item->item_id);
                $update_stock[] = [
                    'product_id'    => $item->item_id,
                    'current_stock' => "current_stock - $item->item_id",
                ];
            }

            $this->sales->delete_multiple('order_items', 'order_item_id', $index_delete);
            if ($sales->order_status == "DELIVERED" && $sales->payment_status == "PAID") {
                $this->sales->update_stock('product_details', $update_stock, 'product_id');
            }
            $this->sales->delete('order_payments', 'order_payment_id', $sales->order_pay_id);
            $this->sales->delete('payments', 'payment_number', $sales->payment_number);

            $output = [
                'success'   => 'true',
                'message'   => 'Penjualan berhasil dihapus, terimakasih.',
                'csrf_hash' => $this->security->get_csrf_hash(),
            ];
        }

        return $this->output->set_content_type('application/json')->set_output(json_encode($output));
    }

    public function cart()
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {
            $output = [
                'success'   => 'true',
                'data'      => [
                    'items'       => $this->_load_cart(),
                    'total_items' => $this->cart->total_items(),
                    'total'       => $this->cart->total(),
                ],
                'csrf_hash' => $this->security->get_csrf_hash()
            ];

            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function add_cart()
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {
            $payload = strip_tags(htmlspecialchars($this->input->post('catalog_id', true) ?? ''));
            $product = $this->products->get_product_id($payload);

            if ($product && $product->current_stock > 0) {
                $data = [
                    'id'            => $product->product_id,
                    'name'          => $product->product_name,
                    'qty'           => 1,
                    'price'         => $product->selling_price,
                    'price_item'    => (float) $product->selling_price,
                    'image'         => base_url('public/image/products/') . $product->product_image,
                    'diskon'        => 0,
                ];
                $this->cart->product_name_rules = '\w \-\.\/\%\:';
                $this->cart->insert($data);
                $output = [
                    'success'   => 'true',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Produk tidak ada atau habis',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }


            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function update_cart()
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {
            $payload = [
                'id'        => strip_tags(htmlspecialchars($this->input->post('rowid', true) ?? '')),
                'type'      => strip_tags(htmlspecialchars($this->input->post('type', true) ?? '')),
                'qty'       => strip_tags(htmlspecialchars($this->input->post('qty', true) ?? '')),
                'diskon'    => strip_tags(htmlspecialchars($this->input->post('diskon_item', true) ?? '')) ?? 0,
            ];
            $check = $this->cart->get_item($payload['id']);
            if ($check) {
                if ($payload['type'] == 'item') {
                    $check_min_price = ($check['price_item'] * $check['qty']) - ($payload['diskon'] * $check['qty']);
                    if ($check_min_price < 0) {
                        $output = [
                            'error'     => 'true',
                            'message'   => 'Mohon Maaf diskon terlalu besar',
                            'csrf_hash' => $this->security->get_csrf_hash()
                        ];
                    } else {
                        $data = [
                            'rowid'  => $payload['id'],
                            'price'  => $check['price_item'] -  $payload['diskon'],
                            'diskon' => (float) $payload['diskon'],
                        ];
                        $this->cart->update($data);
                        $output = [
                            'success'   => 'true',
                            'message'   => 'Produk berhasil diperbarui',
                            'csrf_hash' => $this->security->get_csrf_hash()
                        ];
                    }
                }

                if ($payload['type'] == 'qty') {
                    if ($payload['qty'] < 0) {
                        $output = [
                            'error'     => 'true',
                            'message'   => 'Quantity tidak boleh kurang dari 0',
                            'csrf_hash' => $this->security->get_csrf_hash()
                        ];
                    } else {
                        $check_stock = $this->products->get_product_id($check['id']);
                        if ($payload['qty'] > $check_stock->current_stock) {
                            $output = [
                                'error'     => 'true',
                                'message'   => 'Mohon Maaf produk yang tersisa tinggal ' . $check_stock->current_stock,
                                'csrf_hash' => $this->security->get_csrf_hash()
                            ];
                        } else {
                            $data = [
                                'rowid'  => $payload['id'],
                                'qty'    => $payload['qty']
                            ];
                            $this->cart->update($data);
                            $output = [
                                'success'   => 'true',
                                'message'   => 'Produk berhasil diperbarui',
                                'csrf_hash' => $this->security->get_csrf_hash()
                            ];
                        }
                    }
                }
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Mau menambahkan apa cuy gk ada',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }


            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function remove_cart(String $type = 'cart')
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {

            $rowid = strip_tags(htmlspecialchars($this->input->post('rowid', true) ?? ''));

            if ($type === 'destroy') {
                $this->cart->destroy();
                $output = [
                    'success'   => 'true',
                    'message'   => 'Reset form berhasil',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }

            if ($type === 'cart') {
                if ($this->cart->get_item($rowid)) {
                    $this->cart->remove($rowid);
                    $output = [
                        'success'   => 'true',
                        'message'   => 'Produk berhasil dihapus',
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ];
                } else {
                    $output = [
                        'error'   => 'true',
                        'message'   => 'Mau menghapus apa cuy gk ada',
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ];
                }
            }

            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    public function setting_cart(String $type = 'cart')
    {
        if (!$this->input->is_ajax_request()) {
            show_404('No direct script access allowed');
        } else {
            if ($type === 'cart') {
                $payload = [
                    'discount'  => strip_tags(htmlspecialchars($this->input->post('discount', true) ?? '')),
                    'shipping'  => strip_tags(htmlspecialchars($this->input->post('shipping', true) ?? '')),
                    'subtotal'  => strip_tags(htmlspecialchars($this->input->post('subtotal', true) ?? '')),
                ];

                $result = ($this->cart->total() - (float) $payload['discount']) + (float) $payload['shipping'];
            }

            if ($type === 'pay') {
                $payload = [
                    'pay'  => strip_tags(htmlspecialchars($this->input->post('amount_pay', true) ?? '')),
                    'total'  => strip_tags(htmlspecialchars($this->input->post('total', true) ?? '')),
                ];
                $result = (float) $payload['pay'] - (float) $payload['total'];
            }

            $output = [
                'success'   => 'true',
                'result'    => $result,
                'csrf_hash' => $this->security->get_csrf_hash()
            ];

            return $this->output->set_content_type('application/json')->set_output(json_encode($output));
        }
    }

    private function _load_cart()
    {
        $data = [];
        foreach ($this->cart->contents() as $items) {
            $item = [
                'id'        => $items['id'],
                'name'      => $items['name'],
                'qty'       => $items['qty'],
                'price'     => $items['price'],
                'subtotal'  => $items['subtotal'],
                'image'     => $items['image'],
                'diskon'    => $items['diskon'],
                'rowid'     => $items['rowid'],
            ];
            array_push($data,  $item);
        }
        return $data;
    }

    function select_null($str)
    {
        if ($str == '') {
            $this->form_validation->set_message('select_null', 'Bagian {field} wajib diisi.');
            return FALSE;
        }
        return TRUE;
    }

    function amount_pay($str)
    {
        $payload = [
            'diskon'     => (float) strip_tags(htmlspecialchars($this->input->post('diskon_all', TRUE) ?? '')),
            'shipping'   => (float) strip_tags(htmlspecialchars($this->input->post('shipping', TRUE) ?? '')),
        ];
        $total = ($this->cart->total() - $payload['diskon']) + $payload['shipping'];

        if ($str < $total) {
            $this->form_validation->set_message('amount_pay', '{field} tidak sesuai jumlah yang harus dibayar.');
            return FALSE;
        }
        return TRUE;
    }

    function check_customer($str)
    {
        $check_user = $this->users->find(['uuid' => base64_decode($str)]);
        if (!$check_user) {
            $this->form_validation->set_message('check_customer', '{field} tidak ditemukan.');
            return FALSE;
        }

        return TRUE;
    }

    public function get_sales(String $id = '')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        } else {
            $target = strip_tags(htmlspecialchars($id ?? ''));
            $result = $this->sales->get_sales_by_id($target);
            if (!$result) {
                $message = [
                    'errors' => 'true',
                    'message' => 'Data tidak ditemukan',
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

    public function invoice($order_id)
    {
        $order = $this->sales->get_sales_by_id($order_id);
        if (!$order) {
            show_404();
        } else {
            $data['order'] = $order;
            $data['title'] = "Invoice";
            render_template_admin('admin/sales/invoice', $data);
        }
    }

    public function send_invoice()
    {
        $order_id = $this->input->post('order_id', TRUE);

        $order = $this->sales->get_sales_by_id($order_id);
        if (!$order) {
            $output = [
                'error'     => 'true',
                'message'   => 'Tidak ditemukan atau tidak ada.',
                'csrf_hash' => $this->security->get_csrf_hash()
            ];
        } else {
            if ($order->contact) {
                $token       = $this->settings->find(['option_name' => 'whatsapp_api'])->option_value;

                $shop        = $this->general->site_title;
                $shipping    = "Rp. " . number_format($order->shipping, 0, ',', '.');
                $grand_total = "Rp. " . number_format($order->total_amount, 0, ',', '.');
                $sub_total   = 0;
                $bank_an     = $this->general->bank_an;
                $bank_number = $this->general->bank_number;
                $message     = "Thank you for shopping with us!\nOrder from $shop 😍\n\n\nOrder item :\n";

                foreach ($order->items as $items) {
                    $sub_total += $items->subtotal;
                    $message .= $items->quantity . "x " . $items->product . " @Rp. " . number_format($order->total_amount, 0, ',', '.') . "\n\n";
                }

                $message .= "\nTotal harga barang : $sub_total\nOngkos kirim : $shipping\nTotal yang harus dibayar : $grand_total\n\nBCA $bank_number A.N $bank_an\n\nHarap mengirimkan foto bukti transfer kepada kami. Thank you so much, have a blissful day!🥰";

                $respoonse = json_decode(send_whastapp_message($token, $order->contact, $message));

                if ($respoonse->status) {
                    $output = [
                        'success'   => 'true',
                        'message'   => 'Invoice berhasil terkirim.',
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                } else {
                    $output = [
                        'error'     => 'true',
                        'message'   => $respoonse->reason,
                        'csrf_hash' => $this->security->get_csrf_hash(),
                    ];
                }
            } else {
                $output = [
                    'error'     => 'true',
                    'message'   => 'Customer ini tidak memiliki no. whatsapp',
                    'csrf_hash' => $this->security->get_csrf_hash()
                ];
            }
        }

        echo json_encode($output);
    }
}

/* End of file Sales.php */
