<?php
$data['bg_color'] = 'bg-white';
$data['search'] = '
    <div class="relative w-full md:w-80 mx-3">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <i class="bx bx-search-alt text-gray-500 text-xl"></i>
        </div>
        <input id="search" type="search" class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-[#fcfcfc] focus:outline-none" placeholder="Search Beef Slice, Seafoods..." required>
    </div>
    ';
$this->load->view('layouts/front/topbar_details', $data);
?>

<div class="h-full overflow-y-auto md:ovlow-y">
    <div class="sticky top-14 px-1 py-2 bg-white border-t border-gray-100" style="z-index:1;">
        <table class="w-full text-sm text-left text-gray-400">
            <thead>
                <tr>
                    <th scope="col" class="px-3">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-filter-alt text-lg font-semibold'></i>
                            <span>Filter</span>
                        </div>
                    </th>
                    <th scope="col" class="w-1/2 px-1">
                        <select id="select-categories" class="block w-full p-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
                            <option hidden value="0">Kategori</option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat->slug ?>"><?= $cat->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th scope="col" class="w-1/2 px-1">
                        <select id="select-price" class="block w-full p-2 text-sm font-medium text-gray-500 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none">
                            <option hidden value="0">Harga</option>
                            <option value="max">Tertinggi</option>
                            <option value="min">Terendah</option>
                        </select>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <div id="list-products" class="mt-16 px-3 pt-2 pb-1 grid grid-cols-2 gap-4">

    </div>
    <div id="pagination" class="mt-3 px-3 pb-5"></div>
</div>

<script>
    // "use strict";
    // $(document).ready(function () {
    var csrf = $('meta[name="csrf_token"]')
    var page_url = false;

    $(document).on('change', "#search", function(e) {
        // alert()
        listProducts(page_url);
        e.preventDefault();
    });

    $(document).on('change', "#select-categories", function(e) {
        listProducts(page_url);
        e.preventDefault();
    });

    $(document).on('change', "#select-price", function(e) {
        listProducts(page_url);
        e.preventDefault();
    });

    $(document).on('click', ".pagination li a", function(e) {
        page_url = $(this).attr('href');
        listProducts(page_url);
        e.preventDefault();
    });

    const listProducts = function(page_url) {
        const search_key = $("#search").val();
        const categories = $("#select-categories").val() != 0 ? $("#select-categories").val() : '';
        const priceFilter = $("#select-price").val() != 0 ? $("#select-price").val() : '';

        const data = 'search=' + search_key + '&categories=' + categories + '&price=' + priceFilter + '&csrf_token=' + csrf.attr('content');
        const base_url = '<?= base_url('pages/product') ?>';

        if (page_url == false) {
            page_url = base_url;
        }

        $.ajax({
            type: "POST",
            url: page_url,
            data: data,
            dataType: "JSON",
            cache: false,
            beforeSend: function() {
                var skeleton = '';
                for (let index = 0; index < 6; index++) {
                    skeleton += productSkeleton()
                }
                $('#list-products').prepend(skeleton)
            },
            complete: function() {
                $('.skeleton').remove()
            },
            success: function(res) {
                $('#list-products').html('').removeClass('grid-cols-2')
                if (res.data.products.length != 0) {
                    $('#list-products').addClass('grid-cols-2')
                    res.data.products.forEach(e => {
                        productCard(e.id, e.name, e.slug, e.image, e.price)
                    });
                } else {
                    $('#list-products').prepend(`
                            <div class="bg-white rounded-lg p-10">
                                <div class="flex flex-col items-center text-gray-400">
                                    <i class='bx bx-shopping-bag text-5xl'></i>
                                    <span>Produk tidak ada</span>
                                </div>
                            </div>
                        `)
                }
                $('#pagination').html('').append(res.data.pagination)
                csrf.attr('content', res.csrf_hash)
            }
        });
    }

    const productCard = function(id, name, slug, image, price) {
        $('#list-products').prepend(`
                <div class="bg-white rounded-lg p-3 drop-shadow-lg">
                    <a href="<?= base_url('produk/') ?>${slug}">
                        <img loading="lazy" class="max-h-40 h-28 md:h-40 w-full object-cover rounded-lg" src="<?= base_url('public/image/products/') ?>${image}" alt="${name}" />
                    </a>
                    <div class="pt-2">
                        <a href="<?= base_url('produk/') ?>${slug}">
                            <h4 class="mb-1 text-md font-medium leading-5 text-gray-600 text-limit-2 font-sans-pro">${name}</h4>
                        </a>
                        <span class=" block font-bold text-gray-500">${formatRupiah(price)}</span>
                    </div>
                </div>
            `);
    }

    const productSkeleton = function() {
        return `
            <div class="skeleton bg-white rounded-lg p-3 drop-shadow-lg">
                <div role="status" class="max-w-sm animate-pulse">
                    <div class="h-28 md:h-40 w-full bg-gray-200 rounded-lg"></div>
                    <div class="pt-3">
                        <div class="h-4 bg-gray-200 rounded-lg w-full mb-1"></div>
                        <div class="h-4 bg-gray-200 rounded-lg w-24 md:w-32 mb-3"></div>
                        <div class="flex">
                            <div class="h-4 bg-gray-200 rounded-lg w-6 mr-2"></div>
                            <div class="h-4 bg-gray-200 rounded-lg w-20"></div>
                        </div>
                    </div>
                </div>
            </div>
            `
    }

    // Call
    listProducts(page_url);
    // });
</script>