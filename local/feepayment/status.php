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
// require_once("form/addenrolform.php");
require_once(__DIR__ . '/../../config.php');

$PAGE->set_url('/local/feepayment/status.php');
$PAGE->set_context(context_system::instance());

require_login();


$PAGE->set_title('feepayment Approval Status');
$PAGE->set_heading('feepayment Approval Status');

$approvallist = $DB->get_records('local_feepayment');

 $datecreated = date('Y-m-d H:i') ;

echo $OUTPUT->header();

$templatecontext =(object) [
    'approvallist' => array_values($approvallist),
    'homeurl'  => new moodle_url('/my'),
    'today' =>  $datecreated,
];
// var_dump($templatecontext);
// die;

echo $OUTPUT->render_from_template('local_feepayment/status',$templatecontext);

echo $OUTPUT->footer();

