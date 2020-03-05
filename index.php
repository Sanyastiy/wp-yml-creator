<?php

/*
Plugin Name: YML Creator
Description: Создание YML файла для продуктов сайта apit-kovrov
Version: 1.0
Author: Александр Свинаренко
 */
//thanks to Dmitriy Kishkin (atlantdak)


require_once(ABSPATH . 'wp-admin/includes/plugin.php');
// Проверяю версию WordPress.
if (version_compare(floatval(get_bloginfo('version')), '4.4', '<')) { // если меньше 4.4

    // деактивируем
    add_action('admin_init', 'true_plugin_off_yml_creator');
    function true_plugin_off_yml_creator()
    {
        deactivate_plugins(plugin_basename(__FILE__));
    }

    // добавляем соответствующее уведомление
    add_action('admin_notices', 'true_plugin_notice');
    function true_plugin_notice()
    {
        echo '<div class="updated">Плагин <p><strong>YML creator</strong> был отключен, так как не поддерживается версией WordPress ниже 4.4.</p></div>';

        // также сносим параметр из URL, чтобы не выполнилось ничего лишнего
        if (isset($_GET['activate']))
            unset($_GET['activate']);
    }

} else {
    // тут будет находиться весь остальной код плагина

    require_once('setting.php');
    require_once('function.php');

    add_action('init', 'yml_creator_function');
}
