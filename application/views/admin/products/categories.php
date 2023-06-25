<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Semua Kategori</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/products') ?>">Produk</a>
                    </li>
                    <li class="breadcrumb-item active">Kategori</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">

                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-0 ms-2">Kategori Produk</h5>
                        <button id="add-action" type="button" class="btn btn-sm btn-primary">
                            <span class="tf-icons bx bx-plus"></span>
                            Kategori
                        </button>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
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
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <form id="form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notif" style="display:none;"></div>
                <div class="row">
                    <div class="col-12">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" class="form-control mb-2" placeholder="Ketikan nama kategori">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Slug (url) <span class="text-danger">*</span></label>
                        <input type="text" name="slug" class="form-control" placeholder="Slug" readonly>
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

<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.min.js"></script>
<script>
    $(document).ready(function () {

        const csrf = $('meta[name="csrf_token"')
        const formTable = $('#table')
        const formData = $('#form-data')
        const modalTitle = $('#title')
        const btnSave = $('#btn-save')
        const notif = $('#notif')
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
                url: '<?= base_url() ?>admin/categories',
                type: 'POST',
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

        $('input[name="category_name"]').on('input', function(e){
            const pattern = /[^a-zA-Z0-9]\s*$/g
            this.value = this.value.replace(pattern, ' ')
            let temp = this.value.trim()
            let res = temp.replaceAll(/ +/g, '-').toLowerCase()

            $('input[name="slug"]').val(res)
        })

        $(document).on('click', '#add-action', function (e) { 
            e.preventDefault();
            formModal('add', 'Tambah Kategori', 'Tambah')
        });

        $(document).on('click', '.edit', function(e){
            e.preventDefault();
            formModal('edit', 'Edit Kategori', 'Simpan')
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/categories/get_categories/" + $(this).data('id'),
                data: "csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function (res) {
                    if(res.success == "true"){
                        $('input[name="target"]').val(res.data.categories_id)
                        $('input[name="slug"]').val(res.data.slug)
                        $('input[name="category_name"]').val(res.data.categories_name)
                    }
                    csrf.attr('content', res.csrf_hash)
                },
                error : function(xhr, status){
                    console.log(xhr.responseText)
                }
            });
        })

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
                        <p class="fs-6 mt-2">Anda yakin ingin menghapus kategori ini?</p>
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
                        url: "<?= base_url() ?>admin/categories/action/delete",
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
            if(saveAction == 'add'){
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/categories/action/add",
                    data: formData.serialize() + "&csrf_token=" + csrf.attr('content'),
                    dataType: "JSON",
                    success: function (res) {
                        notifyAjax(res)
                    }
                });
            }
            
            if(saveAction == 'edit'){
                $.ajax({
                    type: "POST",
                    url: "<?= base_url() ?>admin/categories/action/edit",
                    data: formData.serialize() + "&csrf_token="+ csrf.attr('content'),
                    dataType: "JSON",
                    success: function (res) {
                        notifyAjax(res)
                    }
                });
            }
            
        });

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

        // select.select2({
        //     selectOnClose: true,
        //     dropdownParent: $('#modal-add'),
        //     ajax: {
        //         url: '<?= base_url() ?>admin/products/categories_select',
        //         type:'POST',
        //         delay:800,
        //         minimumInputLength: 4,
        //         data: function (params) {
        //             return {
        //                 s: params.term,
        //                 csrf_token: $('meta[name="csrf_token"').attr('content')
        //             }
        //         },
        //         processResults: function (data) {
        //             console.log(data)
        //             return {
        //                 results: data.items
        //             };
        //         }
        //     }
        // });
    });
</script>