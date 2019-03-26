<?php

/**
 * Makes available those super global arrays that are made available in versions
 * of PHP after v4.1.0.
 * This file is where all the "magic" begins. We ignore register_globals setting
 * and retrieve any variable from wherever and transform them to global
 * variables. This is highly insecure, so variables need to be checked
 * carefully.
 *
 * @package    Core
 * @subpackage Backend
 * @author     Martin Horwarth
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 */

// classes cStringMultiByteWrapper and cString are not loaded here as autoloader wasn't called yet
if (false === class_exists('cStringMultiByteWrapper')) {
    include_once dirname(__DIR__) . '/classes/class.string.multi.byte.wrapper.php';
}
if (false === class_exists('cString')) {
    include_once dirname(__DIR__) . '/classes/class.string.php';
}

// simulate magic_quotes behaviour
$_POST   = cString::addSlashes($_POST);
$_GET    = cString::addSlashes($_GET);
$_COOKIE = cString::addSlashes($_COOKIE);

// register globals
$types_to_register = ['GET', 'POST', 'COOKIE', 'SESSION', 'SERVER'];
foreach ($types_to_register as $global_type) {
    $arr = @ ${'_' . $global_type};
    if (is_array($arr) && count($arr) > 0) {
        // inner loop to prevent overwriting of globals by other globals' values
        foreach ($types_to_register as $global_type) {
            $key = '_' . $global_type;
            if (isset($arr[$key])) {
                unset($arr[$key]);
            }
        }
        extract($arr, EXTR_OVERWRITE);
    }
}

// save memory
unset($types_to_register, $global_type, $arr);
