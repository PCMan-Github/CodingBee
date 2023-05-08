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
 * Language file.
 *
 * @package   theme_baz
 * @copyright Copyright © 2021 onwards Marcin Czaja (https://rosea.io)
 * @license   Commercial https://themeforest.net/licenses
 */

defined('MOODLE_INTERNAL') || die();
$siteurl = $CFG->wwwroot;

//$string['backgroundimage'] = 'Background image';
//$string['backgroundimage_desc'] = 'The image to display as a background of the site. The background image you upload here will override the background image in your theme preset files.';
$string['bootswatch'] = 'Bootswatch';
$string['bootswatch_desc'] = 'A bootswatch is a set of Bootstrap variables and css to style Bootstrap';
$string['choosereadme'] = '<div class="text-center"><img src="'.$siteurl.'/theme/baz/doc/baz-icon.svg" class="img-fluid rounded my-3" width="80" height="80" alt="BAZ Moodle Theme" /><h2>BAZ</h2>
<p class="lead-5">Premium Moodle Theme designed by RoseaThemes.</p>
<p class="lead-2 my-5">I want to make beautiful things<br />even if nobody cares.</p>
<p>Without a shadow of a doubt, BAZ has something for everyone and looks amazing!</p>
<hr /><h4>Support</h4><p>Need help with theme customization?<br />or you want to report a bug?</p><a href="https://rosea.gitbook.io/baztheme" target="_blank" class="btn btn-success m-1">Documentation</a><a href="https://roseathemes.ticksy.com" target="_blank" class="btn btn-info m-1">Dedicated ticket system</a></div><hr class="hr-small" /><p class="dimmed small text-center">For my wife Karolina and children, Bruno, Antoni and Zosia.<br /><br />Made in Poland</p>';
$string['currentinparentheses'] = '(current)';
$string['configtitle'] = 'BAZ';
$string['generalsettings'] = 'General settings';
$string['wrappermdfw'] = 'Remove Narrow Container';
$string['wrappermdfw_desc'] = 'Remove narrow wrapper inside the content.';
$string['nobootswatch'] = 'None';
$string['pluginname'] = 'BAZ (1.5.2)';
$string['privacy:metadata'] = 'The baz theme does not store any personal data about any user.';
$string['rawscss'] = 'Raw SCSS';
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';
$string['rawscsspre'] = 'Raw initial SCSS';
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';
$string['privacy:metadata:preference:sidebaropen'] = 'The user\'s preference for hiding or showing the drawer menu navigation.';
$string['privacy:metadata:preference:darkmodeon'] = 'The user\'s preference for dark mode';
$string['privacy:rightdrawerclosed'] = 'The current preference for the navigation drawer is closed.';
$string['privacy:rightdraweropen'] = 'The current preference for the navigation drawer is open.';
$string['privacy:darkmodeoff'] = 'The current preference for the dark mode is off.';
$string['privacy:darkmodeon'] = 'The current preference for the dark mode is on.';
$string['totop'] = 'Go to top';

$string['region-side-pre'] = 'Hidden Sidebar';
$string['region-dtopblocks'] = 'Dashboard Top Blocks';
$string['region-dbottomblocks'] = 'Dashboard Bottom Blocks';
$string['region-dleftblocks'] = 'Dashboard Left Blocks';
$string['region-dmiddleblocks'] = 'Dashboard Middle Blocks';
$string['region-drightblocks'] = 'Dashboard Right Blocks';
$string['region-sidecourseblocks'] = 'Course - Sidebar Blocks';
$string['region-ctopbl'] = 'Course - Top Blocks';
$string['region-cbottombl'] = 'Course - Bottom Blocks';
$string['region-cstopbl'] = 'Course Sections - Top Blocks';
$string['region-csbottombl'] = 'Course Sections - Bottom Blocks';

$string['darkmodetheme'] = 'Dark Mode (beta)';
$string['darkmodetheme_desc'] = 'It is a beta version of the dark layout.';

$string['themeauthor'] = 'Theme Author';
$string['themeauthor_desc'] = 'Show information about the author of the theme. - in the source code.';

$string['thiscourse'] = 'Course Sections';
$string['nothiscourse'] = 'We cannot identify any course sections or topics';

$string['showhintcoursehiddensetting'] = 'Show hint in hidden courses';
$string['showhintcoursehiddensetting_desc'] = 'With this setting a hint will appear in the course header as long as the visibility of the course is hidden. This helps to identify the visibility state of a course at a glance without the need for looking at the course settings.';
$string['showhintcoursehiddensettingslink'] = 'You can change the visibility in the <a href="{$a->url}">course settings</a>.';
$string['showhintcoursehiddengeneral'] = 'This course is currently <strong>hidden</strong>. Only enrolled teachers can access this course when hidden.';


//Edit Button Text
$string['editon'] = 'Turn Edit On';
$string['editoff'] = 'Turn Edit Off';
$string['left'] = 'Left';
$string['center'] = 'Center';
$string['right'] = 'Right';


// Custom Alert
$string['alertsettings'] = 'Custom Alert';
$string['displaycustomalert'] = 'Display Custom Alert';
$string['displaycustomalert_desc'] = '';
$string['closecustomalert'] = 'Close Custom Alert Permanently';
$string['closecustomalert_desc'] = '<p class="small">Clear browsing data to see alert again</p>';
$string['customalert'] = 'Custom Alert Content';
$string['customalert_desc'] = '';

// SEO
$string['seosettings'] = 'SEO';
$string['seometadesc'] = 'Meta Description';
$string['seometadesc_desc'] = '';
$string['seoappletouchicon'] = 'Apple Touch Icon';
$string['seoappletouchicon_desc'] = 'Apple Touch Icon';
$string['seothemecolor'] = 'SEO Theme Color';
$string['seothemecolor_desc'] = 'The theme-color value for the name attribute of the <meta> element indicates a suggested color that user agents should use to customize the display of the page or of the surrounding user interface. <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta/name/theme-color" target="_blank">Learn more about theme-color tag</a>';
$string['seomanifestjson'] = 'Manifest JSON';
$string['seomanifestjson_desc'] = '';

//Course Card
$string['cccteachers'] = 'Display teachers section';
$string['cccteachers_desc'] = 'Display teachers section on the main course page and inside course card.<br /><div class="mt-3 w-100 text-left"><img src="'.$siteurl.'/theme/baz/doc/teachers.png" class="img-fluid rounded" style="max-width: 400px;" alt="BAZ Moodle Theme" /></div>';

$string['cccfooter'] = 'Display "Get access" buttons';
$string['cccfooter_desc'] = '';

$string['stringaccess'] = 'Get access';
$string['stringaccess_desc'] = '';

$string['cccdteachers'] = 'Show Course Card Teachers Section';
$string['cccdteachers_desc'] = '';

$string['cccdfooter'] = 'Show Course Card Footer <small>(Get access)</small>';
$string['cccdfooter_desc'] = '';

$string['coursecarddesclimit'] = 'Course Card Description - Text Limit';
$string['coursecarddesclimit_desc'] = '<span class="badge-sm badge-secondary"><strong class="mr-1">Example (text length - letters):</strong> 120</span>';

// Course Index Page
$string['ipcoursesummary'] = 'Display course summary (Course Index Page)';
$string['ipcoursesummary_desc'] = '';
$string['ipcourseimage'] = 'Display course image (Course Index Page)';
$string['ipcourseimage_desc'] = '';

// Headings
$string['hlogin'] = 'Login Page';
$string['hlogin_desc'] = 'Login page customization. You can select three layouts, add background and more.';
$string['hsignup'] = 'Sign up Page';
$string['hsignup_desc'] = 'Customization of the sign up page.';
$string['hcoursecard'] = 'Course Card';
$string['hcoursecard_desc'] = 'Customize course card e.g Get access label. <br /><img src="'.$siteurl.'/theme/baz/doc/course-card.jpg" class="img-fluid rounded mt-3 w-100" style="max-width: 400px;" alt="BAZ Moodle Theme" />';

// Settings -- Advanced
$string['advancedsettings'] = 'Advanced settings';
$string['googleanalytics'] = 'Google Analytics V4 Code';
$string['googleanalyticsdesc'] = 'Please enter your Google Analytics V4 code to enable analytics on your website. The code format shold be like [G-XXXXXXXXXX]';
$string['favicon'] = 'Custom favicon';
$string['favicon_desc'] = 'Upload your own favicon.  It should be an .ico file.';
$string['fontfilessetting'] = 'Font files';
$string['fontfilessetting_desc'] = 'With this dialogue you can upload own font files. The uplaod is resricted to the font files of type .eot, .woff, .woff2, .ttf and .svg. <br/>
<strong>Important:</strong> To be able to use the uploaded fonts within this theme, you have to add related code to your "Raw SCSS" area in the tab "Advanced Tab". <a href="#">Learn more</a>';

// Settings -- Login page
$string['settingslogin'] = 'Login Page';
$string['setloginlayout'] = 'Login Page Layout';
$string['setloginlayout_desc'] = '<img src="'.$siteurl.'/theme/baz/doc/login-layout.png" class="img-fluid rounded my-3 w-100" alt="BAZ Moodle Theme" />';
$string['loginlayout1'] = 'Layout #1';
$string['loginlayout2'] = 'Layout #2';
$string['loginlayout3'] = 'Layout #3';
$string['loginadditionalcontent'] = 'Layout #1<br />Additional Content';
$string['loginadditionalcontent_desc'] = '';
$string['loginadditionalcontent2'] = 'Layout #2<br />Additional Content';
$string['loginadditionalcontent2_desc'] = '';
$string['loginfootercontent'] = 'Footer Content';
$string['loginfootercontent_desc'] = '';
$string['loginbg'] = 'Login Page Background';
$string['loginbg_desc'] = '';
$string['customloginlogo'] = 'Custom Logo on the Login Page';
$string['customloginlogo_desc'] = '<strong>Recommendation:</strong> SVG files or png files with transparent background.';
$string['colorloginbgtext'] = 'Text Color';
$string['colorloginbgtext_desc'] = 'Color of the text like "Create an account"';
$string['loginintrotext'] = 'Log in - Introduction';
$string['loginintrotext_desc'] = '';
$string['stringca'] = 'Label next to "Create an account" button.';
$string['stringca_desc'] = 'Label displays on the Sign in page';
$string['stringbacktologin'] = 'Label next to "Log in"';
$string['stringbacktologin_desc'] = 'Label displays on the Sign up page';
$string['signuptext'] = 'Sign up Content';
$string['signuptext_desc'] = '';
$string['signupintrotext'] = 'Sign up - Introduction';
$string['signupintrotext_desc'] = '';

//Repeatable
$string['none'] = 'None';
$string['haccordionend'] = '';
$string['haccordionend_desc'] = '';
$string['blockintrotitle'] = 'Intro Title';
$string['blockintrotitle_desc'] = '';
$string['blockintrocontent'] = 'Intro Content';
$string['blockintrocontent_desc'] = '';
$string['blockhtmlcontent'] = 'HTML Content';
$string['blockhtmlcontent_desc'] = '';
$string['blockfootercontent'] = 'Footer Content';
$string['blockfootercontent_desc'] = '';
$string['turnon'] = 'Turn on';
$string['turnon_desc'] = '';

// Settings -- Course page
$string['settingscourses'] = 'Course Page';
$string['setcourseimage'] = 'Course image';
$string['courseimagefullwidth'] = 'Full width';
$string['courseimagecontent'] = 'Inside the content';
$string['courseprogressbar'] = 'Display Course Progress Bar';
$string['courseprogressbar_desc'] = '<div class="w-100 text-left"><img src="'.$siteurl.'/theme/baz/doc/course-progress.png" class="img-fluid rounded w-100" alt="BAZ Moodle Theme" style="max-width: 400px;" /></div>';



// Settings -- Top Bar
$string['settingstopbar'] = 'Top Bar';
$string['topbarhamburgermenu'] = 'Hamburger Menu';
$string['topbarhamburgermenu_desc'] = 'Display hamburger menu instead horizontal navigation. <br /><div class="w-100 text-left"><img src="'.$siteurl.'/theme/baz/doc/hamburger-menu.jpg" class="img-fluid rounded my-3 w-100" alt="BAZ Moodle Theme" /></div>';
$string['hmycoursesbtn'] = 'My Courses Button';
$string['hmycoursesbtn_desc'] = '';
// Depreciated in moodle 4.0
// $string['stringmycourses'] = 'My Courses';
// $string['stringmycourses_desc'] = '';
$string['stringallcourses'] = 'List of all available courses';
$string['stringallcourses_desc'] = 'Leave this field empty if you want to hide this button.';
$string['stringnocourses'] = 'You are not enrolled in any courses.';
$string['stringnocourses_desc'] = '';
$string['stickybreadcrumbs'] = 'Sticky Breadcrumbs';
$string['stickybreadcrumbs_desc'] = '';

$string['htopbarcolors'] = 'Color Customization';
$string['htopbarcolors_desc'] = '';
$string['colortopbarbg'] = 'Topbar Background';
$string['colortopbartext'] = 'Text';
$string['colortopbarbtn'] = 'Button Background';
$string['colortopbarbtntext'] = 'Button Text';
$string['colortopbarbtnhover'] = 'Button Background Hover';
$string['colortopbarbtnhovertext'] = 'Button Hover Text';


// Settings -- Dashboard
$string['settingsdashboard'] = 'Dashboard';
$string['setdashboardlayout'] = 'Dashboard Page Layout';
$string['setdashboardlayout_desc'] = '<img src="'.$siteurl.'/theme/baz/doc/dashboard-layout.jpg" class="img-fluid rounded my-3 w-100" alt="BAZ Moodle Theme" />';
$string['dashboardlayout1'] = 'Layout #1';
$string['dashboardlayout2'] = 'Layout #2';
$string['dashboardlayout3'] = 'Layout #3';
$string['customdcolsize'] = 'Custom Dashboard Column Width (Sidebar Left/Right)';
$string['customdcolsize_desc'] = 'For Layout #2, #3<br />col-lg (50%), col-lg-3 (33%), col-lg-4 (25%)';

// Settings -- Footer
$string['settingsfooter'] = 'Footer';
$string['footerblock1'] = 'Footer Block #1';
$string['footerblock1_desc'] = '<span class="badge-sq badge-warning">More snippets will be added to the next theme release.</span>';
$string['footerblock2'] = 'Footer Block #2';
$string['footerblock2_desc'] = '<span class="badge-sq badge-warning">More snippets will be added to the next theme release.</span>';
$string['footerblock3'] = 'Footer Block #3';
$string['footerblock3_desc'] = '<span class="badge-sq badge-warning">More snippets will be added to the next theme release.</span>';
$string['footercopy'] = 'Footer Copy';
$string['footercopy_desc'] = '';
$string['showfooterbuttons'] = 'Display Footer Buttons';
$string['showfooterbuttons_desc'] = 'Default Moodle buttons on the footer like: Moodle Doc, Data retention summary, etc.';

// Content Builder
$string['scbsettings'] = 'Blocks Order';
$string['block1'] = 'Block #1';
$string['block1_desc'] = '';
$string['block2'] = 'Block #2';
$string['block2_desc'] = '';
$string['block3'] = 'Block #3';
$string['block3_desc'] = '';
$string['block4'] = 'Block #4';
$string['block4_desc'] = '';
$string['block5'] = 'Block #5';
$string['block5_desc'] = '';
$string['block6'] = 'Block #6';
$string['block6_desc'] = '';
$string['block7'] = 'Block #7';
$string['block7_desc'] = '';
$string['block8'] = 'Block #8';
$string['block8_desc'] = '';
$string['block9'] = 'Block #9';
$string['block9_desc'] = '';
$string['block10'] = 'Block #10';
$string['block10_desc'] = '';
$string['block11'] = 'Block #11';
$string['block11_desc'] = '';
$string['block12'] = 'Block #12';
$string['block12_desc'] = '';
$string['block13'] = 'Block #13';
$string['block13_desc'] = '';
$string['block14'] = 'Block #14';
$string['block14_desc'] = '';
$string['block15'] = 'Block #15';
$string['block15_desc'] = '';
$string['block16'] = 'Block #16';
$string['block16_desc'] = '';
$string['block17'] = 'Block #17';
$string['block17_desc'] = '';
$string['block18'] = 'Block #18';
$string['block18_desc'] = '';
$string['block19'] = 'Block #19';
$string['block19_desc'] = '';
$string['block20'] = 'Block #20';
$string['block20_desc'] = '';
$string['block21'] = 'Block #21';
$string['block21_desc'] = '';

$string['displayblockhr'] = 'Show Block Separator (hr)';
$string['displayblockhr_desc'] = '';

// Block 1
$string['settingsblock1'] = 'Block #1';;

$string['displayblock1_desc'] = '<small>Script: <a href="https://swiperjs.com/" target="_blank">Swiper</a>. MIT Licensed, v7.0.8 released on October 4, 2021</small>';

$string['hblock1slide'] = 'Slide';
$string['hblock1slide_desc'] = '';
$string['block1count'] = 'Slider count';
$string['block1count_desc'] = '';
$string['block1slideimg'] = 'Slide Image';
$string['block1slideimg_desc'] = '';
$string['block1slidetitle'] = 'Slide Heading';
$string['block1slidetitle_desc'] = '';
$string['block1slidecaption'] = 'Slide Caption';
$string['block1slidecaption_desc'] = '<br />If you want to display or hide some elements for non-logged in users use dedicated class names:<br /><ul><li>For non-logged in users: <strong>hidefornotloggedin</strong></li><li>For logged in users: <strong>hideforloggedin</strong></li></ul>';
//$string['block1slidebtns'] = 'Slide Buttons';
//$string['block1slidebtns_desc'] = '<br />If you want to display or hide some elements for non-logged in users use dedicated class names:<br /><ul><li>For non-logged in users: <strong>hidefornotloggedin</strong></li><li>For logged in users: <strong>hideforloggedin</strong></li></ul>';
$string['block1slidecss'] = 'Slide Custom CSS';
$string['block1slidecss_desc'] = '<a href="https://css-tricks.com/almanac/properties/b/background/" target="_blank">Learn more about CSS background properties</a>';
$string['showblock1sliderwrapper'] = 'Show Colorized Content Wrapper';
$string['showblock1sliderwrapper_desc'] = '';
$string['block1sliderwrapperbg'] = 'Content Wrapper Color';
$string['block1sliderwrapperbg_desc'] = '';
$string['block1wrapperalign'] = 'Content Wrapper Alignment';
$string['block1wrapperalign_desc'] = '';

// Block 2
$string['settingsblock2'] = 'Block #2';
$string['displayblock2_desc'] = '';

// Block 3
$string['settingsblock3'] = 'Block #3';
$string['displayblock3_desc'] = '';

// Block 4
$string['settingsblock4'] = 'Block #4';
$string['displayblock4_desc'] = '';
$string['hblock4'] = 'Custom HTML';
$string['hblock4_desc'] = 'You can use HTML to display accordion items or just add it using simple form below.';
$string['hblock4_2'] = 'FAQ Items';
$string['hblock4_2_desc'] = 'Add FAQ items manually.';
$string['block4count'] = 'Number of items';
$string['block4count_desc'] = 'Number of items';
$string['block4answer'] = 'Answer';
$string['block4answer_desc'] = '';
$string['block4question'] = 'Question';
$string['block4question_desc'] = '';

// Block 5
$string['hblock5item'] = 'Item';
$string['hblock5item_desc'] = '';
$string['settingsblock5'] = 'Block #5';
$string['displayblock5_desc'] = '';
$string['block5slidesperrow'] = 'Sldies per row';
$string['block5slidesperrow_desc'] = '';
$string['block5count'] = 'Number of items';
$string['block5count_desc'] = '';
$string['block5itemimg'] = 'Image';
$string['block5itemimg_desc'] = '';
$string['block5itemtitle'] = 'Title (Alt)';
$string['block5itemtitle_desc'] = '';
$string['block5itemurl'] = 'URL with (https:// or http://)';
$string['block5itemurl_desc'] = '';

// Block 6
$string['settingsblock6'] = 'Block #6';
$string['displayblock6_desc'] = '';

// Block 7
$string['settingsblock7'] = 'Block #7';
$string['displayblock7_desc'] = '';

// Block 8
$string['settingsblock8'] = 'Block #8';
$string['displayblock8_desc'] = '';

// Block 9
$string['settingsblock9'] = 'Block #9';
$string['displayblock9_desc'] = '';

// Block 10
$string['settingsblock10'] = 'Block #10';
$string['displayblock10_desc'] = '';

// Block 11
$string['settingsblock11'] = 'Block #11';
$string['displayblock11_desc'] = '';

// Block 12
$string['settingsblock12'] = 'Block #12';
$string['displayblock12_desc'] = '';

// Block 13
$string['settingsblock13'] = 'Block #13';
$string['displayblock13_desc'] = '';
$string['block13bg'] = 'Background Image';
$string['block13bg_desc'] = '';
$string['block13bgcolor'] = 'Background Color';
//TODO: dodac lepszy opis
$string['block13bgcolor_desc'] = 'You can use custom CSS for solid background or gradient.';
$string['block13customcss'] = 'Custom CSS (to set up backgroud properties)';
$string['block13customcss_desc'] = 'Tutorial: https://css-tricks.com/almanac/properties/b/background/';


// Block 14
$string['settingsblock14'] = 'Block #14';
$string['displayblock14_desc'] = '';
$string['block14bg'] = 'Background Image';
$string['block14bg_desc'] = '';
$string['block14customcss'] = 'Custom CSS (to set up backgroud properties)';
$string['block14customcss_desc'] = 'Tutorial: https://css-tricks.com/almanac/properties/b/background/';
$string['block14textalign'] = 'Text alignment';
$string['block14textalign_desc'] = '';

// Block 15
$string['settingsblock15'] = 'Block #15';
$string['displayblock15_desc'] = '';

// Block 16
$string['settingsblock16'] = 'Block #16';
$string['displayblock16_desc'] = '';

// Block 17
$string['settingsblock17'] = 'Block #17';
$string['displayblock17_desc'] = '';

// Block 18
$string['settingsblock18'] = 'Block #18';
$string['displayblock18_desc'] = '';

// Block 19
$string['settingsblock19'] = 'Block #19';
$string['displayblock19_desc'] = '';
$string['block19bg'] = 'Background Image';
$string['block19bg_desc'] = '';
$string['block19customcss'] = 'Custom CSS (to set up background properties)';
$string['block19customcss_desc'] = 'Tutorial: https://css-tricks.com/almanac/properties/b/background/';
$string['block19textalign'] = 'Text alignment';
$string['block19textalign_desc'] = '';

// // Block 20
// $string['settingsblock20'] = 'Block #20';
// $string['displayblock20_desc'] = '';

// // Block 21
// $string['settingsblock21'] = 'Block #21';
// $string['displayblock21_desc'] = '';


// Customization
$string['settingscustomization'] = 'Customization';
$string['hfontsettings'] = 'Fonts Settings';
$string['hfontsettings_desc'] = 'Customize Font Properties';
$string['hgooglefont'] = 'Google Font';
$string['hgooglefont_desc'] = 'Google Fonts is a library of 1,284 free licensed font families. <a href="https://fonts.google.com" targe="_blank">Google Fonts Library</a>.';
$string['googlefonturl'] = 'Google Font URL';
$string['googlefonturl_desc'] = "";
$string['fontbody'] = 'Font Name (Body)';
$string['fontbody_desc'] = "";
$string['fontheadings'] = 'Font Name (Headings)';
$string['fontheadings_desc'] = "";
$string['fontweightregular'] = 'Font weight: Regular';
$string['fontweightregular_desc'] = 'e.g 400';
$string['fontweightmedium'] = 'Font weight: Medium';
$string['fontweightmedium_desc'] = 'e.g 500, 600';
$string['fontweightbold'] = 'Font weight: Bold';
$string['fontweightbold_desc'] = 'e.g 700, 800, 900';
$string['fontweightheadings'] = 'Font weight (Headings)';
$string['fontweightheadings_desc'] = 'e.g 700, 800, 900';

$string['hgeneral'] = 'General Colors';
$string['hgeneral_desc'] = '';
$string['colorbodybg'] = 'Body Background Color';
$string['colorcontainerbg'] = 'Container Color';
$string['colorborder'] = 'Border Color (Global)';


$string['customlogo'] = 'Logo (Top Bar)';
$string['customlogo_desc'] = '';
$string['logocontainer'] = 'Logo - Solid Background Container';
$string['logocontainer_desc'] = '<img src="'.$siteurl.'/theme/baz/doc/logo-solid-bg.jpg" class="img-fluid rounded my-3" alt="BAZ Moodle Theme" />';

$string['hcolorstxt'] = 'Text Colors';
$string['hcolorstxt_desc'] = '';
$string['colorheadings'] = 'Headings';
$string['colorbody'] = 'Text Color';
$string['colorbodysecondary'] = 'Text Color (Secondary)';
$string['colorbodylight'] = 'Text Color (Light)';
$string['colorlink'] = 'Link Color';
$string['colorlinkhover'] = 'Link Color (Hover)';

$string['hcolorsgrays'] = 'Grays';
$string['hcolorsgrays_desc'] = 'Secondary buttons, borders etc.';
$string['colorgray100'] = 'Gray 100';
$string['colorgray200'] = 'Gray 200';
$string['colorgray300'] = 'Gray 300<br /><small class="mx-2">e.g main borders color</small>';
$string['colorgray400'] = 'Gray 400';
$string['colorgray500'] = 'Gray 500';
$string['colorgray600'] = 'Gray 600';
$string['colorgray700'] = 'Gray 700';
$string['colorgray800'] = 'Gray 800';
$string['colorgray900'] = 'Gray 900';
$string['colorwhite'] = 'White';
$string['colorblack'] = 'Black';

$string['hcolorsprimary'] = 'Primary colors';
$string['hcolorsprimary_desc'] = 'Primary buttons, top bar, all important/primary UI elements.';
$string['colorprimary100'] = 'Primary 100';
$string['colorprimary200'] = 'Primary 200';
$string['colorprimary300'] = 'Primary 300';
$string['colorprimary400'] = 'Primary 400';
$string['colorprimary500'] = 'Primary 500';
$string['colorprimary600'] = 'Primary 600 - Main Theme Color';
$string['colorprimary700'] = 'Primary 700';
$string['colorprimary800'] = 'Primary 800';
$string['colorprimary900'] = 'Primary 900';

$string['hcolorssecondary'] = 'Secondary Colors';
$string['hcolorssecondary_desc'] = 'Lighter color palette for UI interface like some backgrounds contains primary buttons etc.';
$string['colorsecondary100'] = 'Secondary 100';
$string['colorsecondary200'] = 'Secondary 200';
$string['colorsecondary300'] = 'Secondary 300';
$string['colorsecondary400'] = 'Secondary 400';
$string['colorsecondary500'] = 'Secondary 500';
$string['colorsecondary600'] = 'Secondary 600';
$string['colorsecondary700'] = 'Secondary 700';
$string['colorsecondary800'] = 'Secondary 800';
$string['colorsecondary900'] = 'Secondary 900';

$string['colordesc_desc'] = '';


$string['additionalclass'] = 'Additional Class Name';
$string['additionalclass_desc'] = '<strong class="badge badge-warning mr-2">Only for developers.</strong><span>You can add multiple class names e.g class1 class2 class3 </span>';

$string['hintro'] = '<img src="'.$siteurl.'/theme/baz/doc/baz-icon.svg" class="img-fluid rounded my-3" width="80" height="80" alt="BAZ Moodle Theme" /><div>BAZ Moodle Theme</div>';
$string['hintro_desc'] = '
<div class="mt-1 small">by <a href="https://rosea.io">RoseaThemes</a></div><hr class="mt-3" />
<div class="mt-3"><span class="badge badge-primary"><a href="https://rosea.gitbook.io/baztheme/changelog" target="_blank">Version: 1.5.2</a></span></div>
<div class="mt-4"><h3 class="lead-4 mb-2">Need help with theme customization?<br />Or you want to report a bug?</h3>Just let me know. Open <a href="https://roseathemes.ticksy.com" target="_blank">a ticket</a> or contact me via support form on the ThemeForest item page.</div>
<a href="https://rosea.gitbook.io/baztheme" target="_blank" class="btn btn-sm btn-dark mt-3">Online documentation</a>
';

//Credits: BoostCampus
$string['showhintcourseguestaccesssettinggeneral'] = 'You are currently viewing this course as <strong>{$a->role}</strong>.';
$string['showhintcourseguestaccesssettinglink'] = 'To have full access to the course, you can <a href="{$a->url}">self enrol into this course</a>.';
$string['showhintcoursehiddengeneral'] = 'This course is currently <strong>hidden</strong>. Only enrolled teachers can access this course when hidden.';
$string['showhintcoursehiddensettingslink'] = 'You can change the visibility in the <a href="{$a->url}">course settings</a>.';
$string['showhintcourseselfenrolstartcurrently'] = 'This course is currently visible and <strong>self enrolment without enrolment key</strong> is currently possible.';
$string['showhintcourseselfenrolstartfuture'] = 'This course is currently visible and <strong>self enrolment without enrolment key</strong> is planned to become possible.';
$string['showhintcourseselfenrolunlimited'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment infinitely.';
$string['showhintcourseselfenroluntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolfrom'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} on.';
$string['showhintcourseselfenrolsince'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment currently.';
$string['showhintcourseselfenrolfromuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment from {$a->from} until {$a->until}.';
$string['showhintcourseselfenrolsinceuntil'] = 'The <strong>{$a->name}</strong> enrolment instance allows unrestricted self enrolment until {$a->until}.';
$string['showhintcourseselfenrolinstancecallforaction'] = 'If you don\'t want that any Moodle user can enrol into this course freely, please restrict the self enrolment settings.';
// ...Show hint for hidden course.
$string['showhintcoursehiddensetting'] = 'Show hint in hidden courses';
$string['showhintcoursehiddensetting_desc'] = 'With this setting a hint will appear in the course header as long as the visibility of the course is hidden. This helps to identify the visibility state of a course at a glance without the need for looking at the course settings.<br /><span class="badge badge-sm badge-info mt-2">Credits: Boost Campus</span>';
// ... Show hint for guest access.
$string['showhintcourseguestaccesssetting'] = 'Show hint for guest access';
$string['showhintcourseguestaccesssetting_desc'] = 'With this setting a hint will appear in the course header when a user is accessing it with the guest access feature. If the course provides an active self enrolment, a link to that page is also presented to the user.<br /><span class="badge badge-sm badge-info mt-2">Credits: Boost Campus</span>';
// ... Show hint for unrestricted self enrolment.
$string['showhintcourseselfenrolsetting'] = 'Show hint for self enrolment without enrolment key';
$string['showhintcourseselfenrolsetting_desc'] = 'With this setting a hint will appear in the course header if the course is visible and an enrolment without enrolment key is currently possible.<br /><span class="badge badge-sm badge-info mt-2">Credits: Boost Campus</span>';
// ...Switch role information.
$string['showswitchedroleincoursesetting'] = 'Position of switch role information';
$string['showswitchedroleincoursesetting_desc'] = 'With this setting you can choose the place where the information to which role a user has switched is being displayed. If not checked (default value), the role information will be displayed right beneath the user\'s name in the user menu (like in theme Boost). If checked, this information - together with a link to switch back - will be displayed beneath the course, as this functionality is course related.';
$string['switchedroleto'] = 'You are viewing this course currently with the role:';


//Moodle 4.0
$string['showfooter'] = 'Show footer';
$string['privacy:metadata:preference:draweropenblock'] = 'The user\'s preference for hiding or showing the drawer with blocks.';
$string['privacy:metadata:preference:draweropenindex'] = 'The user\'s preference for hiding or showing the drawer with course index.';
$string['privacy:metadata:preference:draweropennav'] = 'The user\'s preference for hiding or showing the drawer menu navigation.';
$string['privacy:drawerindexclosed'] = 'The current preference for the index drawer is closed.';
$string['privacy:drawerindexopen'] = 'The current preference for the index drawer is open.';
$string['privacy:drawerblockclosed'] = 'The current preference for the block drawer is closed.';
$string['privacy:drawerblockopen'] = 'The current preference for the block drawer is open.';
$string['privacy:drawernavclosed'] = 'The current preference for the navigation drawer is closed.';
$string['privacy:drawernavopen'] = 'The current preference for the navigation drawer is open.';
$string['unaddableblocks'] = 'Unneeded blocks';
$string['unaddableblocks_desc'] = 'The blocks specified are not needed when using this theme and will not be listed in the \'Add a block\' menu.';


$string['hcoursenavitems'] = 'Course Navigation Items';
$string['hcoursenavitems_desc'] = 'Turn on/off any navigation items like: Participants, Grades, Competencies, Badges.';
$string['isitemongrades'] = 'Grade';
$string['isitemongrades_desc'] = '';
$string['isitemonparticipants'] = 'Participants';
$string['isitemonparticipants_desc'] = '';
$string['isitemonbadges'] = 'Badges';
$string['isitemonbadges_desc'] = '';
$string['isitemoncompetencies'] = 'Competencies';
$string['isitemoncompetencies_desc'] = '';

$string['hmainnav'] = 'Main Navigation Items';
$string['hmainnav_desc'] = 'Customization of the Main Navigation Items';
$string['isitemonsitehome'] = 'Site home';
$string['isitemonsitehome_desc'] = '';
$string['isitemondashboard'] = 'Dashboard';
$string['isitemondashboard_desc'] = '';
$string['isitemoncalendar'] = 'Calendar';
$string['isitemoncalendar_desc'] = '';
$string['isitemonprivatefiles'] = 'Private files';
$string['isitemonprivatefiles_desc'] = '';
$string['isitemoncontentbank'] = 'Content bank';
$string['isitemoncontentbank_desc'] = '';