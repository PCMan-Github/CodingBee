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

// Dashboard settings.
$page = new admin_settingpage('theme_baz_settingsdashboard', get_string('settingsdashboard', 'theme_baz'));

	$name = 'theme_baz/setdashboardlayout';
	$title = get_string('setdashboardlayout', 'theme_baz');
	$description = get_string('setdashboardlayout_desc', 'theme_baz');
	$options = [];
	$options[1] = get_string('loginlayout1', 'theme_baz');
	$options[2] = get_string('loginlayout2', 'theme_baz');
	$options[3] = get_string('loginlayout3', 'theme_baz');
	$setting = new admin_setting_configselect($name, $title, $description, 1, $options);
	$page->add($setting);

	$name = 'theme_baz/customdcolsize';
	$title = get_string('customdcolsize', 'theme_baz');
	$description = get_string('customdcolsize_desc', 'theme_baz');
	$default = '';
	$setting = new admin_setting_configtext($name, $title, $description, $default);
	$setting->set_updatedcallback('theme_reset_all_caches');
	$page->add($setting);

$settings->add($page);