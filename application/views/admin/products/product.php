<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Semua Produk</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Produk</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">
                <div class="card p-2 pt-3 p-md-3">
                    <div class="card-header p-0 pb-3 mb-3 d-flex align-items-center justify-content-between border-bottom">
                        <h5 class="mb-0 ms-2">Produk</h5>
                        <button id="add-action" type="button" class="btn btn-sm btn-primary">
                            <span class="tf-icons bx bx-plus"></span>
                            Produk
                        </button>
                    </div>
                    <div class="card-datatable table-responsive">
                        <table id="table" class="table table-responsive dataTable table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Beli</th>
                                    <th>Jual</th>
                                    <th>Stok</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Wrap printer -->
        <div id="print-js" class="row g-3 justify-content-center"></div>
        <!-- </div> -->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form id="form-data" class="modal-content">
            <div class="modal-header">
                <h5 id="title" class="modal-title">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="notif" style="display:none;"></div>
                <div class="row g-3 mb-lg-3">
                    <div class="col-md-3 mb-3">
                        <label for="upload" id="uploaded-image" class="image-product rounded border mb-2 p-2 d-flex align-items-center justify-content-center" style="height: 108px;">
                            <span id="preview-image" style="display:none;">
                                <i class='bx bx-image-add'></i>
                                Preview
                            </span>
                            <img id="render-image" alt="user-avatar" class="rounded w-100 h-100" style="display:none; object-fit:cover;">
                        </label>
                        <div class="d-grid">
                            <label for="upload" class="btn btn-primary" tabindex="0">
                                <span class="d-none d-sm-block">Pilih</span>
                                <i class="bx bx-upload d-block d-sm-none"></i>
                                <input type="file" id="upload" name="image" class="account-file-input" hidden accept="image/png, image/jpeg">
                            </label>
                        </div>
                    </div>
                    <div class="col-md-9 mb-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="product-name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" id="product-name" name="name" class="form-control" placeholder="Ketikan nama produk">
                            </div>
                            <div class="col-md-6">
                                <label for="slug" class="form-label">Slug (url) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="slug" name="slug" class="form-control" placeholder="slug" readonly>
                                    <button class="btn btn-primary edit-slug" type="button" id="edit-slug"><i class="tf-icons bx bx-edit-alt"></i></button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="sku" class="form-label">No. sku <span class="text-danger">*</span></label>
                                <input type="text" id="sku" name="sku" class="form-control" placeholder="SKU">
                            </div>
                            <div class="col-md-6">
                                <label for="barcode" class="form-label">Barcode <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" id="barcode" name="barcode" class="form-control" placeholder="Generate" readonly>
                                    <button class="btn btn-primary" type="button" id="generate-barcode" data-bs-toggle="tooltip" data-bs-placement="top" title="Generate Barcode">
                                        <i class='bx bx-barcode'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">

                    <div class="col-md-4 ">
                        <label for="select-supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                        <select id="select-supplier" class="form-select" name="supplier" style="width:100%;"></select>
                    </div>

                    <div class="col-md-4 ">
                        <label for="select-category" class="form-label">Kategori <span class="text-danger">*</span></label>
                        <select id="select-category" class="form-select" name="category" style="width:100%;"></select>
                    </div>
                    <div class="col-md-4 ">
                        <label for="select-unit" class="form-label">Satuan / unit <span class="text-danger">*</span></label>
                        <select id="select-unit" class="form-select" name="unit" style="width:100%;"></select>
                    </div>
                    <div class="col-md-4 mb-0">
                        <label for="purchase-price" class="form-label">Harga beli <span class="text-danger">*</span></label>
                        <input id="purchase-price" type="text" name="purchase" class="form-control" placeholder="1000" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57">
                    </div>
                    <div class="col-md-4 mb-0">
                        <label for="selling-price" class="form-label">Harga jual <span class="text-danger">*</span></label>
                        <input id="selling-price" name="selling" class="form-control" placeholder="2000" onkeypress="return event.charCode &gt;= 48 &amp;&amp; event.charCode &lt;= 57">
                    </div>
                    <div class="col-md-4 mb-0">
                        <label for="stock" class="form-label">Stok <span class="text-danger">*</span></label>
                        <input id="stock" type="number" name="stock" min="0" class="form-control" placeholder="10">
                    </div>
                    <div class="col-12">
                        <div class="divider my-0 text-end">
                            <a class="divider-text" data-bs-toggle="collapse" href="#spesifikasi" role="button" aria-expanded="false" aria-controls="spesifikasi">
                                <div class="d-flex align-items-center">
                                    <span class="me-1">Spesifikasi Produk </span>
                                    <i class='bx bx-plus-circle'></i>
                                </div>
                            </a>
                        </div>

                        <div class="collapse mt-3" id="spesifikasi">
                            <div class="p-3 border rounded">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Berat</label>
                                        <input type="text" name="product_weight" class="form-control" placeholder="100 Gram" value="500 Gram">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Jenis penyimpanan</label>
                                        <input type="text" name="storage_type" class="form-control" placeholder="Freezer" value="Freezer">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Masa penyimpanan</label>
                                        <input type="text" name="storage_period" class="form-control" placeholder="3 - 12 Bulan" value="3 - 12 Bulan">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Kondisi penyimpanan</label>
                                        <input type="text" name="storage_conditions" class="form-control" placeholder="Sangat beku (di bawah - 15°C)" value="Sangat beku (di bawah - 15°C)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-0">
                        <label for="description" class="form-label">Deskripsi produk <span class="text-danger">*</span></label>
                        <textarea id="description" class="form-control" name="description" rows="3" placeholder="Ketikan deskripsi produk"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <img class="me-auto d-none d-sm-block" id="render-barcode" style="max-height: 38px;"></img>
                <div class="actions">
                    <input type="hidden" name="target">
                    <button type="button" class="btn btn-soft-dark me-1" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-save">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Print-->
