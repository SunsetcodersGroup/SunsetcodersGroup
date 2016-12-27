<?php


include_once(dirname(__FILE__) . '/auth.php');
include_once(dirname(__FILE__) . '/function_class.php');

/*
 * get_head() checks if there is a header.php file in the same directory
 * if there is use it. if there isnt ignore get_head()
 */

get_head();

have_pages();

/*
 * get_foot() checks if there is a foot.php file in the same directory
 * if there is use it. if there isnt ignore get_foot()
 */

get_foot();