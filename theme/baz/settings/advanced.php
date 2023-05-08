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

// Advanced settings.
$page = new admin_settingpage('theme_baz_advanced', get_string('advancedsettings', 'theme_baz'));

    // Raw SCSS to include before the content.
    $setting = new admin_setting_scsscode('theme_baz/scsspre',
        get_string('rawscsspre', 'theme_baz'), get_string('rawscsspre_desc', 'theme_baz'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Raw SCSS to include after the content.
    $setting = new admin_setting_scsscode('theme_baz/scss', get_string('rawscss', 'theme_baz'),
        get_string('rawscss_desc', 'theme_baz'), '', PARAM_RAW);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Font files upload.
    $name = 'theme_baz/fontfiles';
    $title = get_string('fontfilessetting', 'theme_baz', null, true);
    $description = get_string('fontfilessetting_desc', 'theme_baz', null, true);
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'fontfiles', 0,
            array('maxfiles' => 100, 'accepted_types' => array('.ttf', '.eot', '.woff', '.woff2')));
    $page->add($setting);

    // TODO:
    // - link to FontAwesome
    // - link to BootstrapIcon
    // - CustomFonts
    // - SelfHosted
    // ------
    // - Custom HTML HEAD
    // - Custom HTML FOOTER
    // ------
    // - Custom Modal

    // - Show hint in hidden courses
    // - Show hint for guest access
    // - Show Theme Author Info

$settings->add($page);