<div class="modal fade" id="modal-cetak" tabindex="-1" aria-hidden="true" style="z-index:99999;">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="head-title w-75">
                    <h5 class="mb-0">Cetak barcode</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="diskon-item" class="form-label" for="diskon-item">Jumlah Barcode <span class="text-danger">*</span></label>
                        <input type="number" id="total-barcode" class="form-control mb-2" min="0" value="1">
                        <small><em>Notes: Apabila saat cetak blank putih harap ulangi (print) sekali lagi.</em></small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="btn-print">
                    <span class="tf-icons bx bx-printer"></span>
                    Print
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Priview -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="preview" aria-labelledby="previewLabel">
    <div class="offcanvas-header">
        <h5 id="previewLabel" class="offcanvas-title">Detail produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div id="result-preview" class="offcanvas-body flex-grow-0"></div>
    <div id="action-preview" class="offcanvas-footer p-3 mt-auto"></div>
</div>

<script async src="<?= base_url() ?>public/back/assets/vendor/libs/printer/print.min.js"></script>
<script async src="<?= base_url() ?>public/back/assets/vendor/libs/barcode/barcode.min.js"></script>
<script async src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.js"></script>
<script src="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.min.js"></script>


<script>
    $(document).ready(function() {

        const csrf = $('meta[name="csrf_token"]')
        const editor = document.querySelector('#description')
        const formTable = $('#table')
        const formData = $('#form-data')
        const modalTitle = $('#title')
        const btnSave = $('#btn-save')
        const notif = $('#notif')
        let saveAction;

        // Modal
        const modal = document.getElementById('modal')
        const modal2 = document.getElementById('modal-cetak')
        const myModal = new bootstrap.Modal(modal)
        const modalPrint = new bootstrap.Modal(modal2)
        // Canvas
        const canvas = new bootstrap.Offcanvas('#preview')
        $('body').tooltip({
            selector: '.preview, .edit, .delete',
            customClass: 'fs-7'
        });

        formTable.DataTable({
            dom: '<"row" <"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"row align-items-center"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
            scrollX: true,
            processing: true,
            serverSide: true,
            deferRender: true,
            pageLength: 5,
            ajax: {
                url: '<?= base_url() ?>admin/products',
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
                target: [6],
                orderable: false,
            }, ],
        });

        $('#upload').change(function(e) {
            e.preventDefault();
            $("#render-image").css('opacity', '0')
            const [file] = this.files
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

        $('input[name="name"]').on('input', function(e) {
            const pattern = /[^a-zA-Z0-9-/]\s*$/g
            this.value = this.value.replace(pattern, ' ')
            let temp = this.value.trim()
            let res1 = temp.replaceAll(/ +/g, '-')
            let res2 = res1.replaceAll(/\//g, '-')
            let result = res2.replaceAll(/\-+/g, '-').toLowerCase()

            $('input[name="slug"]').val(result)
        })

        $('input[name="slug"]').on('input', function(e) {
            const pattern = /[^a-zA-Z0-9-]\s*$/g
            this.value = this.value.replace(pattern, ' ').trim().toLowerCase()
        })

        $('input[name="sku"]').on('input', function(e) {
            const pattern = /[^a-zA-Z0-9#]\s*$/g
            this.value = this.value.replace(pattern, ' ').trim().toUpperCase()
        })

        $('input[name="name"], input[name="slug"]').on("cut copy paste", function(e) {
            e.preventDefault();
        });

        $('#edit-slug').click(function(e) {
            e.preventDefault();

            if ($(this).hasClass('edit-slug')) {
                $('input[name="slug"]').removeAttr('readonly')
                $(this).removeClass('edit-slug').html(`<i class='bx bx-check'></i>`)
            } else {
                resetSlugInput()
            }
        });

        $('#generate-barcode').click(function(e) {
            e.preventDefault();

            if ($('#barcode').val() == '') {
                const code = new Date().getTime()
                $('input[name="barcode"]').val("TMS-" + code)
            }
            renderBarcode()
        });

        $(document).on('click', '#add-action', function(e) {
            e.preventDefault();
            // Reset
            $('#preview-image').show()
            $('#render-image').hide()
            defaultSelect()
            resetSlugInput()

            formModal('add', 'Tambah Produk', 'Tambah')
        });

        $(document).on('click', '.edit', function(e) {
            e.preventDefault();
            $('#preview-image').hide()
            resetSlugInput()
            formModal('edit', 'Edit Kategori', 'Simpan')
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/products/get_product/" + $(this).data('id'),
                data: "csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function(res) {
                    if (res.success == "true") {
                        $('#render-image').attr('src', `<?= base_url('public/image/products/') ?>${res.data.product_image}`).show()
                        $('input[name="name"]').val(res.data.product_name)
                        $('input[name="target"]').val(res.data.product_id)
                        $('input[name="slug"]').val(res.data.slug)
                        $('input[name="sku"]').val(res.data.sku)
                        $('input[name="barcode"]').val(res.data.barcode)
                        renderBarcode()
                        $('#select-category').append(new Option(res.data.categories_name, res.data.categories_id)).trigger('change');
                        $('#select-supplier').append(new Option(res.data.supplier_name, res.data.supplier_id)).trigger('change');
                        $('#select-unit').append(new Option(res.data.unit_name, res.data.unit_id)).trigger('change');

                        $('input[name="stock"]').val(res.data.current_stock)
                        $('input[name="purchase"]').val(res.data.purchase_price)
                        $('input[name="selling"]').val(res.data.selling_price)
                        $('input[name="product_weight"]').val(res.data.product_weight)
                        $('input[name="storage_type"]').val(res.data.storage_type)
                        $('input[name="storage_period"]').val(res.data.storage_period)
                        $('input[name="storage_conditions"]').val(res.data.storage_conditions)
                        $('textarea[name="description"]').val(res.data.product_desc)
                    }
                    csrf.attr('content', res.csrf_hash)
                },
                error: function(xhr, status) {
                    console.log(xhr.responseText)
                }
            });
        })

        $(document).on('click', '.delete', function(e) {
            Swal.fire({
                html: `<span class="swalfire bg-soft-danger my-3">
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
                        url: "<?= base_url() ?>admin/products/action/delete",
                        data: "target=" + $(this).data('id') + "&csrf_token=" + csrf.attr('content'),
                        dataType: "JSON",
                        success: function(res) {
                            notifyAjax(res)
                        }
                    });
                }
            })
        })

        $(document).on('click', '.preview', function(e) {
            e.preventDefault();
            canvas.show()
            $.ajax({
                type: "POST",
                url: "<?= base_url() ?>admin/products/get_product/" + $(this).data('id'),
                data: "csrf_token=" + csrf.attr('content'),
                dataType: "JSON",
                success: function(res) {
                    if (res.success == "true") {
                        const selling = res.data.selling_price
                        const purchase = res.data.purchase_price

                        $('#result-preview').html(`
                            <img id="image-product" class="w-100 rounded mb-3" style="max-height:220px;object-fit:cover;" src="<?= base_url('public/image/products/') ?>${res.data.product_image}">
                            <h5 class="mb-1">${res.data.product_name}</h5>
                            <small>${res.data.is_active == 1 ? 'Ready Stock 👌' : 'Stock hampir habis 🤏'}</small>
                            <div class="row mt-4">
                                <div class="col-6">
                                    <span class="fw-bold fs-7">NO. SKU</span>
                                    <p class="mt-2">${res.data.sku}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">BARCODE</span>
                                    <p class="mt-2">${res.data.barcode}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">BERAT</span>
                                    <p class="mt-2">${res.data.product_weight}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">STOCK SAAT INI</span>
                                    <p class="mt-2">${res.data.current_stock}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">SUPPLIER</span>
                                    <p class="mt-2">${res.data.supplier_name}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">KATEGORI</span>
                                    <p class="mt-2">${res.data.categories_name}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">HARGA BELI</span>
                                    <p class="mt-2">${formatRupiah(res.data.purchase_price)}</p>
                                </div>
                                <div class="col-6">
                                    <span class="fw-bold fs-7">HARGA JUAL</span>
                                    <p class="mt-2">${formatRupiah(res.data.selling_price)}</p>
                                </div>
                            </div>
                        `)

                        $('#action-preview').html(`
                            <button id="action-print" type="button" class="btn btn-primary w-100" data-barcode="${res.data.barcode}">
                                <span class="tf-icons bx bx-printer"></span>
                                Cetak Barcode
                            </button>
                        `)
                    }
                    csrf.attr('content', res.csrf_hash)
                },
                error: function(xhr, status) {
                    console.log(xhr.responseText)
                }
            });
        })

        $(document).on('click', '#action-print', function(e) {
            modalPrint.show()
            // console.log(this)
            const dataBarcode = $(this).data('barcode')
            $(document).on('click', '#btn-print', function(e) {
                const total = $('#total-barcode').val()
                $('#print-js').html('')
                for (let index = 0; index < total; index++) {
                    $('#print-js').append(`
                        <div class="col-2">
                            <img class="me-auto w-100 barcode-list-print"></img>
                        </div>
                    `);
                }
                JsBarcode(".barcode-list-print").init();
                $('.barcode-list-print').JsBarcode(dataBarcode, {
                    height: 50,
                    width: 1,
                    fontSize: 14,
                    textAlign: "left"
                });

                setTimeout(() => {
                    printJS({
                        printable: 'print-js',
                        type: 'html',
                        targetStyles: '*',
                    })
                }, 300);
            })

        })

        btnSave.click(function(e) {
            e.preventDefault();
            let data = new FormData();
            formData.serializeArray().forEach(function(e) {
                data.append(e.name, e.value)
            })
            data.append('image', $('#upload')[0].files[0]);
            data.append('csrf_token', csrf.attr('content'));

            // for (const j of data.entries()) {
            //     console.log(j)
            // }

            if (saveAction == 'add') {
                var url = "<?= base_url() ?>admin/products/action/add"
            } else {
                var url = "<?= base_url() ?>admin/products/action/edit"
            }

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    notifyAjax(res)
                }
            });

        });

        modal.addEventListener('hide.bs.modal', event => {
            $('#select-unit, #select-category, #select-supplier').html('')
            $('#render-barcode').attr('src', '')
        })

        ajaxSelect('#select-supplier', '<?= base_url() ?>admin/supplier/select_supplier')
        ajaxSelect('#select-category', '<?= base_url() ?>admin/categories/select_categories')
        ajaxSelect('#select-unit', '<?= base_url() ?>admin/units/select_units')


        function formModal(action, title, btnText) {
            saveAction = action
            modalTitle.text(title)
            btnSave.text(btnText)
            notif.html('')
            myModal.show()
            formData[0].reset()
        }

        function reloadTable() {
            formTable.DataTable().ajax.reload();
        }

        function defaultSelect() {
            const defaultSelect = {
                id: 0,
                text: 'Choose...'
            };
            const newOption = new Option(defaultSelect.text, defaultSelect.id);
            $('#select-unit, #select-category, #select-supplier').append(newOption).trigger('change');
        }

        function ajaxSelect(dom, url) {
            $(dom).select2({
                maximumSelectionLength: 3,
                selectOnClose: true,
                dropdownParent: $('#modal'),
                ajax: {
                    url: url,
                    type: 'POST',
                    dataType: 'JSON',
                    delay: 500,
                    minimumInputLength: 4,
                    data: function(params) {
                        return {
                            supplier: params.term,
                            csrf_token: $('meta[name="csrf_token"').attr('content')
                        }
                    },
                    processResults: function(res) {
                        $('meta[name="csrf_token"').attr('content', res.csrf_hash)
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

        function notifyAjax(res) {
            if (res.errors) {
                $('#notif').html(res.message).show()
            }
            if (res.error) {
                show_toast('Mohon maaf', res.message)
            }
            if (res.success) {
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

        function resetSlugInput() {
            $('input[name="slug"]').attr('readonly', true)
            $('#edit-slug').addClass('edit-slug').html(`<i class='bx bx-edit-alt'></i>`)
        }

        function renderBarcode() {
            if ($('input[name="barcode"]').val()) {
                $('#render-barcode').JsBarcode($('input[name="barcode"]').val(), {
                    height: 20,
                    width: 1,
                    fontSize: 14,
                    textAlign: "left"
                });
            }
        }
    });
</script>