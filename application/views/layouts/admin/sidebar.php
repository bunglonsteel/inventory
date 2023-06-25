<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
    <div class="app-brand demo">
        <a href="<?= base_url('admin/dashboard') ?>" class="app-brand-link" style="margin:-8px auto 0 auto;">
            <span class="app-brand-logo demo">
                <img src="<?= base_url('public/image/default/') . $this->general->logo ?>" alt=" logo" style="width:100%; max-width:100px;">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1 mb-3">
        <!-- Dashboard -->
        <li class="menu-item <?= $title == 'Dashboard' ? 'active' : '' ?>">
            <a href="<?= base_url('admin/dashboard') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div>Dashboard</div>
            </a>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Manajemen</span></li>
        <li class="menu-item <?= $title == 'Semua Produk' || $title == 'Satuan' || $title == 'Kategori' || $title == 'Supplier' ? 'active' : '' ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>Produk</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item <?= $title == 'Semua Produk' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/products') ?>" class="menu-link circle">
                        <div>Semua Produk</div>
                    </a>
                </li>
                <li class="menu-item <?= $title == 'Kategori' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/categories') ?>" class="menu-link circle">
                        <div>Kategori</div>
                    </a>
                </li>
                <li class="menu-item <?= $title == 'Satuan' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/units') ?>" class="menu-link circle">
                        <div>Satuan / Unit</div>
                    </a>
                </li>
                <li class="menu-item <?= $title == 'Supplier' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/supplier') ?>" class="menu-link circle">
                        <div>Supplier</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item <?= $title == 'Penjualan' ? 'active' : '' ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-line-chart"></i>
                <div>Penjualan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item <?= $title == 'Penjualan' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/sales') ?>" class="menu-link circle">
                        <div>Penjualan</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="<?= base_url('admin/sales/pos') ?>" class="menu-link circle">
                        <div>POS</div>
                    </a>
                </li>
                <!-- <li class="menu-item">
                    <a href="<?= base_url('admin/sales') ?>" class="menu-link circle">
                        <div>Uang Masuk</div>
                    </a>
                </li> -->
            </ul>
        </li>

        <li class="menu-item <?= $title == 'Stok' ? 'active' : '' ?>">
            <a href="<?= base_url('admin/stock') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Stock</div>
            </a>
        </li>

        <!-- <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart-alt"></i>
                <div>Pembelian</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="<?= base_url('admin/purchases') ?>" class="menu-link circle">
                        <div>Pembelian</div>
                    </a>
                </li>
            </ul>
        </li> -->

        <li class="menu-item <?= $title == 'Pengeluaran' || $title == 'Kategori Pengeluaran' ? 'active' : '' ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-wallet-alt"></i>
                <div>Pengeluaran</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item <?= $title == 'Pengeluaran' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/expenses') ?>" class="menu-link circle">
                        <div>Pengeluaran</div>
                    </a>
                </li>
                <li class="menu-item <?= $title == 'Kategori Pengeluaran' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/expenses/expense_categories') ?>" class="menu-link circle">
                        <div>Kategori</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item <?= $title == 'Laporan Penjualan' || $title == 'Laporan Stock' ? 'active' : '' ?>">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div>Laporan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item <?= $title == 'Laporan Penjualan' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/reports') ?>" class="menu-link circle">
                        <div>Penjualan</div>
                    </a>
                </li>
                <li class="menu-item <?= $title == 'Laporan Stock' ? 'active' : '' ?>">
                    <a href="<?= base_url('admin/reports/stock') ?>" class="menu-link circle">
                        <div>Stock</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Users</span></li>
        <li class="menu-item <?= $title == 'Users' ? 'active' : '' ?>">
            <a href="<?= base_url('admin/users') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div>Users</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Lainnya</span></li>
        <li class="menu-item <?= $title == 'Banner' ? 'active' : '' ?>">
            <a href="<?= base_url('admin/banner') ?>" class="menu-link">
                <i class='menu-icon tf-icons bx bx-images'></i>
                <div>Banner</div>
            </a>
        </li>
        <li class="menu-item <?= $title == 'Settings' ? 'active' : '' ?>">
            <a href="<?= base_url('admin/settings') ?>" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div>Pengaturan</div>
            </a>
        </li>
    </ul>
</aside>
<!-- / Menu -->