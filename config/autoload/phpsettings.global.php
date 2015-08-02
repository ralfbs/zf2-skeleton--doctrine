<?php
/**
 * Global PHP Settings
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'phpSettings' => array(
        'display_startup_errors'     => false,
        'display_errors'             => false,
        'max_execution_time'         => 60,
        'date.timezone'              => 'Europe/Berlin',
        'mbstring.internal_encoding' => 'UTF-8',
    ),
);