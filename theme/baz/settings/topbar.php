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
 *
 * @package   theme_baz
 * @copyright Copyright © 2021 onwards Marcin Czaja (https://rosea.io)
 * @license   Commercial https://themeforest.net/licenses
 *
 */


defined('MOODLE_INTERNAL') || die();


$page = new admin_settingpage('theme_baz_settingstopbar', get_string( 'settingstopbar', 'theme_baz'));

    $name = 'theme_baz/stickybreadcrumbs';
    $title = get_string('stickybreadcrumbs', 'theme_baz');
    $description = get_string('stickybreadcrumbs_desc', 'theme_baz');
    $setting = new admin_setting_configcheckbox($name, $title, $description, 'no', 'yes', 'no');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    $name = 'theme_baz/customlogo';
    $title = get_string('customlogo', 'theme_baz');
    $description = get_string('customlogo_desc', 'theme_baz');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg', 'gif'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'customlogo', 0, $opts);
    $page->add($setting);

    $name = 'theme_baz/logocontainer';
    $title = get_string('logocontainer', 'theme_baz');
    $description = get_string('logocontainer_desc', 'theme_baz');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);


    $name = 'theme_baz/topbarhamburgermenu';
    $title = get_string('topbarhamburgermenu', 'theme_baz');
    $description = get_string('topbarhamburgermenu_desc', 'theme_baz');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);


    $name = 'theme_baz/hmainnav';
	$heading = get_string('hmainnav', 'theme_baz');
	$setting = new admin_setting_heading($name, $heading, format_text(get_string('hmainnav_desc', 'theme_baz'), FORMAT_MARKDOWN));
	$page->add($setting);

    $name = 'theme_baz/isitemonsitehome';
    $title = get_string('isitemonsitehome', 'theme_baz');
    $description = get_string('isitemonsitehome_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemondashboard';
    $title = get_string('isitemondashboard', 'theme_baz');
    $description = get_string('isitemondashboard_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemoncalendar';
    $title = get_string('isitemoncalendar', 'theme_baz');
    $description = get_string('isitemoncalendar_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemonprivatefiles';
    $title = get_string('isitemonprivatefiles', 'theme_baz');
    $description = get_string('isitemonprivatefiles_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemoncontentbank';
    $title = get_string('isitemoncontentbank', 'theme_baz');
    $description = get_string('isitemoncontentbank_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);


    //My Courses String
    // TODO:
    // - my courses custom icon
    // - description and link to documentation

    $name = 'theme_baz/hmycoursesbtn';
    $heading = get_string('hmycoursesbtn', 'theme_baz');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('hmycoursesbtn_desc', 'theme_baz'), FORMAT_MARKDOWN));
    $page->add($setting);

    // Deprecieted in Moodle 4.0
    // $name = 'theme_baz/stringmycourses';
    // $title = get_string('stringmycourses', 'theme_baz');
    // $description = get_string('stringmycourses_desc', 'theme_baz');
    // $default = 'My Courses';
    // $setting = new admin_setting_configtext($name, $title, $description, $default);
    // $page->add($setting);

    $name = 'theme_baz/stringallcourses';
    $title = get_string('stringallcourses', 'theme_baz');
    $description = get_string('stringallcourses_desc', 'theme_baz');
    $default = 'List of all available courses';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/stringnocourses';
    $title = get_string('stringnocourses', 'theme_baz');
    $description = get_string('stringnocourses_desc', 'theme_baz');
    $default = 'You are not enrolled in any courses.';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);


    // Colors
    $name = 'theme_baz/htopbarcolors';
    $heading = get_string('htopbarcolors', 'theme_baz');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('htopbarcolors_desc', 'theme_baz'), FORMAT_MARKDOWN));
    $page->add($setting);

    $name = 'theme_baz/colortopbarbg';
    $title = get_string('colortopbarbg', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_baz/colortopbartext';
    $title = get_string('colortopbartext', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_baz/colortopbarbtn';
    $title = get_string('colortopbarbtn', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_baz/colortopbarbtntext';
    $title = get_string('colortopbarbtntext', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_baz/colortopbarbtnhover';
    $title = get_string('colortopbarbtnhover', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_baz/colortopbarbtnhovertext';
    $title = get_string('colortopbarbtnhovertext', 'theme_baz');
    $description = get_string('colordesc_desc', 'theme_baz');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
