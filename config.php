<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = '10.0.0.15';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'moodleman';
$CFG->dbpass    = 'Development1!';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_unicode_ci',
);

$CFG->wwwroot   = 'https://learn.codingbee.id';
$CFG->dataroot  = '/var/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 02777;

$CFG->session_handler_class = '\core\session\redis';
$CFG->session_redis_host = '10.0.0.16';
$CFG->session_redis_port = 6379;  // Optional.
$CFG->session_redis_database = 0;  // Optional, default is db 0.
$CFG->session_redis_auth = 'm00dler3d!s'; // Optional, default is don't set one.
$CFG->session_redis_prefix = 'redis_session_'; // Optional, default is don't set one.
$CFG->session_redis_acquire_lock_timeout = 120;
$CFG->session_redis_acquire_lock_retry = 100; // Optional, default is 100ms (from 3.9)
$CFG->session_redis_lock_expire = 7200;
$CFG->session_redis_serializer_use_igbinary = true; // Optional, default is PHP builtin serializer.

require_once(__DIR__ . '/lib/setup.php');

$CFG->preventexecpath = true;
        $CFG->pathtophp = '/usr/bin/php';
        $CFG->pathtodu = '/usr/bin/du';
        $CFG->aspellpath = '/usr/bin/aspell';
        $CFG->pathtodot = '/usr/bin/dot';
        $CFG->pathtogs = '/usr/bin/gs';
        $CFG->pathtopdftoppm = '/usr/bin/pdftoppm';
        $CFG->pathtopython = '/usr/bin/python3';

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
