<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/feepaymentform.php');
require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/local/feepayment/index.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Add New Fee Payment');
$PAGE->set_heading('Fee Payment');

$userid = $USER->id;
$useremail = $DB->get_field('user', 'email', array('id' => $userid));


//Instantiate simplehtml_form
$mform = new feepayment_form();
$toform = [];

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
  //Handle form cancel operation, if cancel button is present on form
  redirect($CFG->wwwroot . '/my');
} else if ($fromform = $mform->get_data()) {
  $dataform = new stdClass();
  $dataform->payment = $fromform->payment;
  $dataform->feeamount = $fromform->feeamount;
  $dataform->feepercentage = $fromform->feepercentage;
  $dataform->createdby = $userid;

  $DB->insert_record('local_feepayment', $dataform);

  redirect($CFG->wwwroot . '/local/feepayment/listadminfee.php');
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
