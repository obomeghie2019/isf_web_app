<?php
/**
 * template_start.php
 *
 * Author: 360 Global Network
 *
 * The first block of code used in every page of the template
 *
 */

// Ensure $template exists with default values
$template = isset($template) ? $template : [];
$template_defaults = [
    'title'       => 'Iyekhei Sport Festival',
    'description' => 'Welcome to Iyekhei Sport Festival (ISF) Portal',
    'author'      => '360 Global Network',
    'robots'      => 'index, follow'
];
$template = array_merge($template_defaults, $template);
?>
<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title><?= htmlspecialchars($template['title']) ?></title>

    <meta name="description" content="<?= htmlspecialchars($template['description']) ?>">
    <meta name="author" content="<?= htmlspecialchars($template['author']) ?>">
    <meta name="robots" content="<?= htmlspecialchars($template['robots']) ?>">

    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

    <!-- Icons -->
    <link rel="shortcut icon" href="img/favicon.png">
    <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
    <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
    <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
    <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
    <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
    <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
    <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
    <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/plugins.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/themes.css">
    <!-- END Stylesheets -->

    <!-- Modernizr & Respond.js -->
    <script src="js/vendor/modernizr-respond.min.js"></script>
</head>
<body>
