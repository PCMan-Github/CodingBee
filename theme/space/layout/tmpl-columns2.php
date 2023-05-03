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
 * A two column layout for the space theme.
 *
 * @package    theme_space
 * @copyright  Copyright © 2018 onwards, Marcin Czaja | RoseaThemes, rosea.io - Rosea Themes
 * @license    Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();
global $DB;

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
// MODIFICATION Start: Require own locallib.php.
require_once($CFG->dirroot . '/theme/space/locallib.php');
// MODIFICATION END.

$bodyattributes = $OUTPUT->body_attributes([]);
$siteurl = $CFG->wwwroot;
$extraclasses = [];

//for mobile view
$device = core_useragent::get_device_type();
$removesidebar = theme_space_get_setting('removesidebar');

if (!$removesidebar) {
    if (isloggedin()) {
        if ($device == 'mobile' ) {
            $navdraweropen = false;
        } else {
            $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
        }
    } else {
        $navdraweropen = false;
    }

    if ($navdraweropen) {
        $extraclasses[] = 'drawer-open-left';
    }
} else {
    $navdraweropen = false;
}

//Top bar style
$topbarstyle = theme_space_get_setting('topbarstyle');
$pluginsettings = get_config("theme_space");
if( $topbarstyle == "topbarstyle-1") { $topbarstyle1 = $topbarstyle; } else { $topbarstyle1 = false; }
if( $topbarstyle == "topbarstyle-2") { $topbarstyle2 = $topbarstyle; } else { $topbarstyle2 = false; }
if( $topbarstyle == "topbarstyle-3") { $topbarstyle3 = $topbarstyle; } else { $topbarstyle3 = false; }
if( $topbarstyle == "topbarstyle-4") { $topbarstyle4 = $topbarstyle; } else { $topbarstyle4 = false; }
if( $topbarstyle == "topbarstyle-5") { $topbarstyle5 = $topbarstyle; } else { $topbarstyle5 = false; }
if( $topbarstyle == "topbarstyle-6") { $topbarstyle6 = $topbarstyle; } else { $topbarstyle6 = false; }
if( $topbarstyle == "topbarstyle-7") { $topbarstyle7 = $topbarstyle; } else { $topbarstyle7 = false; }
//end

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$blockshtml2 = $OUTPUT->blocks('sidebar');
$blockshtml3 = $OUTPUT->blocks('maintopwidgets');
$blockshtml4 = $OUTPUT->blocks('mainfwidgets');
$blockshtml5 = $OUTPUT->blocks('sidebar-top');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$siteurl = $CFG->wwwroot;


$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

//Top bar style
$topbarstyle = theme_space_get_setting('topbarstyle');
$pluginsettings = get_config("theme_space");
for ($i = 1; $i <= 6; $i++) {
    if( $topbarstyle == "topbarstyle-" . $i) { ${"topbarstyle" . $i} = $topbarstyle; } else { ${"topbarstyle" . $i} = false; }
}
//end

if(is_siteadmin()){
    $isadmin = 1;
}

$isteacher = $DB->count_records('role_assignments', ['roleid' => 3, 'userid' => $USER->id]);
$isstudent = $DB->count_records('role_assignments', ['roleid' => 5, 'userid' => $USER->id]);

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'sidebarblocks' => $blockshtml2,
    'maintopwidgets' => $blockshtml3,
    'mainfwidgets' => $blockshtml4,
    'sidebartopblocks' => $blockshtml5,
    'hasblocks' => $hasblocks,
    'hasmaintopwidgets' => !empty($blockshtml3),
    'hasmainfwidgets' => !empty($blockshtml4),
    'hassidebarblocks' => !empty($blockshtml2),
    'hassidebartopblocks' => !empty($blockshtml5),
    'navdraweropen' => $navdraweropen,
    'bodyattributes' => $bodyattributes,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'siteurl' => $siteurl,
    'isadmin' => $isadmin,
    'isteacher' => $isteacher,
    'isstudent' => $isstudent
];

// Top bar Styles - add element to the array
for ($i = 1; $i <= 6; $i++) {
    $n = "topbarstyle" . $i;
    $templatecontext[$n] = ${"topbarstyle" . $i};
}
//End

// Improve space navigation.
$boostfumblingnav = theme_space_get_setting('boostfumblingnav');
if (!$boostfumblingnav) {
    theme_space_extend_flat_navigation($PAGE->flatnav);
}
$nav = $PAGE->flatnav;
$templatecontext['flatnavigation'] = $nav;
$templatecontext['firstcollectionlabel'] = $nav->get_collectionlabel();

$themesettings = new \theme_space\util\theme_settings();

$templatecontext = array_merge($templatecontext, $themesettings->head_elements());
$templatecontext = array_merge($templatecontext, $themesettings->footer_items());
$templatecontext = array_merge($templatecontext, $themesettings->customnav());
$templatecontext = array_merge($templatecontext, $themesettings->sidebar_custom_block());
$templatecontext = array_merge($templatecontext, $themesettings->top_bar_custom_block());
$templatecontext = array_merge($templatecontext, $themesettings->fonts());
echo $OUTPUT->render_from_template('theme_space/columns2', $templatecontext);
