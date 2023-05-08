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
 * Overriden course topics format renderer.
 *
 * @package    theme_baz
 * @copyright  2021 Marcin Czaja - Rosea Themes - rosea.io
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/format/topics/renderer.php');

class theme_baz_format_topics_renderer extends format_topics_renderer {

    /**
     * Generate the display of the header part of a section before
     * course modules are included
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param bool $onsectionpage true if being printed on a single-section page
     * @param int $sectionreturn The section to return to after an action
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn=null) {
          $o = '';
          $currenttext = '';
          $sectionstyle = '';

          if ($section->section != 0) {
              // Only in the non-general sections.
              if (!$section->visible) {
                  $sectionstyle = ' hidden';
              }
              if (course_get_format($course)->is_section_current($section)) {
                  $sectionstyle = ' current';
              }
          }

          $o .= html_writer::start_tag('li', [
              'id' => 'section-'.$section->section,
              'class' => 'section main clearfix'.$sectionstyle,
              'role' => 'region',
              'aria-labelledby' => "sectionid-{$section->id}-title",
              'data-sectionid' => $section->section,
              'data-sectionreturnid' => $sectionreturn
          ]);

          $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
          $o.= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

          $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
          $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
          $o.= html_writer::start_tag('div', array('class' => 'rui-course-content content'));

          // When not on a section page, we display the section titles except the general section if null
          $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));

          // When on a section page, we only display the general section title, if title is not the default one
          $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));

          $classes = ' accesshide';
          if ($hasnamenotsecpg || $hasnamesecpg) {
              $classes = '';
          }
          $sectionname = html_writer::tag('span', $this->section_title($section, $course));
          $o .= $this->output->heading($sectionname, 3, 'rui-course-sectionname sectionname' . $classes, "sectionid-{$section->id}-title");

          $o .= $this->section_availability($section);

          if(!empty($this->format_summary_text($section))) {
            if ($section->uservisible || $section->visible) {
                $o .= html_writer::start_tag('div', array('class' => 'rui-course-summary summary'));
                    // Show summary if section is available or has availability restriction information.
                    // Do not show summary if section is hidden but we still display it because of course setting
                    // "Hidden sections are shown in collapsed form".
                    $o .= $this->format_summary_text($section);
                  $o .= html_writer::end_tag('div');
              }
          }

          return $o;
    }

    /**
     * Returns controls in the bottom of the page to increase/decrease number of sections
     *
     * @param stdClass $course
     * @param int|null $sectionreturn
     * @return string
     */
    protected function change_number_sections($course, $sectionreturn = null) {
        $coursecontext = context_course::instance($course->id);
        if (!has_capability('moodle/course:update', $coursecontext)) {
            return '';
        }

        $format = course_get_format($course);
        $options = $format->get_format_options();
        $maxsections = $format->get_max_sections();
        $lastsection = $format->get_last_section_number();
        $supportsnumsections = array_key_exists('numsections', $options);

        if ($supportsnumsections) {
            // Current course format has 'numsections' option, which is very confusing and we suggest course format
            // developers to get rid of it (see MDL-57769 on how to do it).
            // Display "Increase section" / "Decrease section" links.

            echo html_writer::start_tag('div', array('id' => 'changenumsections', 'class' => 'mdl-right'));

            // Increase number of sections.
            if ($lastsection < $maxsections) {
                $straddsection = get_string('increasesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                          'increase' => true,
                          'sesskey' => sesskey()));
                $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
                echo html_writer::link($url, $icon.get_accesshide($straddsection), array('class' => 'increase-sections'));
            }

            if ($course->numsections > 0) {
                // Reduce number of sections sections.
                $strremovesection = get_string('reducesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                          'increase' => false,
                          'sesskey' => sesskey()));
                $icon = $this->output->pix_icon('t/switch_minus', $strremovesection);
                echo html_writer::link($url, $icon.get_accesshide($strremovesection), array('class' => 'reduce-sections'));
            }

