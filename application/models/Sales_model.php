<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Sales_model extends CI_Model
{

    private $orderable = ['name', 'invoice_number', 'order_date', 'order_status', 'grand_total', 'paid_amount', 'change_amount', 'payment_status'];

    private function join_table()
    {
        $this->db->from('order_payments as order_pay')
            ->join('orders', 'orders.order_id = order_pay.order_id')
            ->join('users as user', 'orders.user_id = user.user_id')
            ->join('payments as pay', 'order_pay.payment_id = pay.payment_id')
            ->join('payment_modes as pay_mode', 'pay_mode.payment_mode_id = pay.payment_mode_id');
    }

    private function _get_query()
    {
        $this->db->select('
                orders.unique_id as order_id,
                orders.invoice_number as invoice,
                orders.order_type as type,
                orders.order_date as date,
                orders.order_status,
                orders.payment_status,
                orders.grand_total as total_amount,
                pay.paid_amount as total_pay,
                pay.change_amount as total_change,
                user.name as customer,
                ');
        $this->join_table();
        if (strip_tags(htmlspecialchars($_POST['search']['value']))) {
            $this->db->like('user.' . $this->orderable[0], strip_tags(htmlspecialchars($_POST['search']['value'])));
            $this->db->or_like('orders.' . $this->orderable[1], strip_tags(htmlspecialchars($_POST['search']['value'])));
        }

        if ($_POST['order'][0]['column']) {
            $this->db->order_by($this->orderable[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
        } else {
            $this->db->order_by('orders.order_id', 'DESC');
        }
    }

    public function result_data()
    {
        $this->_get_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_query();
        return $this->db->get()->num_rows();
    }

    public function count_all_result_value()
    {
        $this->_get_query();
        return $this->db->count_all_results();
    }

    public function generate_invoice()
    {
        $query = "SELECT MAX(MID(invoice_number, 14, 4)) AS invoice
                    FROM orders
                    WHERE MID(invoice_number, 8, 6) = DATE_FORMAT(CURDATE(), '%y%m%d')";
        $result = $this->db->query($query);
        // var_dump($query->row());die;
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $temp_no = ((int) $row->invoice) + 1;
            $no = sprintf("%'.04d", $temp_no);
        } else {
            $no = "0001";
        }
        return "TMS-INV" . date('ymd') . $no;
    }

    public function generate_payment()
    {
        $query = "SELECT MAX(MID(payment_number, 14, 4)) AS pay_number
                    FROM payments
                    WHERE MID(payment_number, 8, 6) = DATE_FORMAT(CURDATE(), '%y%m%d')";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $temp_no = ((int) $row->pay_number) + 1;
            $no = sprintf("%'.04d", $temp_no);
        } else {
            $no = "0001";
        }
        return "TMS-PAY" . date('ymd') . $no;
    }

    public function get_payment_method()
    {
        return $this->db->get('payment_modes')->result();
    }

    public function get_sales_by_id($id = '')
    {
        $this->db->select('
                orders.order_id as id,
                orders.unique_id as order_id,
                orders.invoice_number as invoice,
                orders.order_type as type,
                orders.order_date as date,
                orders.discount,
                orders.cost_shipping as shipping,
                orders.order_status,
                orders.notes,
                orders.payment_status,
                orders.grand_total as total_amount,
                order_pay.order_payment_id as order_pay_id,
                pay.payment_number,
                pay.paid_amount as total_pay,
                pay.change_amount as total_change,
                pay_mode.payment_mode_id as paymode_id,
                user.name as customer,
                user.contact,
                user.uuid as userid,
                ');
        $this->join_table();
        $this->db->join('order_items as oi', 'oi.order_id = orders.order_id');
        $this->db->where('orders.unique_id', $id);
        $sales =  $this->db->get()->row();

        if (!$sales) {
            return 0;
        }

        $item = $this->db->select('
                oi.order_item_id as item_id,
                oi.qty as quantity,
                oi.unit_price as price,
                oi.total_discount as discount,
                oi.sub_total as subtotal,
                pd.product_id,
                pd.product_name as product,
                pd.product_image as image,
                ')
            ->from('order_items as oi')
            ->join('product_details as pd', 'oi.product_id = pd.product_id')
            ->where('oi.order_id', $sales->id)
            ->get()
            ->result();
        $sales->date   = strtotime($sales->date);
        $sales->userid = base64_encode($sales->userid);
        $sales->items  = $item;
        return $sales;
    }

    public function insert($table, $data)
    {
        $this->db->insert($table, $data);
    }

    public function update($table, $where, $data)
    {
        $this->db->update($table, $data, $where);
    }

    public function insert_id($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function insert_batch($table, $data)
    {
        $this->db->insert_batch($table, $data);
    }

    public function update_batch($table, $data, $where)
    {
        $this->db->update_batch($table, $data, $where);
    }

    public function update_stock($table, $data, $where)
    {
        $this->db->set_update_batch($data, 'current_stock', false);
        $this->db->update_batch($table, null, $where);
        // return $this->db->last_query();
    }

    public function delete($table, $where, $value)
    {
        $this->db->where($where, $value);
        $this->db->delete($table);
    }

    public function delete_multiple($table, $where, $array)
    {
        $this->db->where_in($where, $array);
        $this->db->delete($table);
    }

    public function count_amount_sales($type = 'day')
    {
        $this->db->select('SUM(amount) as amount')->from('payments');
        if ($type == 'day') {
            $this->db->where('DATE(payment_date)', date('Y-m-d'));
        } else if ($type == "last_day") {
            $this->db->where('DATE(payment_date) <', date('Y-m-d'));
            $this->db->where('DATE(payment_date) >=', date('Y-m-d', strtotime('-1 day')));
        } else if ($type == "month") {
            $this->db->where('MONTH(payment_date) =', date('m'));
            $this->db->where('YEAR(payment_date) =', date('Y'));
        } else if ($type == "last_month") {
            $this->db->where('MONTH(payment_date) =', date('m', strtotime('-1 month')));
            $this->db->where('YEAR(payment_date) =', date('Y'));
        }
        return $this->db->get()->row();
        // $this->db->last_query();
    }

    public function count_sales()
    {
        $this->db->select('MONTH(payment_date) AS month, SUM(amount) AS total_sales');
        $this->db->from('payments');
        $this->db->where('YEAR(payment_date)', date('Y'));
        $this->db->group_by('MONTH(payment_date)');
        $query = $this->db->get();
        return $query->result();
    }
}

/* End of file Sales_model.php */
