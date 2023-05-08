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
 * A two column layout for the baz theme.
 *
 * @package   theme_baz
 * @copyright 2021 Marcin Czaja (https://rosea.io)
 * @license   Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('sidepre-open', PARAM_ALPHA);
user_preference_allow_ajax_update('darkmode-on', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

$draweropenright = false;
$extraclasses = [];

// Dark mode
if (isloggedin()) {
    if (theme_baz_get_setting('darkmodetheme') == '1') {
        $darkmodeon = (get_user_preferences('darkmode-on', 'false') == 'true'); //return 1
        if($darkmodeon) {
            $extraclasses[] = 'theme-dark'; 
        }
    }
    else {
        $darkmodeon = false;
    }
} 

// Check navigation style
if (theme_baz_get_setting('topbarhamburgermenu') == '1') {
    $extraclasses[] = 'rui-hamburger-menu'; 
}
// end

if (isloggedin()) {
    $draweropenright = (get_user_preferences('sidepre-open', 'true') == 'true');
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$sidecourseblocks = $OUTPUT->blocks('sidecourseblocks');
$ctopbl = $OUTPUT->blocks('ctopbl');
$cbottombl = $OUTPUT->blocks('cbottombl');
$cstopbl = $OUTPUT->blocks('cstopbl');
$csbottombl = $OUTPUT->blocks('csbottombl');

$hassidecourseblocks = strpos($sidecourseblocks, 'data-block=') !== false;

if ($draweropenright && $hasblocks) {
    $extraclasses[] = 'drawer-open-right';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;
$siteurl = $CFG->wwwroot;

//Theme Settings

// Start - Course Image Position
$couseimagesettings = theme_baz_get_setting('setcourseimage');
$courseimagecontent = false;
$courseimagefw = false;
if ($couseimagesettings == 1) {
    $courseimagefw = true;
} elseif ($couseimagesettings == 2) {
    $courseimagecontent = true;
}
// End - Course Image Position

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'ctopbl' => $ctopbl,
    'cbottombl' => $cbottombl,
    'cstopbl' => $cstopbl,
    'csbottombl' => $csbottombl,
    'hasctopbl' => !empty($ctopbl),
    'hascbottombl' => !empty($cbottombl),
    'hascstopbl' => !empty($cstopbl),
    'hascsbottombl' => !empty($csbottombl),
    'sidecourseblocks' => $sidecourseblocks,
    'hassidecourseblocks' => $hassidecourseblocks,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'draweropenright' => $draweropenright,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'darkmodeon' => $darkmodeon,
    'draweropenright' => $draweropenright,
    'siteurl' => $siteurl,
    'courseimagefw' => $courseimagefw,
    'courseimagecontent' => $courseimagecontent
];

// MODIFICATION START: Get and use the course page information banners HTML code, if any course page hints are configured.
$coursepageinformationbannershtml = theme_baz_get_course_information_banners();
if ($coursepageinformationbannershtml) {
    $templatecontext['coursepageinformationbanners'] = $coursepageinformationbannershtml;
}
// MODIFICATION END.

$isdefaultnav = theme_baz_get_setting('topbarhamburgermenu');
if ($isdefaultnav  == 1) {
    $nav = $PAGE->flatnav;
    theme_baz_extend_flat_navigation($PAGE->flatnav);
    $templatecontext['flatnavigation'] = $nav;
}

//Load theme settings
$themesettings = new \theme_baz\util\theme_settings();
$templatecontext = array_merge($templatecontext, $themesettings->global_settings());
$templatecontext = array_merge($templatecontext, $themesettings->footer_settings());

$PAGE->requires->js_call_amd('theme_baz/baz', 'init');
$PAGE->requires->js_call_amd('theme_baz/moremenu', 'init');
echo $OUTPUT->render_from_template('theme_baz/tmpl-course', $templatecontext);
