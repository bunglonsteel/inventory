<div class="layout-page">
    <?php $this->load->view('layouts/admin/topbar'); ?>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12 mb-md-0 mb-4">
                <div id="invoice-order" class="card invoice-preview-card shadow-none">
                    <div class="card-body pt-0">
                        <div class="d-flex justify-content-between align-items-sm-end flex-xl-row flex-md-column flex-sm-row flex-column p-sm-3 p-0">
                            <div class="mb-4 mb-sm-0 width-50">
                                <img id="logo" src="<?= base_url('public/image/default/') . $this->general->logo ?>" alt="logo" style="width:100%; max-width:100px;">
                                <p class="mb-1"><?= $this->general->address ?></p>
                                <p class="mb-2">Whatsapp : <?= $this->general->number_phone ?></p>
                                <?php if ($order->payment_status == "PAID") : ?>
                                    <span class="badge bg-label-primary py-2 px-4 fw-bold">Sudah Dibayar</span>
                                <?php else : ?>
                                    <span class="badge bg-label-warning py-2 px-4 fw-bold">Belum Dibayar</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="mb-0">Invoice Number</p>
                                <h4><?= $order->invoice ?></h4>
                                <div class="mb-0">
                                    <span class="me-1">Tanggal :</span>
                                    <span class="fw-semibold"><?= date('d M Y', $order->date) ?></span>
                                </div>
                                <div>
                                    <span class="me-1">Tagihan untuk :</span>
                                    <span class="fw-semibold"><?= $order->customer ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0">
                    <div class="table-responsive">
                        <table class="table border-top m-0">
                            <thead>
                                <tr>
                                    <th>Nama produk</th>
                                    <th>Qty</th>
                                    <th>Diskon</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $subtotal = 0;
                                foreach ($order->items as $items) :
                                    $subtotal += $items->subtotal;
                                ?>
                                    <tr>
                                        <td class="text-nowrap"><?= $items->product ?></td>
                                        <td><?= $items->quantity ?></td>
                                        <td><?= "Rp. " . number_format($items->discount, 0, ',', '.') ?></td>
                                        <td class="text-nowrap"><?= "Rp. " . number_format($items->price, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2" class="align-top p-4">
                                        <p class="mb-2 mt-1">
                                            <span class="me-1 fw-semibold">Silahkan transfer ke :</span>
                                        </p>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td class="pe-3">A.N :</td>
                                                    <td><?= $this->general->bank_an ?></td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">Bank :</td>
                                                    <td>BCA</td>
                                                </tr>
                                                <tr>
                                                    <td class="pe-3">No. Rek :</td>
                                                    <td class="fw-bold"><?= $this->general->bank_number ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td class="text-end p-4">
                                        <p class="mb-2">Subtotal:</p>
                                        <p class="mb-2">Diskon:</p>
                                        <p class="mb-0 text-nowrap">Grand total:</p>
                                    </td>
                                    <td class="p-4">
                                        <p class="fw-semibold mb-2 text-nowrap"><?= "Rp. " . number_format($subtotal, 0, ',', '.') ?></p>
                                        <p class="fw-semibold mb-2 text-nowrap"><?= "Rp. " . number_format($order->discount, 0, ',', '.') ?></p>
                                        <p class="fw-semibold mb-0 text-nowrap"><?= "Rp. " . number_format($order->total_amount, 0, ',', '.') ?></p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <span>Harap mengirimkan foto bukti transfer kepada kami. Thank you so much, have a blissful day!</span>
                            </div>

                            <div class="col-12">
                                <div id="previewImage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions">
                <div class="card">
                    <div class="card-body">
                        <button id="download-invoice" class="btn btn-primary text-white d-grid w-100 mb-3" data-type="download" data-name="<?= $order->invoice ?>" data-id="<?= $order->order_id ?>">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="bx bx-cloud-download bx-xs me-1"></i>Download Ivoice</span>
                        </button>
                        <button id="send-invoice" class="btn btn-primary d-grid w-100" data-type="send" data-name="<?= $order->invoice ?>" data-id="<?= $order->order_id ?>">
                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i class="bx bx-paper-plane bx-xs me-1"></i>Kirim Invoice</span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </div>
</div>

<script src="<?= base_url() ?>public/back/assets/js/html2canvas.js"></script>
<script>
    $(function() {
        const csrf = $('meta[name="csrf_token"')
        const element = document.querySelector("#invoice-order")

        $('#download-invoice, #send-invoice').click(function(e) {
            e.preventDefault()
            if ($(this).data('type') == "send") {
                // var formData = new FormData();
                // formData.append('csrf_token', csrf.attr('content'));
                // formData.append('order_id', $(this).data('id'));

                $.ajax({
                    url: '<?= base_url('admin/sales/send_invoice') ?>',
                    method: 'POST',
                    data: "csrf_token=" + csrf.attr('content') + "&order_id=" + $(this).data('id'),
                    dataType: "JSON",
                    success: function(response) {
                        csrf.attr('content', response.csrf_hash)
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            } else {
                html2canvas(element).then(canvas => {
                    const image = canvas.toDataURL('image/jpeg');
                    const link = document.createElement("a");
                    link.href = image;
                    link.download = $(this).data('name') + ".jpeg"
                    link.click()
                });
            }

        })
    });
</script>