<?php

/**
 * @file
 * Include local-dev-environment-only settings here.
 */

require_once DRUPAL_ROOT . '/sites/example.settings.local.php';

$databases['migrate']['default'] = [
  'database'  => 'loyalist',
  'username'  => 'root',
  'password'  => 'import',
  'host'      => 'mysqlimport',
  'port'      => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver'    => 'mysql',
];

$databases['migratemotty']['default'] = [
  'database'  => 'motty',
  'username'  => 'root',
  'password'  => 'import',
  'host'      => 'mottyimport',
  'port'      => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver'    => 'mysql',
];
