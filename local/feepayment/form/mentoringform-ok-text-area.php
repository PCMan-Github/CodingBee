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
 * @package    local_uploadform
 * @copyright  2020 Ricoshae Pty Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("$CFG->libdir/formslib.php");

class feepayment_form extends moodleform {
//Add elements to form
public function definition()
{
global $CFG,$DB;

$mform = $this->_form; // Don't forget the underscore!

//$mform->setType('email', PARAM_NOTAGS);                   //Set type of element
    $mform->addElement('html','<h3>feepayment Notes</h3>');
    $mform->addElement('textarea', 'introduction', get_string("introtext", "survey"), 'wrap="virtual" rows="20" cols="50"');
    $mform->addElement('editor', 'activity','' );
    $mform->setDefault('activity', 'link to file');

    $mform->addElement('file', 'attachment', get_string('attachment', 'forum'));
    // $mform->addElement('text', 'email', 'Select file'); // Add elements to your form

    // $mform->setDefault('email', 'Select file to upload');        //Default value

    // $mform->addElement('html', '<a href=./upload.html >Upload File</a>' );


    $buttonarray=array();
    $buttonarray[] = $mform->createElement('submit', 'Submit', 'Save');
    $buttonarray[] = $mform->createElement('cancel');
    $mform->addgroup($buttonarray, 'buttonar', '', ' ', false);

}
//Custom validation should be added here
function validation($data, $files) {
return array();
}
}