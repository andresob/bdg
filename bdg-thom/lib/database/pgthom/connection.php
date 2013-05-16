<?php

    define('bd_dns'     , "pgsql");
    define("bd_name"    , "hat");
    define("bd_server"  , "localhost");
    define("bd_user"    , "postgres");
    define("bd_password", "2");
    define("bd_port"    , "5432");
    define("geografico" , true);
    
    function db_schema() {
        return     "
        SELECT information_schema.tables.table_name as tabela, column_name as coluna
        FROM information_schema.columns, information_schema.tables
        WHERE information_schema.tables.table_name = information_schema.columns.table_name and information_schema.tables.table_catalog = '".bd_name."' and information_schema.tables.table_schema = 'public' and information_schema.tables.table_type = 'BASE TABLE'
        ORDER BY information_schema.tables.table_name, column_name;
        ";
    }
    
    function installView(){
        return "DROP VIEW IF EXISTS VIEW_BRASIL;

        CREATE VIEW VIEW_BRASIL as (
            SELECT information_schema.tables.table_name as tabela, column_name as coluna
            FROM  information_schema.columns, information_schema.tables
            WHERE information_schema.tables.table_name = information_schema.columns.table_name and
            information_schema.tables.table_catalog = 'hat' and
            information_schema.tables.table_schema = 'public' and
            information_schema.tables.table_type = 'BASE TABLE'
            ORDER BY information_schema.tables.table_name, column_name
        );";
    }

?>
