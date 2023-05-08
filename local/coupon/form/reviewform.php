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
 * Rates form
 *
 * @package    local_reviewform
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");

 class review_form extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG,$DB;

        $mform = $this->_form; 

        $mform->addElement('hidden','id', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $mform);

        $mform->addElement('hidden','userid', 'userid');
        $mform->setType('userid', PARAM_INT);
        $mform->setDefault('userid', $mform);

        // $mform->addElement('hidden','activity', 'activity');
        // $mform->setType('activity', PARAM_TEXT);
        // $mform->setDefault('activity', $mform);

        $mform->addElement('text','useremail', 'Email');
        $mform->setType('useremail', PARAM_TEXT);
        $mform->setDefault('useremail', $mform);

        $mform->addElement('hidden','uploadfile', 'uploadfile');
        $mform->setType('uploadfile', PARAM_TEXT);
        $mform->setDefault('uploadfile', $mform);

        $mform->addElement('hidden','reviewlink', 'reviewlink');
        $mform->setType('reviewlink', PARAM_TEXT);
        $mform->setDefault('reviewlink', $mform);

        $mform->addElement('hidden','created', 'created');
        $mform->setType('created', PARAM_TEXT);
        $mform->setDefault('created', $mform);
     


        // $em = $mform->addElement('text','useremail', 'Email');
        // $em->freeze();

        // $note = $mform->addElement('textarea', 'activity', get_string('activity', 'local_coupon'), 'wrap="virtual" rows="20" cols="50"');
        // $note->freeze();
        $mform->addElement('textarea', 'activity', get_string('activity', 'local_coupon'), 'wrap="virtual" rows="20" cols="50"');
        $mform->setType('activity', PARAM_TEXT);
        $mform->setDefault('activity', $mform);
        
        $options = array(
          '5' => '5',
          '10' => '10',
          '15' => '15',
          '20' => '20'
        );
        $select = $mform->addElement('select', 'score', 'Score', $options);
        $select->setSelected('5');
        $mform->setType('score', PARAM_INT);

        // $mform->addElement('text','score', 'Score');
        // $mform->setType('score', PARAM_TEXT);
        // $mform->setDefault('score', 1);

        $mform->addElement('text','comment', 'Comment');
        $mform->setType('comment', PARAM_TEXT);
        $mform->setDefault('comment', $mform);

        $options = array(
            'Waiting for  approval' => 'Waiting for approval',
            'Approved' => 'Approved',
            'Not Approved' => 'Not Approved',
          );
        $select = $mform->addElement('select','approvalstatus', get_string('approvalstatus','local_coupon'), $options);
        $select->SetSelected('Waiting for approval');
        $mform->setType('approvalstatus', PARAM_TEXT);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'Submit', 'Save');
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addgroup($buttonarray, 'buttonar', '', ' ', false);
    

    }


  }
