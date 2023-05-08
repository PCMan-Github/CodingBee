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


$page = new admin_settingpage('theme_baz_settingscourses', get_string( 'settingscourses', 'theme_baz'));

   $name = 'theme_baz/ipcoursesummary';
   $title = get_string('ipcoursesummary', 'theme_baz');
   $description = get_string('ipcoursesummary_desc', 'theme_baz');
   $default = 1;
   $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
   $page->add($setting);
   
   $name = 'theme_baz/ipcourseimage';
   $title = get_string('ipcourseimage', 'theme_baz');
   $description = get_string('ipcourseimage_desc', 'theme_baz');
   $default = 1;
   $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
   $page->add($setting);

   $name = 'theme_baz/setcourseimage';
   $title = get_string('setcourseimage','theme_baz');
   $setting = new admin_setting_configselect($name,$title,'',1, array(
       0 => get_string('none', 'theme_baz'),
       1 => get_string('courseimagefullwidth', 'theme_baz'),
       2 => get_string('courseimagecontent', 'theme_baz')
   ) );
   $page->add($setting);
   
    $name = 'theme_baz/cccteachers';
    $title = get_string('cccteachers', 'theme_baz');
    $description = get_string('cccteachers_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    //Progress Bar
    $name = 'theme_baz/courseprogressbar';
    $title = get_string('courseprogressbar', 'theme_baz');
    $description = get_string('courseprogressbar_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);


    // Start - Course Navigation
    $name = 'theme_baz/hcoursenavitems';
	$heading = get_string('hcoursenavitems', 'theme_baz');
	$setting = new admin_setting_heading($name, $heading, format_text(get_string('hcoursenavitems_desc', 'theme_baz'), FORMAT_MARKDOWN));
	$page->add($setting);

    $name = 'theme_baz/isitemonparticipants';
    $title = get_string('isitemonparticipants', 'theme_baz');
    $description = get_string('isitemonparticipants_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemonbadges';
    $title = get_string('isitemonbadges', 'theme_baz');
    $description = get_string('isitemonbadges_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemoncompetencies';
    $title = get_string('isitemoncompetencies', 'theme_baz');
    $description = get_string('isitemoncompetencies_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/isitemongrades';
    $title = get_string('isitemongrades', 'theme_baz');
    $description = get_string('isitemongrades_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);
    // End - Course Navigation


    // Start - Course Card
    $name = 'theme_baz/hcoursecard';
	$heading = get_string('hcoursecard', 'theme_baz');
	$setting = new admin_setting_heading($name, $heading, format_text(get_string('hcoursecard_desc', 'theme_baz'), FORMAT_MARKDOWN));
	$page->add($setting);

    $name = 'theme_baz/cccfooter';
    $title = get_string('cccfooter', 'theme_baz');
    $description = get_string('cccfooter_desc', 'theme_baz');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_baz/stringaccess';
    $title = get_string('stringaccess', 'theme_baz');
    $description = get_string('stringaccess_desc', 'theme_baz');
    $default = 'Get access';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    //Customize Course Card Desc Limit
    $name = 'theme_baz/coursecarddesclimit';
    $title = get_string('coursecarddesclimit', 'theme_baz');
    $description = get_string('coursecarddesclimit_desc', 'theme_baz');
    $setting = new admin_setting_configtext($name, $title, $description,'100');
    $page->add($setting);
    // End - Course Card

// Must add the page after definiting all the settings!
$settings->add($page);
