<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Laporan Stock</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Laporan Stock</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-warning border-1">
                    <small>- Untuk mengirim whatsapp masukan seperti : <strong>08123456789</strong></small>
                </div>
                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-md-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-3 mb-md-0 ms-2">Daftar Stock</h5>
                        <div class="d-flex align-items-center mx-2 mx-md-0">
                            <input id="no-whatsapp" class="form-control" type="tel" name="whatsapp" placeholder="Kirim whatsapp" required onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57">
                            <button id="send-stock-whatsapp" type="button" class="btn btn-icon btn-primary ms-2">
                                <span class="tf-icons bx bx-paper-plane"></span>
                            </button>
                        </div>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 90%">Produk</th>
                                    <th class="text-nowrap">Stock</th>
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
            dom: '<"row align-items-center"<"col-sm-6 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            drawCallback: function () {
                $('select').addClass('form-select')
                $('select').css('padding','0.3rem 1.6rem 0.3rem 0.875rem')
                $('input[type="search"]').addClass('form-control')
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
                url: '<?= base_url() ?>admin/reports/stock',
                type: 'POST',
                data: function (e) {
                    e.csrf_token = csrf.attr('content');
                },
                dataSrc: function (e){
                    csrf.attr('content', e.csrf_hash)
                    return e.data
                }
            },
        });

        const notifyAjax = (res) => {
            
            console.log(res)
            if(res.errors){
                $('#notif').html(res.message).show()
            }
            if(res.error){
                show_toast('Mohon maaf', res.message)
            }
            if(res.success){
                show_toast('Berhasil', res.message)
            }
            csrf.attr('content', res.csrf_hash)
        }

        $('#send-stock-whatsapp').click(function (e) { 
            e.preventDefault();
            const noWhatsapp = $('input[name="whatsapp"]').val()
            $.ajax({
                type: "POST",
                url: "<?= base_url('admin/reports/send_whatsapp') ?>",
                data: `whatsapp=${noWhatsapp}&csrf_token=${csrf.attr('content')}`,
                dataType:"JSON",
                success: function (res) {
                    notifyAjax(res)
                }
            });
        });

    });
</script>