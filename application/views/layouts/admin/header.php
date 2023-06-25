<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?= $title ?> - TheMeatStuff</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/fonts/boxicons.css" />

    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/css/demo.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/css/custom.css" />

    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/css/pages/page-auth.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/datatables/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/select2/select2.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/back/assets/vendor/libs/printer/print.min.css" />

    <script src="<?= base_url() ?>public/back/assets/vendor/js/helpers.js"></script>
    <script src="<?= base_url() ?>public/back/assets/js/config.js"></script>
    <script src="<?= base_url() ?>public/back/assets/vendor/libs/jquery/jquery.js"></script>
    <meta name="<?= $this->security->get_csrf_token_name() ?>" content="<?= $this->security->get_csrf_hash() ?>">
    <style>
        input:disabled {
            cursor: not-allowed !important;
        }

        .tab-settings ul li {
            min-width: 210px;
        }

        @media (max-width:600px) {
            .tab-settings ul li {
                min-width: 80px;
            }

            .tab-settings {
                flex-direction: column;
            }
        }

        @media (min-width:600px) {
            .width-50 {
                width: 50%;
            }
        }
    </style>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar" style="background:var(--bs-body-bg)">
        <div class="layout-container">