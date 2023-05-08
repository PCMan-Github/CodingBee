<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/couponform.php');
require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/local/coupon/index.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Coupon');
$PAGE->set_heading('Coupon');

$userid = $USER->id;
$useremail = $DB->get_field('user', 'email', array('id' => $userid));

//Instantiate simplehtml_form
$mform = new coupon_form();
$toform = [];

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
  //Handle form cancel operation, if cancel button is present on form
  redirect($CFG->wwwroot . '/my');
} else if ($fromform = $mform->get_data()) {
  $dataform = new stdClass();
  $dataform->couponcode = $fromform->coupon_code;
  $dataform->couponname = $fromform->coupon_name;
  $dataform->amount = $fromform->amount;
  $dataform->percentage = $fromform->percentage;
  $dataform->couponlimit = $fromform->couponlimit;
  $dataform->description = $fromform->description;
  $dataform->createdby = $userid;
  $dataform->startdate = $fromform->start_date;
  $dataform->enddate = $fromform->end_date;
  $dataform->category = $fromform->category;
  $DB->insert_record('local_coupon', $dataform);

  redirect($CFG->wwwroot . '/local/coupon/listcoupon.php');
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.

  //Set default data (if any)
  $mform->set_data($toform);
  echo $OUTPUT->header();
  //displays the form
  $mform->display();
  echo $OUTPUT->footer();
}
