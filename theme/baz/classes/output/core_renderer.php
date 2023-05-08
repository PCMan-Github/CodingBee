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

namespace theme_baz\output;

use html_writer;
use stdClass;
use moodle_url;
use context_course;
use core_course_list_element;
use custom_menu;
use action_menu_filler;
use action_menu_link_secondary;
use action_menu;
use action_link;
use core_text;
use coding_exception;
use navigation_node;
use context_header;
use pix_icon;
use renderer_base;
use theme_config;
use get_string;

defined('MOODLE_INTERNAL') || die;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_baz
 * @copyright  Copyright © 2021 onwards Marcin Czaja (https://rosea.io)
 * @license    Commercial https://themeforest.net/licenses
 */
class core_renderer extends \core_renderer {


    /**
     * The standard tags (meta tags, links to stylesheets and JavaScript, etc.)
     * that should be included in the <head> tag. Designed to be called in theme
     * layout.php files.
     *
     * @return string HTML fragment.
     */
    public function standard_end_of_body_html() {
        $output = parent::standard_end_of_body_html();

        $googleanalyticscode = "<script
                                    async
                                    src='https://www.googletagmanager.com/gtag/js?id=GOOGLE-ANALYTICS-CODE'>
                                </script>
                                <script>
                                    window.dataLayer = window.dataLayer || [];
                                    function gtag() {
                                        dataLayer.push(arguments);
                                    }
                                    gtag('js', new Date());
                                    gtag('config', 'GOOGLE-ANALYTICS-CODE');
                                </script>";

        $theme = theme_config::load('baz');

        if (!empty($theme->settings->googleanalytics)) {
            $output .= str_replace("GOOGLE-ANALYTICS-CODE", trim($theme->settings->googleanalytics), $googleanalyticscode);
        }

        return $output;
    }

	/**
     *
     * Method to load theme element form 'layout/elements' folder
     *
     */
    public function theme_part( $name, $vars = array() )
	{

		global $CFG, $SITE, $USER;

		$OUTPUT = $this;
        $PAGE = $this->page;
        $COURSE = $this->page->course;
        $element = $name . '.php';
        $candidate1 = $this->page->theme->dir . '/layout/parts/' . $element;

		// Require for child theme
		if ( file_exists( $candidate1 ) )
		{
			$candidate = $candidate1;
		}
		else
		{
			$candidate = $CFG->dirroot . theme_baz_theme_dir() . '/baz/layout/parts/' . $element;
		}

		if ( ! is_readable( $candidate ) )
		{
			debugging("Could not include element $name.");
            return;
        }

        extract($vars);
        ob_start();
        include($candidate);
        $output = ob_get_clean();
        return $output;

    }

    /**
     * Renders the custom menu
     *
     * @param custom_menu $menu
     * @return mixed
     */
    protected function render_custom_menu(custom_menu $menu) {
        if (!$menu->has_children()) {
            return '';
        }

        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('core/custom_moremenu_item', $context);
        }

        return $content;
    }

    /**
     * Outputs the favicon urlbase.
     *
     * @return string an url
     */
    public function favicon() {
        $theme = theme_config::load('baz');

        $favicon = $theme->setting_file_url('favicon', 'favicon');

        if (!empty(($favicon))) {
            return $favicon;
        }

        return parent::favicon();
    }    

    public function render_lang_menu() {
        $langs = get_string_manager()->get_list_of_translations();
        $haslangmenu = $this->lang_menu() != '';
        $menu = new custom_menu;

        if ($haslangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $menu->add($currentlang, new moodle_url('#'), $strlang, 10000);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
            foreach ($menu->get_children() as $item) {
                $context = $item->export_for_template($this);
            }

            $context->currentlangname = array_search($currentlang, $langs);

            if (isset($context)) {
                return $this->render_from_template('theme_baz/lang_menu', $context);
            }
        }
    }

    public function edit_button(moodle_url $url) {
        global $SITE, $PAGE, $USER, $CFG, $COURSE;
        if (!$PAGE->user_allowed_editing() || $COURSE->id <= 1) {
            return '';
        }
        if ($PAGE->pagelayout == 'course' || $PAGE->pagelayout == 'admin') {
            $url = new moodle_url($PAGE->url);
            $url->param('sesskey', sesskey());
            if ($PAGE->user_is_editing()) {
                $url->param('edit', 'off');
                $btn = 'ml-1 btn btn-sq btn-danger sideicon courseedit ';
                $title = get_string('turneditingoff');
                $icon = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.75 8.75L19.25 12L15.75 15.25"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H10.75"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.25 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H15.25"></path></svg>';
            }
            else {
                $url->param('edit', 'on');
                $btn = ' attention courseedit';
                $title = get_string('turneditingon');
                $icon = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.75 19.25L9 18.25L18.2929 8.95711C18.6834 8.56658 18.6834 7.93342 18.2929 7.54289L16.4571 5.70711C16.0666 5.31658 15.4334 5.31658 15.0429 5.70711L5.75 15L4.75 19.25Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.25 19.25H13.75"></path></svg>';
            }
            //old return html_writer::tag('a',  html_writer::tag('div', $title, array('class' => 'btn--text mr-2')) . $icon , array(
            return html_writer::tag('a',  html_writer::tag('div', $title, array('class' => 'btn--text d-none')) . $icon , array(
                'href' => $url,
                'class' => 'btn btn-sq btn-success ' . $btn,
                'data-tooltip' => "tooltip",
                'data-placement' => "right",
                'title' => $title,
            ));
        }

    }

    public static function get_course_progress_count($course, $userid = 0) {
        global $USER;

        // Make sure we continue with a valid userid.
        if (empty($userid)) {
            $userid = $USER->id;
        }

        $completion = new \completion_info($course);

        // First, let's make sure completion is enabled.
        if (!$completion->is_enabled()) {
            return null;
        }

        if (!$completion->is_tracked_user($userid)) {
            return null;
        }

        // Before we check how many modules have been completed see if the course has.
        if ($completion->is_course_complete($userid)) {
            return 100;
        }

        // Get the number of modules that support completion.
        $modules = $completion->get_activities();
        $count = count($modules);
        if (!$count) {
            return null;
        }

        // Get the number of modules that have been completed.
        $completed = 0;
        foreach ($modules as $module) {
            $data = $completion->get_data($module, true, $userid);
            $completed += $data->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
        }

        return ($completed / $count) * 100;
    }

    /**
     * TODO
     * Outputs the course progress donut if course completion is on.
     *
     * @return string Markup.
     */
    protected function courseprogress($course) {
        global $USER;
        $theme = \theme_config::load('baz');

        $output = '';
        $courseformat = course_get_format($course);

        if (get_class($courseformat) != 'format_tiles') {
            $completion = new \completion_info($course);

            // Start Course progress count
            // Make sure we continue with a valid userid.
            if (empty($userid)) {
                $userid = $USER->id;
            }
            $completion = new \completion_info($course);

            // Get the number of modules that support completion.
            $modules = $completion->get_activities();
            $count = count($modules);
            if (!$count) {
                return null;
            }

            // Get the number of modules that have been completed.
            $completed = 0;
            foreach ($modules as $module) {
                $data = $completion->get_data($module, true, $userid);
                $completed += $data->completionstate == COMPLETION_INCOMPLETE ? 0 : 1;
            }
            $progresscountc = $completed;
            $progresscounttotal = $count;
            // end progress count

            if ($completion->is_enabled()) {
                $templatedata = new \stdClass;
                $templatedata->progress = \core_completion\progress::get_course_progress_percentage($course);
                $templatedata->progresscountc = $progresscountc;
                $templatedata->progresscounttotal  = $progresscounttotal ;

                if (!is_null($templatedata->progress)) {
                    $templatedata->progress = floor($templatedata->progress);
                } else {
                    $templatedata->progress = 0;
                }
                if(get_config('theme_baz', 'courseprogressbar') == 1) {
                    $progressbar = '<div class="rui-course-progresschart">' . $this->render_from_template('theme_baz/progress-chart', $templatedata) .'</div>';
                    if (has_capability('report/progress:view',  \context_course::instance($course->id))) {
                        $courseprogress = new \moodle_url('/report/progress/index.php');
                        $courseprogress->param('course', $course->id);
                        $courseprogress->param('sesskey', sesskey());
                        $output .= html_writer::link($courseprogress, $progressbar, array('class'=>'rui-course-progressbar'));
                    } else {
                        $output .= $progressbar;
                    }
                }
            }
        }

        return $output;
    }

    /**
     * Get the user profile pic
     *
     * @param null $userobject
     * @param int $imgsize
     * @return moodle_url
     * @throws \coding_exception
     */
    protected function get_user_picture($userobject = null, $imgsize = 60) {
        global $USER, $PAGE;

        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new \user_picture($userobject);

        $userimg->size = $imgsize;

        return  $userimg->get_url($PAGE);
    }

     /**
     * TODO: Teachers string
     * Returns HTML to display course contacts.
     *
     */
    protected function course_contacts() {
        global $CFG, $COURSE, $DB;
        $course = $DB->get_record('course', ['id' => $COURSE->id]);
        $course = new core_course_list_element($course);
        $instructors = $course->get_course_contacts();

        if(!empty($instructors)) {
            $content = html_writer::start_div('course-teachers-box');

                // $content .= html_writer::start_tag('h5', array('class'=>'course-contact-title'));
                // $content .= html_writer::end_tag('h5');

                foreach ($instructors as $key => $instructor) {
                    $name = $instructor['username'];
                    $role = $instructor['rolename'];
                    $roleshortname = $instructor['role']->shortname;

                    $url = $CFG->wwwroot.'/user/profile.php?id='.$key;
                    $picture = $this->get_user_picture($DB->get_record('user', array('id' => $key)));

                    $content .= "<div class='course-contact-title-item'><a href='{$url}' 'title='{$name}' class='course-contact rui-user-{$roleshortname}'>";
                    $content .= "<img src='{$picture}' class='course-teacher-avatar' alt='{$name}' title='{$name} - {$role}' />";
                    $content .= "</a></div>";
                }

            $content .= html_writer::end_div(); // teachers-box
            return $content;
        }

    }



     /**
     * TODO:
     * Returns HTML to display course summary.
     *
     */
    protected function course_summary() {
        global $COURSE;
        $output = '';
        $output .= html_writer::start_div('rui-course-desc mt-3');
        $output .= $COURSE->summary;
        $output .= html_writer::end_div();
        return $output;
    }

    /**
     * Outputs the pix url base
     *
     * @return string an URL.
     */
    public function get_pix_image_url_base() {
        global $CFG;

        return $CFG->wwwroot . "/theme/baz/pix";
    }


     /**
     * TODO: alt dla img
     * Returns HTML to display course hero.
     *
     */
    public function course_hero() {
        global $CFG, $COURSE, $DB;

        $course = $DB->get_record('course', ['id' => $COURSE->id]);

        $course = new core_course_list_element($course);

        $courseimage = '';
        $imageindex = 1;
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();

            $url = new moodle_url("$CFG->wwwroot/pluginfile.php" . '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), ['forcedownload' => !$isimage]);

            if ($isimage) {
                $courseimage = $url;
            }

            if ($imageindex == 2) {
                break;
            }

            $imageindex++;
        }

        $html = '';
        // Create html for header.
        if (!empty($courseimage)) {
            $html .= '<img src='. $courseimage .' class="course-hero-img img-fluid w-100" alt="">';
        }
        return $html;
    }


     /**
     * Breadcrumbs
     *
     */
    public function breadcrumbs() {
        global $USER, $COURSE, $CFG;

        $header = new stdClass();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->courseheader = $this->course_header();
        $header->hastopbarhamburgermenu = theme_baz_get_setting('topbarhamburgermenu');
        $html = $this->render_from_template('theme_baz/breadcrumbs', $header);

        return $html;
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function simple_header() {

        global $USER, $COURSE, $CFG;


        if ($this->page->include_region_main_settings_in_header_actions() &&
        !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();

        $html = $this->render_from_template('theme_baz/header', $header);

        // MODIFICATION START:
        // If the setting showhintcourseguestaccess is set, a hint for users that view the course with guest access is shown.
        // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
        // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
        // intended.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $this->page->has_set_url()
            && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-guestaccess-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcourseguestaccessgeneral', 'theme_baz',
                array('role' => role_get_name(get_guest_role())));
            $html .= theme_baz_get_course_guest_access_hint($COURSE->id);
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START:
        // If the setting showhintcoursehidden is set, the visibility of the course is hidden and
        // a hint for the visibility will be shown.
        if (get_config('theme_baz', 'page-header-headings') == 1 && $COURSE->visible == false &&
                $this->page->has_set_url() && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-hidden-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'far fa-eye-slash fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcoursehiddengeneral', 'theme_baz', $COURSE->id);
            // If the user has the capability to change the course settings, an additional link to the course settings is shown.
            if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
                $html .= html_writer::tag('div', get_string('showhintcoursehiddensettingslink',
                    'theme_baz', array('url' => $CFG->wwwroot.'/course/edit.php?id='. $COURSE->id)));
            }
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1) {
            // Check if the user did a role switch.
            // If not, adding this section would make no sense and, even worse,
            // user_get_user_navigation_info() will throw an exception due to the missing user object.
            if (is_role_switched($COURSE->id)) {
                // Get the role name switched to.
                $opts = \user_get_user_navigation_info($USER, $this->page);
                $role = $opts->metadata['rolename'];
                // Get the URL to switch back (normal role).
                $url = new moodle_url('/course/switchrole.php',
                    array('id'        => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0,
                          'returnurl' => $this->page->url->out_as_local_url(false)));
                $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
                $html .= html_writer::tag('i', null, array('class' => 'fa fa-user-circle fa-pull-left icon d-inline-flex mr-3'));
                $html .= html_writer::start_tag('div');
                $html .= get_string('switchedroleto', 'theme_baz');
                // Give this a span to be able to address via CSS.
                $html .= html_writer::tag('strong', $role, array('class' => 'switched-role px-2'));
                $html .= html_writer::end_tag('div');
                // Return to normal role link.
                $html .= html_writer::start_tag('div');
                $html .= html_writer::tag('a', get_string('switchrolereturn', 'core'),
                    array('class' => 'switched-role-backlink', 'href' => $url));
                $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
                $html .= html_writer::end_tag('div');
            }
        }
        // MODIFICATION END.
        return $html;
    }



        /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function simpler_header() {

        global $USER, $COURSE, $CFG, $PAGE;

        if ($this->page->include_region_main_settings_in_header_actions() &&
        !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->courseheader = $this->course_header();
        $header->headeractions = $this->page->get_header_actions();

        $html = '';

        if ($PAGE->pagelayout != 'admin') {
            $html .= $this->render_from_template('theme_baz/header', $header);
        }

        // MODIFICATION START:
        // If the setting showhintcourseguestaccess is set, a hint for users that view the course with guest access is shown.
        // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
        // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
        // intended.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $this->page->has_set_url()
            && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-guestaccess-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcourseguestaccessgeneral', 'theme_baz',
                array('role' => role_get_name(get_guest_role())));
            $html .= theme_baz_get_course_guest_access_hint($COURSE->id);
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START:
        // If the setting showhintcoursehidden is set, the visibility of the course is hidden and
        // a hint for the visibility will be shown.
        if (get_config('theme_baz', 'page-header-headings') == 1 && $COURSE->visible == false &&
                $this->page->has_set_url() && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-hidden-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'far fa-eye-slash fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcoursehiddengeneral', 'theme_baz', $COURSE->id);
            // If the user has the capability to change the course settings, an additional link to the course settings is shown.
            if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
                $html .= html_writer::tag('div', get_string('showhintcoursehiddensettingslink',
                    'theme_baz', array('url' => $CFG->wwwroot.'/course/edit.php?id='. $COURSE->id)));
            }
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1) {
            // Check if the user did a role switch.
            // If not, adding this section would make no sense and, even worse,
            // user_get_user_navigation_info() will throw an exception due to the missing user object.
            if (is_role_switched($COURSE->id)) {
                // Get the role name switched to.
                $opts = \user_get_user_navigation_info($USER, $this->page);
                $role = $opts->metadata['rolename'];
                // Get the URL to switch back (normal role).
                $url = new moodle_url('/course/switchrole.php',
                    array('id'        => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0,
                          'returnurl' => $this->page->url->out_as_local_url(false)));
                $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
                $html .= html_writer::tag('i', null, array('class' => 'fa fa-user-circle fa-pull-left icon d-inline-flex mr-3'));
                $html .= html_writer::start_tag('div');
                $html .= get_string('switchedroleto', 'theme_baz');
                // Give this a span to be able to address via CSS.
                $html .= html_writer::tag('strong', $role, array('class' => 'switched-role px-2'));
                $html .= html_writer::end_tag('div');
                // Return to normal role link.
                $html .= html_writer::start_tag('div');
                $html .= html_writer::tag('a', get_string('switchrolereturn', 'core'),
                    array('class' => 'switched-role-backlink', 'href' => $url));
                $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
                $html .= html_writer::end_tag('div');
            }
        }
        // MODIFICATION END.
        return $html;
    }


    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {

        global $USER, $COURSE, $CFG, $PAGE;

        $theme = \theme_config::load('baz');

        if ($this->page->include_region_main_settings_in_header_actions() &&
        !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        //TODO: edit page btns
        $header->pageheadingbutton = $this->page_heading_button();
        //end
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();

        $header->courseheader = $this->course_header();

        $html = $this->courseprogress($this->page->course);

        $html .= html_writer::start_tag('div', array('class' => 'rui-course-header'));
            if($PAGE->theme->settings->cccteachers == 1) {
                $html .= $this->course_contacts();
            }
            $html .= $this->render_from_template('theme_baz/header', $header);
            $html .= $this->course_summary();
        $html .= html_writer::end_tag('div');//rui-course-header

        // MODIFICATION START:
        // If the setting showhintcourseguestaccess is set, a hint for users that view the course with guest access is shown.
        // We also check that the user did not switch the role. This is a special case for roles that can fully access the course
        // without being enrolled. A role switch would show the guest access hint additionally in that case and this is not
        // intended.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1
            && is_guest(\context_course::instance($COURSE->id), $USER->id)
            && $this->page->has_set_url()
            && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)
            && !is_role_switched($COURSE->id)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-guestaccess-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'fa fa-exclamation-circle fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcourseguestaccessgeneral', 'theme_baz',
                array('role' => role_get_name(get_guest_role())));
            $html .= theme_baz_get_course_guest_access_hint($COURSE->id);
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START:
        // If the setting showhintcoursehidden is set, the visibility of the course is hidden and
        // a hint for the visibility will be shown.
        if (get_config('theme_baz', 'showhintcoursehidden') == 1 && $COURSE->visible == false &&
                $this->page->has_set_url() && $this->page->url->compare(new moodle_url('/course/view.php'), URL_MATCH_BASE)) {
            $html .= html_writer::start_tag('div', array('class' => 'course-hidden-infobox alert alert-warning'));
            $html .= html_writer::tag('i', null, array('class' => 'far fa-eye-slash fa-pull-left icon d-inline-flex mr-3'));
            $html .= get_string('showhintcoursehiddengeneral', 'theme_baz', $COURSE->id);
            // If the user has the capability to change the course settings, an additional link to the course settings is shown.
            if (has_capability('moodle/course:update', context_course::instance($COURSE->id))) {
                $html .= html_writer::tag('div', get_string('showhintcoursehiddensettingslink',
                    'theme_baz', array('url' => $CFG->wwwroot.'/course/edit.php?id='. $COURSE->id)));
            }
            $html .= html_writer::end_tag('div');
        }
        // MODIFICATION END.
        // MODIFICATION START.
        if (get_config('theme_baz', 'showhintcourseguestaccess') == 1) {
            // Check if the user did a role switch.
            // If not, adding this section would make no sense and, even worse,
            // user_get_user_navigation_info() will throw an exception due to the missing user object.
            if (is_role_switched($COURSE->id)) {
                // Get the role name switched to.
                $opts = \user_get_user_navigation_info($USER, $this->page);
                $role = $opts->metadata['rolename'];
                // Get the URL to switch back (normal role).
                $url = new moodle_url('/course/switchrole.php',
                    array('id'        => $COURSE->id, 'sesskey' => sesskey(), 'switchrole' => 0,
                          'returnurl' => $this->page->url->out_as_local_url(false)));
                $html .= html_writer::start_tag('div', array('class' => 'switched-role-infobox alert alert-info'));
                $html .= html_writer::tag('i', null, array('class' => 'fa fa-user-circle fa-pull-left icon d-inline-flex mr-3'));
                $html .= html_writer::start_tag('div');
                $html .= get_string('switchedroleto', 'theme_baz');
                // Give this a span to be able to address via CSS.
                $html .= html_writer::tag('strong', $role, array('class' => 'switched-role px-2'));
                $html .= html_writer::end_tag('div');
                // Return to normal role link.
                $html .= html_writer::start_tag('div');
                $html .= html_writer::tag('a', get_string('switchrolereturn', 'core'),
                    array('class' => 'switched-role-backlink', 'href' => $url));
                $html .= html_writer::end_tag('div'); // Return to normal role link: end div.
                $html .= html_writer::end_tag('div');
            }
        }
        // MODIFICATION END.
        return $html;
    }


    public function courseheadermenu() {
        global $PAGE, $COURSE, $USER, $DB;

        $headerlinks = '';

        $editcog = html_writer::div($this->context_header_settings_menu() , 'pull-xs-right context-header-settings-menu');
        // Header Menus for Users.
        if ($PAGE->pagelayout !== 'coursecategory' && $PAGE->pagetype !== 'course-management' && $PAGE->pagetype !== 'course-delete') {
            $course = $this->page->course;
            $showcoursenav = true;
            $context = context_course::instance($course->id);
            $hasgradebookshow = $PAGE->course->showgrades == 1;

            $hascompetencyshow = get_config('core_competency', 'enabled');
            $isteacher = has_capability('moodle/course:viewhiddenactivities', $context);

            $gradeurl = '';
            $gradestatus = '';
            // Show for student in course.
            if ($COURSE->id > 1 && isloggedin() && !isguestuser() && has_capability('gradereport/user:view', $context) && $hasgradebookshow) {
                $gradeurl = new moodle_url('/grade/report/user/index.php', array('id' => $PAGE->course->id));
                $gradestatus = true;
            }
            // Show for teacher in course.
            if ($COURSE->id > 1 && has_capability('gradereport/grader:view', $context) && isloggedin() && !isguestuser()) {
                $gradeurl = new moodle_url('/grade/report/grader/index.php', array('id' => $PAGE->course->id));
                $gradestatus = true;
            }

            // TODO: Sprawdzic i ewentualnie usunac. Easy Enrollment Integration.
            $globalhaseasyenrollment = enrol_get_plugin('easy');
            $coursehaseasyenrollment = '';
            $easycodelink = '';
            $easycodetitle = '';
            if ($globalhaseasyenrollment) {
                $coursehaseasyenrollment = $DB->record_exists('enrol', array('courseid' => $COURSE->id, 'enrol' => 'easy'));
                $easyenrollinstance = $DB->get_record('enrol', array('courseid' => $COURSE->id,'enrol' => 'easy'));
            }
            if ($coursehaseasyenrollment && isset($COURSE->id) && $COURSE->id > 1) {
                $easycodetitle = get_string('header_coursecodes', 'enrol_easy');
                $easycodelink = new moodle_url('/enrol/editinstance.php', array('courseid' => $PAGE->course->id,'id' => $easyenrollinstance->id,'type' => 'easy'));
            }

            // Header links on course pages.

            $course = $this->page->course;
            $context = context_course::instance($course->id);
            $hasadminlink = has_capability('moodle/site:configview', $context);
    
            if ($COURSE->id > 1 && isloggedin() && !isguestuser() && is_enrolled($context, $USER->id, '', true) || is_siteadmin() || $hasadminlink) {
                global $CFG;
                $headerlinks = [
                    'showcoursenav' => $showcoursenav,
                    'editcog' => $editcog,
                    'headerlinksdata' => array(
                        array(
                            'status' => !isguestuser() && has_capability('moodle/course:viewparticipants', $context),
                            'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.78168 19.25H13.2183C13.7828 19.25 14.227 18.7817 14.1145 18.2285C13.804 16.7012 12.7897 14 9.5 14C6.21031 14 5.19605 16.7012 4.88549 18.2285C4.773 18.7817 5.21718 19.25 5.78168 19.25Z"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 14C17.8288 14 18.6802 16.1479 19.0239 17.696C19.2095 18.532 18.5333 19.25 17.6769 19.25H16.75"></path>
                            <circle cx="9.5" cy="7.5" r="2.75" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.75 10.25C16.2688 10.25 17.25 9.01878 17.25 7.5C17.25 5.98122 16.2688 4.75 14.75 4.75"></path>
                            </svg>
                            ',
                            'title' => get_string('participants', 'moodle'),
                            'url' => new moodle_url('/user/index.php', array('id' => $PAGE->course->id)),
                            'isactiveitem' => $this->isMenuActive('/user/index.php'),
                            'itemid' => 'itemParticipants',
                            ),
                        array(
                            'status' => has_capability('moodle/badges:earnbadge', $context) && $CFG->enablebadges == 1,
                            'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.25 8.75L18.25 4.75H5.75L9.75 8.75"></path>
                            <circle cx="12" cy="14" r="5.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
                            </svg>
                            ',
                            'title' => get_string('badges', 'badges'),
                            'url' => new moodle_url('/badges/view.php?type=2', array('id' => $PAGE->course->id)),
                            'isactiveitem' => $this->isMenuActive('/badges/view.php'),
                            'itemid' => 'itemBadges',
                            ),
                        array(
                            'status' => !isguestuser() && $hascompetencyshow,
                            'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.25 7C9.25 8.24264 8.24264 9.25 7 9.25C5.75736 9.25 4.75 8.24264 4.75 7C4.75 5.75736 5.75736 4.75 7 4.75C8.24264 4.75 9.25 5.75736 9.25 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M6.75 9.5V14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M10.75 12.25H15.25C16.3546 12.25 17.25 11.3546 17.25 10.25V9.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M19.25 7C19.25 8.24264 18.2426 9.25 17 9.25C15.7574 9.25 14.75 8.24264 14.75 7C14.75 5.75736 15.7574 4.75 17 4.75C18.2426 4.75 19.25 5.75736 19.25 7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.25 17C9.25 18.2426 8.24264 19.25 7 19.25C5.75736 19.25 4.75 18.2426 4.75 17C4.75 15.7574 5.75736 14.75 7 14.75C8.24264 14.75 9.25 15.7574 9.25 17Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>',
                            'title' => get_string('competencies', 'competency'),
                            'url' => new moodle_url('/admin/tool/lp/coursecompetencies.php', array('courseid' => $PAGE->course->id)),
                            'isactiveitem' => $this->isMenuActive('/admin/tool/lp/coursecompetencies'),
                            'itemid' => 'itemCompetency',
                            ),
                        array(
                            'status' => $coursehaseasyenrollment && $isteacher,
                            'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.75 19.2502H18.25C18.8023 19.2502 19.25 18.8025 19.25 18.2502V5.75C19.25 5.19772 18.8023 4.75 18.25 4.75H5.75C5.19772 4.75 4.75 5.19772 4.75 5.75V18.2502C4.75 18.8025 5.19772 19.2502 5.75 19.2502Z"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 9.25L5.25 9.25"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 14.75L5.25 14.75"></path>
                            </svg>
                            ',
                            'title' => $easycodetitle,
                            'url' => $easycodelink,
                            'isactiveitem' => $this->isMenuActive('/enrol/editinstance.php'),
                            'itemid' => 'itemEditInstance',
                            ),
                        array(
                            'status' => $gradestatus,
                            'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5.75 19.2502H18.25C18.8023 19.2502 19.25 18.8025 19.25 18.2502V5.75C19.25 5.19772 18.8023 4.75 18.25 4.75H5.75C5.19772 4.75 4.75 5.19772 4.75 5.75V18.2502C4.75 18.8025 5.19772 19.2502 5.75 19.2502Z"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 9.25L5.25 9.25"></path>
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 14.75L5.25 14.75"></path>
                            </svg>
                            ',
                            'title' => get_string('grades', 'moodle'),
                            'url' => $gradeurl,
                            'isactiveitem' => $this->isMenuActive('/grade/report/grader/index.php'),
                            'itemid' => 'itemGrade',
                            ),
                    ),
                ];
            }
        }
        return $this->render_from_template('theme_baz/nav-course', $headerlinks);
    }

    /**
     * This is an optional menu that can be added to a layout by a theme. It contains the
     * menu for the course administration, only on the course main page.
     *
     * @return string
     */
    public function context_header_settings_menu() {
        $context = $this->page->context;
        $menu = new action_menu();

        $items = $this->page->navbar->get_items();
        $currentnode = end($items);

        $showcoursemenu = false;
        $showfrontpagemenu = false;
        $showusermenu = false;

        // We are on the course home page.
        if (($context->contextlevel == CONTEXT_COURSE) &&
                !empty($currentnode) &&
                ($currentnode->type == navigation_node::TYPE_COURSE || $currentnode->type == navigation_node::TYPE_SECTION)) {
            $showcoursemenu = true;
        }

        $courseformat = course_get_format($this->page->course);
        // This is a single activity course format, always show the course menu on the activity main page.
        if ($context->contextlevel == CONTEXT_MODULE &&
                !$courseformat->has_view_page()) {

            $this->page->navigation->initialise();
            $activenode = $this->page->navigation->find_active_node();
            // If the settings menu has been forced then show the menu.
            if ($this->page->is_settings_menu_forced()) {
                $showcoursemenu = true;
            } else if (!empty($activenode) && ($activenode->type == navigation_node::TYPE_ACTIVITY ||
                    $activenode->type == navigation_node::TYPE_RESOURCE)) {

                // We only want to show the menu on the first page of the activity. This means
                // the breadcrumb has no additional nodes.
                if ($currentnode && ($currentnode->key == $activenode->key && $currentnode->type == $activenode->type)) {
                    $showcoursemenu = true;
                }
            }
        }

        // This is the site front page.
        if ($context->contextlevel == CONTEXT_COURSE &&
                !empty($currentnode) &&
                $currentnode->key === 'home') {
            $showfrontpagemenu = true;
        }

        // This is the user profile page.
        if ($context->contextlevel == CONTEXT_USER &&
                !empty($currentnode) &&
                ($currentnode->key === 'myprofile')) {
            $showusermenu = true;
        }

        if ($showfrontpagemenu) {
            $settingsnode = $this->page->settingsnav->find('frontpage', navigation_node::TYPE_SETTING);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showcoursemenu) {
            $settingsnode = $this->page->settingsnav->find('courseadmin', navigation_node::TYPE_COURSE);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $skipped = $this->build_action_menu_from_navigation($menu, $settingsnode, false, true);

                // We only add a list to the full settings menu if we didn't include every node in the short menu.
                if ($skipped) {
                    $text = get_string('morenavigationlinks');
                    $url = new moodle_url('/course/admin.php', array('courseid' => $this->page->course->id));
                    $link = new action_link($url, $text, null, null, new pix_icon('t/edit', $text));
                    $menu->add_secondary_action($link);
                }
            }
        } else if ($showusermenu) {
            // Get the course admin node from the settings navigation.
            $settingsnode = $this->page->settingsnav->find('useraccount', navigation_node::TYPE_CONTAINER);
            if ($settingsnode) {
                // Build an action menu based on the visible nodes from this navigation tree.
                $this->build_action_menu_from_navigation($menu, $settingsnode);
            }
        }

        return $this->render($menu);
    }

    public function isMenuActive($x) {
        if(strpos($_SERVER['REQUEST_URI'], strval($x))!=false) {
            return true;
        }
        else {
            return false;
        }      
    }



    public function mainheadermenu() {
        global $CFG, $PAGE, $COURSE, $USER, $DB;

        // Turn on/off menu items
        $isitemonsitehome = '';
        $isitemondashboard = '';
        $isitemoncalendar = '';
        $isitemoncontentbank = '';
        $isitemonprivatefiles = '';

        if(get_config('theme_baz', 'isitemonsitehome') == 1) {
            $isitemonsitehome = true;
        }
        if(get_config('theme_baz', 'isitemondashboard') == 1) {
            $isitemondashboard = true;
        }
        if(get_config('theme_baz', 'isitemoncalendar') == 1) {
            $isitemoncalendar = true;
        }
        if(get_config('theme_baz', 'isitemoncontentbank') == 1) {
            $isitemoncontentbank = true;
        }
        if(get_config('theme_baz', 'isitemonprivatefiles') == 1) {
            $isitemonprivatefiles = true;
        }

        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = new stdClass();

            $course = $this->page->course;
            $context = context_course::instance($course->id);

            if (is_role_switched($course->id)) { // Has switched roles
                    $rolename = '';
                    $realuser = \core\session\manager::get_realuser();
                    $fullname = fullname($realuser, true);
                    if ($role = $DB->get_record('role', array('id'=>$USER->access['rsw'][$context->path]))) {
                        $rolename = ': '.role_get_name($role, $context);
                    }

                    $loggedinas = get_string('loggedinas', 'moodle', $fullname).$rolename;
            }
            if (\core\session\manager::is_loggedinas()) {
                $header->loginas = $this->login_info();
            }
            if (is_role_switched($course->id) && !\core\session\manager::is_loggedinas()) {
                $header->roleswitch = $loggedinas;
            }
            $hascontentbankpermission = has_capability('contenttype/h5p:access', $context);

            $calendarurl = new moodle_url('/calendar/view.php?view=month');
            $calendarurl = '';
            if (isset($COURSE->id) && $COURSE->id > 1 && isloggedin() && !isguestuser()) {
                $calendarurl = new moodle_url('/calendar/view.php?view=upcoming', array('course' => $PAGE->course->id ));
            } else {
                $calendarurl = new moodle_url('/calendar/view.php?view=month');
            }

            // Header links on non course areas.
            if (isloggedin() && !isguestuser()) {

                if ($COURSE->id > 1) {
                    $headerlinks = [
                        'headerlinksdata' => array(
                            array(
                                'status' => $isitemonsitehome && !isguestuser() ,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 15.25V6.75H15.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7L17.25 17.25"></path>
                                </svg>
                                ',
                                'title' => get_string('sitehome', 'moodle'),
                                'url' => new moodle_url('/'),
                                'itemid' => 'itemHome',
                                ),
                            array(
                                'status' => $isitemondashboard && !isguestuser() ,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 6.75C4.75 5.64543 5.64543 4.75 6.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V6.75Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 8.75V19"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8.25H19"></path>
                                </svg>
                                ',
                                'title' => get_string('myhome', 'moodle'),
                                'url' => new moodle_url('/my/'),
                                'isactiveitem' => $this->isMenuActive('/my'),
                                'itemid' => 'itemDashboard',
                                ),
                            array(
                                'status' => $isitemonprivatefiles && !isguestuser(),
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 11.75L17.6644 6.20056C17.4191 5.34195 16.6344 4.75 15.7414 4.75H8.2586C7.36564 4.75 6.58087 5.34196 6.33555 6.20056L4.75 11.75"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.2142 12.3689C9.95611 12.0327 9.59467 11.75 9.17085 11.75H4.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25V11.75H14.8291C14.4053 11.75 14.0439 12.0327 13.7858 12.3689C13.3745 12.9046 12.7276 13.25 12 13.25C11.2724 13.25 10.6255 12.9046 10.2142 12.3689Z"></path>
                                </svg>
                                ',
                                'title' => get_string('privatefiles', 'moodle'),
                                'url' => new moodle_url('/user/files.php'),
                                'isactiveitem' => $this->isMenuActive('/user/files'),
                                'itemid' => 'itemFiles',
                                ),
                            array(
                                'status' => $isitemoncalendar && !isguestuser(),
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 8.75C4.75 7.64543 5.64543 6.75 6.75 6.75H17.25C18.3546 6.75 19.25 7.64543 19.25 8.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V8.75Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4.75V8.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 4.75V8.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.75 10.75H16.25"></path>
                                </svg>
                                ',
                                'title' => get_string('calendar', 'calendar'),
                                'url' => $calendarurl,
                                'isactiveitem' => $this->isMenuActive('/calendar'),
                                'itemid' => 'itemCalendar',
                                ),
                            array(
                                'status' => $isitemoncontentbank && $hascontentbankpermission,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 17.25V9.75C19.25 8.64543 18.3546 7.75 17.25 7.75H4.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 7.5L12.5685 5.7923C12.2181 5.14977 11.5446 4.75 10.8127 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V11"></path>
                                </svg>
                                ',
                                'title' => get_string('contentbank', 'moodle'),
                                'url' => new moodle_url('/contentbank/index.php', array('contextid' => $context->id)),
                                'isactiveitem' => $this->isMenuActive('/contentbank'),
                                'itemid' => 'itemContentBank',
                                ),

                        ),
                    ];
                } else {
                    $headerlinks = [
                        'headerlinksdata' => array(
                            array(
                                'status' => $isitemonsitehome && !isguestuser() ,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 15.25V6.75H15.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7L17.25 17.25"></path>
                                </svg>
                                ',
                                'title' => get_string('sitehome', 'moodle'),
                                'url' => new moodle_url('/'),
                                'itemid' => 'itemHome',
                                ),
                            array(
                                'status' => $isitemondashboard && !isguestuser() ,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 6.75C4.75 5.64543 5.64543 4.75 6.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V6.75Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 8.75V19"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8.25H19"></path>
                                </svg>
                                ',
                                'title' => get_string('myhome', 'moodle'),
                                'url' => new moodle_url('/my/'),
                                'isactiveitem' => $this->isMenuActive('/my'),
                                'itemid' => 'itemDashboard',
                                ),
                            array(
                                'status' => $isitemonprivatefiles && !isguestuser(),
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 11.75L17.6644 6.20056C17.4191 5.34195 16.6344 4.75 15.7414 4.75H8.2586C7.36564 4.75 6.58087 5.34196 6.33555 6.20056L4.75 11.75"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.2142 12.3689C9.95611 12.0327 9.59467 11.75 9.17085 11.75H4.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25V11.75H14.8291C14.4053 11.75 14.0439 12.0327 13.7858 12.3689C13.3745 12.9046 12.7276 13.25 12 13.25C11.2724 13.25 10.6255 12.9046 10.2142 12.3689Z"></path>
                                </svg>
                                ',
                                'title' => get_string('privatefiles', 'moodle'),
                                'url' => new moodle_url('/user/files.php'),
                                'isactiveitem' => $this->isMenuActive('/user/files'),
                                'itemid' => 'itemFiles',
                                ),
                            array(
                                'status' => $isitemoncalendar && !isguestuser(),
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.75 8.75C4.75 7.64543 5.64543 6.75 6.75 6.75H17.25C18.3546 6.75 19.25 7.64543 19.25 8.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H6.75C5.64543 19.25 4.75 18.3546 4.75 17.25V8.75Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 4.75V8.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 4.75V8.25"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7.75 10.75H16.25"></path>
                                </svg>
                                ',
                                'title' => get_string('calendar', 'calendar'),
                                'url' => $calendarurl,
                                'isactiveitem' => $this->isMenuActive('/calendar'),
                                'itemid' => 'itemCalendar',
                                ),
                            array(
                                'status' => $isitemoncontentbank && $hascontentbankpermission,
                                'icon' => '<svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.25 17.25V9.75C19.25 8.64543 18.3546 7.75 17.25 7.75H4.75V17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H17.25C18.3546 19.25 19.25 18.3546 19.25 17.25Z"></path>
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 7.5L12.5685 5.7923C12.2181 5.14977 11.5446 4.75 10.8127 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V11"></path>
                                </svg>
                                ',
                                'title' => get_string('contentbank', 'moodle'),
                                'url' => new moodle_url('/contentbank/index.php', array('contextid' => 1)),
                                'isactiveitem' => $this->isMenuActive('/contentbank'),
                                'itemid' => 'itemContentBank',
                                ),

                        ),
                    ];
                }

                return $this->render_from_template('theme_baz/nav-main', $headerlinks);

            }

    }



    public function adminheaderlink() {
        global $PAGE, $COURSE, $CFG, $USER, $OUTPUT;

        $course = $this->page->course;
        $context = context_course::instance($course->id);
        $hasadminlink = has_capability('moodle/site:configview', $context);

        if (is_siteadmin() || $hasadminlink) {
            $adminlinktitle = get_string('administrationsite', 'moodle');
            $adminlinkurl = new moodle_url('/admin/search.php');

            // Send to template
            $adminlinkheaderlinktmpl = [
                'admintitle' => $adminlinktitle,
                'adminurl' => $adminlinkurl
            ];

            return $this->render_from_template('theme_baz/btn-admin', $adminlinkheaderlinktmpl);
        }
    }



    public function customeditblockbtn() {
        global $USER, $COURSE, $CFG;
        $header = new stdClass();
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->pageheadingbutton = $this->page_heading_button();

        $html = $this->render_from_template('theme_baz/header_settings_menu', $header);

        return $html;
    }


        /**
     * Generates an array of sections and an array of activities for the given course.
     *
     * This method uses the cache to improve performance and avoid the get_fast_modinfo call
     *
     * @param stdClass $course
     * @return array Array($sections, $activities)
     */
    // protected function generate_sections_and_activities(stdClass $course) {
    //     global $CFG;
    //     require_once ($CFG->dirroot . '/course/lib.php');
    //     $modinfo = get_fast_modinfo($course);
    //     $sections = $modinfo->get_section_info_all();
    //     // For course formats using 'numsections' trim the sections list
    //     $courseformatoptions = course_get_format($course)->get_format_options();
    //     if (isset($courseformatoptions['numsections'])) {
    //         $sections = array_slice($sections, 0, $courseformatoptions['numsections'] + 1, true);
    //     }
    //     $activities = array();
    //     foreach ($sections as $key => $section) {
    //         // Clone and unset summary to prevent $SESSION bloat (MDL-31802).
    //         $sections[$key] = clone ($section);
    //         unset($sections[$key]->summary);
    //         $sections[$key]->hasactivites = false;
    //         if (!array_key_exists($section->section, $modinfo->sections)) {
    //             continue;
    //         }
    //         foreach ($modinfo->sections[$section->section] as $cmid) {
    //             $cm = $modinfo->cms[$cmid];
    //             $activity = new stdClass;
    //             $activity->id = $cm->id;
    //             $activity->course = $course->id;
    //             $activity->section = $section->section;
    //             $activity->name = $cm->name;
    //             $activity->icon = $cm->icon;
    //             $activity->iconcomponent = $cm->iconcomponent;
    //             $activity->hidden = (!$cm->visible);
    //             $activity->modname = $cm->modname;
    //             $activity->nodetype = navigation_node::NODETYPE_LEAF;
    //             $activity->onclick = $cm->onclick;
    //             $url = $cm->url;
    //             if (!$url) {
    //                 $activity->url = null;
    //                 $activity->display = false;
    //             }
    //             else {
    //                 $activity->url = $url->out();
    //                 $activity->display = $cm->is_visible_on_course_page() ? true : false;
    //             }
    //             $activities[$cmid] = $activity;
    //             if ($activity->display) {
    //                 $sections[$key]->hasactivites = true;
    //             }
    //         }
    //     }
    //     return array($sections, $activities);
    // }

    // My Course Menu - Inspred by Fordson Theme
    public function baz_allcourseslink() {
        global $CFG;
        $allcourseicon = '<svg width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.75 6.75L19.25 12L13.75 17.25"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 12H4.75"></path></svg>';
        //$allcoursetxt = theme_baz_get_setting('stringallcourses');
        $allcoursetxt = format_text(theme_baz_get_setting('stringallcourses'),FORMAT_HTML, array('noclean' => true));

        if (!empty($allcoursetxt)) {
            $allcourses = "<a class=\"rui-course-menu-list--more justify-content-between w-100\" href=\"$CFG->wwwroot/course/index.php\">" . $allcoursetxt . $allcourseicon . '</a>';
        } else {
            $allcourses = '';
        }

        return $allcourses;
    }

    public function baz_mycourses() {
        global $DB, $USER;


        //$branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort, $branchactive);

        $courses = enrol_get_my_courses(null, 'fullname ASC'); // more info about sorting etc. -> lib/enrollib.php
        //$nomycoursestxt = theme_baz_get_setting('stringnocourses');
        $nomycoursestxt = format_text(theme_baz_get_setting('stringnocourses'),FORMAT_HTML, array('noclean' => true));
        $nomycourses = '<div class="alert alert-info alert-block m-0">' . $nomycoursestxt . '</div>';
        if ($courses) {

            // Determine if we need to query the enrolment and user enrolment tables.
            $enrolquery = false;
            foreach ($courses as $course) {
                if (empty($course->timeaccess)) {
                    $enrolquery = true;
                    break;
                }
            }
            if ($enrolquery) {
                $params = array(
                    'userid' => $USER->id
                );
                $sql = "SELECT ue.id, e.courseid, ue.timestart
                    FROM {enrol} e
                    JOIN {user_enrolments} ue ON (ue.enrolid = e.id AND ue.userid = :userid)";

                $enrolments = $DB->get_records_sql($sql, $params, 0, 0);

                if ($enrolments) {
                    // Sort out any multiple enrolments on the same course.
                    $userenrolments = array();
                    foreach ($enrolments as $enrolment) {
                        if (!empty($userenrolments[$enrolment->courseid])) {
                            if ($userenrolments[$enrolment->courseid] < $enrolment->timestart) {
                                // Replace.
                                $userenrolments[$enrolment->courseid] = $enrolment->timestart;
                            }
                        }
                        else {
                            $userenrolments[$enrolment->courseid] = $enrolment->timestart;
                        }
                    }

                    // We don't need to worry about timeend etc. as our course list will be valid for the user from above.
                    foreach ($courses as $course) {
                        if (empty($course->timeaccess)) {
                            $course->timestart = $userenrolments[$course->id];
                        }
                    }
                }
            }

        }
        else {
            return $nomycourses;
        }

        $content = '';
        foreach ($courses as $course) {
            if ($course->visible) {
                $url = new moodle_url('/course/view.php?id=' . $course->id);
                $name = '<span class="rui-course-menu-list-text">' . format_string($course->fullname) . '</span>';
                $shortname = format_string($course->shortname);
                $checkactive = $this->isMenuActive('/course/view.php?id=' . $course->id);
                $isactive = '';
                $icon = '<div class="mr-2"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M19.25 15.25V5.75C19.25 5.19772 18.8023 4.75 18.25 4.75H6.75C5.64543 4.75 4.75 5.64543 4.75 6.75V16.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path><path d="M19.25 15.25H6.75C5.64543 15.25 4.75 16.1454 4.75 17.25C4.75 18.3546 5.64543 19.25 6.75 19.25H19.25V15.25Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></div>';
                if($checkactive == true) {
                    $isactive .= format_string("active");
                }
                $content .= '<li class="rui-course-menu-list"><a href="' . $url . '" class="rui-course-menu-list-item ' . $isactive .'" title="' . $shortname .'">' . $icon . $name  . '</a></li>';
                //$branch->add(format_string($course->fullname), new moodle_url('/course/view.php?id=' . $course->id) , format_string($course->shortname));
            }
        }

        return '<ul class="rui-course-menu">' . $content .'</ul>';
    }

    // Course Menu
    public function baz_thiscourse_menu() {
        global $CFG, $COURSE, $PAGE, $OUTPUT;
        $context = $this->page->context;
        $menu = new custom_menu();

        $branchtitle = get_string('thiscourse', 'theme_baz');
        $branchlabel = '';
        $branchurl = new moodle_url('/my/index.php');
        $branchsort = 10000;
        $thisbranchtitle = get_string('thiscourse', 'theme_baz');


        if (isloggedin() && !isguestuser()) {

            $sections = $this->generate_sections_and_activities($COURSE);
            if ($sections && $COURSE->id > 1) {
                $branchlabel = $thisbranchtitle;
                $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
                $course = course_get_format($COURSE)->get_course();
                $coursehomelabel = $course->fullname;
                $coursehomeurl = new moodle_url('/course/view.php?', array(
                    'id' => $PAGE->course->id
                ));
                $coursehometitle = $coursehomelabel;
                $branch->add($coursehomelabel, $coursehomeurl, $coursehometitle);

                foreach ($sections[0] as $sectionid => $section) {
                    $sectionname = get_section_name($COURSE, $section);
                    if (isset($course->coursedisplay) && $course->coursedisplay == COURSE_DISPLAY_MULTIPAGE) {
                        $sectionurl = '/course/view.php?id=' . $COURSE->id . '&section=' . $sectionid;
                    }
                    else {
                        $sectionurl = '/course/view.php?id=' . $COURSE->id . '#section-' . $sectionid;
                    }
                    $branch->add(format_string($sectionname) , new moodle_url($sectionurl) , format_string($sectionname));
                }
            }
        }
        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('theme_baz/nav-mycourses', $context);
        }
        return $content;
    }


     /**
      * Renders the header bar.
      *
      * @param context_header $contextheader Header bar object.
      * @return string HTML for the header bar.
      */
      protected function render_context_header(context_header $contextheader) {

        $showheader = empty($this->page->layout_options['nocontextheader']);
        if (!$showheader) {
            return '';
        }

        $html = '';

        if (isset($contextheader->imagedata)) {
            $html .= html_writer::start_div('page-context-header page-content-header--img flex-wrap');
            // Header specific image.
            $html .= html_writer::div($contextheader->imagedata, 'page-header-image');
        }

        // Headings.
        if (!isset($contextheader->heading)) {
            $headings = $this->heading($this->page->heading, 2, 'rui-page-title rui-page-title--page');
        }
        elseif (isset($contextheader->imagedata)) {
            $headings = $this->headingwithavatar($this->page->heading, 2, 'rui-page-title rui-page-title--avatar');
        }
        else {
            $headings = $this->heading($contextheader->heading, 2, 'rui-page-title rui-page-title--context');
        }

        $html .= $headings;

        // Buttons.
        if (isset($contextheader->additionalbuttons)) {
            $html .= html_writer::start_div('header-button-group mt-2 mt-md-0 ml-md-2');
            foreach ($contextheader->additionalbuttons as $button) {
                if (!isset($button->page)) {
                    // Include js for messaging.
                    if ($button['buttontype'] === 'togglecontact') {
                        \core_message\helper::togglecontact_requirejs();
                    }
                    if ($button['buttontype'] === 'message') {
                        \core_message\helper::messageuser_requirejs();
                    }
                    $image = $this->pix_icon($button['formattedimage'], $button['title'], 'moodle', array(
                        'class' => 'iconsmall',
                        'role' => 'presentation'
                    ));
                    $image .= html_writer::span($button['title'], 'header-button-title ml-2');
                } else {
                    $image = html_writer::empty_tag('img', array(
                        'src' => $button['formattedimage'],
                        'role' => 'presentation'
                    ));
                }
                $html .= html_writer::link($button['url'], html_writer::tag('span', $image), $button['linkattributes']);
            }
            $html .= html_writer::end_div();
        }

        if (isset($contextheader->imagedata)) {
            $html .= html_writer::end_div();
        }
        return $html;
    }

    /**
     * Construct a user menu, returning HTML that can be echoed out by a
     * layout file.
     *
     * @param stdClass $user A user object, usually $USER.
     * @param bool $withlinks true if a dropdown should be built.
     * @return string HTML fragment.
     */
    public function user_menu($user = null, $withlinks = null) {
        global $USER, $CFG;
        require_once($CFG->dirroot . '/user/lib.php');

        if (is_null($user)) {
            $user = $USER;
        }

        // Note: this behaviour is intended to match that of core_renderer::login_info,
        // but should not be considered to be good practice; layout options are
        // intended to be theme-specific. Please don't copy this snippet anywhere else.
        if (is_null($withlinks)) {
            $withlinks = empty($this->page->layout_options['nologinlinks']);
        }

        // Add a class for when $withlinks is false.
        $usermenuclasses = 'usermenu';
        if (!$withlinks) {
            $usermenuclasses .= ' withoutlinks';
        }

        $returnstr = "";

        // If during initial install, return the empty return string.
        if (during_initial_install()) {
            return $returnstr;
        }

        $loginpage = $this->is_login_page();
        $loginurl = get_login_url();
        // If not logged in, show the typical not-logged-in string.
        if (!isloggedin()) {
            if (!$loginpage) {
                $returnstr .= "<a class=\"rui-login-btn\" href=\"$loginurl\"><span class=\"rui-login-btn-txt\">" . get_string('login') . '</span><svg class="ml-2" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 8.75L13.25 12L9.75 15.25"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H9.75"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 12H4.75"></path></svg></a>';
            }
            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );

        }

        // If logged in as a guest user, show a string to that effect.
        if (isguestuser()) {
            $icon = '<svg class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 12C10 12.5523 9.55228 13 9 13C8.44772 13 8 12.5523 8 12C8 11.4477 8.44772 11 9 11C9.55228 11 10 11.4477 10 12Z" fill="currentColor" /><path d="M15 13C15.5523 13 16 12.5523 16 12C16 11.4477 15.5523 11 15 11C14.4477 11 14 11.4477 14 12C14 12.5523 14.4477 13 15 13Z" fill="currentColor" /><path fill-rule="evenodd" clip-rule="evenodd" d="M12.0244 2.00003L12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.74235 17.9425 2.43237 12.788 2.03059L12.7886 2.0282C12.5329 2.00891 12.278 1.99961 12.0244 2.00003ZM12 20C16.4183 20 20 16.4183 20 12C20 11.3014 19.9105 10.6237 19.7422 9.97775C16.1597 10.2313 12.7359 8.52461 10.7605 5.60246C9.31322 7.07886 7.2982 7.99666 5.06879 8.00253C4.38902 9.17866 4 10.5439 4 12C4 16.4183 7.58172 20 12 20ZM11.9785 4.00003L12.0236 4.00003L12 4L11.9785 4.00003Z" fill="currentColor" /></svg>';
            $returnstr = '<div class="rui-badge-guest">' . $icon . get_string('loggedinasguest') . '</div>';
            if (!$loginpage && $withlinks) {
                $returnstr .= "<a class=\"rui-login-btn\" href=\"$loginurl\"><span class=\"rui-login-btn-txt\">" . get_string('login') . '</span><svg class="ml-2" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 8.75L13.25 12L9.75 15.25"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 4.75H17.25C18.3546 4.75 19.25 5.64543 19.25 6.75V17.25C19.25 18.3546 18.3546 19.25 17.25 19.25H9.75"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 12H4.75"></path></svg></a>';
            }

            return html_writer::div(
                html_writer::span(
                    $returnstr,
                    'login'
                ),
                $usermenuclasses
            );
        }

        // Get some navigation opts.
        $opts = user_get_user_navigation_info($user, $this->page, array('avatarsize' => 56));

        $avatarclasses = "avatars";
        $avatarcontents = html_writer::span($opts->metadata['useravatar'], 'avatar current');
        $usertextcontents = '<span class="rui-fullname">' . $opts->metadata['userfullname'] . '</span>';
        $usertextmail = $user->email;
        $usernick = '<svg class="mr-2" width="18" height="18" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V15"></path>
        <circle cx="12" cy="9" r="1" fill="currentColor"></circle>
        <circle cx="12" cy="12" r="7.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></circle>
        </svg>' . $user->username;
        /* TODO: Add quick user card
        $useraddress = $user->address;
        $usercity = $user->city;
        $usercountry = $user->country;
        $userinstitution = $user->institution;
        $userdepartment = $user->department;
        $userphone1 = $user->phone1;
        $userphone2 = $user->phone2;*/

        // Other user.
        $usermeta = '';
        if (!empty($opts->metadata['asotheruser'])) {
            $avatarcontents .= html_writer::span(
                $opts->metadata['realuseravatar'],
                'avatar realuser'
            );
            $usermeta .= $opts->metadata['realuserfullname'];
            $usermeta .= html_writer::tag(
                'span',
                get_string(
                    'loggedinas',
                    'moodle',
                    html_writer::span(
                        $opts->metadata['userfullname'],
                        'value'
                    )
                ),
                array('class' => 'meta viewingas')
            );
        }

        // Role.
        if (!empty($opts->metadata['asotherrole'])) {
            $role = core_text::strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['rolename'])));
            $usermeta .= html_writer::span(
                $opts->metadata['rolename'],
                'meta role role-' . $role
            );
        }

        // User login failures.
        if (!empty($opts->metadata['userloginfail'])) {
            $usermeta .= html_writer::div(
                '<svg class="mr-2" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.9522 16.3536L10.2152 5.85658C10.9531 4.38481 13.0539 4.3852 13.7913 5.85723L19.0495 16.3543C19.7156 17.6841 18.7487 19.25 17.2613 19.25H6.74007C5.25234 19.25 4.2854 17.6835 4.9522 16.3536Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10V12"></path><circle cx="12" cy="16" r="1" fill="currentColor"></circle></svg>' .  $opts->metadata['userloginfail'],
                'meta loginfailures'
            );
        }

        // MNet.
        if (!empty($opts->metadata['asmnetuser'])) {
            $mnet = strtolower(preg_replace('#[ ]+#', '-', trim($opts->metadata['mnetidprovidername'])));
            $usermeta .= html_writer::span(
                $opts->metadata['mnetidprovidername'],
                'meta mnet mnet-' . $mnet
            );
        }

        $returnstr .= html_writer::span(
            //html_writer::span($usermeta, 'usertext') .
            html_writer::span($avatarcontents, $avatarclasses),
            'userbutton'
        );

        // Create a divider (well, a filler).
        $divider = new action_menu_filler();
        $divider->primary = false;

        $am = new action_menu();
        $am->set_menu_trigger(
            $returnstr
        );
        $am->set_action_label(get_string('usermenu'));
        $am->set_alignment(action_menu::TR, action_menu::BR);
        $am->set_nowrap_on_items();

        $am->add(
        '<div class="dropdown-user-wrapper"><div class="dropdown-user">' . $usertextcontents  .'</div>'
        .'<div class="dropdown-user-mail text-truncate" title="'.$usertextmail.'">'. $usertextmail . '</div>'
        .'<span class="dropdown-user-nick badge badge-sm badge-info">'. $usernick .'</span>'
        .'<div class="dropdown-user-meta">' . $usermeta . '</div>'
        .'</div><div class="dropdown-divider dropdown-divider-user"></div>'
        );

        if ($withlinks) {
            $navitemcount = count($opts->navitems);
            $idx = 0;
            foreach ($opts->navitems as $key => $value) {

                switch ($value->itemtype) {
                    case 'divider':
                        // If the nav item is a divider, add one and skip link processing.
                        $am->add($divider);
                        break;

                    case 'invalid':
                        // Silently skip invalid entries (should we post a notification?).
                        break;

                    case 'link':
                        $al = '<a class="dropdown-item" href="'.$value->url.'" data-identifier="'.$value->titleidentifier.'" title="'.$value->titleidentifier.'">'.$value->title.'</a>';
                        $am->add($al);
                        break;
                }

                $idx++;

                // Add dividers after the first item and before the last item.
                if ($idx == 1 || $idx == $navitemcount - 1) {
                    $am->add($divider);
                }
            }
        }

        return html_writer::div(
            $this->render($am),
            $usermenuclasses
        );




    }


    /**
     * Returns standard main content placeholder.
     * Designed to be called in theme layout.php files.
     *
     * @return string HTML fragment.
     */
    public function main_content() {
        // This is here because it is the only place we can inject the "main" role over the entire main content area
        // without requiring all theme's to manually do it, and without creating yet another thing people need to
        // remember in the theme.
        // This is an unfortunate hack. DO NO EVER add anything more here.
        // DO NOT add classes.
        // DO NOT add an id.
        return '<div class="main-content" role="main">'.$this->unique_main_content_token.'</div>';
    }

    /**
     * Outputs a heading
     *
     * @param string $text The text of the heading
     * @param int $level The level of importance of the heading. Defaulting to 2
     * @param string $classes A space-separated list of CSS classes. Defaulting to null
     * @param string $id An optional ID
     * @return string the HTML to output.
     */
    public function heading($text, $level = 2, $classes = null, $id = null) {
        $level = (integer) $level;
        if ($level < 1 or $level > 6) {
            throw new coding_exception('Heading level must be an integer between 1 and 6.');
        }
        return html_writer::tag('div', html_writer::tag('h' . $level, $text, array('id' => $id, 'class' => renderer_base::prepare_classes($classes) . ' rui-main-content-title rui-main-content-title--h' . $level)), array('class' => 'rui-title-container'));
    }


    public function headingwithavatar($text, $level = 2, $classes = null, $id = null) {
        $level = (integer) $level;
        if ($level < 1 or $level > 6) {
            throw new coding_exception('Heading level must be an integer between 1 and 6.');
        }
        return html_writer::tag('div', html_writer::tag('h' . $level, $text, array('id' => $id, 'class' => renderer_base::prepare_classes($classes) . ' rui-main-content-title-with-avatar')), array('class' => 'rui-title-container-with-avatar'));
    }

    /**
     * Renders the login form.
     *
     * @param \core_auth\output\login $form The renderable.
     * @return string
     */
    public function render_login(\core_auth\output\login $form) {
        global $CFG, $SITE, $PAGE;

        $context = $form->export_for_template($this);

        // Override because rendering is not supported in template yet.
        if ($CFG->rememberusername == 0) {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabledonlysession');
        } else {
            $context->cookieshelpiconformatted = $this->help_icon('cookiesenabled');
        }
        $context->errorformatted = $this->error_text($context->error);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context->logourl = $url;
        $context->sitename = format_string($SITE->fullname, true,
            ['context' => context_course::instance(SITEID), "escape" => false]);

        if ($PAGE->theme->settings->setloginlayout == 1) {
            $context->loginlayout1 = 1;
            $context->loginlayout2 = 0;
            $context->loginlayout3 = 0;
        } elseif ($PAGE->theme->settings->setloginlayout == 2) {
            $context->loginlayout1 = 0;
            $context->loginlayout2 = 1;
            $context->loginlayout3 = 0;
        } elseif ($PAGE->theme->settings->setloginlayout == 3) {
            $context->loginlayout1 = 0;
            $context->loginlayout2 = 0;
            $context->loginlayout3 = 1;
        }

        if (isset($PAGE->theme->settings->stringca)) {
            $context->stringca = format_text(($PAGE->theme->settings->stringca),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->loginadditionalcontent)) {
            $context->loginadditionalcontent = format_text(($PAGE->theme->settings->loginadditionalcontent),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->loginadditionalcontent2)) {
            $context->loginadditionalcontent2 = format_text(($PAGE->theme->settings->loginadditionalcontent2),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->loginfootercontent)) {
            $context->loginfootercontent = format_text(($PAGE->theme->settings->loginfootercontent),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->loginintrotext)) {
            $context->loginintrotext = format_text(($PAGE->theme->settings->loginintrotext),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->loginintrotext)) {
            $context->loginintrotext = format_text(($PAGE->theme->settings->loginintrotext),FORMAT_HTML, array('noclean' => true));
        }

        if (isset($PAGE->theme->settings->customloginlogo)) {
            $context->customloginlogo = $PAGE->theme->setting_file_url('customloginlogo', 'customloginlogo');
        }

        return $this->render_from_template('core/loginform', $context);
    }

    /**
     * Render the login signup form into a nice template for the theme.
     *
     * @param mform $form
     * @return string
     */
    public function render_login_signup_form($form) {
        global $CFG, $SITE, $PAGE;

        $context = $form->export_for_template($this);
        $url = $this->get_logo_url();
        if ($url) {
            $url = $url->out(false);
        }
        $context['logourl'] = $url;
        $context['sitename'] = format_string($SITE->fullname, true,
            ['context' => context_course::instance(SITEID), "escape" => false]);

        if (isset($PAGE->theme->settings->stringbacktologin)) {
            $context['stringbacktologin'] = format_text(($PAGE->theme->settings->stringbacktologin),FORMAT_HTML, array('noclean' => true));
        }
        if (isset($PAGE->theme->settings->signupintrotext)) {
            $context['signupintrotext'] = format_text(($PAGE->theme->settings->signupintrotext),FORMAT_HTML, array('noclean' => true));
        }
        if (isset($PAGE->theme->settings->signuptext)) {
            $context['signuptext'] = format_text(($PAGE->theme->settings->signuptext),FORMAT_HTML, array('noclean' => true));
        }

        if (!empty($this->page->theme->settings->customloginlogo)) {
            $url = $this->page->theme->setting_file_url('customloginlogo', 'customloginlogo');
            $context['customloginlogo'] = $url;
        }

        return $this->render_from_template('core/signup_form_layout', $context);
    }
}
