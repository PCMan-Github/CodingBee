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

// Content Builder
$page = new admin_settingpage('theme_baz_scb', get_string('scbsettings', 'theme_baz'));

$slotsArray = array(
    "1" => "1",
    "2" => "2",
    "3" => "3",
    "4" => "4",
    "5" => "5",
    "6" => "6",
    "7" => "7",
    "8" => "8",
    "9" => "9",
    "10" => "10",
    "11" => "11",
    "12" => "12",
    "13" => "13",
    "14" => "14",
    "15" => "15",
    "16" => "16",
    "17" => "17",
    "18" => "18",
    "19" => "19"
);

$name = 'theme_baz/block1';
$title = get_string('block1', 'theme_baz');
$description = get_string('block1_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '1', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block2';
$title = get_string('block2', 'theme_baz');
$description = get_string('block2_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '2', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block3';
$title = get_string('block3', 'theme_baz');
$description = get_string('block3_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '3', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block4';
$title = get_string('block4', 'theme_baz');
$description = get_string('block4_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '4', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block5';
$title = get_string('block5', 'theme_baz');
$description = get_string('block5_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '5', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block6';
$title = get_string('block6', 'theme_baz');
$description = get_string('block6_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '6', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block7';
$title = get_string('block7', 'theme_baz');
$description = get_string('block7_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '7', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block8';
$title = get_string('block8', 'theme_baz');
$description = get_string('block8_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '8', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block9';
$title = get_string('block9', 'theme_baz');
$description = get_string('block9_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '9', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block10';
$title = get_string('block10', 'theme_baz');
$description = get_string('block10_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '10', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block11';
$title = get_string('block11', 'theme_baz');
$description = get_string('block11_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '11', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block12';
$title = get_string('block12', 'theme_baz');
$description = get_string('block12_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '12', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block13';
$title = get_string('block13', 'theme_baz');
$description = get_string('block13_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '13', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block14';
$title = get_string('block14', 'theme_baz');
$description = get_string('block14_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '14', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block15';
$title = get_string('block15', 'theme_baz');
$description = get_string('block15_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '15', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block16';
$title = get_string('block16', 'theme_baz');
$description = get_string('block16_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '16', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block17';
$title = get_string('block17', 'theme_baz');
$description = get_string('block17_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '17', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block18';
$title = get_string('block18', 'theme_baz');
$description = get_string('block18_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '18', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block19';
$title = get_string('block19', 'theme_baz');
$description = get_string('block19_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '19', $slotsArray);
$page->add($setting);

/*$name = 'theme_baz/block20';
$title = get_string('block20', 'theme_baz');
$description = get_string('block20_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '20', $slotsArray);
$page->add($setting);

$name = 'theme_baz/block21';
$title = get_string('block21', 'theme_baz');
$description = get_string('block21_desc', 'theme_baz');
$setting = new admin_setting_configselect($name, $title, $description, '21', $slotsArray);
$page->add($setting);*/

$settings->add($page);