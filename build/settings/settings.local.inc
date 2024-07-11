<?php

/**
 * @file
 * Include local-dev-environment-only settings here.
 */

require_once DRUPAL_ROOT . '/sites/example.settings.local.php';

$databases['migrate']['default'] = array (
  'database'  => 'loyalist',
  'username'  => 'root',
  'password'  => 'import',
  'host'      => 'mysqlimport',
  'port'      => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver'    => 'mysql',
);
