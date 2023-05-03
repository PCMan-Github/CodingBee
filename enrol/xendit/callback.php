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

/**
 * An oauth2 redirection endpoint which can be used for an application:
 * http://tools.ietf.org/html/draft-ietf-oauth-v2-26#section-3.1.2
 *
 * This is used because some oauth servers will not allow a redirect urls
 * with get params (like repository callback) and that needs to be called
 * using the state param.
 *
 * @package    core
 * @copyright  2012 Dan Poltawski
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $DB;

$data = json_decode(file_get_contents('php://input'));
$external_id = explode('/', $data->external_id);
$course_id = $external_id[3];
$userid = $external_id[4];

$status = $data->status;
$paymentarea = $data->id;

if($status == "PAID"){
    $instance = $DB->get_record('enrol', ['enrol' => 'xendit', 'courseid' => $course_id], '*', MUST_EXIST);
    $plugin = enrol_get_plugin('xendit');

    if ($instance->enrolperiod) {
        $timestart = time();
        $timeend   = $timestart + $instance->enrolperiod;
    } else {
        $timestart = 0;
        $timeend   = 0;
    }

    $plugin->enrol_user($instance, $userid, $instance->roleid, $timestart, $timeend);
}else{
    $payment = $DB->get_record("payments", ["paymentarea" => $paymentarea]);
    $payment->paymentarea = "I-".$payment->paymentarea;
    $DB->update_record("payments", $payment);
    // $DB->delete_records("payments", ["paymentarea" => $paymentarea]);
}
