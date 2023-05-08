<?php
//include simplehtml_form.php
require_once '../../config.php';
global $USER, $DB, $CFG;
require_once('form/couponform.php');
require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/local/coupon/index.php');
$PAGE->set_context(context_system::instance());

require_login();

$PAGE->set_title('Get Active Employee');
$PAGE->set_heading('Get Active Employee');

   echo $OUTPUT->header();

// echo "hello";
// die;

echo "line 21 response :<br>";

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://hcapp-dev.unitedtractors.com/utone_api_2.1/api/GeneralInformation/GetActiveEmployee',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS => 'Username=utsmartsys&Password=UtSmart%2C2021&grant_type=password',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer GT5BKZ0dXoXzRhWeyWJ33eZaY9iKNfQ32hL6sk1usrS2--aqixUEnDwH47XGPrQowoIWchmSZwQcN7OT7JNzVZD-kZjOw6u5hKwF9i8GPvz7gndyMFuj8HExBCmQSvQNO6yFzRgAwyEcvi4SfJIY5KzrYT_f31VST3JpzZt4SVdb1b-1YosJoWg6olvgPbadSgPUnsLwlNp6GlEqpC6a8cR3oSkbTGesFCVZbTedwca-QF5jrfbGlkJnhZxGQknpiEbW3fe2uqjnDCpoDptzjPSd4hmhRphXSoLa94HZGxWmP4Kity8TMDCAv2PfC2Xd',
    'Content-Type: application/x-www-form-urlencoded'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;
echo "line 44 response :<br>";
$response = json_decode($response);
print_object($response);



   echo $OUTPUT->footer();