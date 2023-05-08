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

class coupon_form extends moodleform {
//Add elements to form
public function definition()
{
    global $CFG,$DB;
    $mform = $this->_form; // Don't forget the underscore!

    $mform->addElement('html','<h3>Add Coupon Course</h3>');
    $mform->addElement('text', 'coupon_code', 'Couopon Code');
    $mform->addElement('text', 'coupon_name', 'Coupon Name' );
    $mform->addElement('text', 'amount', 'Coupon Amount' );
    $mform->addElement('text', 'percentage', 'Coupon Percentage' );
    $mform->addElement('text', 'couponlimit', 'Usage Limit' );
    $mform->addElement('text', 'description', 'Coupon Description' );
    $mform->addElement('date_time_selector', 'start_date', "Coupon Start Date");
    $mform->addElement('date_time_selector', 'end_date', "Coupon End Date");
    $displaylist = core_course_category::make_categories_list();
    $mform->addElement('autocomplete', 'category', get_string('coursecategory'), $displaylist);
    $mform->addHelpButton('category', 'coursecategory');

    $mform->setDefault('amount', 0);
    $mform->setDefault('percentage', 0);
    $mform->setDefault('couponlimit', 0);

    $mform->addRule('coupon_code', get_string('required'), 'required', null);
    $mform->addRule('coupon_name', get_string('required'), 'required', null);
    $mform->addRule('amount', get_string('required'), 'required', null);
    $mform->addRule('percentage', get_string('required'), 'required', null);
    $mform->addRule('couponlimit', get_string('required'), 'required', null);

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