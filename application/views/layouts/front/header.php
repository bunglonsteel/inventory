<!DOCTYPE html>
<html lang="id-ID" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <meta name="keywords" content>
    <meta name="description" content>
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:keywords" content />
    <meta property="og:description" content />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= base_url(uri_string()); ?>" />
    <meta property="og:site_name" content="TheMeatStuff" />
    <meta property="og:image" content="#" />
    <link rel="canonical" href="<?= base_url(uri_string()); ?>">
    <link rel="icon" type="image/x-icon" href="#">

    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Source+Sans+Pro:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>public/front/css/slick.min.css" />
    <link rel="stylesheet" href="<?= base_url() ?>public/front/css/themeatstuff.css" />
    <meta name="<?= $this->security->get_csrf_token_name() ?>" content="<?= $this->security->get_csrf_hash() ?>">
    <script src="<?= base_url() ?>public/front/js/jquery3.6.3.min.js"></script>
</head>
<body style="background-color:#f5f5f5;">
    <div class="relative max-w-[30rem] mx-auto h-screen">
        