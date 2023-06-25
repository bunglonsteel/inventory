<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Semua Banner</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Banner</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">

                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-0 ms-2">Daftar Banner</h5>
                        <button id="action-add" type="button" class="btn btn-sm btn-primary">
                            <span class="tf-icons bx bx-plus"></span>
                            Banner
                        </button>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:60%">Banner</th>
                                    <th style="width:20%">Tampilkan</th>
                                    <th style="width:20%">Action</th>
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
                        <label for="upload" id="uploaded-image" class="image-product rounded border mb-2 p-2 d-flex align-items-center justify-content-center" style="height: 140px;">
                            <span id="preview-image" class="text-center" style="display:none;">
                                <i class='bx bx-image-add d-block' style="font-size:2.5rem;"></i>
                                Upload Image
                            </span>
                            <img id="render-image" alt="user-avatar" class="rounded" style="display:none; object-fit:contain; width:auto; max-width:100%;max-height:100%;">
                        </label>
                        <input type="file" id="upload" name="image" class="account-file-input" hidden accept="image/png, image/jpeg">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Judul Banner <span class="text-danger">*</span></label>
                        <input class="form-control mb-2" type="text" name="banner_name" placeholder="Judul banner">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Tampilkan Banner ? <span class="text-danger">*</span></label>
                        <select class="form-control py-2" name="active">
                            <option value="1">Ya, tampilkan</option>
                            <option value="0">Jangan tampilkan</option>
                        </select>
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

        const csrf       = $('meta[name="csrf_token"')
        const formTable  = $('#table')
        const formData   = $('#form-data')
        const modalTitle = $('#title')
        const btnSave    = $('#btn-save')
        const notif      = $('#notif')
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
                url: '<?= base_url() ?>admin/banner',
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
                    target:[1, 2],
                    orderable:false,
                },
            ]
        });

        $('#upload').change(function (e) { 
            e.preventDefault();
            const [ file ] = this.files
            $("#render-image").css('opacity', '0')
            $("#preview-image").show()
            $("#render-image").hide()
            if (file) {
                $("#preview-image").hide()
                $("#render-image").attr('src', URL.createObjectURL(file))
                $("#render-image").show().animate({
                    opacity: '1'
                }, 'slow')
            }
        });

        $(document).on('click', '#action-add', function (e) { 
            e.preventDefault();
            $('#preview-image').show();
            $('#render-image').hide();
            formModal('add', 'Tambah Banner', 'Tambah')
        });

        $(document).on('click', '.edit', function(e){
            e.preventDefault();
            formModal('edit', 'Edit Banner', 'Simpan')
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/banner/get_banner/" + $(this).data('id'),
                data: "csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function (res) {
                    if(res.success == "true"){
                        $('#render-image').attr('src', `<?= base_url('public/image/banner/')?>${res.data.banner_image}`).show()
                        $('input[name="target"]').val(res.data.banner_id)
                        $('input[name="banner_name"]').val(res.data.banner_name)
                        $('select[name="active"]').val(res.data.is_active);
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
                        <p class="fs-6 mt-2">Anda yakin ingin menghapus ini?</p>
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
                        url: "<?= base_url() ?>admin/banner/action/delete",
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
            let data = new FormData();
            formData.serializeArray().forEach(function(e) {
                data.append(e.name, e.value)
            })
            data.append( 'image', $('#upload')[0].files[0]);
            data.append( 'csrf_token', csrf.attr('content'));
            for (const d of data.entries()) {
                console.log(d)
            }
            if(saveAction == 'add'){
                var url = "<?= base_url() ?>admin/banner/action/add";
            } else {
                var url = "<?= base_url() ?>admin/banner/action/edit";
            }
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    notifyAjax(res)
                }
            });
            
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
    });
</script>