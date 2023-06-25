<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <div class="container-breadcrumb my-2">
            <h3 class="fw-bold mb-1">Pengaturan</h3>
            <nav aria-label="breadcrumb mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin/dashboard') ?>">Beranda</a>
                    </li>
                    <li class="breadcrumb-item active">Pengaturan</li>
                </ol>
            </nav>
        </div>
        <!-- End Breadcrumb -->

        <div class="row">
            <div class="col-12 mt-4">
                <div class="tab-settings nav-align-left">
                    <ul class="nav nav-pills me-2 me-lg-3" role="tablist">
                        <li class="nav-item w-100 mb-1">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#general" aria-controls="general" aria-selected="true">Umum</button>
                        </li>
                        <li class="nav-item w-100 mb-1">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#account" aria-controls="account" aria-selected="false">Akun</button>
                        </li>
                        <li class="nav-item w-100 mb-1">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#socmed" aria-controls="socmed" aria-selected="false">Sosial Media</button>
                        </li>
                        <li class="nav-item w-100 mb-1">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#ecommerce" aria-controls="ecommerce" aria-selected="false">E-Commerce</button>
                        </li>
                        <li class="nav-item w-100 mb-1">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#whatsapp-connect" aria-controls="whatsapp-connect" aria-selected="false">Whatsapp API</button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="notif" style="display:none;"></div>
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <form action="<?= base_url('admin/settings/update') ?>" class="row g-2 g-lg-3">
                                <div class="col-12">
                                    <h5 class="mb-1">Umum</h5>
                                </div>
                                <div class="col-md-3">
                                    <label for="upload-logo" class="form-label">Logo / Brand <span class="text-danger">*</span></label>
                                    <label for="upload-logo" class="image-product rounded border mb-2 mb-md-0 p-2 d-flex align-items-center justify-content-center" style="height: 108px;">
                                        <span class="preview-image" style="display:none;">
                                            <i class="bx bx-image-add"></i>
                                            Preview
                                        </span>
                                        <img src="<?= base_url('public/image/general/') ?><?= $this->general->logo ?>" alt="<?= $this->general->site_title ?>" class="rounded w-100 h-100 render-image" style="object-fit:contain;">
                                    </label>
                                    <input type="file" id="upload-logo" name="image" class="account-file-input upload" hidden accept="image/png, image/jpeg">
                                </div>
                                <div class="col-md-9">

                                    <label for="site-title" class="form-label">Judul Situs / Toko<span class="text-danger">*</span></label>
                                    <input type="text" id="site-title" name="site_title" class="form-control mb-3 mb-md-2" value="<?= $this->general->site_title ?>" placeholder="Ketikan judul situs">

                                    <label for="keywords" class="form-label">Kata kunci <span class="text-danger">*</span></label>
                                    <input type="text" id="keywords" name="keywords" class="form-control mb-2 mb-md-0" value="<?= $this->general->keywords ?>" placeholder="Kata kunci SEO, pisahkan dengan (koma)">
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label">deskripsi <span class="text-danger">*</span></label>
                                    <textarea id="description" class="form-control mb-2" name="desc" rows="2" placeholder="Deskripsi web"><?= $this->general->description ?></textarea>
                                </div>
                                <div class="col-12">
                                    <h5 class="mb-1">Info Toko</h5>
                                </div>
                                <div class="col-md-4">
                                    <label for="bank_an" class="form-label">Bank A.N <span class="text-danger">*</span></label>
                                    <input type="text" id="bank_an" name="bank_an" class="form-control mb-2 mb-md-0" value="<?= $this->general->bank_an ?>" placeholder="Nama">
                                </div>
                                <div class="col-md-4">
                                    <label for="bank_number" class="form-label">Rek BCA<span class="text-danger">*</span></label>
                                    <input type="text" id="bank_number" name="bank_number" class="form-control mb-2 mb-md-0" value="<?= $this->general->bank_number ?>" placeholder="No. Rek">
                                </div>
                                <div class="col-md-4">
                                    <label for="number" class="form-label">No. telepon <span class="text-danger">*</span></label>
                                    <input type="text" id="number" name="number" class="form-control mb-2 mb-md-0" value="<?= $this->general->number_phone ?>" placeholder="ex:+628123456789">
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                    <textarea id="address" class="form-control mb-2" name="address" rows="2" placeholder="Deskripsi web"><?= $this->general->address ?></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary update" name="general">Simpan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade w-100" id="account" role="tabpanel">
                            <form action="<?= base_url('admin/settings/update/account') ?>" class="row g-2 g-lg-3 align-items-center">
                                <div class="col-12">
                                    <h5>Ubah password</h5>
                                    <label for="old-password" class="form-label">Password lama <span class="text-danger">*</span></label>
                                    <input type="password" id="old-password" name="old_password" class="form-control mb-2" placeholder="Password lama">
                                    <label for="new-password" class="form-label">Password baru <span class="text-danger">*</span></label>
                                    <input type="password" id="new-password" name="new_password" class="form-control mb-2" placeholder="Password baru">
                                    <label for="new-password" class="form-label">Ulangi password <span class="text-danger">*</span></label>
                                    <input type="password" id="re-password" name="re_password" class="form-control mb-2" placeholder="Ulangi password">
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary update" name="account">Simpan</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="socmed" role="tabpanel">
                            <div class="row g-2 g-lg-3 align-items-center">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5>Sosial Media</h5>
                                        <button id="social-action" type="button" class="btn btn-sm btn-primary">
                                            <span class="tf-icons bx bx-plus"></span>
                                            Sosmed
                                        </button>
                                    </div>
                                    <div class="table-responsive overflow-x">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Sosial Media</th>
                                                    <th>Icon</th>
                                                    <th>Link</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($socmed) : ?>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($socmed as $key => $media) : ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $media->name ?></td>
                                                            <td><?= $media->icon ?></td>
                                                            <td><?= $media->link ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <button type="button" class="d-flex btn edit btn-icon btn-sm btn-secondary me-2" name="socmed" data-id="<?= $media->key ?>" data-name="<?= $media->name ?>" data-icon="<?= $media->icon ?>" data-link="<?= $media->link ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                                        <i class="tf-icons bx bx-edit"></i>
                                                                    </button>
                                                                    <button type="button" class="d-flex btn delete btn-icon btn-sm btn-danger" name="socmed" data-id="<?= $media->key ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                                        <i class="tf-icons bx bx-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">Belum ada sosial media</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="ecommerce" role="tabpanel">
                            <div class="row g-2 g-lg-3 align-items-center">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5>E-Commerce</h5>
                                        <button id="ecommerce-action" type="button" class="btn btn-sm btn-primary">
                                            <span class="tf-icons bx bx-plus"></span>
                                            Link
                                        </button>
                                    </div>
                                    <div class="table-responsive overflow-x">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Platform</th>
                                                    <th>Link</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($ecommerce) : ?>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($ecommerce as $media) : ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td>
                                                                <div class="d-flex align-items-center flex-wrap flex-lg-nowrap">
                                                                    <img class="w-100 h-100 me-2 mb-2 mb-sm-0 rounded border" src="<?= base_url('public/image/general/') . $media->image ?>" style="max-width:80px;max-height: 50px;object-fit: cover;"></img>
                                                                    <span><?= $media->platform ?></span>
                                                                </div>
                                                            </td>
                                                            <td><?= $media->link ?></td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    <button type="button" class="d-flex btn edit btn-icon btn-sm btn-secondary me-2" name="ecommerce" data-id="<?= $media->key ?>" data-platform="<?= $media->platform ?>" data-image="<?= $media->image ?>" data-link="<?= $media->link ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                                        <i class="tf-icons bx bx-edit"></i>
                                                                    </button>
                                                                    <button type="button" class="d-flex btn delete btn-icon btn-sm btn-danger" name="ecommerce" data-id="<?= $media->key ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                                        <i class="tf-icons bx bx-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">Belum ada ecommerce</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="whatsapp-connect" role="tabpanel">
                            <form action="<?= base_url('admin/settings/update/token') ?>" class="row g-2 g-lg-3 align-items-center">
                                <div class="col-12">
                                    <h5>Connect ke API Fonnte</h5>
                                    <label for="no-whatsapp" class="form-label d-block">
                                        Test No. Whatsapp
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="no-whatsapp" name="no_whatsapp" class="form-control" placeholder="081xxxxxxxxx">
                                    <small>No Whatsapp ini digunakan untuk testing token berhasil atau tidak.</small>
                                </div>
                                <div class="col-12">
                                    <label for="token-api" class="form-label d-block">
                                        Token
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="token-api" name="token_whatsapp" class="form-control mb-1" placeholder="Token API Fonnte">

                                    <small id="connected" class="d-flex align-items-center justify-content-between">
                                        <?php if ($whatsapp_token) : ?>
                                            <span>Token saat ini : <i><?= substr($whatsapp_token, 0, 8) ?>*****</i></span>
                                            <span>
                                                <i class="text-success ms-auto me-1">Terhubung </i>
                                                <i class='bx bx-check-circle' style="font-size: 12px;"></i>
                                            </span>
                                        <?php else : ?>
                                            <span>Silahkan masukan token dari situs fonnte</i></span>
                                            <span>
                                                <i class="text-danger ms-auto me-1">Belum terhubung </i>
                                                <i class='bx bx-x-circle' style="font-size: 12px;"></i>
                                            </span>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                <div class="col-12 text-end">
                                    <button class="btn btn-primary update" name="whatsapp_api">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal-->
