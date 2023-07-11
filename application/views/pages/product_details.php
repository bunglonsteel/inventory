<?php
$data['button_top'] =
    '
    <button id="copy" class="text-2xl text-gray-600 mr-2 relative" type="button" aria-label="copy url" data-tooltip-target="tooltip-copy" data-tooltip-placement="bottom" data-url="<?= base_url(uri_string()); ?>">
            <div id="copy-item" class="flex items-center">
                <i class="bx bx-share-alt mr-1.5"></i>
                <span class="text-sm font-bold">Copy</span>
            </div>
        </button>
    <div id="tooltip-copy" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
        <span id="copy-text">Copy Link</span>
        <div class="tooltip-arrow" data-popper-arrow></div>
    </div>';
$this->load->view('layouts/front/topbar_details', $data, FALSE); ?>


<div class="h-full overflow-y-auto md:ovlow-y">
    <div class="carousel-product-details relative bg-white">
        <img loading="lazy" width="300" height="300" class="h-80 w-full object-cover rounded-b-3xl" src="<?= base_url() ?>public/image/products/<?= $product->image ?>" alt="<?= $product->name ?>">
        <div class="absolute bottom-3 left-3 bg-white rounded-xl py-2 px-4 shadow-lg">
            <span class="font-inter text-sm font-bold text-gray-600"><?= $product->category ?></span>
        </div>
    </div>

    <div class="px-3 py-5 mb-1.5 bg-white">
        <!-- Breadcrumb -->
        <ol class="inline-flex items-center space-x-1 md:space-x-3 mb-1">
            <li class="inline-flex items-center">
                <a href="<?= base_url() ?>" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-400" aria-label="beranda">
                    <i class='bx bx-home mr-2'></i>
                    Beranda
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-1 text-sm text-gray-500 md:ml-2 text-limit-1"><?= $product->name ?></span>
                </div>
            </li>
        </ol>

        <h1 class="text-xl text-gray-600 font-bold font-inter"><?= $product->name ?></h1>
        <span class="text-lg text-gray-600 font-medium"><?= "Rp. " . number_format($product->price, 0, ',', '.'); ?></span>
    </div>
    <div class="px-3 py-4 mb-1.5 bg-white">
        <h3 class="text-lg text-gray-600 font-bold font-inter mb-3">Spesifikasi Produk</h3>
        <div class="mb-5">
            <div class="flex border-b border-slate-100 pb-1">
                <span class="flex-1 self-start text-gray-600">Kondisi</span>
                <span class="flex-1 self-start">Fresh & Frozen</span>
            </div>
            <div class="flex border-b border-slate-100 pb-1">
                <span class="flex-1 self-start text-gray-600">Berat Produk</span>
                <span class="flex-1 self-start"><?= $product->weight ?></span>
            </div>
            <div class="flex border-b border-slate-100 pb-1">
                <span class="flex-1 self-start text-gray-600">Jenis Penyimpanan</span>
                <span class="flex-1 self-start"><?= $product->type ?></span>
            </div>
            <div class="flex border-b border-slate-100 pb-1">
                <span class="flex-1 self-start text-gray-600">Masa Penyimpanan</span>
                <span class="flex-1 self-start"><?= $product->period ?></span>
            </div>
            <div class="flex border-b border-slate-100 pb-1">
                <span class="flex-1 self-start text-gray-600">Kondisi Penyimpanan</span>
                <span class="flex-1 self-start"><?= $product->conditions ?></span>
            </div>
        </div>
        <h3 class="text-lg text-gray-600 font-bold font-inter mb-3">Tersedia Juga di Platform</h3>
        <div class="flex mb-3">
            <?php foreach ($ecommerce as $shop) : ?>
                <a href="<?= $shop->link ?>" target="_BLANK" class="flex-1 mx-1 w-32" aria-label="<?= $shop->platform ?>">
                    <img loading="lazy" width="200" height="200" class="border border-slate-100 rounded-md w-full object-cover" src="<?= base_url(); ?>public/image/general/<?= $shop->image ?>" alt="<?= $shop->platform ?>">
                </a>
            <?php endforeach; ?>
        </div>
        <h3 class="text-lg text-gray-600 font-bold font-inter mb-3">Deskripsi Produk</h3>
        <div style="white-space:pre-line">
            <?= $product->description ?>
        </div>
    </div>

    <?php if (count($more_products) > 0) : ?>
        <div class="px-3 pt-3 bg-white">
            <h3 class="text-lg text-gray-600 font-bold font-inter">Mungkin anda suka😍</h3>
            <div id="list-product" class="py-2">
                <?php foreach ($more_products as $product) : ?>
                    <div class="bg-white w-44 md:w-56 mr-4 mt-3 mb-4 rounded-lg p-3 drop-shadow-lg">
                        <a href="<?= base_url('produk/') ?><?= $product->slug ?>" aria-label="<?= $product->name ?>">
                            <img loading="lazy" width="300" height="300" class="max-h-40 h-28 md:h-40 h w-full object-cover rounded-lg h" src="<?= base_url('public/image/products/') ?><?= $product->image ?>" alt="" />
                        </a>
                        <div class="pt-3">
                            <a href="<?= base_url('produk/') ?><?= $product->slug ?>" aria-label="<?= $product->name ?>">
                                <h4 class="mb-2 text-md font-bold leading-5 text-gray-600 text-limit-2 font-sans-pro"><?= $product->name ?></h4>
                            </a>
                            <span class=" block font-bold text-gray-500"><?= "Rp. " . number_format($product->price, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('#list-product').slick({
            variableWidth: true,
            arrows: false,
            infinite: false,
        });
        $(".carousel-product-details .slick-arrow").remove();

        $('#copy').click(function(e) {
            e.preventDefault();
            var target = document.createElement("input");
            document.body.appendChild(target);
            target.value = $(this).data('url');
            target.select();
            target.setSelectionRange(0, 99999);
            document.execCommand("copy");
            document.body.removeChild(target);

            $('#copy-item').html()
            $('#copy-item').html(
                `
                <i class='bx bx-check-circle mr-1'></i>
                <span class="text-sm font-bold">Copied!</span>
                `)
        });
    });
</script>