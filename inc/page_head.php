<?php
/**
 * page_head.php
 *
 * Site header and navigation
 *
 */

// Ensure $template exists
$template = isset($template) ? $template : [];
// Provide default for active_page
if (!isset($template['active_page'])) {
    $template['active_page'] = ''; // default empty string
}

// Ensure $primary_nav exists (menu items)
$primary_nav = isset($primary_nav) ? $primary_nav : [
    ['name' => 'Home', 'url' => 'index.php'],
    ['name' => 'About', 'url' => 'about.php'],
    ['name' => 'Contact', 'url' => 'contact.php'],
];
?>

<div id="page-container"<?php if (!empty($template['boxed'])) { echo ' class="boxed"'; } ?>>
    <!-- Site Header -->
    <header>
        <div class="container">
            <!-- Site Logo -->
            <a href="./" class="site-logo">
                <img src="img/logoisf.png" width="310" height="100" alt="Logo">
            </a>

            <!-- Site Navigation -->
            <nav>
                <!-- Menu Toggle for small screens -->
                <a href="javascript:void(0)" class="btn btn-default site-menu-toggle visible-xs visible-sm">
                    <i class="fa fa-bars"></i>
                </a>

                <!-- Main Menu -->
                <?php if ($primary_nav) { ?>
                <ul class="site-nav">
                    <li class="visible-xs visible-sm">
                        <a href="javascript:void(0)" class="site-menu-toggle text-center">
                            <i class="fa fa-times"></i>
                        </a>
                    </li>
                    <?php foreach ($primary_nav as $link) :
                        $url = $link['url'] ?? '#';
                        $active = ($template['active_page'] == $url) ? 'active' : '';
                        $has_sub = isset($link['sub']) && $link['sub'];
                    ?>
                    <li class="<?= $active ?>">
                        <a href="<?= $url ?>">
                            <?= htmlspecialchars($link['name']) ?>
                            <?php if ($has_sub) { ?><i class="fa fa-angle-down site-nav-arrow"></i><?php } ?>
                        </a>
                        <?php if ($has_sub) : ?>
                        <ul>
                            <?php foreach ($link['sub'] as $sub_link) :
                                $sub_url = $sub_link['url'] ?? '#';
                                $sub_active = ($template['active_page'] == $sub_url) ? 'active' : '';
                            ?>
                            <li class="<?= $sub_active ?>">
                                <a href="<?= $sub_url ?>"><?= htmlspecialchars($sub_link['name']) ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php } ?>
                <!-- END Main Menu -->
            </nav>
        </div>
    </header>
    <!-- END Site Header -->
