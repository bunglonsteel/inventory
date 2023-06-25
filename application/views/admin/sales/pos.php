<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?= $title ?> - TheMeatStuff</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/css/custom.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.css" />

    <script src="<?= base_url() ?>public/back/assets/vendor/js/helpers.js"></script>
    <script src="<?= base_url() ?>public/back/assets/js/config.js"></script>
    <script src="<?= base_url() ?>public/back/assets/vendor/libs/jquery/jquery.js"></script>
    <meta name="<?= $this->security->get_csrf_token_name() ?>" content="<?= $this->security->get_csrf_hash() ?>">
    </div>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper" style="background:var(--bs-body-bg)">

        <div class="container-fluid position-relative container-xxl">
            <div class="row bg-white mb-3">
                <div class="col-12">
                    <div class="py-3 px-lg-3">
                        <div class="d-flex align-items-center mb-2">
                            <a href="<?= base_url('admin/sales') ?>" class="btn rounded-pill btn-icon btn-dark me-3">
                                <span class="tf-icons bx bx-left-arrow-alt"></span>
                            </a>
                            <h5 class="mb-0 fw-bold">POINT OF SALES</h5>
                        </div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="<?= base_url('admin/sales') ?>">Sales</a>
                                </li>
                                <li class="breadcrumb-item active">Point Of Sales</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <form id="form-data">
                <div class="row">
                    <div class="col-md-6 position-relative">

                        <div class="card mb-3" style="box-shadow:none;">
                            <div class="card-body">
                                <label for="select-customer" class="form-label">Customer <span class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <select id="select-customer" class="form-select w-100" name="customer"></select>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3" style="box-shadow:none;">
                            <div class="card-body position-relative">
                                <div class="table-responsive cart-list overflow-y" style="overflow-y:auto;">
                                    <table class="table">
                                        <thead class="position-relative">
                                            <tr class="bg-white position-sticky top-0">
                                                <th style="padding-top:16px;padding-bottom:16px;">#</th>
                                                <th style="padding-top:16px;padding-bottom:16px;">Produk</th>
                                                <th style="padding-top:16px;padding-bottom:16px;">Quantity</th>
                                                <th style="padding-top:16px;padding-bottom:16px;">Subtotal</th>
                                                <th style="padding-top:16px;padding-bottom:16px;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-cart" class="table-border-bottom-0" style="border-top:1px solid #d9dee3;"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3" style="box-shadow:none;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="diskon-all" class="form-label">Diskon</label>
                                        <div class="input-group mb-2 mb-md-0">
                                            <label class="input-group-text">Rp.</label>
                                            <input type="number" id="diskon-all" class="form-control" name="diskon_all" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="shipping" class="form-label">Pengiriman</label>
                                        <div class="input-group">
                                            <label class="input-group-text">Rp.</label>
                                            <input type="number" id="shipping" class="form-control" name="shipping" value="0">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="divider divider-dashed my-1">
                                            <div class="divider-text">
                                                <i class="bx bx-sun"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row align-items-center">
                                            <div class="col-12 col-md-8 col-lg-7">
                                                <div class="bg-grey px-3 py-3 rounded mb-3 mb-sm-0">
                                                    <h4 class="mb-0">
                                                        <span class="fw-bold">Grand total : </span>
                                                        <span id="grand-total">Rp 0</span>
                                                    </h4>
                                                    <input type="hidden" id="total">
                                                    <input type="hidden" id="total-items">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-lg-5 text-sm-end d-flex d-md-block d-lg-flex flex-lg-row">
                                                <button id="modal-pay" type="button" class="btn d-sm-flex align-items-center d-lg-inline btn-primary me-2 me-sm-0 me-lg-2 mb-0 mb-sm-2 mb-lg-0">
                                                    <span class="tf-icons bx bx-credit-card me-0 me-sm-1 me-lg-0"></span> Bayar
                                                </button>
                                                <button id="reset-form" type="reset" class="btn d-sm-flex align-items-center d-lg-inline btn-danger">
                                                    <span class="tf-icons bx bx-sync me-0 me-sm-1 me-lg-0"></span> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3" style="box-shadow:none;">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                                            <input id="search" type="text" class="form-control" placeholder="Cari produk...">
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <select id="select-categories" class="form-select w-100"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="catalog" class="row g-3">
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
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

                <!-- Enable Backdrop -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="canvasPay" aria-labelledby="canvasPayLabel">
                    <div class="offcanvas-header">
                        <h5 id="canvasPayLabel" class="offcanvas-title">Pembayaran Pesanan</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0">
                        <div class="row mt-3">

                            <div class="col-7">
                                <span>Jumlah harus dibayar</span>
                                <h3 id="label-total" class="mt-1"></h3>
                            </div>
                            <div class="col-5 mb-3">
                                <span>Total Items</span>
                                <h3 id="label-total-items" class="mt-1">0</h3>
                            </div>
                            <div class="col-12">
                                <span>Jumlah kembalian</span>
                                <h3 id="label-kembalian" class="mt-1">Rp 0</h3>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Jumlah bayar <span class="text-danger">*</span></label>
                                <div class="input-group mb-2">
                                    <label class="input-group-text">Rp.</label>
                                    <input id="amount-pay" name="amount_pay" type="number" class="form-control" min="0" placeholder="50000">
                                </div>

                                <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                <select class="form-select mb-2" name="method_pay">
                                    <option value="" disabled selected>Choose...</option>
                                    <?php foreach ($payments as $pay) : ?>
                                        <option value="<?= $pay->payment_mode_id ?>"><?= $pay->payment_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" name="notes" rows="3" placeholder="Ketikan catatan..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="offcanvas-footer mt-auto mx-4 mb-4">
                        <button id="btn-pay" class="btn btn-lg w-100 btn-primary mt-auto">
                            <span class="tf-icons bx bx-credit-card me-0 me-sm-1 me-lg-0"></span> Bayar Sekarang
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <div id="toast-notif" data-bs-delay="2500" class="bs-toast toast toast-placement-ex m-2 bg-primary fade top-0 start-50 translate-middle-x" role="alert" aria-live="polite" aria-atomic="true">
        <div class="toast-header">
            <i class='bx bxs-bell-ring me-2'></i>
            <div id="toast-title" class="me-auto fw-semibold"></div>
        </div>
        <div id="toast-desc" class="toast-body"></div>
    </div>

    <script src="<?= base_url() ?>public/back/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url() ?>public/back/assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
    <script src="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.js"></script>
    <script>
        var targetToast = document.getElementById('toast-notif')
        var toastNotif = bootstrap.Toast.getOrCreateInstance(targetToast)
        var show_toast = function(title, desc) {
            toastNotif.show()
            $('#toast-notif #toast-title').text(title)
            $('#toast-notif #toast-desc').text(desc)
        }

        var formatRupiah = function(money) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(money);
        }
    </script>

    <script>
        'use strict'
        $(document).ready(function() {

            // Instance Modal
            const csrf = $('meta[name="csrf_token"')
            const modal = document.getElementById('modal')
            const myModal = new bootstrap.Modal(modal)

            const canvasPay = document.getElementById('canvasPay')
            const myCanvas = new bootstrap.Offcanvas(canvasPay)
            let page_url;
            /* Load */
            ajaxSelect('#select-categories', '<?= base_url() ?>admin/categories/select_categories')
            ajaxSelect('#select-customer', '<?= base_url() ?>admin/users/select_user')
            productsCatalog(page_url = false);
            product_cart()

            /* Catalog Products */
            $(document).on('input', "#search", function(e) {
                productsCatalog(page_url = false);
                e.preventDefault();
            });

            $(document).on('change', "#select-categories", function(e) {
                productsCatalog(page_url = false);
                e.preventDefault();
            });

            $(document).on('click', ".pagination li a", function(e) {
                var page_url = $(this).attr('href');
                productsCatalog(page_url);
                e.preventDefault();
            });

            function productsCatalog(page_url = false) {
                var search_key = $("#search").val();
                var categories = $("#select-categories").val() || '';

                var data = 'search_key=' + search_key + '&categories=' + categories + '&csrf_token=' + csrf.attr('content');
                var base_url = '<?= base_url('admin/sales/pos') ?>';

                if (page_url == false) {
                    var page_url = base_url;
                }

                $.ajax({
                    type: "POST",
                    url: page_url,
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        $('#catalog').html('')
                        if (res.data.products.length != 0) {
                            res.data.products.forEach(e => {
                                show_product_catalog(e.id, e.item, e.image, e.stock, e.price)
                            });
                        } else {
                            $('#catalog').prepend(`
                            <div class="col-12 ">
                                <div class="d-flex flex-column align-items-center py-5">
                                    <i class='bx bx-shopping-bag display-2'></i>
                                    <span class="text-muted">Maaf produk tidak ada</span>
                                </div>
                            </div>
                            `)
                        }
                        $('#catalog').append(res.data.pagination)
                        csrf.attr('content', res.csrf_hash)
                        $('.pagination .page-item a').addClass('page-link')
                    }
                });
            }

            function show_product_catalog(id, name, image, stock, price) {
                $('body').tooltip({
                    selector: '.name-product',
                    customClass: 'fs-7'
                });
                $('#catalog').prepend(`
                    <div class="col-md-4 col-6 col-lg-3">
                        <a class="add-product" href="javascript:void(0);" style="color:var(--bs-body-color)" data-id="${id}" data-stock="${stock}">
                            <div class="card h-100">
                                <div class="header">
                                    <div class="stock ${stock == 0 ? 'bg-danger text-white': ''} d-flex align-items-center justify-content-center">
                                        ${stock == 0 ? '<span>Habis</span>': "<i class='bx bx-basket me-1'></i><span>" + stock + "</span>"}</span>
                                    </div>
                                    <img style="height: 100px; object-fit:cover;" class="card-img-top" src="<?= base_url() ?>public/image/products/${image}" alt="Card image cap" />
                                    <button type="button" class="btn btn-sm btn-icon btn-light btn-add" style="z-index:1; box-shadow:0 3px 12px 0 rgba(0,0,0,0.15)">
                                        <span class="tf-icons bx bx-plus"></span>
                                    </button>
                                    <span class="fs-7 fw-bold position-absolute bottom-0 start-0 bg-white px-3 py-1" style="border-bottom: 1px solid #f2f2f2; border-radius:0 5px 0 0;">${formatRupiah(price)}</span>
                                </div>
                                <div class="card-body p-2">
                                    <p class="card-text text-limit-2 name-product" style="font-size:12px;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${name}">${name}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                `);
            }
            /* End Catalog Products */

            /* Cart */
            $(document).on('click', '.add-product', function(e) {
                e.preventDefault();

                if ($(this).data('stock') <= 0) {
                    show_toast('Mohon Maaf', 'Produk ini sudah habis')
                } else {
                    const data = {
                        catalog_id: $(this).data('id'),
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
                    $(this).data('stock', $(this).data('stock') - 1)
                }

            });

            $(document).on('click', '.btn-delete', function(e) {

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
            });

            $(document).on('click', '.edit-cart', function(e) {
                myModal.show()

                const name = $(this).data('name')
                const price = $(this).data('price')
                const diskon = $(this).data('diskon')
                const rowid = $(this).closest('tr').data('rowid');

                $('#title-item').text(name)
                $('#diskon-item').val(diskon)
                $('#target').val(rowid)

            });

            $(document).on('change', '#qty', function(e) {

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

            $(document).on('input', '#diskon-all , #shipping', function(e) {
                e.preventDefault();
                const diskonAll = $('#diskon-all').val() || 0
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

            $(document).on('click', '#modal-pay', function(e) {
                e.preventDefault();
                myCanvas.show()
                const grandTotal = $('#total').val()
                const totalItems = $('#total-items').val()

                $('#label-total').text(formatRupiah(grandTotal))
                $('#label-total-items').text(totalItems)
            });

            $(document).on('input', '#amount-pay', function(e) {
                const data = {
                    total: $('#total').val(),
                    amount_pay: $(this).val(),
                    csrf_token: csrf.attr('content')
                }
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/setting_cart/pay",
                    data: data,
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            $('#label-kembalian').text(formatRupiah(res.result))
                        }
                        csrf.attr('content', res.csrf_hash)
                    }
                });
            })

            $(document).on('submit', '#form-data', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/sales/pay",
                    data: $(this).serialize() + '&csrf_token=' + csrf.attr('content'),
                    dataType: "JSON",
                    success: function(res) {
                        if (res.success) {
                            show_toast('Buat pesanan', res.message)
                        }
                        if (res.error) {
                            show_toast('Mohon Maaf', res.message)
                        }
                        csrf.attr('content', res.csrf_hash)
                        myCanvas.hide()
                        product_cart()
                    }
                });
            })

            function product_cart() {
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
                            $('#grand-total').html(formatRupiah(res.data.total))
                            $('#total').val(res.data.total)
                            $('#total-items').val(res.data.total_items)
                            show_product_cart(res.data.items)
                        }

                        csrf.attr('content', res.csrf_hash)
                    }
                });

                $('body table').tooltip({
                    selector: '.name-product-cart',
                    customClass: 'fs-7'
                });
            }

            function show_product_cart(product) {
                $('#product-cart').html('')
                product.forEach((e, index) => {
                    $('#product-cart').append(`
                        <tr data-rowid="${e.rowid}">
                            <th scope="row">${index + 1}</th>
                            <td>
                                <div class="d-flex">
                                    <img class="me-2 rounded" src="${e.image}" style="width:40px;height:40px;object-fit:cover;">
                                    <span class="text-limit-2 name-product-cart" data-bs-toggle="tooltip" data-bs-placement="bottom" title="${e.name}">${e.name}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <input class="form-control text-center" id="qty" value="${e.qty}" type="text" style="width:60px;">
                            </td>
                            <td>
                                ${formatRupiah(e.subtotal)}
                            </td>
                            <td>
                                <div class="d-flex">
                                    <button class="btn btn-sm btn-icon btn-secondary edit-cart me-2" type="button" style="box-shadow:none;" data-name="${e.name}" data-price="${e.price}" data-diskon="${e.diskon}">
                                        <span class="tf-icons bx bx-edit"></span>
                                    </button>
                                    <button class="btn btn-sm btn-icon btn-danger btn-delete" type="button" style="box-shadow:none;">
                                        <span class="tf-icons bx bx-trash"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `);
                });
            }

            function cart_no_data() {
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

            function update_cart(data) {
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

                        setTimeout(() => {
                            myModal.hide()
                        }, 2000);
                    }
                });
            }

            function ajaxSelect(dom, url) {
                $(dom).select2({
                    placeholder: "Choose...",
                    maximumSelectionLength: 3,
                    selectOnClose: true,
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

            // modal.addEventListener('hide.bs.modal', event => {
            //     $('#diskon-item').val(0)
            // })
        });
    </script>
</body>

</html>