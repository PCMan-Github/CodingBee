<?php
// This file is part of The Bootstrap Moodle theme
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
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_space
 * @copyright  Copyright © 2018 onwards, Marcin Czaja | RoseaThemes, rosea.io - Rosea Themes
 * @license    Commercial https://themeforest.net/licenses
 */

namespace theme_space\output\core_course\management;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/course/classes/management_renderer.php");

use html_writer;
use core_course_category;
use moodle_url;
use core_course_list_element;
use lang_string;
use context_system;
use stdClass;
use action_menu;
use action_menu_link_secondary;

/**
 * Main renderer for the course management pages.
 *
 * @package theme_space
 * @copyright 2013 Sam Hemelryk
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \core_course_management_renderer {

    /**
     * Renderers the actions that are possible for the course category listing.
     *
     * These are not the actions associated with an individual category listing.
     * That happens through category_listitem_actions.
     *
     * @param core_course_category $category
     * @return string
     */
    public function category_listing_actions(core_course_category $category = null) {
        $actions = array();

        $cancreatecategory = $category && $category->can_create_subcategory();
        $cancreatecategory = $cancreatecategory || core_course_category::can_create_top_level_category();
        if ($category === null) {
            $category = core_course_category::top();
        }

        if ($cancreatecategory) {
            $url = new moodle_url('/course/editcategory.php', array('parent' => $category->id));
            $actions[] = html_writer::link($url, get_string('createnewcategory'), array('class' => 'btn btn-primary w-100'));
        }
        if (core_course_category::can_approve_course_requests()) {
            $actions[] = html_writer::link(new moodle_url('/course/pending.php'), get_string('coursespending'));
        }
        if (count($actions) === 0) {
            return '';
        }
        return html_writer::div(join(' ', $actions), 'listing-actions category-listing-actions mb-3');
    }

    /**
     * Renderers actions for the course listing.
     *
     * Not to be confused with course_listitem_actions which renderers the actions for individual courses.
     *
     * @param core_course_category $category
     * @param core_course_list_element $course The currently selected course.
     * @param int $perpage
     * @return string
     */
    public function course_listing_actions(core_course_category $category, core_course_list_element $course = null, $perpage = 20) {
        $actions = array();
        if ($category->can_create_course()) {
            $url = new moodle_url('/course/edit.php', array('category' => $category->id, 'returnto' => 'catmanage'));
            $actions[] = html_writer::link($url, get_string('createnewcourse'), array('class' => 'btn btn-primary w-100 mb-3'));
        }
        if ($category->can_request_course()) {
            // Request a new course.
            $url = new moodle_url('/course/request.php', array('category' => $category->id, 'return' => 'management'));
            $actions[] = html_writer::link($url, get_string('requestcourse'));
        }
        if ($category->can_resort_courses()) {
            $params = $this->page->url->params();
            $params['action'] = 'resortcourses';
            $params['sesskey'] = sesskey();
            $baseurl = new moodle_url('/course/management.php', $params);
            $fullnameurl = new moodle_url($baseurl, array('resort' => 'fullname'));
            $fullnameurldesc = new moodle_url($baseurl, array('resort' => 'fullnamedesc'));
            $shortnameurl = new moodle_url($baseurl, array('resort' => 'shortname'));
            $shortnameurldesc = new moodle_url($baseurl, array('resort' => 'shortnamedesc'));
            $idnumberurl = new moodle_url($baseurl, array('resort' => 'idnumber'));
            $idnumberdescurl = new moodle_url($baseurl, array('resort' => 'idnumberdesc'));
            $timecreatedurl = new moodle_url($baseurl, array('resort' => 'timecreated'));
            $timecreateddescurl = new moodle_url($baseurl, array('resort' => 'timecreateddesc'));
            $menu = new action_menu(array(
                    new action_menu_link_secondary($fullnameurl,
                            null,
                            get_string('sortbyx', 'moodle', get_string('fullnamecourse'))),
                    new action_menu_link_secondary($fullnameurldesc,
                            null,
                            get_string('sortbyxreverse', 'moodle', get_string('fullnamecourse'))),
                    new action_menu_link_secondary($shortnameurl,
                            null,
                            get_string('sortbyx', 'moodle', get_string('shortnamecourse'))),
                    new action_menu_link_secondary($shortnameurldesc,
                            null,
                            get_string('sortbyxreverse', 'moodle', get_string('shortnamecourse'))),
                    new action_menu_link_secondary($idnumberurl,
                            null,
                            get_string('sortbyx', 'moodle', get_string('idnumbercourse'))),
                    new action_menu_link_secondary($idnumberdescurl,
                            null,
                            get_string('sortbyxreverse', 'moodle', get_string('idnumbercourse'))),
                    new action_menu_link_secondary($timecreatedurl,
                            null,
                            get_string('sortbyx', 'moodle', get_string('timecreatedcourse'))),
                    new action_menu_link_secondary($timecreateddescurl,
                            null,
                            get_string('sortbyxreverse', 'moodle', get_string('timecreatedcourse')))
            ));
            $menu->set_menu_trigger(get_string('resortcourses'));
            $actions[] = $this->render($menu);
        }
        $strall = get_string('all');
        $menu = new action_menu(array(
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 5)), null, 5),
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 10)), null, 10),
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 20)), null, 20),
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 50)), null, 50),
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 100)), null, 100),
                new action_menu_link_secondary(new moodle_url($this->page->url, array('perpage' => 999)), null, $strall),
        ));
        if ((int)$perpage === 999) {
            $perpage = $strall;
        }
        $menu->attributes['class'] .= ' courses-per-page';
        $menu->set_menu_trigger(get_string('perpagea', 'moodle', $perpage));
        $actions[] = $this->render($menu);
        return html_writer::div(join(' ', $actions), 'listing-actions course-listing-actions');
    }



    /**
     * Renders pagination for a course listing.
     *
     * @param core_course_category $category The category to produce pagination for.
     * @param int $page The current page.
     * @param int $perpage The number of courses to display per page.
     * @param bool $showtotals Set to true to show the total number of courses and what is being displayed.
     * @param string|null $viewmode The view mode the page is in, one out of 'default', 'combined', 'courses' or 'categories'.
     * @return string
     */
    protected function listing_pagination(core_course_category $category, $page, $perpage, $showtotals = false,
                                          $viewmode = 'default') {
        $html = '';
        $totalcourses = $category->get_courses_count();
        $totalpages = ceil($totalcourses / $perpage);
        if ($showtotals) {
            if ($totalpages == 0) {
                $str = get_string('nocoursesyet');
            } else if ($totalpages == 1) {
                $str = get_string('showingacourses', 'moodle', $totalcourses);
            } else {
                $a = new stdClass;
                $a->start = ($page * $perpage) + 1;
                $a->end = min((($page + 1) * $perpage), $totalcourses);
                $a->total = $totalcourses;
                $str = get_string('showingxofycourses', 'moodle', $a);
            }
            $html .= html_writer::div($str, 'listing-pagination-totals badge badge-success w-100 text-center');
        }

        if ($viewmode !== 'default') {
            $baseurl = new moodle_url('/course/management.php', array('categoryid' => $category->id,
                'view' => $viewmode));
        } else {
            $baseurl = new moodle_url('/course/management.php', array('categoryid' => $category->id));
        }

        $html .= $this->output->paging_bar($totalcourses, $page, $perpage, $baseurl);
        return $html;
    }


        /**
     * Displays pagination for search results.
     *
     * @param int $totalcourses The total number of courses to be displayed.
     * @param int $page The current page.
     * @param int $perpage The number of courses being displayed.
     * @param bool $showtotals Whether or not to print total information.
     * @param string $search The string we are searching for.
     * @return string
     */
    protected function search_pagination($totalcourses, $page, $perpage, $showtotals = false, $search = '') {
        $html = '';
        $totalpages = ceil($totalcourses / $perpage);
        if ($showtotals) {
            if ($totalpages == 0) {
                $str = get_string('nocoursesfound', 'moodle', s($search));
            } else if ($totalpages == 1) {
                $str = get_string('showingacourses', 'moodle', $totalcourses);
            } else {
                $a = new stdClass;
                $a->start = ($page * $perpage) + 1;
                $a->end = min((($page + 1) * $perpage), $totalcourses);
                $a->total = $totalcourses;
                $str = get_string('showingxofycourses', 'moodle', $a);
            }
            $html .= html_writer::div($str, 'listing-pagination-totals badge badge-success w-100 text-center');
        }

        if ($totalcourses < $perpage) {
            return $html;
        }
        $aside = 2;
        $span = $aside * 2 + 1;
        $start = max($page - $aside, 0);
        $end = min($page + $aside, $totalpages - 1);
        if (($end - $start) < $span) {
            if ($start == 0) {
                $end = min($totalpages - 1, $span - 1);
            } else if ($end == ($totalpages - 1)) {
                $start = max(0, $end - $span + 1);
            }
        }
        $items = array();
        $baseurl = $this->page->url;
        if ($page > 0) {
            $items[] = $this->action_button(new moodle_url($baseurl, array('page' => 0)), get_string('first'));
            $items[] = $this->action_button(new moodle_url($baseurl, array('page' => $page - 1)), get_string('prev'));
            $items[] = '...';
        }
        for ($i = $start; $i <= $end; $i++) {
            $class = '';
            if ($page == $i) {
                $class = 'active-page';
            }
            $items[] = $this->action_button(new moodle_url($baseurl, array('page' => $i)), $i + 1, null, $class);
        }
        if ($page < ($totalpages - 1)) {
            $items[] = '...';
            $items[] = $this->action_button(new moodle_url($baseurl, array('page' => $page + 1)), get_string('next'));
            $items[] = $this->action_button(new moodle_url($baseurl, array('page' => $totalpages - 1)), get_string('last'));
        }

        $html .= html_writer::div(join('', $items), 'listing-pagination');
        return $html;
    }

    /**
     * Displays a view mode selector.
     *
     * @param array $modes An array of view modes.
     * @param string $currentmode The current view mode.
     * @param moodle_url $url The URL to use when changing actions. Defaults to the page URL.
     * @param string $param The param name.
     * @return string
     */
    public function view_mode_selector(array $modes, $currentmode, moodle_url $url = null, $param = 'view') {
        if ($url === null) {
            $url = $this->page->url;
        }

        $menu = new action_menu;
        $menu->attributes['class'] .= ' view-mode-selector vms ml-1';

        $selected = null;
        foreach ($modes as $mode => $modestr) {
            $attributes = array(
                'class' => 'vms-mode',
                'data-mode' => $mode
            );
            if ($currentmode === $mode) {
                $attributes['class'] .= ' currentmode';
                $selected = $modestr;
            }
            if ($selected === null) {
                $selected = $modestr;
            }
            $modeurl = new moodle_url($url, array($param => $mode));
            if ($mode === 'default') {
                $modeurl->remove_params($param);
            }
            $menu->add(new action_menu_link_secondary($modeurl, null, $modestr, $attributes));
        }

        $menu->set_menu_trigger($selected);

        $html = html_writer::start_div('view-mode-selector vms d-flex');
        $html .= '<span class="mr-1">' .get_string('viewing').' '.$this->render($menu) . '</span>';
        $html .= html_writer::end_div();

        return $html;
    }

    /**
     * Renderers a search result course list item.
     *
     * This function will be called for every course being displayed by course_listing.
     *
     * @param core_course_list_element $course The course to produce HTML for.
     * @param int $selectedcourse The id of the currently selected course.
     * @return string
     */
    public function search_listitem(core_course_list_element $course, $selectedcourse) {

        $text = $course->get_formatted_name();
        $attributes = array(
                'class' => 'listitem listitem-course list-group-item list-group-item-action',
                'data-id' => $course->id,
                'data-selected' => ($selectedcourse == $course->id) ? '1' : '0',
                'data-visible' => $course->visible ? '1' : '0'
        );
        $bulkcourseinput = '';
        if (core_course_category::get($course->category)->can_move_courses_out_of()) {
            $bulkcourseinput = array(
                    'type' => 'checkbox',
                    'id' => 'coursesearchlistitem' . $course->id,
                    'name' => 'bc[]',
                    'value' => $course->id,
                    'class' => 'bulk-action-checkbox custom-control-input',
                    'data-action' => 'select'
            );
        }
        $viewcourseurl = new moodle_url($this->page->url, array('courseid' => $course->id));
        $categoryname = core_course_category::get($course->category)->get_formatted_name();

        $html  = html_writer::start_tag('li', $attributes);
        $html .= html_writer::start_div('clearfix');
        $html .= html_writer::start_div('float-left');
        if ($bulkcourseinput) {
            $html .= html_writer::start_div('custom-control custom-checkbox mr-1');
            $html .= html_writer::empty_tag('input', $bulkcourseinput);
            $html .= html_writer::tag('label', '', array(
                'aria-label' => get_string('bulkactionselect', 'moodle', $text),
                'class' => 'custom-control-label',
                'for' => 'coursesearchlistitem' . $course->id));
            $html .= html_writer::end_div();
        }
        $html .= html_writer::end_div();
        $html .= html_writer::link($viewcourseurl, $text, array('class' => 'float-left coursename aalink'));
        $html .= html_writer::tag('span', $categoryname, array('class' => 'float-left ml-3 badge badge-success'));
        $html .= html_writer::start_div('float-right');
        $html .= $this->search_listitem_actions($course);
        $html .= html_writer::tag('span', s($course->idnumber), array('class' => 'text-muted idnumber'));
        $html .= html_writer::end_div();
        $html .= html_writer::end_div();
        $html .= html_writer::end_tag('li');
        return $html;
    }


    /**
     * Renderers a key value pair of information for display.
     *
     * @param string $key
     * @param string $value
     * @param string $class
     * @return string
     */
    protected function detail_pair($key, $value, $class ='') {
        $html = html_writer::start_div('detail-pair row align-items-center pt-3 px-3 mt-3 border-top '.preg_replace('#[^a-zA-Z0-9_\-]#', '-', $class));
        $html .= html_writer::div(html_writer::span($key), 'pair-key col-md-4 font-weight-bold text-right');
        $html .= html_writer::div(html_writer::span($value), 'pair-value col');
        $html .= html_writer::end_div();
        return $html;
    }


    /**
     * A collection of actions for a course.
     *
     * @param core_course_list_element $course The course to display actions for.
     * @return string
     */
    public function course_detail_actions(core_course_list_element $course) {
        $actions = \core_course\management\helper::get_course_detail_actions($course);
        if (empty($actions)) {
            return '';
        }
        $options = array();
        foreach ($actions as $action) {
            $options[] = $this->action_link($action['url'], $action['string'], null,
                    array('class' => 'btn mr-1'));
        }
        return html_writer::div(join('', $options), 'listing-actions course-detail-listing-actions');
    }

    /**
     * Creates an action button (styled link)
     *
     * @param moodle_url $url The URL to go to when clicked.
     * @param string $text The text for the button.
     * @param string $id An id to give the button.
     * @param string $class A class to give the button.
     * @param array $attributes Any additional attributes
     * @return string
     */
    protected function action_button(moodle_url $url, $text, $id = null, $class = null, $title = null, array $attributes = array()) {
        if (isset($attributes['class'])) {
            $attributes['class'] .= '';
        } else {
            $attributes['class'] = '';
        }
        if (!is_null($id)) {
            $attributes['id'] = $id;
        }
        if (!is_null($class)) {
            $attributes['class'] .= ' '.$class;
        }
        if (is_null($title)) {
            $title = $text;
        }
        $attributes['title'] = $title;
        if (!isset($attributes['role'])) {
            $attributes['role'] = 'button';
        }
        return html_writer::link($url, $text, $attributes);
    }

    /**
     * Renders bulk actions for categories.
     *
     * @param core_course_category $category The currently selected category if there is one.
     * @return string
     */
    public function category_bulk_actions(core_course_category $category = null) {
        // Resort courses.
        // Change parent.
        if (!core_course_category::can_resort_any() && !core_course_category::can_change_parent_any()) {
            return '';
        }
        $strgo = new lang_string('go');

        $html  = html_writer::start_div('category-bulk-actions bulk-actions');
        $html .= html_writer::div(get_string('categorybulkaction'), 'accesshide', array('tabindex' => '0'));
        if (core_course_category::can_resort_any()) {
            $selectoptions = array(
                'selectedcategories' => get_string('selectedcategories'),
                'allcategories' => get_string('allcategories')
            );
            $form = html_writer::start_div();
            if ($category) {
                $selectoptions = array('thiscategory' => get_string('thiscategory')) + $selectoptions;
                $form .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => 'currentcategoryid', 'value' => $category->id));
            }
            $form .= html_writer::div(
                html_writer::select(
                    $selectoptions,
                    'selectsortby',
                    'selectedcategories',
                    false,
                    array('aria-label' => get_string('selectcategorysort'))
                )
            );
            $form .= html_writer::div(
                html_writer::select(
                    array(
                        'name' => get_string('sortbyx', 'moodle', get_string('categoryname')),
                        'namedesc' => get_string('sortbyxreverse', 'moodle', get_string('categoryname')),
                        'idnumber' => get_string('sortbyx', 'moodle', get_string('idnumbercoursecategory')),
                        'idnumberdesc' => get_string('sortbyxreverse' , 'moodle' , get_string('idnumbercoursecategory')),
                        'none' => get_string('dontsortcategories')
                    ),
                    'resortcategoriesby',
                    'name',
                    false,
                    array('aria-label' => get_string('selectcategorysortby'), 'class' => 'mt-1')
                )
            );
            $form .= html_writer::div(
                html_writer::select(
                    array(
                        'fullname' => get_string('sortbyx', 'moodle', get_string('fullnamecourse')),
                        'fullnamedesc' => get_string('sortbyxreverse', 'moodle', get_string('fullnamecourse')),
                        'shortname' => get_string('sortbyx', 'moodle', get_string('shortnamecourse')),
                        'shortnamedesc' => get_string('sortbyxreverse', 'moodle', get_string('shortnamecourse')),
                        'idnumber' => get_string('sortbyx', 'moodle', get_string('idnumbercourse')),
                        'idnumberdesc' => get_string('sortbyxreverse', 'moodle', get_string('idnumbercourse')),
                        'timecreated' => get_string('sortbyx', 'moodle', get_string('timecreatedcourse')),
                        'timecreateddesc' => get_string('sortbyxreverse', 'moodle', get_string('timecreatedcourse')),
                        'none' => get_string('dontsortcourses')
                    ),
                    'resortcoursesby',
                    'fullname',
                    false,
                    array('aria-label' => get_string('selectcoursesortby'), 'class' => 'mt-1')
                )
            );
            $form .= html_writer::empty_tag('input', array('type' => 'submit', 'name' => 'bulksort',
                'value' => get_string('sort'), 'class' => 'btn btn-secondary my-1'));
            $form .= html_writer::end_div();

            $html .= html_writer::start_div('detail-pair row pt-3 px-3 mt-3');
            $html .= html_writer::div(html_writer::span(get_string('sorting')), 'pair-key col-md-4 pt-3 text-right');
            $html .= html_writer::div($form, 'pair-value col');
            $html .= html_writer::end_div();
        }
        if (core_course_category::can_change_parent_any()) {
            $options = array();
            if (core_course_category::top()->has_manage_capability()) {
                $options[0] = core_course_category::top()->get_formatted_name();
            }
            $options += core_course_category::make_categories_list('moodle/category:manage');
            $select = html_writer::select(
                $options,
                'movecategoriesto',
                '',
                array('' => 'choosedots'),
                array('aria-labelledby' => 'moveselectedcategoriesto', 'class' => 'mr-1')
            );
            $submit = array('type' => 'submit', 'name' => 'bulkmovecategories', 'value' => get_string('move'),
                'class' => 'btn btn-secondary');
            $html .= $this->detail_pair(
                html_writer::div(get_string('moveselectedcategoriesto'), '', array('id' => 'moveselectedcategoriesto')),
                $select . html_writer::empty_tag('input', $submit)
            );
        }
        $html .= html_writer::end_div();
        return $html;
    }

     /**
     * Renderers bulk actions that can be performed on courses.
     *
     * @param core_course_category $category The currently selected category and the category in which courses that
     *      are selectable belong.
     * @return string
     */
    public function course_bulk_actions(core_course_category $category) {
        $html  = html_writer::start_div('course-bulk-actions bulk-actions');
        if ($category->can_move_courses_out_of()) {
            $html .= html_writer::div(get_string('coursebulkaction'), 'accesshide', array('tabindex' => '0'));
            $options = core_course_category::make_categories_list('moodle/category:manage');
            $select = html_writer::select(
                $options,
                'movecoursesto',
                '',
                array('' => 'choosedots'),
                array('aria-labelledby' => 'moveselectedcoursesto', 'class' => 'mr-1')
            );
            $submit = array('type' => 'submit', 'name' => 'bulkmovecourses', 'value' => get_string('move'),
                'class' => 'btn btn-secondary');
            $html .= $this->detail_pair(
                html_writer::div(get_string('moveselectedcoursesto'), '', array('id' => 'moveselectedcoursesto')),
                $select . html_writer::empty_tag('input', $submit)
            );
        }
        $html .= html_writer::end_div();
        return $html;
    }
}
