<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Edit Penjualan</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/sales') ?>">Sales</a>
                    </li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <form id="form-data" data-id="<?= $target_sales->order_id ?>" class="row">
            <div class="col-12 mt-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="invoice" class="form-label">Nomor Invoice</label>
                                <input disabled id="invoice" class="form-control" type="text" name="inv" value="<?= $target_sales->invoice ?>" placeholder="Ketikan Nomor Invoice">
                                <small style="font-size:11px">*Biarkan kosong secara otomatis dibuatkan</small>
                            </div>
                            <div class="col-md-4">
                                <label for="select-customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select <?= $target_sales->payment_status == "PAID" ? "disabled" : '' ?> id="select-customer" class="form-select w-100" name="customer">
                                        <option value="<?= $target_sales->userid ?>" selected><?= $target_sales->customer ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="select-date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input <?= $target_sales->payment_status == "PAID" ? "readonly" : '' ?> id="select-date" class="form-control" type="datetime-local" name="date" value="<?= date('Y-m-d\TH:i', $target_sales->date) ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-3 p-2 pt-3 p-md-1">
                    <div class="card-body position-relative">
                        <select id="select-product">
                            <option></option>
                        </select>
                        <div class="table-responsive cart-list overflow-y mt-3 text-nowrap" style="overflow-y:auto;">
                            <table class="table">
                                <thead class="bg-white position-sticky top-0" style="z-index:1;">
                                    <tr>
                                        <th style="padding-top:16px;padding-bottom:16px;">#</th>
                                        <th style="padding-top:16px;padding-bottom:16px;">Produk</th>
                                        <th style="padding-top:16px;padding-bottom:16px;">Quantity</th>
                                        <th style="padding-top:16px;padding-bottom:16px;">Subtotal</th>
                                        <th style="padding-top:16px;padding-bottom:16px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="product-cart" class="table-border-bottom-0" style="border-top:1px solid #d9dee3;">

                                </tbody>
                                <tfoot class="bg-white position-sticky bottom-0" style="border-top:1px solid #d9dee3;">
                                    <tr>
                                        <th colspan="2" style="padding-top:16px;padding-bottom:16px;"></th>
                                        <th style="padding-top:16px;padding-bottom:16px;">Subtotal</th>
                                        <th id="subtotal" colspan="2" style="padding-top:16px;padding-bottom:16px;">Rp 0</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-2 g-md-3">
                            <div class="col-md-6">
                                <label for="discount-all" class="form-label">Diskon</label>
                                <div class="input-group mb-2 mb-md-0">
                                    <label class="input-group-text">Rp.</label>
                                    <input <?= $target_sales->payment_status == "PAID" ? "disabled" : '' ?> type="number" id="discount-all" class="form-control" name="discount_all" value="0" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="shipping" class="form-label">Pengiriman</label>
                                <div class="input-group mb-2 mb-md-0">
                                    <label class="input-group-text">Rp.</label>
                                    <input <?= $target_sales->payment_status == "PAID" ? "disabled" : '' ?> type="number" id="shipping" class="form-control" name="shipping" value="0" min="0">
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <label for="order-status" class="form-label">Order Status <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <?php
                                    $order_stat = [
                                        'ORDERED'    => "Dipesan",
                                        'CONFIRMED'  => "Dikonfimasi",
                                        'PROCESSING' => "Diproses",
                                        'SHIPPING'   => "Dikirim",
                                        'DELIVERED'  => "Diterima"
                                    ]
                                    ?>
                                    <select <?= $target_sales->order_status == "DELIVERED" ? "disabled" : '' ?> id="order-status" class="form-select" name="order_status">
                                        <option value="" hidden>Choose</option>
                                        <?php foreach ($order_stat as $key => $stat) : ?>
                                            <option value="<?= $key ?>" <?= $target_sales->order_status == $key ? "selected" : '' ?>><?= $stat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-4">
                                <label for="payment-method" class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <div class="d-flex mb-2 mb-md-0">
                                    <select <?= $target_sales->payment_status == "PAID" ? "disabled" : '' ?> id="payment-method" class="form-select w-100" name="method_pay">
                                        <option value="" hidden>Choose...</option>
                                        <?php foreach ($payments as $pay) : ?>
                                            <option value="<?= $pay->payment_mode_id ?>" <?= $target_sales->paymode_id == $pay->payment_mode_id ? "selected" : '' ?>><?= $pay->payment_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg-4">
                                <label for="payment-status" class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                <div class="d-flex mb-2 mb-md-0">
                                    <select <?= $target_sales->payment_status == "PAID" ? "disabled" : '' ?> id="payment-status" class="form-select w-100" name="status_pay">
                                        <option value="" hidden>Choose</option>
                                        <?php if ($target_sales->payment_status == "PAID") : ?>
                                            <option value="PAID" selected>Sudah Bayar</option>
                                            <option value="UNPAID">Belum Bayar</option>
                                        <?php else : ?>
                                            <option value="PAID">Sudah Bayar</option>
                                            <option value="UNPAID" selected>Belum Bayar</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-12 col-md-7 col-lg-8">
                                        <label for="shipping" class="form-label">Catatan</label>
                                        <textarea class="form-control mb-3 mb-md-0" <?= $target_sales->payment_status == "PAID" && $target_sales->order_status == "DELIVERED" ? "disabled" : '' ?> name="notes" rows="3" placeholder="Catatan"><?= $target_sales->notes ?></textarea>
                                    </div>
                                    <div class="col-12 col-md-5 col-lg-4 align-self-end">
                                        <h3>
                                            <span class="fw-bold">Total : </span>
                                            <span id="grand-total">Rp 0</span>

                                            <input type="hidden" id="total">
                                            <input type="hidden" id="total-items">
                                        </h3>
                                        <button id="btn-save" class="btn btn-primary me-2" <?= $target_sales->order_status == "DELIVERED" ? "disabled" : '' ?>>
                                            <span class="tf-icons bx bx-save me-0 me-sm-1 me-lg-0"></span> Simpan
                                        </button>
                                        <button type="reset" id="reset-form" class="btn btn-danger" <?= $target_sales->order_status == "DELIVERED" ? "disabled" : '' ?>>
                                            <span class="tf-icons bx bx-sync me-0 me-sm-1 me-lg-0"></span> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal-item" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-title w-75">
                    <p id="title-item" class="mb-0 text-limit-2 fs-7"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="diskon-item" class="form-label" for="diskon-item">Diskon / item</label>
                        <div class="input-group">
                            <label class="input-group-text" for="diskon-item">Rp.</label>
                            <input type="number" id="diskon-item" class="form-control" min="0">
                        </div>
                        <small><em>Simulasi ( harga produk * qty ) - ( diskon * qty )</em></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input id="target" type="hidden">
                <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="save-item">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.js"></script>

