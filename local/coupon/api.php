<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/couponform.php');
require_once('prosescoupon.php');
require_once(__DIR__ . '/../../config.php');

$action = optional_param('action', '', PARAM_TEXT);
$couponcode = optional_param('couponcode', '', PARAM_TEXT);
$category = optional_param('category', '', PARAM_TEXT);

require_login();

if($action == "getcoupon"){
    $sqlCoupon = "SELECT id, couponcode, couponname, description, amount, 
    percentage, couponlimit, from_unixtime(startdate, '%d-%m-%Y') as startdate, 
    from_unixtime(enddate, '%d-%m-%Y') as enddate, category FROM {local_coupon} 
    WHERE couponcode = '$couponcode'
    AND category = '$category'
    AND (from_unixtime(startdate, '%Y-%m-%d')) <= CURDATE()
    AND (from_unixtime(enddate, '%Y-%m-%d')) >= CURDATE()
    AND couponlimit > 0 
    ORDER BY id DESC LIMIT 1";
    $couponsFromDb = $DB->get_records_sql($sqlCoupon);
    echo json_encode(array_values($couponsFromDb));
}

