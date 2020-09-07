<?php

function yml_creator_function()
{

    /* Достаю все товары каталога для дальнейшей обработки материала */
    $args = array(
        'post_type' => 'products',
        'posts_per_page' => -1
    );

    //'posts_per_page' => -1 MEANS 'posts_per_page' => ALL
    $query = new WP_Query;
    $my_posts = $query->query($args);
    //echo var_dump($my_posts); => содержимое всех постов подходящих по условию $args

    //Создает XML-строку и XML-документ при помощи DOM
    $dom = new DomDocument('1.0', "utf-8");

    //добавление корня - <yml_catalog>
    $yml_catalog = $dom->appendChild($dom->createElement('yml_catalog'));
    //добавляем дату в формате GMT +0
    $att = $dom->createAttribute('date');
    $att->value = date("Y-m-d") . " " . date("H:i");
    $yml_catalog->appendChild($att);
    $dom->appendChild($yml_catalog);


    //добавление элемента <shop> в <yml_catalog>
    $shop = $yml_catalog->appendChild($dom->createElement('shop'));

    // добавление элемента <name> в <shop>
    $name = $shop->appendChild($dom->createElement('name'));

    // добавление элемента текстового узла <name> в <shop>
    $name->appendChild(
        $dom->createTextNode(htmlspecialchars(get_bloginfo(name), ENT_QUOTES)));

    // добавление элемента <company> в <shop>
    $company = $shop->appendChild($dom->createElement('company'));

    // добавление элемента текстового узла <company> в <shop>
    $company->appendChild(
        $dom->createTextNode(htmlspecialchars(get_bloginfo(description), ENT_QUOTES)));

    // добавление элемента <url> в <shop>
    $url = $shop->appendChild($dom->createElement('url'));

    // добавление элемента текстового узла <url> в <shop>
    $url->appendChild(
        $dom->createTextNode(get_bloginfo(url)));

    // добавление элемента <currencies> в <shop>
    $currencies = $shop->appendChild($dom->createElement('currencies'));
    // добавление элемента <currency> в <currencies>
    $currency = $currencies->appendChild($dom->createElement('currency'));

    $id = $dom->createAttribute('id');
    $id->value = "RUR";
    $currency->appendChild($id);
    $currencies->appendChild($currency);
    $rate = $dom->createAttribute('rate');
    $rate->value = "1";
    $currency->appendChild($rate);
    $currencies->appendChild($currency);

    // добавление элемента <categories> в <shop>
    $categories = $shop->appendChild($dom->createElement('categories'));


    //получаем список всех категорий товаров
    $catlist = get_terms();

    //разбираем категории на первичные и побочные
    foreach ($catlist as $categories_item) {
        $category = $categories->appendChild($dom->createElement('category'));
        $id = $dom->createAttribute('id');
        $id->value = $categories_item->term_id;//cat_ID;
        $category->appendChild($id);
        $categories->appendChild($category);
        if (!$categories_item->parent) {
            $category->appendChild(
                $dom->createTextNode(htmlspecialchars($categories_item->name, ENT_QUOTES)));//cat_name
        }
        if ($categories_item->parent) {
            $parentId = $dom->createAttribute('parentId');
            $parentId->value = $categories_item->parent;
            $category->appendChild($parentId);
            $categories->appendChild($category);
            $category->appendChild(
                $dom->createTextNode(htmlspecialchars($categories_item->name, ENT_QUOTES)));
        }
    }//конец разбивки категорий на первичные и побочные

    // добавление элемента <delivery-options> в <shop>
    $delivery_options = $shop->appendChild($dom->createElement('delivery-options'));

    // добавление элемента <option> в <delivery-options>
    $option = $delivery_options->appendChild($dom->createElement('option'));
    $cost = $dom->createAttribute('cost');
    $cost->value = "0";
    $option->appendChild($cost);
    $delivery_options->appendChild($option);

    // добавление элемента <offers> в <shop>
    $offers = $shop->appendChild($dom->createElement('offers'));

    //начинаем выводить посты как офферы
    foreach ($my_posts as $my_post) {
        $gc_price = get_post_meta($my_post->ID, '_price', true); // echo 'Цена'.(int)$gc_price;
        $gc_price = intval(str_replace(" ", "", $gc_price)); // Из цены удаляю пробел, а потом преобразую в целое число.

        //после преобразования цены, пропускаем посты с нулевой стоимостью
        if ($gc_price === 0){
            continue;
        }

        // добавление элемента <offer> в <offers> (гениально)
        $offer = $offers->appendChild($dom->createElement('offer'));
        $id = $dom->createAttribute('id');
        $id->value = $my_post->ID;
        $available = $dom->createAttribute('available');
        $available->value = "true";
        $offer->appendChild($id);
        $offer->appendChild($available);
        //	  $offers->appendChild($offer);

        $url = $offer->appendChild($dom->createElement('url'));
        $url->appendChild(
            $dom->createTextNode(get_permalink($my_post->ID)));
        $price = $offer->appendChild($dom->createElement('price'));
        $price->appendChild(
            $dom->createTextNode((int)$gc_price === 0 ? $gc_price : str_replace(' ', '', $gc_price)));
        $currencyId = $offer->appendChild($dom->createElement('currencyId'));
        $currencyId->appendChild(
            $dom->createTextNode('RUR'));


        /*МОИ МУЧЕНИЯ. ТУТ ПОЛЕГЛО НЕСКОЛЬКО ДЕСЯТКОВ СТРОК КОДА. ОНИ ИСЧЕЗЛИ НАВСЕГДА.
        $taxId = $wpdb->get_results("SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id=$my_post->ID");
        $arguments=array('number'=>5,'taxonomy'=>'product-category');
        Получаю ID категории по ID поста
        $temp =get_terms();
        работает, отображает списком все таксономи сайта, в т.ч. нужные нам категории продукции
        ["taxonomy"]=>
        string(16) "product-category"
        */

        $term = get_post_meta($my_post->ID,"_yoast_wpseo_primary_product-category",true);

                ?>
<!-- <pre><?php // echo var_dump($term); ?></pre> -->
        <?php

        if (!is_wp_error($term)) {
            $categoryId = $offer->appendChild($dom->createElement('categoryId'));
            $categoryId->appendChild($dom->createTextNode($term));
        }

        /*Если есть картинка, выводим ее в YML */
        if (get_the_post_thumbnail_url($my_post->ID, large)) {
            $picture = $offer->appendChild($dom->createElement('picture'));
            $picture->appendChild(
                $dom->createTextNode(get_the_post_thumbnail_url($my_post->ID, large)));
        }

        $name = $offer->appendChild($dom->createElement('name'));
        $name->appendChild(
            $dom->createTextNode(htmlspecialchars(get_the_title($my_post->ID), ENT_QUOTES)));
    }

    //генерация xml
    $dom->formatOutput = true; // установка атрибута formatOutput
    // domDocument в значение true

    // сохранение файла
    $dom->save($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/wp-yml-creator/yandex.yml');
}
