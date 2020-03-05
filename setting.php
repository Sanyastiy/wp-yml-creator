<?php
function setup_theme_admin_menus_yml_creator() {
    add_submenu_page('edit.php?post_type=products',
        'YML настройка', 'YML настройка', 'manage_options',
        'products-setting-yml-admin', 'theme_front_page_settings_yml_creator');
}  

// Она говорит WP, что нужно вызвать функцию "setup_theme_admin_menus"
// когда нужно будет создать страницы меню.
add_action("admin_menu", "setup_theme_admin_menus_yml_creator");


require_once('function.php');

function theme_front_page_settings_yml_creator() {
// проверяем, что пользователь может обновлять настройки
	if (!current_user_can('manage_options')) {
		wp_die('К сожалению, у вас нет разрешения для доступа к этой страницу.');
	}     ?>
	<div class="wrap">
			<h2>Настройка создания Products Catalog YML </h2>
        <!--
			<form method="post" action="options.php">
				<?php /* wp_nonce_field('update-options') ?>

				<p><strong>(не рабочая функция)Включить автоматическую генерацию YML(Cron):</strong><br />
					<input type="checkbox" name="pYmlCron"  value="1" <?php checked( get_option('pYmlCron') ); ?>" />
				</p>
				<p><input type="submit" name="Submit" value="Сохранить" /></p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="pYmlCours,pYmlCPA,pYmlCron" />
			</form>
			<p>YML файл каталога находится по ссылке: <a href="<?php echo bloginfo(url).'/yandex.yml';?> "><?php echo bloginfo(url).'/yandex.yml';?> </a></p>
			<?php if(get_option('pYmlCron')==1){
						echo '<p>Следующая генерация YML файла произойдет: '.  date("Y-m-d"." в "."h:i:s",wp_next_scheduled('yml_products_refresh')).' </p>';
					}else{
						echo '<p style="color:red">Генерация YML файла выключена </p>';
						echo 'Чтобы включить, нажмите галочку "Включить автоматическую генерацию" ';
					} 
			*/ ?>
		</div>
		-->
    <div class="wrap">
        <h2>Генерация файла</h2>

        <?php add_action( 'create_yml','yml_creator_function')?>
        <form method="post" action="/wp-content/plugins/wp-yml-creator/function.php">
            <!--options-->
            <?php wp_nonce_field('update-options') ?>
            <p><strong>После нажатия кнопку, вернитесь на предыдущую страницу</strong><br/>
            <p><input type="submit" name="Submit" value="Сделать YML прямо сейчас" /></p>

            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="pYmlCours,pYmlCron" />
        </form>
        <p>YML файл Продукции находится по ссылке: <a href="<?php echo bloginfo(url).'/wp-content/plugins/wp-yml-creator/yandex.yml';?> "><?php echo bloginfo(url).'/wp-content/plugins/wp-yml-creator/yandex.yml';?></a></p>
    </div>
		<?php

}

?>