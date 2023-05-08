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
 * Settings for the Fee enrolment plugin
 *
 * @package    enrol_xendit
 * @copyright  2019 Shamim Rezaie <shamim@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    $currencies = enrol_get_plugin('fee')->get_possible_currencies();

    if (empty($currencies)) {
        $notify = new \core\output\notification(
            get_string('nocurrencysupported', 'core_payment'),
            \core\output\notification::NOTIFY_WARNING
        );
        $settings->add(new admin_setting_heading('enrol_xendit_nocurrency', '', $OUTPUT->render($notify)));
    }

    $settings->add(new admin_setting_heading('enrol_xendit_settings', '', get_string('pluginname_desc', 'enrol_xendit')));

    // Note: let's reuse the ext sync constants and strings here, internally it is very similar,
    // it describes what should happen when users are not supposed to be enrolled any more.
    $options = array(
        ENROL_EXT_REMOVED_KEEP           => get_string('extremovedkeep', 'enrol'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES => get_string('extremovedsuspendnoroles', 'enrol'),
        ENROL_EXT_REMOVED_UNENROL        => get_string('extremovedunenrol', 'enrol'),
    );
    $settings->add(new admin_setting_configselect(
        'enrol_xendit/expiredaction',
        get_string('expiredaction', 'enrol_xendit'),
        get_string('expiredaction_help', 'enrol_xendit'),
        ENROL_EXT_REMOVED_SUSPENDNOROLES,
        $options));

    $settings->add(new admin_setting_heading('enrol_xendit_defaults',
        get_string('enrolinstancedefaults', 'admin'), get_string('enrolinstancedefaults_desc', 'admin')));

    $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                     ENROL_INSTANCE_DISABLED => get_string('no'));
    $settings->add(new admin_setting_configselect('enrol_xendit/status',
        get_string('status', 'enrol_xendit'), get_string('status_desc', 'enrol_xendit'), ENROL_INSTANCE_DISABLED, $options));

    if (!empty($currencies)) {
        $settings->add(new admin_setting_configselect('enrol_xendit/currency', get_string('currency', 'enrol_xendit'), '', 'USD',
            $currencies));
        $settings->add(new admin_setting_configtext('enrol_xendit/cost', get_string('cost', 'enrol_xendit'), '', 0, PARAM_FLOAT, 4));
        $settings->add(new admin_setting_configtext('enrol_xendit/customint1', "Full Price", '', 0, PARAM_FLOAT, 4));
    }

    if (!during_initial_install()) {
        $options = get_default_enrol_roles(context_system::instance());
        $student = get_archetype_roles('student');
        $student = reset($student);
        $settings->add(new admin_setting_configselect('enrol_xendit/roleid',
            get_string('defaultrole', 'enrol_xendit'), get_string('defaultrole_desc', 'enrol_xendit'), $student->id ?? null, $options));
    }

    $settings->add(new admin_setting_configduration('enrol_xendit/enrolperiod',
        get_string('enrolperiod', 'enrol_xendit'), get_string('enrolperiod_desc', 'enrol_xendit'), 0));
}
