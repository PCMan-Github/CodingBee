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
 * @package    local_manageform
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once("$CFG->libdir/formslib.php");

 class manage_form extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG,$DB;
        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('html', '<h3>Coupon Form</h3><br><br>');

        $emails = $DB->get_records('local_addenrol',null);
        $options= [];
        $options[-1]= 'Pilih Email User';
        foreach($emails AS $email) {
        $options[$email->id] = $email->email;
        }

        $mform->addElement('select', 'email', 'Email Peserta', $options); // Add elements to your form
        $mform->setType('emails', PARAM_INT);                   //Set type of element
        $mform->setDefault('emails', -1);        //Default value



        //====================== begin training name ================================

        $courses = $DB->get_records('course',null);
        $options= [];
        $options[-1]= 'Pilih Nama Training';
        foreach($courses AS $course) {
            $options[$course->id] = $course->fullname;
        }

        $mform->addElement('select', 'courses', 'Nama Training', $options); // Add elements to your form
        $mform->setType('courses', PARAM_INT);                   //Set type of element
        $mform->setDefault('courses', -1);        //Default value


        //====================== end training name ================================
        $mform->addElement('text', 'messagetext', 'Keterangan'); // Add elements to your form
        $mform->setType('messagetext', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('messagetext', '');        //Default value

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'Submit', 'Simpan');
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addgroup($buttonarray, 'buttonar', '', ' ', false);

    }


  }
