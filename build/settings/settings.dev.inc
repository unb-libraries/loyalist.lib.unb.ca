<?php

/**
 * @file
 * Include dev-environment-only settings here.
 */

$settings['config_readonly'] = TRUE;
$settings['trusted_host_patterns'] = [
  '^dev-loyalist\.lib\.unb\.ca$',
  '^127\.0\.0\.1$',
  '^10\.244\.[0-9]+\.[0-9]+$',
];
