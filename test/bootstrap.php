<?php
/**
 * odtimetracker-php-lib
 *
 * @author Ondřej Doněk, <ondrejd@gmail.com>
 * @license Mozilla Public License 2.0 https://www.mozilla.org/MPL/2.0/
 * @link https://github.com/odtimetracker/odtimetracker-php-gtk
 */

chdir(dirname(__DIR__));

ini_set('xdebug.default_enable', false);
ini_set('xdebug.remote_enable', false);
ini_set('xdebug.auto_trace', false);
ini_set('xdebug.show_exception_trace', false);
ini_set('xdebug.coverage_enable', false);
ini_set('xdebug.profiler_enable', false);

require_once 'vendor/autoload.php';

