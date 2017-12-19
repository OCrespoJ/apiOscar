<?php

namespace Fuel\Migrations;

class Canciones
{

    function up()
    {
        \DBUtil::create_table('canciones', 
            array(
            'id' => array('type' => 'int', 'constraint' => 11, 'auto_increment' => true),
            'titulo' => array('type' => 'varchar', 'constraint' => 50),
            'artista' => array('type' => 'varchar', 'constraint' => 50),
            'url' => array('type' => 'varchar', 'constraint' => 50)
        ), array('id'), false, 'InnoDB', 'utf8_general_ci');

    }
    function down()
    {
       \DBUtil::drop_table('canciones');
    }

}