<?php $this->load->view('layouts/front/topbar') ?>

<div class="wrapper h-full overflow-y-auto md:ovlow-y">
    <div id="banner" class="flex bg-white p-4 mt-[3.8rem] mb-2 overflow-x-hidden carousel">
        <?php foreach ($banners as $banner) : ?>
            <img class="h-32 cursor-pointer w-72 object-cover rounded-xl mr-3 border border-gray-200" src="<?= base_url('public/image/banner/') ?><?= $banner->banner ?>" alt="<?= $banner->name ?>">
        <?php endforeach; ?>
    </div>

    <?php if (count($socmed)) : ?>
        <section id="socmed" class="flex bg-white p-3 border-2 border-dashed border-slate-200 mb-3">
            <?php foreach ($socmed as $key => $social) : ?>
                <div class="w-2/4 <?= count($socmed) != $key + 1 ? "border-r-2 border-dashed border-slate-200" : "" ?> text-center">
                    <a href="<?= $social->link ?>">
                        <i class='bx <?= $social->icon ?> text-xl text-gray-500'></i>
                        <p class="font-normal text-gray-500"><?= $social->name ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
    <?php if (count($categories)) : ?>
        <section class="carousel pl-2 mb-3">
            <?php foreach ($categories as $cat) : ?>
                <a class="link-categories relative ml-2 border-0 rounded-xl h-16 w-40 bg-gradient-to-r from-rose-600 to-rose-400" href="#<?= $cat->slug ?>">
                    <div class="absolute px-3 w-full top-1/2 left-1/2 -translate-y-2/4 -translate-x-2/4">
                        <div class="h-1 w-11 bg-white rounded-sm"></div>
                        <h3 class=" text-white text-lg font-bold"><?= $cat->name ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <?php if (count($categories)) : ?>
        <section id="product" class="relative">
            <?php foreach ($categories as $cat) : ?>
                <div id="<?= $cat->slug ?>" class="bg-gradient-to-b from-white to-bg-slate-200">
                    <div class="sticky top-14 z-[9] p-3 grid grid-cols-2 text-lg font-bold text-gray-600 bg-white">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                            </svg>
                            <h3 class="font-inter"><?= $cat->name ?></h3>
                        </div>

                        <a href="<?= base_url('produk') ?>" class="flex items-center ml-auto">
                            <span>Lainnya</span>
                            <i class='bx bx-right-arrow-alt'></i>
                        </a>
                    </div>
                    <?php
                    $products = $this->db->select('
                        p.product_id as id,
                        p.slug as slug,
                        pd.product_name as name,
                        pd.product_image as image,
                        pd.selling_price as price,
                        ')
                        ->from('products as p')
                        ->join('product_details as pd', 'p.product_id = pd.product_id')
                        ->join('product_categories as pc', 'p.categories_id = pc.categories_id')
                        ->where('pc.categories_id', $cat->id)
                        ->order_by('p.product_id', 'desc')
                        ->limit(4)
                        ->get()
                        ->result();
                    ?>
                    <div class="px-3 py-2 grid grid-cols-2 gap-4">
                        <?php foreach ($products as $prod) : ?>
                            <div class="bg-white rounded-lg p-3 drop-shadow-lg">
                                <a href="<?= base_url('produk/' . $prod->slug) ?>">
                                    <img loading="lazy" class="max-h-40 h-28 md:h-40 h w-full object-cover rounded-lg h" src="<?= base_url('public/image/products/' . $prod->image) ?>" alt="" />
                                </a>
                                <div class="pt-2">
                                    <a href="<?= base_url('produk/' . $prod->slug) ?>">
                                        <h4 class="mb-1 text-md font-bold leading-5 text-gray-600 text-limit-2 font-sans-pro"><?= $prod->name ?></h4>
                                    </a>
                                    <span class=" block font-bold text-gray-500"><?= "Rp. " . number_format($prod->price, 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="px-3 py-2 mb-3">
                        <?php if (count($products) <= 0) : ?>
                            <div class="flex flex-col items-center bg-white rounded-lg py-6">
                                <img class="mb-2 w-18 block" src="<?= base_url() ?>public/image/default/no-data.png" alt="">
                                <span class="text-gray-400 block">Produk belum tersedia😣</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('.link-categories').click(function(e) {
            e.preventDefault()
            const hash = this.hash
            $('html, body, .wrapper').animate({
                scrollTop: $(hash).offset().top - 55
            }, 800, function() {
                window.location.hash = hash;
            });
        })
    });
</script>