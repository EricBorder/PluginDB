<?php
/*
Plugin Name: Palabras Malsonantes
Plugin URI:
Description: Busca palabras malsonantes y las cambia por sinónimos más aceptables
Author. Sergio Fernández
Version: 1.0
Author URI:
*/


function crearTablas()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    $table_name1 = $wpdb->prefix . 'palabras_malsonantes';
    $table_name2 = $wpdb->prefix . 'palabras_reemplazo';

    $sql = "CREATE TABLE $table_name1 (
        id mediumint(9) NOT NULL,
        palabra text NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $sql2 = "CREATE TABLE $table_name2 (
        id mediumint(9) NOT NULL,
        palabra text NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    dbDelta($sql2);
}


add_action('plugins_loaded', 'crearTablas');


function insertValoresTablas()
{
    global $wpdb;
    $table_name1 = $wpdb->prefix . 'palabras_malsonantes';
    $table_name2 = $wpdb->prefix . 'palabras_reemplazo';

    $sql11 = "INSERT INTO $table_name1 (id, palabra) VALUES (1, 'caca')";
    $sql12 = "INSERT INTO $table_name1 (id, palabra) VALUES (2, 'culo')";
    $sql13 = "INSERT INTO $table_name1 (id, palabra) VALUES (3, 'pedo')";
    $sql14 = "INSERT INTO $table_name1 (id, palabra) VALUES (4, 'pis')";
    $sql15 = "INSERT INTO $table_name1 (id, palabra) VALUES (5, 'pirola')";

    $sql21 = "INSERT INTO $table_name2 (id, palabra) VALUES (1, 'excremento')";
    $sql22 = "INSERT INTO $table_name2 (id, palabra) VALUES (2, 'trasero')";
    $sql23 = "INSERT INTO $table_name2 (id, palabra) VALUES (3, 'flatulencia')";
    $sql24 = "INSERT INTO $table_name2 (id, palabra) VALUES (4, 'orina')";
    $sql25 = "INSERT INTO $table_name2 (id, palabra) VALUES (5, 'pene')";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql11);
    dbDelta($sql12);
    dbDelta($sql13);
    dbDelta($sql14);
    dbDelta($sql15);

    dbDelta($sql21);
    dbDelta($sql22);
    dbDelta($sql23);
    dbDelta($sql24);
    dbDelta($sql25);
}

add_action('plugins_loaded', 'insertValoresTablas');


function reescribir_malsonantes($text){

    global $wpdb;
    $table_malsonantes = $wpdb->prefix . 'palabras_malsonantes';
    $table_reemplazo = $wpdb->prefix . 'palabras_reemplazo';

    $queryMalsonantes = $wpdb->get_results("SELECT palabra FROM $table_malsonantes",ARRAY_A);
    $queryReemplazos = $wpdb->get_results("SELECT palabra FROM $table_reemplazo",ARRAY_A);

    $malsonantes = array();
    /*for ($i = 0; $i < sizeof($queryMalsonantes); $i++) {
        $malsonantes[] = $queryMalsonantes[$i]['text'];
    }*/
    foreach ($queryMalsonantes as $malsonante) {
        $malsonantes[] = $malsonante['palabra'];
    }

    $reemplazos = array();
    /*for ($i = 0; $i < sizeof($queryReemplazos); $i++) {
        $reemplazos[] = $queryReemplazos[$i]['text'];
    }*/
    foreach ($queryReemplazos as $reemplazo) {
        $reemplazos[] = $reemplazo['palabra'];
    }


    return str_replace($malsonantes, $reemplazos, $text);

}

add_filter('the_content', 'reescribir_malsonantes');