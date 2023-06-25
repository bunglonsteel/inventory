<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Penjualan</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">

                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-0 ms-2">Semua Penjualan</h5>
                        <a href="<?= base_url('admin/sales/manual') ?>" class="btn btn-sm btn-primary">
                            <span class="tf-icons bx bx-plus"></span>
                            Penjualan
                        </a>
                    </div>
                    <div class="card-datatable table-responsive overflow-x">
                        <table id="table" class="table dataTable table-striped table-sales" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="bg-white text-nowrap" style="z-index:10;">Invoice Number</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Tanggal Pesanan</th>
                                    <th class="text-nowrap">Status Pesanan</th>
                                    <th class="text-nowrap">Jumlah Total</th>
                                    <th class="text-nowrap">Pembayaran</th>
                                    <th class="text-nowrap">Kembalian</th>
                                    <th class="text-nowrap">Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Priview -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="preview" aria-labelledby="previewLabel">
    <div class="offcanvas-header">
        <div>
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="spinner-grow spinner-grow-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
</div>


<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.min.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/fixedColumn.dataTables.js"></script>

<script>
    $(document).ready(function() {

        const csrf = $('meta[name="csrf_token"')
        const table = $('#table')
        const notif = $('#notif')
        const canvas = new bootstrap.Offcanvas('#preview')

        table.DataTable({
            dom: '<"row"<"col-sm-6 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            drawCallback: function() {
                $('#example_paginate').addClass('pagination align-items-center justify-content-end');
                $('select').addClass('form-select')
                $('select').css('padding', '0.3rem 1.6rem 0.3rem 0.875rem')
                $('input[type="search"]').addClass('form-control')
            },
            language: {
                search: "",
                searchPlaceholder: "Search",
                paginate: {
                    previous: '←',
                    next: '→'
                },
                processing: `<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>`
            },
            responsive: true,
            fixedColumns: {
                left: 1,
            },
            scrollX: true,
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: '<?= base_url() ?>admin/sales',
                type: 'POST',
                data: function(e) {
                    e.csrf_token = csrf.attr('content');
                },
                dataSrc: function(e) {
                    csrf.attr('content', e.csrf_hash)
                    return e.data
                }
            },
            columnDefs: [{
                target: [8],
                orderable: false,
            }, ]
        });

        $(document).on('click', '.preview', function(e) {
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/sales/get_sales/" + $(this).data('id'),
                data: "csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function(res) {
                    if (res.success == "true") {
                        const date = new Date(Number(res.data.date))
                        const finalDate = `${date.getDate()} ${date.getMonth()} ${date.getFullYear()}`
                        const stat = {
                            'ORDERED': 'bg-label-info',
                            'CONFIRMED': 'bg-soft-success text-green',
                            'PROCESSING': 'bg-label-secondary',
                            'SHIPPING': 'bg-label-dark',
                            'DELIVERED': 'bg-label-primary'
                        };
                        let item = ''
                        res.data.items.forEach(e => {
                            item +=
                                `
                                <tr>
                                    <td>${e.product}</td>
                                    <td>${e.quantity}</td>
                                    <td>${e.price}</td>
                                    <td>${e.discount}</td>
                                    <td>${formatRupiah(e.subtotal)}</td>
                                </tr>
                            `
                        });
                        $('#preview').html(`
                            <div class="offcanvas-header">
                                <div>
                                    <h5 id="previewLabel" class="offcanvas-title">#${res.data.invoice}</h5>
                                    ${res.data.payment_status == "PAID" ? '<span class="badge bg-label-primary">'+res.data.payment_status+'</span>' : '<span class="badge bg-label-danger">'+res.data.payment_status+'</span>'}
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body flex-grow-0 overflow-y">
                                <div class="row mt-4">
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">CUSTOMER</span>
                                        <p class="mt-2">${res.data.customer}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">TGL PESANAN</span>
                                        <p class="mt-2">${finalDate}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase d-block">status pesanan</span>
                                        <p class="mt-2 badge ${stat[res.data.order_status]}">${res.data.order_status}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase d-block">status pembayaran</span>
                                        ${res.data.payment_status == "PAID" ? '<p class="mt-2 badge bg-label-primary">'+res.data.payment_status+'</p>' : '<p class="mt-2 badge bg-label-danger">'+res.data.payment_status+'</p>'}
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">DISKON</span>
                                        <p class="mt-2">${formatRupiah(res.data.discount)}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">PENGIRIMAN</span>
                                        <p class="mt-2">${formatRupiah(res.data.shipping)}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">Jumlah total</span>
                                        <p class="mt-2">${formatRupiah(res.data.total_amount)}</p>
                                    </div>
                                    <div class="col-6">
                                        <span class="fw-bold fs-7 text-uppercase">total pembayaran</span>
                                        <p class="mt-2">${formatRupiah(res.data.total_pay)}</p>
                                    </div>
                                    <div class="col-12">
                                        <div class="table-responsive text-nowrap mt-4 overflow-x">
                                            <table class="table mb-2">
                                                <thead class="bg-grey">
                                                    <tr>
                                                        <th>PRODUK</th>
                                                        <th>QTY</th>
                                                        <th>HARGA /UNIT</th>
                                                        <th>DISKON</th>
                                                        <th>SUBTOTAL</th>
                                                    </tr>
                                                </thead>
                                                <tbody style="border-top:1px solid #d9dee3">
                                                    ${item}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="offcanvas-footer px-4 pb-3 pt-3 mt-auto">
                                <a href="<?= base_url('admin/sales/invoice/') ?>${res.data.order_id}" type="button" class="btn btn-primary w-100">
                                    <span class="tf-icons bx bx-file"></span>
                                    Invoice
                                </a>
                            </div>
                        `)
                    }
                    canvas.show()
                    csrf.attr('content', res.csrf_hash)
                },
                error: function(xhr, status) {
                    console.log(xhr.responseText)
                }
            });
        });

        $(document).on('click', '.delete', function(e) {
            Swal.fire({
                html: `<span class="swalfire bg-soft-danger my-3">
                        <div class="swalfire-icon">
                            <i class='bx bx-trash text-danger'></i>
                        </div>
                    </span>
                    <div>
                        <h5 class="text-dark">Hapus</h5>
                        <p class="fs-6 mt-2">Anda yakin ingin menghapus penjualan ini?</p>
                    </div>`,
                customClass: {
                    content: 'p-3 text-center',
                    actions: 'justify-content-end mt-1 p-0',
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-soft-dark me-2'
                },
                width: 300,
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: false
            }).then((e) => {
                if (e.value) {
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url() ?>admin/sales/delete",
                        data: "target=" + $(this).data('id') + "&csrf_token=" + csrf.attr('content'),
                        dataType: "JSON",
                        success: function(res) {
                            csrf.attr('content', res.csrf_hash)
                            if (res.error) {
                                show_toast('Mohon maaf', res.message)
                            }
                            if (res.success) {
                                show_toast('Berhasil', res.message)
                                setTimeout(() => {
                                    table.DataTable().ajax.reload();
                                }, 1000);
                            }
                        }
                    });
                }
            })
        })

    })
</script>