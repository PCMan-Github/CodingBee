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


$page = new admin_settingpage('theme_baz_settingsfooter', get_string( 'settingsfooter', 'theme_baz'));

        $name = 'theme_baz/showfooterbuttons';
        $title = get_string('showfooterbuttons', 'theme_baz');
        $description = get_string('showfooterbuttons_desc', 'theme_baz');
        $default = 1;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
        $page->add($setting);

        $name = 'theme_baz/footerblock1';
        $title = get_string('footerblock1', 'theme_baz');
        $description = get_string('footerblock1_desc', 'theme_baz');
        $default = '<p>Sample paragraph lorem ipsum dolar set...</p>
        <ol class="px-4 mt-3">
            <li>Stanrad list</li>
            <li>Lorem ipsum</li>
            <li>Dolar set...</li>
        </ol>';
        $setting = new baz_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);

        $name = 'theme_baz/footerblock2';
        $title = get_string('footerblock2', 'theme_baz');
        $description = get_string('footerblock2_desc', 'theme_baz');
        $default = '<div class="rui-footer-nav row text-sm-center text-md-left my-5">
        <div class="col-sm-12 col-md-6 col-lg my-sm-2 my-md-0">
            <h5 class="rui-footer-nav-title">Company</h5>
            <ul class="rui-footer-nav-items list-unstyled mb-0">
                <li> <a href="#">About Us</a> </li>
                <li> <a href="#">Blog</a><span class="badge-xs badge-success ml-2">New</span> </li>
                <li> <a href="#">FAQ</a> </li>
                <li> <a href="#">Contact</a> </li>
                <li> <a href="#">Help</a> </li>
            </ul>
        </div>
        <div class="col-sm-12 col-md-6 col-lg my-sm-2 my-md-0">
            <h5 class="rui-footer-nav-title">Products</h5>
            <ul class="rui-footer-nav-items list-unstyled mb-0">
                <li> <a href="#">Parents</a> </li>
                <li> <a href="#">Schools</a> </li>
                <li> <a href="#">Partners</a> </li>
            </ul>
        </div>
        <div class="col-sm-12 col-md-6 col-lg my-sm-2 my-md-0">
            <h5 class="rui-footer-nav-title">Legal</h5>
            <ul class="rui-footer-nav-items list-unstyled mb-0">
                <li> <a href="#">Privacy Policy</a> </li>
                <li> <a href="#">Terms of Service</a> </li>
            </ul>
        </div>
        <div class="col-sm-12 col-md-6 col-lg my-sm-2 my-md-0">
            <h5 class="rui-footer-nav-title">Office</h5>
            <ul class="rui-footer-nav-items list-unstyled mb-0">
                <li>
                    <p> Victoria Garden City, Lagos </p>
                </li>
                <li> 1234 Fruitvale Avenue, Oakland, Califonia, USA. </li>
            </ul>
        </div>
    </div>';
        $setting = new baz_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);

        $name = 'theme_baz/footerblock3';
        $title = get_string('footerblock3', 'theme_baz');
        $description = get_string('footerblock3_desc', 'theme_baz');
        $default = 'Get this theme exclusively on the ThemeForest: More premium moodle themes: <a href="https://rosea.io">rosea.io</a>';
        $setting = new baz_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);

        $name = 'theme_baz/footercopy';
        $title = get_string('footercopy', 'theme_baz');
        $description = get_string('footercopy_desc', 'theme_baz');
        $default = '<p><strong>Copyright © 2020 baz Moodle Theme. All right reserved.</strong></p>BAZ premium moodle theme by RoseaThemes.';
        $setting = new baz_setting_confightmleditor($name, $title, $description, $default);
        $page->add($setting);


// Must add the page after definiting all the settings!
$settings->add($page);