            echo html_writer::end_tag('div');

        } else if (course_get_format($course)->uses_sections()) {
            if ($lastsection >= $maxsections) {
                // Don't allow more sections if we already hit the limit.
                return;
            }
            // Current course format does not have 'numsections' option but it has multiple sections suppport.
            // Display the "Add section" link that will insert a section in the end.
            // Note to course format developers: inserting sections in the other positions should check both
            // capabilities 'moodle/course:update' and 'moodle/course:movesections'.
            echo html_writer::start_tag('div', array('id' => 'changenumsections', 'class' => 'mdl-right'));
            if (get_string_manager()->string_exists('addsections', 'format_'.$course->format)) {
                $straddsections = get_string('addsections', 'format_'.$course->format);
            } else {
                $straddsections = get_string('addsections');
            }
            $url = new moodle_url('/course/changenumsections.php',
                ['courseid' => $course->id, 'insertsection' => 0, 'sesskey' => sesskey()]);
            if ($sectionreturn !== null) {
                $url->param('sectionreturn', $sectionreturn);
            }
            $icon = '<svg class="mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9.25 4.75H8.75C6.54086 4.75 4.75 6.54086 4.75 8.75V9.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M9.25 19.25H8.75C6.54086 19.25 4.75 17.4591 4.75 15.25V14.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M14.75 4.75H15.25C17.4591 4.75 19.25 6.54086 19.25 8.75V9.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M14.75 19.25H15.25C17.4591 19.25 19.25 17.4591 19.25 15.25V14.75" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M8.75 12H15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M12 8.75L12 15.25" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>';
            $newsections = $maxsections - $lastsection;
            echo html_writer::link($url, $icon . $straddsections,
                array('class' => 'add-sections btn btn-lg btn-success w-100 mb-4', 'data-add-sections' => $straddsections, 'data-new-sections' => $newsections));
            echo html_writer::end_tag('div');
        }
    }

    /**
     * Generate a summary of a section for display on the 'coruse index page'
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_summary($section, $course, $mods) {
        $classattr = 'section main section-summary rui-section-summary';
        $linkclasses = '';

        // If section is hidden then display grey section link.
        if (!$section->visible) {
            $classattr .= ' hidden';
            $linkclasses .= ' dimmed_text';
        } else if (course_get_format($course)->is_section_current($section)) {
            $classattr .= ' current';
        }

        $title = get_section_name($course, $section);
        $output = '';
        $output .= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
            'class' => $classattr, 'role' => 'region', 'aria-label' => $title));

        $output .= html_writer::start_tag('a', array('href' => course_get_url($course, $section->section), 'class' => 'course-card-link'));
        $output .= html_writer::start_tag('div', array('class' => 'rui-course-format-card'));
        $output .= html_writer::start_tag('div', array('class' => 'rui-course-card-content'));
        $output .= html_writer::start_tag('div', array('class' => 'content'));

        $output .= $this->output->heading($title, 4, 'section-title');

        $output .= html_writer::start_tag('div', array('class' => 'summarytext'));
        $output .= $this->format_summary_text($section);
        $output .= html_writer::end_tag('div');
        $output .= $this->section_activity_summary($section, $course, null);

        $context = context_course::instance($course->id);
        $output .= $this->section_availability_message($section,
            has_capability('moodle/course:viewhiddensections', $context));

        $output .= html_writer::end_tag('div'); // Content.
        $output .= html_writer::end_tag('div'); // rui-course-card-content
        $output .= html_writer::end_tag('div'); // rui-course-card
        $output .= html_writer::end_tag('a');
        $output .= html_writer::end_tag('li');

        return $output;
    }

    /**
     * Generate a summary of the activites in a section
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course the course record from DB
     * @param array    $mods (argument not used)
     * @return string HTML to output.
     */
    protected function section_activity_summary($section, $course, $mods) {
        $modinfo = get_fast_modinfo($course);
        if (empty($modinfo->sections[$section->section])) {
            return '';
        }

        // Generate array with count of activities in this section.
        $sectionmods = array();
        $total = 0;
        $complete = 0;
        $cancomplete = isloggedin() && !isguestuser();
        $completioninfo = new completion_info($course);
        foreach ($modinfo->sections[$section->section] as $cmid) {
            $thismod = $modinfo->cms[$cmid];

            if ($thismod->uservisible) {
                if (isset($sectionmods[$thismod->modname])) {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modplural;
                    $sectionmods[$thismod->modname]['count']++;
                } else {
                    $sectionmods[$thismod->modname]['name'] = $thismod->modfullname;
                    $sectionmods[$thismod->modname]['count'] = 1;
                }
                if ($cancomplete && $completioninfo->is_enabled($thismod) != COMPLETION_TRACKING_NONE) {
                    $total++;
                    $completiondata = $completioninfo->get_data($thismod, true);
                    if ($completiondata->completionstate == COMPLETION_COMPLETE ||
                            $completiondata->completionstate == COMPLETION_COMPLETE_PASS) {
                        $complete++;
                    }
                }
            }
        }

        if (empty($sectionmods)) {
            // No sections.
            return '';
        }

        // Output section completion data.
        $output = '';
        if ($total > 0) {
            $completion = new stdClass;
            $completion->complete = $complete;
            $completion->total = $total;

            $percent = 0;
            if ($complete > 0) {
                $percent = (int) (($complete / $total) * 100);

                $output = html_writer::start_tag('div', array('class' => 'course-progressbar-wrapper mb-0 mt-3'));
                $output .= "<div class='rui-progress rui-progress-sm'>";
                $output .= "<div class='rui-progress-bar rui-progress-bar-info' role='progressbar' aria-valuenow='{$percent}' ";
                $output .= " aria-valuemin='0' aria-valuemax='100' style='width: {$percent}%;'>";
                $output .= "</div>";

                if ($complete > 10) {
                    $output .= '</div><div class="rui-progress-text rui-progress--value--'.$percent.'" style="left: calc('.$percent.'%)"><svg class="ml-1 mr-2" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>'. $percent .'%</div>';
                } else {
                    $output .= '</div><div class="rui-progress-text rui-progress--value--'.$percent.'" style="left: calc('.$percent.'% - 62px)"><svg class="ml-1 mr-2" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>'. $percent .'%</div>';
                }

                $output .= html_writer::end_tag('div');
            }

        }

        return $output;
    }

    /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections (argument not used)
     * @param array $mods (argument not used)
     * @param array $modnames (argument not used)
     * @param array $modnamesused (argument not used)
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();

        // Can we view the section in question?
        if (!($sectioninfo = $modinfo->get_section_info($displaysection)) || !$sectioninfo->uservisible) {
            // This section doesn't exist or is not available for the user.
            // We actually already check this in course/view.php but just in case exit from this function as well.
            print_error('unknowncoursesection', 'error', course_get_url($course),
                format_string($course->fullname));
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);
        $thissection = $modinfo->get_section_info(0);
        if ($thissection->summary or !empty($modinfo->sections[0]) or $this->page->user_is_editing()) {
            echo $this->start_section_list();
            echo $this->section_header($thissection, $course, true, $displaysection);
            echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
            echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);
            echo $this->section_footer();
            echo $this->end_section_list();
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section rui-single-section--with-nav'));

        // The requested section page.
        $thissection = $modinfo->get_section_info($displaysection);

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $modinfo->get_section_info_all(), $displaysection);
        $sectiontitle = '';
        $sectiontitle .= html_writer::start_tag('div', array('class' => 'row rui-course-nav-wrapper'));
        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left col-6 mx-0 px-0'));
        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right col-6 mx-0 px-0'));
        $sectiontitle .= html_writer::end_tag('div');

        // Title attributes
        $classes = 'sectionname';
        if (!$thissection->visible) {
            $classes .= ' dimmed_text';
        }
        $sectionname = html_writer::tag('span', $this->section_title_without_link($thissection, $course));
        $sectiontitle .= $this->output->heading($sectionname, 3, $classes);


        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        echo $this->section_header($thissection, $course, true, $displaysection);

        echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
        echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
        echo $this->section_footer();
        echo $this->end_section_list();

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'p-0'));
        //$sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        //$sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));

        $sectionbottomnav .= html_writer::tag('div', $this->section_nav_selection($course, $sections, $displaysection),
            array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // Close single-section div.
        echo html_writer::end_tag('div');
    }

    /**
     * Generate next/previous section links for naviation
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param int $sectionno The section number in the course which is being displayed
     * @return array associative array with previous and next section link
     */
    protected function get_nav_links($course, $sections, $sectionno) {
        // FIXME: This is really evil and should by using the navigation API.
        $course = course_get_format($course)->get_course();
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
            or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $back = $sectionno - 1;
        while ($back > 0 and empty($links['previous'])) {
            if ($canviewhidden || $sections[$back]->uservisible) {
                $params = array();
                if (!$sections[$back]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslinktitle = get_section_name($course, $sections[$back]);
                $previouslinklabel = '<span class="text-truncate rui-course-nav-btn-label" title="' . $previouslinktitle . '">' . $previouslinktitle . '</span>';
                $larrow = '<div class="rui-course-nav-btn-icon mr-3"><svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.25 6.75L4.75 12L10.25 17.25"></path>
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.25 12H5"></path>
                </svg></div>' .  $previouslinklabel;

                $previouslink = html_writer::tag('span', $larrow, array('class' => 'larrow rui-course-nav-btn'));

                $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
            }
            $back--;
        }

        $forward = $sectionno + 1;
        $numsections = course_get_format($course)->get_last_section_number();
        while ($forward <= $numsections and empty($links['next'])) {
            if ($canviewhidden || $sections[$forward]->uservisible) {

                $params = array();
                if (!$sections[$forward]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlinktitle = get_section_name($course, $sections[$forward]);
                $nextlinklabel = '<span class="text-truncate rui-course-nav-btn-label" title="' .$nextlinktitle . '">' . $nextlinktitle . '</span>'; // Nav Link Title
                $rarrow = $nextlinklabel . '<div class="rui-course-nav-btn-icon ml-3"><svg width="18" height="18" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.75 6.75L19.25 12L13.75 17.25"></path>
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 12H4.75"></path>
                </svg></div>';

                $nextlink = html_writer::tag('span', $rarrow, array('class' => 'rarrow rui-course-nav-btn'));
                $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
            }
            $forward++;
        }

        return $links;
    }
}