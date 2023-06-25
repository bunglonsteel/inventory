<header class="absolute top-0 z-10 w-full border-b border-slate-200 flex items-center py-2 px-3 bg-white">
    <a href="<?= base_url() ?>">
        <img class="w-11 ml-2" src="https://img.ws.mms.shopee.co.id/31e400fbf8fa79ef1d675b41eae2f404_tn" alt="">
    </a>
    <div class="ml-auto">
        <a href="<?= base_url('produk') ?>" class="text-2xl text-gray-500 mr-2">
            <i class='bx bx-search-alt'></i>
        </a>
        <!-- <button class="text-2xl text-gray-500 mr-2 relative" type="button">
            <i class='bx bx-cart-alt'></i>
            <span class="absolute top-0 -right-2 text-xs bg-red-600 w-5 h-4 text-white font-bold rounded leading-tight">2</span>
        </button> -->
        <?php $this->load->view('layouts/front/components/menu_dropdown') ?>
    </div>
</header>