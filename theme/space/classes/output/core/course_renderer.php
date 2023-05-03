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
 * Course renderer.
 *
 * @package    theme_space
 * @copyright  2016 Frédéric Massart, mod 2018 Rosea Themes
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_space\output\core;
defined('MOODLE_INTERNAL') || die();

use moodle_url;
use html_writer;
use core_course_category;
use coursecat_helper;
use stdClass;
use core_course_list_element;
use Mustache_LambdaHelper;

require_once($CFG->dirroot . '/course/renderer.php');

/**
 * Course renderer class.
 *
 * @package    theme_space
 * @copyright  mod by 2018 Rosea Themes, 2016 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function trunc($phrase, $max_words) {
    $phrase_array = explode(' ',$phrase);
    if(count($phrase_array) > $max_words && $max_words > 0)
       $phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
    return $phrase;
}

class course_renderer extends \core_course_renderer {

    /**
     * Renders html to display a course search form.
     *
     * @param string $value default value to populate the search field
     * @param string $format display format - 'plain' (default), 'short' or 'navbar'
     * @return string
     */
    public function course_search_form($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }

        switch ($format) {
            case 'navbar' :
                $formid = 'coursesearchnavbar';
                $inputid = 'navsearchbox';
                $inputsize = 20;
                break;
            case 'short' :
                $inputid = 'shortsearchbox';
                $inputsize = 12;
                break;
            default :
                $inputid = 'coursesearchbox';
                $inputsize = 30;
        }

        $data = (object) [
            'searchurl' => (new moodle_url('/course/search.php'))->out(false),
            'id' => $formid,
            'inputid' => $inputid,
            'inputsize' => $inputsize,
            'value' => $value
        ];

        return $this->render_from_template('theme_space/course_search_form', $data);
    }

    /**
     * Renders the list of courses
     *
     * This is internal function, please use {@link core_course_renderer::courses_list()} or another public
     * method from outside of the class
     *
     * If list of courses is specified in $courses; the argument $chelper is only used
     * to retrieve display options and attributes, only methods get_show_courses(),
     * get_courses_display_option() and get_and_erase_attributes() are called.
     *
     * @param coursecat_helper $chelper various display options
     * @param array $courses the list of courses to display
     * @param int|null $totalcount total number of courses (affects display mode if it is AUTO or pagination if applicable),
     *     defaulted to count($courses)
     * @return string
     */
    protected function coursecat_courses(coursecat_helper $chelper, $courses, $totalcount = null) {
	    global $CFG;
        $theme = \theme_config::load('space');

        if (!empty($theme->settings->courselistview)) {
            return parent::coursecat_courses($chelper, $courses, $totalcount);
        }

        if ($totalcount === null) {
            $totalcount = count($courses);
        }

        if (!$totalcount) {
            // Courses count is cached during courses retrieval.
            return '';
        }

        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_AUTO) {
            // In 'auto' course display mode we analyse if number of courses is more or less than $CFG->courseswithsummarieslimit.
            if ($totalcount <= $CFG->courseswithsummarieslimit) {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED);
            } else {
                $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_COLLAPSED);
            }
        }

        // Prepare content of paging bar if it is needed.
        $paginationurl = $chelper->get_courses_display_option('paginationurl');
        $paginationallowall = $chelper->get_courses_display_option('paginationallowall');
        if ($totalcount > count($courses)) {
            // There are more results that can fit on one page.
            if ($paginationurl) {
                // The option paginationurl was specified, display pagingbar.
                $perpage = $chelper->get_courses_display_option('limit', $CFG->coursesperpage);
                $page = $chelper->get_courses_display_option('offset') / $perpage;
                $pagingbar = $this->paging_bar($totalcount, $page, $perpage,
                        $paginationurl->out(false, array('perpage' => $perpage)));
                if ($paginationallowall) {
                    $pagingbar .= html_writer::tag('div', html_writer::link($paginationurl->out(false, array('perpage' => 'all')),
                            get_string('showall', '', $totalcount)), array('class' => 'paging paging-showall'));
                }
            } else if ($viewmoreurl = $chelper->get_courses_display_option('viewmoreurl')) {
                // The option for 'View more' link was specified, display more link.
                $viewmoretext = $chelper->get_courses_display_option('viewmoretext', new \lang_string('viewmore'));
                $morelink = html_writer::tag('div', html_writer::link($viewmoreurl, $viewmoretext),
                        array('class' => 'paging paging-morelink'));
            }
        } else if (($totalcount > $CFG->coursesperpage) && $paginationurl && $paginationallowall) {
            // There are more than one page of results and we are in 'view all' mode, suggest to go back to paginated view mode.
            $pagingbar = html_writer::tag(
                'div',
                html_writer::link(
                    $paginationurl->out(
                        false,
                        array('perpage' => $CFG->coursesperpage)
                    ),
                    get_string('showperpage', '', $CFG->coursesperpage)
                ),
                array('class' => 'paging paging-showperpage')
            );
        }

        // Display list of courses.
        $attributes = $chelper->get_and_erase_attributes('s-courses');
        $content = html_writer::start_tag('div', $attributes);

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        $coursecount = 1;
        $content .= html_writer::start_tag('div', array('class' => 'card-deck m-0 justify-content-start'));
        foreach ($courses as $course) {
            $content .= $this->coursecat_coursebox($chelper, $course);
            $coursecount ++;
        }

        $content .= html_writer::end_tag('div');

        if (!empty($pagingbar)) {
            $content .= $pagingbar;
        }

        if (!empty($morelink)) {
            $content .= $morelink;
        }

        $content .= html_writer::end_tag('div'); // End courses.
        return $content;
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@link core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param course_in_list|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG;

        $theme = \theme_config::load('space');

        if (!empty($theme->settings->courselistview)) {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');

        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            $course = new core_course_list_element($course);
        }

        $classes = trim('card c-course-box');

        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }

        // End coursebox.
        $content = html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
            'data-type' => self::COURSECAT_TYPE_COURSE,
        ));

        $content .= html_writer::start_tag('div', ['class' => 'c-course-content row no-gutters']);
        $content .= $this->coursecat_coursebox_content($chelper, $course);

        $content .= html_writer::end_tag('div'); // End course-content
        $content .= html_writer::end_tag('div');


        return $content;
    }


    /**
     * Returns HTML to display course content (summary, course contacts and optionally category name)
     *
     * This method is called from coursecat_coursebox() and may be re-used in AJAX
     *
     * @param coursecat_helper $chelper various display options
     * @param stdClass|course_in_list $course
     * @return string
     */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course) {
        global $PAGE, $CFG, $DB;

		if ($course instanceof stdClass) {

			$course = new core_course_list_element($course);
		}
        // Course name.
        $coursename = $chelper->get_course_formatted_name($course);
        $courselink = new moodle_url('/course/view.php', array('id' => $course->id));
        $coursenamelink = html_writer::link($courselink, $coursename, array('class' => $course->visible ? '' : 'dimmed'));

        $content = $this->get_course_summary_image($course, $courselink);

        $content .= html_writer::start_tag('div', array('class' => 'c-course-box-content w-100'));

        $content .= "<h4 class='course-box-title'>";
        $content .= "<span class='course-box-title-txt' title='$coursename'>". $coursenamelink ."</span>";

	         // Print enrolmenticons.
             if ($icons = enrol_get_course_info_icons($course)) {
                $content .= "<div class='course-box-icons'><ul class='course-box-icons-list'>";
	            foreach ($icons as $pixicon) {
                    $content .= "<li>";
                    $content .= $this->render($pixicon);
                    $content .= "</li>";
                }
                $content .= "</ul></div>";
	        }

	    $content .= "</h4>";

		$content .= "<div class='course-box-content-desc'>";

        // Display course summary.
        if ($course->has_summary()) {
            $content .= html_writer::start_tag('div', array('class' => 'course-box-desc'));

            if(!empty($PAGE->theme->settings->cccdlimit)) {
                //info https://github.com/moodle/moodle/blob/master/lib/classes/output/mustache_shorten_text_helper.php
                $content .= shorten_text($chelper->get_course_formatted_summary($course,
                    array('overflowdiv' => false, 'noclean' => false, 'para' => false)), $PAGE->theme->settings->coursecarddesclimit);
            } else {
                $content .= $chelper->get_course_formatted_summary($course,
                array('overflowdiv' => false, 'noclean' => false, 'para' => false));
            }


            $content .= html_writer::end_tag('div'); // End summary.
        }

        $content .= html_writer::end_tag('div'); // end course-box-desc

        $content .= html_writer::end_tag('div');

        // Course instructors.
        if ($course->has_course_contacts()) {
            $content .= html_writer::start_tag('div', array('class' => 'course-contacts'));

            $i = 0;

            $instructors = $course->get_course_contacts();
            foreach ($instructors as $key => $instructor) {
                $name = $instructor['username'];

                $url = $CFG->wwwroot.'/user/profile.php?id='.$key;
                $picture = $this->get_user_picture($DB->get_record('user', array('id' => $key)));

                $content .= "<a href='{$url}' title='{$name}' class='c-courses-contact' data-toggle='tooltip' title='{$name}'>";
                $content .= "<img src='{$picture}' class='c-courses-teacher-avatar rounded-circle' alt='{$name}' />";
                $content .= "</a>";

                if (++$i > 2) {
                    $idtemp = "box-" . rand();
                    $content .= "<a href='#$idtemp' class='course-contacts-toggle-btn show-course-contacts-btn' data-toggle='collapse' aria-expanded='false' data-target='#$idtemp'></a>";

                    $content .= "<div id='$idtemp' class='course-contacts-ext collapse'>";
                    $content .= "<a href='#$idtemp' class='course-contacts-toggle-btn close close-course-contacts-btn' data-toggle='collapse' aria-expanded='false' data-target='#$idtemp'></a>";

                    $content .= "<h6 class='course-contacts-ext-title ml-3 my-3'>" . get_string('teachers', 'theme_space') . "</h6>";
                    $content .= html_writer::start_tag('div', array('class' => 'course-contacts-ext-content'));
                    $content .= html_writer::start_tag('ul', array('class' => 'course-contacts-ext-list'));
                    $instructors = $course->get_course_contacts();
                    foreach ($instructors as $key => $instructor) {
                        $name = $instructor['username'];
                        $url = $CFG->wwwroot.'/user/profile.php?id='.$key;
                        $picture = $this->get_user_picture($DB->get_record('user', array('id' => $key)));

                        $content .= "<li><a href='{$url}' title='{$name}' class='c-courses-contact' title='{$name}'>";
                        $content .= "<img src='{$picture}' class='c-courses-teacher-avatar rounded-circle' alt='{$name}' /><span class='c-courses-teacher-name'>{$name}</span>";
                        $content .= "</a></li>";
                    }
                    $content .= html_writer::end_tag('ul'); // Ends course-contacts-ext-list.
                    $content .= html_writer::end_tag('div'); // Ends course-contacts-ext-content.
                    $content .= html_writer::end_tag('div'); // Ends course-contacts-ext.
                break;
                }
            }

            $content .= html_writer::end_tag('div'); // Ends course-contacts.

        }

        // Display course category if necessary (for example in search results).

            if ($cat = core_course_category::get($course->category, IGNORE_MISSING)) {
                $content .= html_writer::start_tag('div', array('class' => 'coursecat'));
                $content .=
                    html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                        $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'), array('class' => 'paging paging-showall'));
                $content .= html_writer::end_tag('div'); // End coursecat.
            }


        $content .= html_writer::start_tag('div', array('class' => 'courses-view-course-item-footer col-12 align-self-end'));

        $content .= html_writer::start_tag('div', array('class' => 'course--btn'));
        $content .= html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
            get_string('access', 'theme_space'), array('class' => 'btn btn-primary w-100'));
        $content .= html_writer::end_tag('div'); // End pull-right.

        $content .= html_writer::end_tag('div'); // End card-block.


        return $content;
    }


    /**
     * Returns the first course's summary issue
     *
     * @param stdClass $course the course object
     * @return string
     */
    protected function get_course_summary_image($course, $courselink) {
        global $CFG;

        $content = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
           if ($isimage) {
                $content .= html_writer::start_tag('a', array('class' => 'col-12 align-self-start p-0', 'href' => $courselink));
                    $content .= html_writer::start_tag('div', array('class' => 'card-img-top myoverviewimg courseimage', 'style' => 'background-image: url(' . $url . ');'));
                    $content .= html_writer::end_tag('div');
                $content .= html_writer::end_tag('a');
            }

        }

        if (empty($content)) {
            $url = $CFG->wwwroot . "/theme/space/pix/default_course.jpg";
            $content .= html_writer::start_tag('a', array('class' => 'col-12 align-self-start p-0', 'href' => $courselink));
                $content .= html_writer::start_tag('div', array('class' => 'card-img-top myoverviewimg courseimage', 'style' => 'background-image: url(' . $url . ');'));
                $content .= html_writer::end_tag('div');
            $content .= html_writer::end_tag('a');

        }

        return $content;
    }

    /**
     * Get the user profile pic
     *
     * @param null $userobject
     * @param int $imgsize
     * @return moodle_url
     * @throws \coding_exception
     */
    protected function get_user_picture($userobject = null, $imgsize = 300) {
        global $USER, $PAGE;

        if (!$userobject) {
            $userobject = $USER;
        }

        $userimg = new \user_picture($userobject);

        $userimg->size = $imgsize;

        return  $userimg->get_url($PAGE);
    }

    /**
     * Returns HTML to display a course category as a part of a tree
     *
     * This is an internal function, to display a particular category and all its contents
     * use {@link core_course_renderer::course_category()}
     *
     * @param coursecat_helper $chelper various display options
     * @param coursecat $coursecat
     * @param int $depth depth of this category in the current tree
     * @return string
     */
    protected function coursecat_category(coursecat_helper $chelper, $coursecat, $depth) {
        // open category tag
        $classes = array('category');
        if (empty($coursecat->visible)) {
            $classes[] = 'dimmed_category';
        }
        if ($chelper->get_subcat_depth() > 0 && $depth >= $chelper->get_subcat_depth()) {
            // do not load content
            $categorycontent = '';
            $classes[] = 'notloaded';
            if ($coursecat->get_children_count() ||
                    ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_COLLAPSED && $coursecat->get_courses_count())) {
                $classes[] = 'with_children';
                $classes[] = 'collapsed';
            }
        } else {
            // load category content
            $categorycontent = $this->coursecat_category_content($chelper, $coursecat, $depth);
            $classes[] = 'loaded';
            if (!empty($categorycontent)) {
                $classes[] = 'with_children collapsed';
                // Category content loaded with children.
                $this->categoryexpandedonload = true;
            }
        }

        // Make sure JS file to expand category content is included.
        $this->coursecat_include_js();

        $content = html_writer::start_tag('div', array(
            'class' => join(' ', $classes),
            'data-categoryid' => $coursecat->id,
            'data-depth' => $depth,
            'data-showcourses' => $chelper->get_show_courses(),
            'data-type' => self::COURSECAT_TYPE_CATEGORY,
        ));

        // category name
        $categoryname = $coursecat->get_formatted_name();
        $categoryname = html_writer::link(new moodle_url('/course/index.php',
                array('categoryid' => $coursecat->id)),
                $categoryname);
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_COUNT
                && ($coursescount = $coursecat->get_courses_count())) {
            $categoryname .= html_writer::tag('span', ' ('. $coursescount.')',
                    array('title' => get_string('numberofcourses'), 'class' => 'numberofcourse'));
        }
        $content .= html_writer::start_tag('div', array('class' => 'info'));

        $content .= html_writer::tag(($depth > 1) ? 'h4' : 'h3', $categoryname, array('class' => 'categoryname'));
        $content .= html_writer::end_tag('div'); // .info

        // add category content to the output
        $content .= html_writer::tag('div', $categorycontent, array('class' => 'content'));

        $content .= html_writer::end_tag('div'); // .category

        // Return the course category tree HTML
        return $content;
    }

}
