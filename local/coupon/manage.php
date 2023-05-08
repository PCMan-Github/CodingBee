<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once '../../config.php';
global $USER, $DB, $CFG;
require_once("form/manageform.php");
require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/local/coupon/manage.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_title('Mentoring List');
$PAGE->set_heading('Mentoring List');


require_login();


$userid = $USER->id;
$username = $DB->get_field('user', 'username', array('id' => $userid));


// if ($username == 'admin' or $username == 'dede_rmg' or $username == 'Admin_1'){


    $feelist = $DB->get_records('local_coupon');

    // $created = date('Y-m-d H:i') ;
    $created = time();

// $approvallist = $DB->get_records('local_custommission');

echo $OUTPUT->header();

// $templatecontext =(object) [
//     'feeist' => array_values($feeist),
//     'homeurl'  => new moodle_url('/local/coupon/manage.php'),
//     'today' =>  $created,
// ];
// var_dump($templatecontext);
// die;

$templatecontext =(object) [
    'feelist'   => array_values($feelist),
    'homeurl'   => new moodle_url('/my'),
    'today'     =>  $created,
];


echo $OUTPUT->render_from_template('local_coupon/manage',$templatecontext);

echo $OUTPUT->footer();

// ==========================





// echo $OUTPUT->render_from_template('local_feemidtrans/manage',$templatecontext);

// echo $OUTPUT->footer();

// } else {
//     redirect($CFG->wwwroot.'/my');
// }
