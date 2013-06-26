<?php

    define('bd_dns'     , "pgsql");
    define("bd_name"    , "brasil");
    define("bd_server"  , "localhost");
    define("bd_user"    , "postgres");
    define("bd_password", "");
    define("bd_schema"  , "geodata");
    define("bd_port"    , "54321");
    define("geografico" , true);
    
    function db_schema() {
        $sql = "
        SELECT t.table_name as tabela, c.column_name as coluna
        FROM information_schema.tables t, information_schema.columns c
        WHERE t.table_name = c.table_name
        and t.table_catalog = '".bd_name."'
        and t.table_schema = '".bd_schema."'
        and t.table_type = 'BASE TABLE'
        ORDER BY t.table_name, c.column_name;
        ";

        return $sql;
    }
    
    function installView(){
        return "DROP VIEW IF EXISTS VIEW_BRASIL;

        CREATE VIEW VIEW_BRASIL as (
            SELECT t.table_name as tabela, c.column_name as coluna
            FROM information_schema.tables t, information_schema.columns c
            WHERE t.table_name = c.table_name
            and t.table_catalog = '".bd_name."'
            and t.table_schema = '".bd_schema."'
            and t.table_type = 'BASE TABLE'
            ORDER BY t.table_name, c.column_name;
        );";
    }

?>
