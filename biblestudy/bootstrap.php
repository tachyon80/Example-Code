<?php
$root = $_SERVER['DOCUMENT_ROOT'].'/biblestudy/';
require_once $root . '../../config/DB_BibleStudy.php';
date_default_timezone_set('America/Chicago');
require_once 'library/Database.php';
require_once 'library/User.php';
require_once 'library/Util.php';
require_once 'library/Dates.php';
Database::init('mysql:host=localhost:3306;dbname=gearsonl_biblestudy');
session_start();