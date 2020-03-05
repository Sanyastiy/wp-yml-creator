<?php

/*
Plugin Name: YML Creator
Description: Создание YML файла для продуктов сайта apit-kovrov
Version: 1.0
Author: Александр Свинаренко
 */
//thanks to Dmitriy Kishkin (atlantdak)


require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
// Проверяю версию WordPress.
if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.4', '<' ) ) { // если меньше 4.4

    // деактивируем
    add_action( 'admin_init', 'true_plugin_off_yml_creator' );
    function true_plugin_off_yml_creator() {
        deactivate_plugins( plugin_basename( __FILE__ ) );
    }

    // добавляем соответствующее уведомление
    add_action( 'admin_notices', 'true_plugin_notice' );
    function true_plugin_notice() {
        echo '<div class="updated">Плагин <p><strong>YML creator</strong> был отключен, так как не поддерживается версией WordPress ниже 4.4.</p></div>';

        // также сносим параметр из URL, чтобы не выполнилось ничего лишнего
        if ( isset( $_GET['activate'] ) )
            unset( $_GET['activate'] );
    }

}
else {
    // тут будет находиться весь остальной код плагина

    require_once('setting.php');
    require_once('function.php');
    //generate_xml_file_yml_creator();

/*

    function true_my_interval( $raspisanie ) {
        $raspisanie['minutely'] = array(
            'interval' => 120,
            'display' => 'Каждую минуту'
        );
        return $raspisanie;
    }
    add_filter( 'cron_schedules', 'true_my_interval');

    //Планирование
    if(get_option('pYmlCron')==1){
        if( !wp_next_scheduled( 'yml_products_refresh' ) ) {
            wp_schedule_event( time()+60*60*3+20, 'minutely', 'yml_products_refresh' );
        }
    }
    //Удалить планирование если не стоит галочка в админке на планировании
    if(get_option('pYmlCron')!=1){
        $timestamp = wp_next_scheduled('yml_products_refresh');
        wp_unschedule_event($timestamp, 'yml_products_refresh');
    }
    //add_action( 'yml_products_refresh', 'yml_creator_function' );

/*

    $timestamp = wp_next_scheduled('yml_products_refresh');
    wp_unschedule_event($timestamp, 'yml_products_refresh');

*/

    add_action( 'init', 'yml_creator_function' );
}
