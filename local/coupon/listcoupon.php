<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/couponform.php');
require_once('prosescoupon.php');
require_once(__DIR__ . '/../../config.php');

$id = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_ALPHA);

$PAGE->set_url('/local/coupon/listcoupon.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Coupon List');
$PAGE->set_heading('Coupon List');

if($action == "delete"){
    $DB->delete_records('local_coupon', array('id' => $id));
    redirect($CFG->wwwroot.'/local/coupon/listcoupon.php');
}else{
    $sqlCoupon = "SELECT a.id, a.couponcode, a.couponname, a.description, FORMAT(a.amount, 0,'id_ID') as amount, a.percentage, a.couponlimit, from_unixtime(a.startdate, '%d-%m-%Y') as startdate, from_unixtime(a.enddate, '%d-%m-%Y') as enddate, b.name
    FROM {local_coupon} as a
    inner join {course_categories} as b on a.category = b.id
    ORDER BY a.id DESC";

    $couponsFromDb = $DB->get_records_sql($sqlCoupon);
    $coupons = [];
    foreach ($couponsFromDb as $coupon) {
        $coupons[] = $coupon;
    }
    
    $data = [
        'coupons' => $coupons,
        'hascoupon' => !empty($coupons),
    ];
    
    echo $OUTPUT->header();
    echo $OUTPUT->render_from_template('local_coupon/couponlist', $data);
    echo $OUTPUT->footer();
}

