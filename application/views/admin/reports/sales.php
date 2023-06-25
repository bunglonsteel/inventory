<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Laporan Penjualan</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Laporan penjualan</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Penjualan Bulan Ini</span>
                        <h3 class="card-title mb-1"><?= "Rp. " . htmlspecialchars(number_format($sales_by_month->total,0,',','.')) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Penjualan Tahun Ini</span>
                        <h3 class="card-title mb-1"><?= "Rp. " . htmlspecialchars(number_format($sales_by_year->total,0,',','.')) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Pengeluaran Bulan Ini</span>
                        <h3 class="card-title mb-1"><?= "Rp. " . htmlspecialchars(number_format($expense_by_month->total,0,',','.')) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Pengeluaran Tahun Ini</span>
                        <h3 class="card-title mb-1"><?= "Rp. " . htmlspecialchars(number_format($expense_by_year->total,0,',','.')) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-md-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-3 mb-md-0 ms-2">Daftar Penjualan</h5>
                        <div class="d-flex align-items-center mx-2 mx-md-0">
                            <input id="start-date" class="form-control" type="date" name="start_date" placeholder="dd/mm/yyyy">
                            <span class="mx-2">-</span>
                            <input id="end-date" class="form-control" type="date" name="end_date" placeholder="dd/mm/yyyy">
                            <button id="filter" type="button" class="btn btn-icon btn-primary ms-2">
                                <span class="tf-icons bx bx-filter-alt"></span>
                            </button>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 90%">Tanggal</th>
                                    <th class="text-nowrap">Jumlah Penjualan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.min.js"></script>
<script>
    $(function () {
        const csrf       = $('meta[name="csrf_token"')
        const formTable  = $('#table')

        formTable.DataTable({
            dom: '<"row align-items-center"<"col-6 col-md-6"l><"col-6 col-md-6"<"#grand-total.text-center text-lg-end">>>t<"row align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            drawCallback: function () {
                $('select').addClass('form-select')
                $('select').css('padding','0.3rem 1.6rem 0.3rem 0.875rem')
                $('input[type="search"]').addClass('form-control')
                $('select[name="table_length"]').append('<option value="1000">1000</option>')
            },
            language: { 
                search: "",
                searchPlaceholder: "Search",
                paginate: {previous: '←', next: '→'},
                processing:`<div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div>`
            },
            responsive: true,
            scrollX:true,
            processing: true,
            serverSide: true,
            deferRender: true,
            ajax: {
                url: '<?= base_url() ?>admin/reports',
                type: 'POST',
                data: function (e) {
                    e.csrf_token        = csrf.attr('content');
                    e.start_date_filter = $('#start-date').val()
                    e.end_date_filter   = $('#end-date').val()
                },
                dataSrc: function (e){
                    $('#grand-total').html(`<span>Subtotal Pendapatan</span><h3>${formatRupiah(e.grand_total)}</h3>`)
                    csrf.attr('content', e.csrf_hash)
                    return e.data
                }
            },
        });

        $('#filter').on('click', function(e){
            const startDateFilter = $('#start-date').val()
            const endDateFilter   = $('#start-date').val()

            if (startDateFilter && endDateFilter) {
                formTable.DataTable().draw()
            } else {
                show_toast('Peringatan', 'Filter tanggal tidak boleh kosong')
            }
        })
    });
</script>