<div class="modal fade" id="modal" tabindex="-1" aria-hidden="true">
    <div id="body-modal" class="modal-dialog modal-sm modal-dialog-centered" role="document">

    </div>
</div>

<script src="<?= base_url() ?>public/back/assets/vendor/libs/sweetalert/sweetalert2.js"></script>
<script>
    $(function() {
        const CSRF = $('meta[name="csrf_token"')
        const TARGET_MODAL = document.getElementById('modal')
        const MODAL = new bootstrap.Modal(modal)
        const BODY_MODAL = $('#body-modal')

        $('body').tooltip({
            selector: '.edit, .delete, small',
            customClass: 'fs-7'
        });

        const notifyAjax = res => {
            if (res.errors) {
                $('.notif').html(res.message).show()
            }
            if (res.error) {
                show_toast('Mohon maaf', res.message)
            }
            if (res.success) {
                show_toast('Berhasil', res.message)
                setTimeout(() => {
                    MODAL.hide()
                }, 1000);
            }
            CSRF.attr('content', res.csrf_hash)
        }

        const notifyModal = res => {
            if (res.errors) {
                $('#notif').html(res.message).show()
            }
            if (res.error) {
                show_toast('Mohon maaf', res.message)
            }
            if (res.success) {
                show_toast('Berhasil', res.message)
                setTimeout(() => {
                    MODAL.hide()
                }, 1000);
            }
            CSRF.attr('content', res.csrf_hash)
        }

        const formSocialMedia = (...socmed) => {
            const [url, action, id = "", name = "", icon = "", link = ""] = socmed

            if (action == "add") {
                var text = "Tambah"
            } else {
                var text = "Update"
            }
            BODY_MODAL.append(`
                <form id="socmed" action="${url}${id}" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${text} Social Media</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="notif" style="display:none;"></div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label">Judul <span class="text-danger">*</span></label>
                                <input class="form-control mb-2" type="text" name="name" placeholder="Nama sosial media" value="${name}">

                                <label class="form-label">Icon <span class="text-danger">*</span> Klik 
                                    <a target="_BLANK" href="https://boxicons.com/">disini</a>
                                    <small class="py-1 px-2 rounded-circle bg-primary text-white" style="cursor:pointer;" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Ambil text classnya">?</small>
                                </label>
                                <input class="form-control mb-2" type="text" name="icon" placeholder="bx-instagram / bxl-instagram" value="${icon}">

                                <label class="form-label">Link <span class="text-danger">*</span></label>
                                <input class="form-control mb-2" type="url" name="url" placeholder="https://link.com" value="${link}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary update" name="socmed">${text}</button>
                    </div>
                </form>
            `)
        }

        const formEcommerce = (...ecommerce) => {
            const [url, action, id = "", platform = "", image = "", link = ""] = ecommerce

            if (action == "add") {
                var text = "Tambah"
            } else {
                var text = "Update"
            }
            MODAL.show()
            BODY_MODAL.append(`
                <form action="${url}${id}" id="form-data" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Tambah E-Commerce</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="notif" style="display:none;"></div>
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label text-underline">Logo <span class="text-danger">*</span> <em class="fs-7">800 x 300px</em></label>
                                <label for="upload-ecommerce" class="image-product rounded border mb-2 p-2 d-flex align-items-center justify-content-center" style="height: 108px;">
                                    <span class="preview-image" style="${image ? "display:none;" : ''}">
                                        <i class="bx bx-image-add"></i>
                                        Preview
                                    </span>
                                    <img src="${image ? image : ''}" alt="user-avatar" class="rounded w-100 h-100 render-image" style="${!image ? "display:none;" : ''} object-fit:contain;">
                                </label>
                                <input type="file" id="upload-ecommerce" name="logo_platform" class="account-file-input upload" hidden accept="image/png, image/jpeg">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Platform <span class="text-danger">*</span></label>
                                <input class="form-control mb-2" type="text" name="platform" placeholder="Tokopedia, shopee..." value="${platform}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Link <span class="text-danger">*</span></label>
                                <input class="form-control mb-2" type="url" name="url" placeholder="https://link.com" value="${link}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-soft-dark" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary update" name="ecommerce">Simpan</button>
                    </div>
                </form>
            `)
        }

        $('#social-action').click((e) => {
            MODAL.show()
            formSocialMedia("<?= base_url('admin/settings/create/socmed') ?>", "add")
        })

        $('#ecommerce-action').click((e) => {
            MODAL.show()
            formEcommerce("<?= base_url('admin/settings/create/ecommerce') ?>", "add")
        })

        $(document).on('click', '.edit', function(e) {
            MODAL.show()
            if (this.name == "socmed") {
                const id = $(this).data('id')
                const name = $(this).data('name')
                const icon = $(this).data('icon')
                const link = $(this).data('link')
                formSocialMedia("<?= base_url('admin/settings/update/socmed/') ?>", "update", id, name, icon, link)
            } else {
                const id = $(this).data('id')
                const platform = $(this).data('platform')
                const image = "<?= base_url('public/image/general/') ?>" + $(this).data('image')
                const link = $(this).data('link')
                formEcommerce("<?= base_url('admin/settings/update/ecommerce/') ?>", "update", id, platform, image, link)
            }
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
                        <p class="fs-6 mt-2">Anda yakin ingin menghapus ini?</p>
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
                    if (this.name == "socmed") {
                        var url = `<?= base_url() ?>admin/settings/delete/socmed/${this.dataset.id}`
                    } else if (this.name == "ecommerce") {
                        var url = `<?= base_url() ?>admin/settings/delete/ecommerce/${this.dataset.id}`
                    }
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: "csrf_token=" + CSRF.attr('content'),
                        dataType: "JSON",
                        success: function(res) {
                            notifyAjax(res)
                        }
                    });
                }
            })
        })

        $(document).on('click', '.update', function(e) {
            e.preventDefault()
            const url = $(this).closest('form').attr('action')

            switch ($(this).attr('name')) {
                case "general":
                    var type = 'page'
                    var params = payload($(this).closest('form').serializeArray(), 'upload-logo')
                    break;
                case "account":
                    var type = 'page'
                    var params = payload($(this).closest('form').serializeArray())
                    break;
                case "socmed":
                    var type = 'modal'
                    var params = payload($(this).closest('form').serializeArray())
                    break;
                case "ecommerce":
                    var type = 'modal'
                    var params = payload($(this).closest('form').serializeArray(), 'upload-ecommerce')
                    break;
                case "whatsapp_api":
                    var type = 'page'
                    var params = payload($(this).closest('form').serializeArray())
                    break;
                default:
                    break;
            }

            $.ajax({
                type: "POST",
                url: url,
                data: params,
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (type != "modal") {
                        notifyAjax(res)
                    } else {
                        notifyModal(res)
                    }
                }
            });
        })

        $(document).on('change', '.upload', function(e) {
            e.preventDefault();
            const [file] = this.files

            $(".preview-image").show()
            $(".render-image").hide().css('opacity', '0')
            if (file) {
                $(".preview-image").hide()
                $(".render-image").attr('src', URL.createObjectURL(file))
                    .show()
                    .animate({
                        opacity: '1'
                    }, 'slow')
            }
        });

        TARGET_MODAL.addEventListener('hide.bs.modal', event => {
            BODY_MODAL.html('')
        })

        const payload = function(params = [], inputImage = null) {
            let data = new FormData();
            params.forEach(function(e) {
                data.append(e.name, e.value)
            })
            if (inputImage) {
                data.append('image', $(`#${inputImage}`)[0].files[0]);
            }
            data.append('csrf_token', CSRF.attr('content'));
            return data;
        }

    });
</script>