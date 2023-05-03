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

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
// MODIFICATION Start: Require own locallib.php.
require_once($CFG->dirroot . '/theme/space/locallib.php');
// MODIFICATION END.

$extraclasses = [];
$frontpagenavdrawer = theme_space_get_setting('displaynavdrawerfp');
$device = core_useragent::get_device_type();

$removesidebar= theme_space_get_setting('removesidebar');
if (!$removesidebar) {
    if ($frontpagenavdrawer == 0) {
        $navdraweropen = false;
        $extraclasses[] = 'drawer-open-hidden';
    } else {
        if (isloggedin()) {
            if ($device == 'mobile' ) {
                $navdraweropen = false;
            } else {
                $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
            }
        } else {
            $navdraweropen = false;
        }
    }
} else {
    $navdraweropen = false;
}

$teammember = theme_space_get_setting('teammemberno');
if ($teammember == 1) {
    $teammemberperrow = ' col-md-4 col-lg-2';
}
if ($teammember == 2) {
    $teammemberperrow = ' col-md-4 col-lg-3';
}
if ($teammember == 3) {
    $teammemberperrow = ' col-md-4 col-lg-4';
}

$logos = theme_space_get_setting('logosperrow');
if ($logos == 1) {
    $logosno = 'col-md-4 col-lg-2';
}
if ($logos == 2) {
    $logosno = 'col-md-4 col-lg-3';
}
if ($logos == 3) {
    $logosno = 'col-md-4 col-lg-4';
}

$isslider = false;
if (theme_space_get_setting('sliderenabled', true) == true || theme_space_get_setting('fpblock11', true) == true || theme_space_get_setting('fpblock12', true) == true || theme_space_get_setting('fpblock13', true) == true || theme_space_get_setting('FPLogos', true) == true || theme_space_get_setting('FPTeam', true) == true) {
    $isslider = true;
}

//Simple content builder
$elements = 14;
$pluginsettings = get_config("theme_space");
for ($i = 1; $i <= $elements; $i++) {
    ${"slotblock". $i} = theme_space_get_setting("slotblock" . $i);
}

$showfpblock1hr = theme_space_get_setting('showfpblock1hr');
$showfpblock2hr = theme_space_get_setting('showfpblock2hr');
$showfpblock4hr = theme_space_get_setting('showfpblock4hr');
$showfpblock6hr = theme_space_get_setting('showfpblock6hr');
$showfpblock7hr = theme_space_get_setting('showfpblock7hr');
$showfpblock8hr = theme_space_get_setting('showfpblock8hr');
$showfpblock10hr = theme_space_get_setting('showfpblock10hr');
$showfpblock11hr = theme_space_get_setting('showfpblock11hr');
$showfpblock12hr = theme_space_get_setting('showfpblock12hr');
$showfpblockteamhr = theme_space_get_setting('showfpblockteamhr');

$heroshadowtype = $pluginsettings->heroshadowtype;
if ($heroshadowtype == 1) {
    $heroshadowstyle = 'c-hero-shadow-gradient';
}
if ($heroshadowtype == 2) {
    $heroshadowstyle = 'c-hero-shadow-img';
}

//Simple content builder
for ($i = 1; $i <= $elements; $i++) {
    ${"slotblock". $i} = $pluginsettings->{"slotblock" . $i};

    for ($j = 1; $j <= $elements; $j++) {
        if( ${"slotblock" . $j} == "$i")
        {
            ${"slot" . $i . "block" . $j} = true;
        } else
        {
            ${"slot" . $i . "block" . $j}  = false;
        }

    }
}
//End

//Top bar style
$topbarstyle = theme_space_get_setting('topbarstyle');
$pluginsettings = get_config("theme_space");
for ($i = 1; $i <= 6; $i++) {
    if( $topbarstyle == "topbarstyle-" . $i) { ${"topbarstyle" . $i} = $topbarstyle; } else { ${"topbarstyle" . $i} = false; }
}
//end

$siteurl = $CFG->wwwroot;

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

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'navdraweropen' => $navdraweropen,
    'teammemberperrow' => $teammemberperrow,
    'logosno' => $logosno,
    'showfpblock1hr' => $showfpblock1hr,
    'showfpblock2hr' => $showfpblock2hr,
    'showfpblock4hr' => $showfpblock4hr,
    'showfpblock6hr' => $showfpblock6hr,
    'showfpblock7hr' => $showfpblock7hr,
    'showfpblock8hr' => $showfpblock8hr,
    'showfpblock11hr' => $showfpblock11hr,
    'showfpblock12hr' => $showfpblock12hr,
    'showfpblock10hr' => $showfpblock10hr,
    'showfpblockteamhr' => $showfpblockteamhr,
    'heroshadowstyle' => $heroshadowstyle,
    'isslider' => $isslider,
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
    'siteurl' => $siteurl
];

// Top bar Styles - add element to the array
for ($i = 1; $i <= 6; $i++) {
    $n = "topbarstyle" . $i;
    $templatecontext[$n] = ${"topbarstyle" . $i};
}
//End

// Content Builder - add element to the array
for ($i = 1; $i <= $elements; $i++) {
    for ($j = 1; $j <= $elements; $j++) {
        $n = "slot" . $i . "block" . $j;
        $templatecontext[$n] = ${"slot" . $i . "block" . $j};
    }
}
//End content buidler

// Improve space navigation.
$boostfumblingnav = theme_space_get_setting('boostfumblingnav');
if (!$boostfumblingnav) {
    theme_space_extend_flat_navigation($PAGE->flatnav);
}
$templatecontext['flatnavigation'] = $PAGE->flatnav;


$themesettings = new \theme_space\util\theme_settings();

$templatecontext = array_merge($templatecontext, $themesettings->frontpage_elements());
$templatecontext = array_merge($templatecontext, $themesettings->footer_items());
$templatecontext = array_merge($templatecontext, $themesettings->hero());
$templatecontext = array_merge($templatecontext, $themesettings->blockcategories());
$templatecontext = array_merge($templatecontext, $themesettings->block1());
$templatecontext = array_merge($templatecontext, $themesettings->block2());
$templatecontext = array_merge($templatecontext, $themesettings->block3());
$templatecontext = array_merge($templatecontext, $themesettings->block4());
$templatecontext = array_merge($templatecontext, $themesettings->block10());
$templatecontext = array_merge($templatecontext, $themesettings->block11());
$templatecontext = array_merge($templatecontext, $themesettings->block12());
$templatecontext = array_merge($templatecontext, $themesettings->team());
$templatecontext = array_merge($templatecontext, $themesettings->logos());
$templatecontext = array_merge($templatecontext, $themesettings->customnav());
$templatecontext = array_merge($templatecontext, $themesettings->sidebar_custom_block());
$templatecontext = array_merge($templatecontext, $themesettings->top_bar_custom_block());
$templatecontext = array_merge($templatecontext, $themesettings->siemaSlider());
$templatecontext = array_merge($templatecontext, $themesettings->head_elements());
$templatecontext = array_merge($templatecontext, $themesettings->fonts());

echo $OUTPUT->render_from_template('theme_space/frontpage', $templatecontext);
