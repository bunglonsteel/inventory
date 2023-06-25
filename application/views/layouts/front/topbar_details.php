<header class="absolute top-0 z-10 w-full flex items-center py-2 px-3 <?= isset($bg_color) ? $bg_color : 'backdrop-blur-md bg-white/20'?>">
    <span onclick="window.history.back();" class="w-11 h-11 rounded-full text-center cursor-pointer">
        <i class='bx bx-arrow-back text-2xl text-gray-600 leading-10'></i>
    </span>
    <?= isset($search) ? $search : ''?>
    <div class="flex items-center ml-auto">
        <?= isset($button_top) ? $button_top : ''?>
        <!-- Dropdown menu -->
        <?php $this->load->view('layouts/front/components/menu_dropdown') ?>
    </div>
</header>