<script>
    $(document).ready(function() {
        const paid = "<?= $target_sales->payment_status ?>";
        const csrf = $('meta[name="csrf_token"')
        const iTem = document.getElementById('modal-item')
        const modalItem = new bootstrap.Modal(iTem)

        const ajaxSelect = (dom, url, placeholder) => {
            $(dom).select2({
                placeholder: placeholder,
                maximumSelectionLength: 3,
                width: "100%",
                ajax: {
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 500,
                    minimumInputLength: 4,
                    data: function(params) {
                        return {
                            select_search: params.term,
                            csrf_token: csrf.attr('content')
                        }
                    },
                    processResults: function(res) {
                        csrf.attr('content', res.csrf_hash)
                        return {
                            results: $.map(res.data, function(item) {
                                return {
                                    id: item.id,
                                    text: item.name
                                }
                            })
                        };
                    }
                }
            });
        }

        const cart_no_data = () => {
            $('#product-cart').html('')
            $('#product-cart').append(`
            <tr id="no-data">
                    <td colspan="5" class="text-center p-5">
                        <img class="mb-2" src="<?= base_url() ?>public/image/default/no-data.png" alt="">
                        <span class="text-muted d-block">Produk masih kosong</span>
                    </td>
                </tr>
            `);
        }

        const product_cart = () => {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/sales/cart",
                data: {
                    csrf_token: csrf.attr('content')
                },
                dataType: "JSON",
                success: function(res) {
                    if (res.data.items.length == 0) {
                        cart_no_data()
                    } else {
                        $('#subtotal').html(formatRupiah(res.data.total))
                        $('#grand-total').html(formatRupiah(res.data.total))
                        $('#total').val(res.data.total)
                        $('#total-items').val(res.data.total_items)
                        show_product_cart(res.data.items)
                    }
                    csrf.attr('content', res.csrf_hash)
                }
            });
        }

        const update_cart = (data) => {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/sales/update_cart",
                data: data,
                dataType: "JSON",
                success: function(res) {
                    if (res.success) {
                        show_toast('Berhasil', res.message)
                    }

                    if (res.error) {
                        show_toast('Mohon Maaf', res.message)
                    }
                    csrf.attr('content', res.csrf_hash)
                    product_cart()
                }
            });
        }

        const show_product_cart = (product) => {
            $('#product-cart').html('')
            product.forEach((e, index) => {
                $('#product-cart').append(`
                    <tr data-rowid="${e.rowid}">
                        <th scope="row">${index + 1}</th>
                        <td>
                            <div class="d-flex align-items-center">
                                <img class="me-2 rounded" src="${e.image}" style="width:40px;height:40px;object-fit:cover;">
                                <span class="text-limit-2 name-product-cart" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${e.name}">${e.name}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <input ${paid == "PAID" ? "disabled" : ''} class="form-control text-center" id="quantity" value="${e.qty}" type="text" style="width:60px;">
                        </td>
                        <td>
                            ${formatRupiah(e.subtotal)}
                        </td>
                        <td>
                            <div class="d-flex">
                                <button ${paid == "PAID" ? "disabled" : ''} class="btn btn-sm btn-icon btn-secondary edit-cart me-2" type="button" style="box-shadow:none;" data-name="${e.name}" data-price="${e.price}" data-diskon="${e.diskon}">
                                    <span class="tf-icons bx bx-edit"></span>
                                </button>
                                <button ${paid == "PAID" ? "disabled" : ''} class="btn btn-sm btn-icon btn-danger btn-delete" type="button" style="box-shadow:none;">
                                    <span class="tf-icons bx bx-trash"></span>
                                </button>
                            </div>
                        </td>
                    </tr>
                `);
            });
        }

        if (paid == "UNPAID") {

            $(document).on('click', '.edit-cart', function(e) {
                modalItem.show()

                const name = $(this).data('name')
                const price = $(this).data('price')
                const diskon = $(this).data('diskon')
                const rowid = $(this).closest('tr').data('rowid');

                $('#title-item').text(name)
                $('#diskon-item').val(diskon)
                $('#target').val(rowid)

            });

            $(document).on('change', '#quantity', function(e) {
                const data = {
                    rowid: $(this).closest('tr').data('rowid'),
                    qty: $(this).val(),
                    type: 'qty',
                    csrf_token: csrf.attr('content')
                }
                update_cart(data)
            });

            $(document).on('click', '#save-item', function(e) {
                e.preventDefault();
                const data = {
                    rowid: $('#target').val(),
                    type: 'item',
                    diskon_item: $('#diskon-item').val(),
                    csrf_token: csrf.attr('content')
                }
                update_cart(data)
            });

            $(document).on('click', '#reset-form', function(e) {
                $("#select-customer").html('');
                const data = {
                    csrf_token: csrf.attr('content')
                }
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/remove_cart/destroy",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            show_toast('Berhasil', res.message)
                            cart_no_data()
                            $('#grand-total').html('Rp 0')
                            $('#total').val(0)
                            $('#total-items').val(0)
                        }
                        csrf.attr('content', res.csrf_hash)
                    }
                });
            });

            $(document).on('input', '#discount-all , #shipping', function(e) {
                e.preventDefault();
                const diskonAll = $('#discount-all').val() || 0
                const shipping = $('#shipping').val() || 0
                const subtotal = $('#total').val() || 0

                const data = {
                    discount: diskonAll,
                    shipping: shipping,
                    subtotal: subtotal,
                    csrf_token: csrf.attr('content')
                }

                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/setting_cart",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            $('#grand-total').text(formatRupiah(res.result))
                            $('#total').val(res.result)
                        }
                        csrf.attr('content', res.csrf_hash)
                    }
                });
            });

            $('#select-product').on('select2:select', function(e) {
                e.preventDefault();
                const data = {
                    catalog_id: e.params.data.id,
                    csrf_token: csrf.attr('content')
                }

                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/add_cart",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        if (res.error) {
                            show_toast('Mohon Maaf', res.message)
                        }
                        csrf.attr('content', res.csrf_hash)
                        product_cart()
                    }
                });
            });

            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault()

                const data = {
                    rowid: $(this).closest('tr').data('rowid'),
                    csrf_token: csrf.attr('content')
                }
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/remove_cart",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            show_toast('Berhasil', res.message)
                        }

                        if (res.error) {
                            show_toast('Mohon Maaf', res.message)
                        }
                        csrf.attr('content', res.csrf_hash)
                        product_cart()
                    }
                });
            })
        }

        $(document).on('submit', '#form-data', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/sales/manual/update/" + $(this).data('id'),
                data: $(this).serialize() + '&csrf_token=' + csrf.attr('content'),
                dataType: "JSON",
                success: function(res) {
                    if (res.success) {
                        show_toast('Buat pesanan', res.message)
                        setTimeout(_ => {
                            window.location = "<?= base_url('admin/sales') ?>";
                        }, 500)
                    }
                    if (res.error) {
                        show_toast('Mohon Maaf', res.message)
                    }
                    csrf.attr('content', res.csrf_hash)
                    product_cart()
                }
            });
        })

        $(document).on('click', '#reset-form', function(e) {
            $("#select-customer").html('');
            const data = {
                csrf_token: csrf.attr('content')
            }
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/sales/remove_cart/destroy",
                data: data,
                dataType: "JSON",
                success: function(res) {
                    if (res.success) {
                        show_toast('Berhasil', res.message)
                        cart_no_data()
                        $('#subtotal').html('Rp 0')
                        $('#grand-total').html('Rp 0')
                        $('#total').val(0)
                        $('#total-items').val(0)
                        $('#form-data')[0].reset()
                    }
                    csrf.attr('content', res.csrf_hash)
                }
            });
        });

        product_cart()
        ajaxSelect('#select-customer', '<?= base_url() ?>admin/users/select_user', 'Choose...')
        ajaxSelect('#select-product', '<?= base_url() ?>admin/products/select_product', 'Pilih Produk...')
    });
</script>