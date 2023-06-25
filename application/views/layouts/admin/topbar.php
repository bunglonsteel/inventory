<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav">
        <a href="<?= base_url('admin/sales/pos') ?>" class="d-flex fw-semibold py-2 px-3 bg-soft-primary rounded-3">
            <i class="bx bx-shopping-bag me-1"></i>
            <span>POS</span>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item">
                <a id="fullscreen-mode" href="#" class="me-3 d-flex align-items-center flex-column">
                    <i id="fullscreen-icon" class='bx bx-fullscreen'></i>
                    <span id="fullscreen-text" style="font-size:10px !important;">FullScreen</span>
                </a>
            </li>
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online d-inline-block rounded-circle bg-primary">
                        <span class="avatar-text text-white fw-bold"><?= mb_substr($this->session->userdata('name'), 0, 1); ?></span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <div class="dropdown-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online d-inline-block rounded-circle bg-primary">
                                        <span class="avatar-text text-white fw-bold"><?= mb_substr($this->session->userdata('name'), 0, 1); ?></span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block"><?= $this->session->userdata('name') ?></span>
                                    <small class="text-muted">Online</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modal-exit">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Keluar</span>
                        </button>
                    </li>
                </ul>

            </li>
        </ul>
    </div>
</nav>

<script>

    let buttonScreen = document.getElementById("fullscreen-mode");
    let targetFullScreen = document.body
    let icon   = document.getElementById("fullscreen-icon");
    let text   = document.getElementById("fullscreen-text");

    buttonScreen.addEventListener('click', function(){
        let isInFullScreen = (document.fullscreenElement && document.fullscreenElement !== null) ||
                            (document.webkitFullscreenElement && document.webkitFullscreenElement !== null) ||
                            (document.mozFullScreenElement && document.mozFullScreenElement !== null) ||
                            (document.msFullscreenElement && document.msFullscreenElement !== null);
            if (!isInFullScreen) {
                if (targetFullScreen.requestFullscreen) {
                    targetFullScreen.requestFullscreen();
                } else if (targetFullScreen.mozRequestFullScreen) {
                    targetFullScreen.mozRequestFullScreen();
                } else if (targetFullScreen.webkitRequestFullScreen) {
                    targetFullScreen.webkitRequestFullScreen();
                } else if (targetFullScreen.msRequestFullscreen) {
                    targetFullScreen.msRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
            }

            
        })
        document.addEventListener('fullscreenchange', (event) => {
            if (document.fullscreenElement) {
                icon.classList.remove('bx-fullscreen')
                icon.classList.add('bx-exit-fullscreen')
                text.innerText = 'Exit FullScreen'
            } else {
                icon.classList.add('bx-fullscreen')
                icon.classList.remove('bx-exit-fullscreen')
                text.innerText = 'FullScreen'
            }
        });
</script>