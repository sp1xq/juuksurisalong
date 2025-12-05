<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

  const HTTP_SERVER = 'https://oscb.maiste.fi';
  const COOKIE_OPTIONS = [
    'lifetime' => 0,
    'domain' => 'oscb.maiste.fi',
    'path' => '/',
    'samesite' => 'Lax',
  ];
  const DIR_WS_CATALOG = '/';

  date_default_timezone_set('Europe/Helsinki');
  const DIR_FS_CATALOG = '/home/sp1x/public_html/oscb/';

  const DB_SERVER = 'localhost';
  const DB_SERVER_USERNAME = 'root';
  const DB_SERVER_PASSWORD = 'segane';
  const DB_DATABASE = 'oscb';


