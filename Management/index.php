<?php

ini_set('date.timezone', 'Australia/Sydney');
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include ('auth.php');
include ('function_class.php');

$localAction = filter_input(INPUT_GET, 'id');

Switch (strtoupper($localAction)) {

    case "PROJECT":
        have_project();
        break;
    default:
        have_management();
}
