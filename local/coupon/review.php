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
require_once("form/reviewform.php");
require_once(__DIR__ . '/../../config.php');


$PAGE->set_url('/local/coupon/review.php');
$PAGE->set_context(context_system::instance());

require_login();

// get id if editing form

// $d = required_param('d', PARAM_TEXT);
// $id = required_param('id', PARAM_INT);

$d =  htmlspecialchars($_GET["d"]);
$tid =  htmlspecialchars($_GET["id"]);
$id = intval($tid);

$userid = $USER->id;
$username = $DB->get_field('user', 'username', array('id' => $userid));



    $obj = new stdClass();

    $PAGE->set_title('Review Mentoring');
    $PAGE->set_heading('Review Mentoring');

    $mform = new review_form();
    $toform = []; 

    // $id='2';
    // $toform = $DB->get_record('local_coupon', array('id'=> 2 ),'*', MUST_EXIST);
    // $toform = $DB->get_record_sql('local_coupon', array(userid= 2 AND 'creted' = $d),'*', MUST_EXIST);


    // print_object($toform);
    // die;
    //----------begin loop get record-------------------------------------------
    // echo "d : " .$d."<br>";
    // echo "id :" .$id."<br>";
    // die;
    $rs = $DB->get_records_sql("SELECT * FROM {local_coupon} WHERE (userid = '$id' AND created = '$d' )", array(1)) ;
    foreach ($rs as $record ){
        $toform['id'] = $record->id;
        $toform['useremail'] = $record->useremail;
        $toform['score'] = $record->score;
        $toform['comment'] = $record->comment;
        $toform['activity'] = $record->activity;
        $toform['approvalstatus'] = $record->approvalstatus;
        $toform['userid'] = $record->userid;
        $toform['uploadfile'] = $record->uploadfile;
        $toform['reviewlink'] = $record->reviewlink;
        $toform['created'] = $record->created;
        $reviewlink = $record->reviewlink;
        $created = $record->created;
        $uploadfile = $record->uploadfile;
        $id = $record->id;
        $useremail = $record->useremail;
        $activity = $record->activity;
    }

    // print_object($toform);
    // die;
 
     //Form processing and displaying is done here
    if ($mform->is_cancelled()) {
        //Handle form cancel operation, if cancel button is present on form
        redirect($CFG->wwwroot . '/local/coupon/manage.php');
    } elseif ($fromform = $mform->get_data()) {
        // echo "fromform :<br>";
        // print_object($fromform);
        // echo "stop";
        // die;
        // $now = time() ;

        $recordtoinsert = array();
        $recordtoinsert = new stdClass();

        $recordtoinsert->id = $fromform->id;
        $recordtoinsert->userid = $fromform->userid;
        $recordtoinsert->activity = $fromform->activity;
        $recordtoinsert->score = $fromform->score;
        $recordtoinsert->useremail = $fromform->useremail;
        $recordtoinsert->comment = $fromform->comment;
        $recordtoinsert->approvalstatus =   $fromform->approvalstatus;
        $recordtoinsert->uploadfile = $fromform->uploadfile;
        $recordtoinsert->reviewlink = $fromform->reviewlink;
        $recordtoinsert->created = $fromform->created;
        
        // var_dump($dataform);
        // print_object($recordtoinsert); 
        // die;
        
        $DB->update_record('local_coupon', $recordtoinsert);
        
        if ($fromform->approvalstatus == 'Approved')
        {
            // get record from table block_xp
            $curr_point = $DB->get_field('block_xp', 'xp',array('userid' => $fromform->userid));
            $curr_id = $DB->get_field('block_xp', 'id',array('userid' => $fromform->userid));

            $recordtoinsert = array();
            $recordtoinsert = new stdClass();
            
            $recordtoinsert->id = $curr_id;
            $recordtoinsert->courseid = 1;
            $recordtoinsert->userid = $fromform->userid;
            $recordtoinsert->xp = $fromform->score + $curr_point;
            
            $DB->update_record('block_xp', $recordtoinsert);

        }
// ----- start send message ----

// $username = 97000005;
// $user = $DB->get_record('user', array('username'=>$username),'*', MUST_EXIST);
// echo "-local-coupon-review.php line 119 user <br>";
// print_object($USER);
// die;
// $userfrom = $USER;

// $message = new \core\message\message();
// $message->component = 'local_coupon'; // Your plugin's name
// $message->name = 'coupon'; // Your notification name from message.php
// $message->userfrom = core_user::get_noreply_user(); // If the message is 'from' a specific user you can set them here
// $message->userfrom = $userfrom;
// $message->userto = $user;
// $message->subject = 'message subject 1';
// $message->fullmessage = 'message body';
// $message->fullmessageformat = FORMAT_MARKDOWN;
// $message->fullmessagehtml = '<p>message body</p>';
// $message->smallmessage = 'small message';
// $message->notification = 1; // Because this is a notification generated from Moodle, not a user-to-user message
// $message->contexturl = 'https://unitedtractors.com/mod/assign/view.php?id=20';
// $message->contexturlname = 'Course list'; // Link title explaining where users get to for the contexturl
// $content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
// $message->set_additional_content('email', $content);

// echo "-local-coupon-review.php line 142 user <br>";
// print_object($message);
// die;

// Actually send the message
// $messageid = message_send($message);
// ----- end send messgae ----


    //  redirect($CFG->wwwroot . '/local/coupon/manage.php', 'You just updated : '. $email);

         redirect($CFG->wwwroot . '/local/coupon/manage.php');


}   
        $mform->set_data($toform);
        //displays the form
        echo $OUTPUT->header();
        $mform->display();
        echo $OUTPUT->footer();



