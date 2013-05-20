/**
 * Database
 */
self::$_strServer="localhost";
self::$_strDatabase="ut_racore2";
self::$_strUser="racore";
self::$_strPassword="racoretest";

/**
 * Source-Database
 */
self::$_strSourceServer="localhost";
self::$_strSourceDatabase="racore2";
self::$_strSourceUser="racore";
self::$_strSourcePassword="racoretest";

/**
 * Full-Tables
 */
self::$_arrFullTables = array();
array_push(self::$_arrFullTables, array('name' => 'df_core_label));

/**
 * Content-Tables
 */
self::$_arrContentTables = array();

/**
 * Allgemeine Einstellungen
 */
self::$_strMandant="df";
self::_addIncludePath('/usr/share/php5/PEAR');

