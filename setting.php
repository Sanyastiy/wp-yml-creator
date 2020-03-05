<?php
function setup_theme_admin_menus_yml_creator()
{
    add_submenu_page('edit.php?post_type=products',
        'YML настройка', 'YML настройка', 'manage_options',
        'products-setting-yml-admin', 'theme_front_page_settings_yml_creator');
}

// Она говорит WP, что нужно вызвать функцию "setup_theme_admin_menus"
// когда нужно будет создать страницы меню.
add_action("admin_menu", "setup_theme_admin_menus_yml_creator");

require_once('function.php');

function theme_front_page_settings_yml_creator()
{
// проверяем, что пользователь может обновлять настройки
    if (!current_user_can('manage_options')) {
        wp_die('К сожалению, у вас нет разрешения для доступа к этой страницу.');
    } ?>
    <div class="wrap">
    <h2>Настройка создания Products Catalog YML </h2>

    <div class="wrap">
        <h2>Генерация файла</h2>

        <?php add_action('create_yml', 'yml_creator_function') ?>
        <form method="post" action="/wp-content/plugins/wp-yml-creator/function.php">
            <!--options-->
            <?php wp_nonce_field('update-options') ?>
            <p><strong>После нажатия кнопку, вернитесь на предыдущую страницу</strong><br/>
            <p><input type="submit" name="Submit" value="Сделать YML прямо сейчас"/></p>

            <input type="hidden" name="action" value="update"/>
            <input type="hidden" name="page_options" value="pYmlCours"/>
        </form>
        <p>YML файл Продукции находится по ссылке:
            <a href="<?php echo bloginfo(url) . '/wp-content/plugins/wp-yml-creator/yandex.yml'; ?> ">
                <?php echo bloginfo(url) . '/wp-content/plugins/wp-yml-creator/yandex.yml'; ?></a>
        </p>
    </div>
    <?php
}
?>
