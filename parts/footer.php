<div class="container">
    <p class="float-end text-white"><a href="#top" title="Torna su"><svg width="25" height="25" data-icon="arrow-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="#ffffff" d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"></path></svg></a></p>
    <p>
        <?php global $site_name; $menu_items = sl_get_menu_items('footer'); ?>
        © <?php echo date("Y"); ?> <?php echo $site_name; ?> <span class="badge bg-dark text-white rounded-0 border-0 fw-bold pt-2">|</span>
        <?php foreach ( $menu_items as $menu_item ) { ?>
            <a title="<?php echo $menu_item['title']; ?>" class="nav-link d-inline p-0 text-white"<?php echo $menu_item['target']; ?> href="<?php echo $menu_item['url']; ?>">
            <?php echo $menu_item['title']; ?>
            </a>
        <?php } ?>
    </p>
</div>
