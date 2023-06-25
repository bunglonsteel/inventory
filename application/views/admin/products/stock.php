<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Stock Produk</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/stock') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Stock</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">
                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-0 ms-2">Daftar Stock </h5>
                        <button id="add-action" type="button" class="btn btn-sm btn-primary">
                            <span class="tf-icons bx bx-plus"></span>
                            Stock
                        </button>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table table-responsive dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>QTY</th>
                                    <th>Tanggal</th>
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

<!-- Modal-->
<div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="form-data" class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center">
                    <h5 class="modal-title me-2" id="title"></h5>
                    <div id="loading" class="spinner-border spinner-border-sm" role="status" style="display:none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notif" style="display:none;"></div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Pilih Produk <span class="text-danger">*</span></label>
                        <select name="product" id="select-product">
                            <option></option>
                        </select>
                    </div>

                    <div class="col-4">
                        <label class="form-label">Stock</label>
                        <input class="form-control" type="text" value="-" readonly >
                    </div>
                    <div class="col-4">
                        <label class="form-label">Qty <span class="text-danger">*</span></label>
                        <input type="number" name="qty" class="form-control" value="1" min="0">
                    </div>
                    <div class="col-4">
                        <label class="form-label">type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select py-2">
                            <option value="in">Tambah</option>
                            <option value="out">Kurang</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Catatan"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="target">
                <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="btn-save"></button>
            </div>
        </form>
    </div>
</div>

<script async src="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        const csrf          = $('meta[name="csrf_token"')
        const formTable     = $('#table')
        const formData      = $('#form-data')
        const modalTitle    = $('#title')
        const btnSave       = $('#btn-save')
        const notif         = $('#notif')
        let saveAction;

        // Modal
        const modal = document.getElementById('modal')
        const myModal = new bootstrap.Modal(modal)

        formTable.DataTable({
            dom: '<"row"<"col-sm-6 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            drawCallback: function () {
                $('#example_paginate').addClass('pagination align-items-center justify-content-end');
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
                url: '<?= base_url() ?>admin/stock',
                type: 'POST',
                delay : 2000,
                data: function (e) {
                    e.csrf_token = csrf.attr('content');
                },
                dataSrc: function (e){
                    csrf.attr('content', e.csrf_hash)
                    return e.data
                }
            },
            columnDefs:[
                {
                    target:[2],
                    orderable:false,
                },
            ]
        });

        $(document).on('click', '#add-action', function (e) { 
            e.preventDefault();
            formModal('add', 'Tambah Stock', 'Tambah')
            ajaxSelect('#select-product', '<?= base_url() ?>admin/products/select_product')
        });

        $(document).on('click', '.delete', function(e){
            Swal.fire({
                html:
                    `<span class="swalfire bg-soft-danger my-3">
                        <div class="swalfire-icon">
                            <i class='bx bx-trash text-danger'></i>
                        </div>
                    </span>
                    <div>
                        <h5 class="text-dark">Hapus</h5>
                        <p class="fs-6 mt-2">Stok akan mengurangi atau menambah sisa stok produk saat ini tergantung (-/+) stok yang anda ingin hapus sekarang, Anda yakin ingin melanjutkan?</p>
                    </div>`,
                customClass: {
                    content: 'p-3 text-center',
                    actions: 'justify-content-end mt-1 p-0',
                    confirmButton: 'btn btn-primary',
                    cancelButton: 'btn btn-soft-dark me-2'
                },
                width:300,
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                buttonsStyling: false
            }).then((e) => {
                if(e.value){
                    $.ajax({
                        type: "POST",
                        url: "<?= base_url() ?>admin/stock/action/delete",
                        data: "target=" + $(this).data('id') + "&csrf_token=" + csrf.attr('content'),
                        dataType: "JSON",
                        success: function (res) {
                            notifyAjax(res)
                        }
                    });
                }
            })
        })

        btnSave.click(function (e) { 
            e.preventDefault();
            let url;
            if(saveAction == 'add'){
                url = "<?= base_url() ?>admin/stock/action/add";
            }
            
            $.ajax({
                type: "POST",
                url: url,
                data: formData.serialize() + "&csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function (res) {
                    notifyAjax(res)
                }
            });
        });

        function ajaxSelect(dom, url, placeholder = 'Choose...'){
            $(dom).select2({
                width:"100%",
                placeholder: placeholder,
                maximumSelectionLength: 3,
                selectOnClose: true,
                dropdownParent: $('#modal'),
                ajax: {
                    url : url,
                    type:'POST',
                    dataType: 'JSON',
                    delay: 500,
                    chace: false,
                    minimumInputLength: 4,
                    data: function (params) {
                        return {
                            select_search: params.term,
                            csrf_token: $('meta[name="csrf_token"').attr('content')
                        }
                    },
                    processResults: function (res) {
                        $('meta[name="csrf_token"').attr('content', res.csrf_hash)
                        return {
                            results: $.map(res.data, function(item){
                                return {
                                    id : item.id,
                                    text : item.name
                                }
                            })
                        };
                    }
                }
            });
        }
        
        function reloadTable(){
            formTable.DataTable().ajax.reload();
        }

        function formModal(action, title, btnText){
            saveAction = action
            modalTitle.text(title)
            btnSave.text(btnText)
            notif.html('')
            myModal.show()
            formData[0].reset()
        }

        function notifyAjax(res){
            if(res.errors){
                $('#notif').html(res.message).show()
            }
            if(res.error){
                show_toast('Mohon maaf', res.message)
            }
            if(res.success){
                show_toast('Berhasil', res.message)
                setTimeout(() => {
                    myModal.hide()
                }, 1000);

                setTimeout(() => {
                    reloadTable()
                }, 1300);
            }
            csrf.attr('content', res.csrf_hash)
        }
    });
</script>