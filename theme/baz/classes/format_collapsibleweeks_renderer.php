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

require_once($CFG->dirroot . '/course/format/collapsibleweeks/renderer.php');

class theme_baz_format_collapsibleweeks_renderer extends format_collapsibleweeks_renderer {
    /**
     * Overrides format_section_renderer_base
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
        global $PAGE;

        $o = '';
        $sectionstyle = '';

        if ($course->sectionprogress) {
            $total = 0;
            $complete = 0;
            $cancomplete = isloggedin() && !isguestuser();
            $modinfo = get_fast_modinfo($course);
            $completioninfo = new completion_info($course);
            if (!empty($modinfo->sections[$section->section])) {
                foreach ($modinfo->sections[$section->section] as $cmid) {

                    $thismod = $modinfo->cms[$cmid];

                    if ($thismod->modname == 'label') {
                        // Labels are special (not interesting for students)!
                        continue;
                    }

                    if ($thismod->uservisible) {
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
            }
        }

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } else if (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }

        $o .= html_writer::start_tag('li', array('id' => 'section-' . $section->section,
            'class' => 'section main clearfix' . $sectionstyle, 'role' => 'region',
            'aria-label' => get_section_name($course, $section)));


        // Create a span that contains the section title to be used to create the keyboard section move menu.
        $o .= html_writer::tag('span', get_section_name($course, $section), array('class' => 'hidden sectionname'));
        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o .= html_writer::tag('div', $rightcontent, array('class' => 'right side'));

        if ($section->section != 0) {
        $o .= html_writer::start_tag('div', array('class' => 'rui-course-format-card content'));
        } else {
        $o .= html_writer::start_tag('div', array('class' => 'rui-course-content-intro content m-0 p-0'));
        }

        // When not on a section page, we display the section titles except the general section if null.
        $hasnamenotsecpg = (!$onsectionpage && ($section->section != 0 || !is_null($section->name)));

        // When on a section page, we only display the general section title, if title is not the default one.
        $hasnamesecpg = ($onsectionpage && ($section->section == 0 && !is_null($section->name)));

        $classes = ' accesshide';
        if ($hasnamenotsecpg || $hasnamesecpg) {
            $classes = '';
        }
        if (!$PAGE->user_is_editing()) {
            $sectionname = html_writer::tag('h4', $this->section_title_without_link($section, $course),
                array('class' => 'section-title rui-main-content-title rui-main-content-title--h4'));
            // Add collapse toggle.
            if (course_get_format($course)->is_section_current($section)) {
                if ($course->sectionprogress && $total > 0) {
                    $o .= $this->section_progressbar($total, $complete);
                }
                $o .= '<a class="sectiontoggle' .
                    '" data-toggle="collapse" data-parent=".accordion" href="#collapse-' .
                    $section->section .
                    '" aria-expanded="true" aria-controls="collapse-' .
                    $section->section .
                    '">' . $sectionname . '</a> ';
            } else if ($section->section != 0) {
                if ($course->sectionprogress && $total > 0) {
                    $o .= $this->section_progressbar($total, $complete);
                }
                $o .= '<a class="rui-sectiontoggle sectiontoggle collapsed' .
                    '" data-toggle="collapse" data-parent=".accordion" href="#collapse-' .
                    $section->section .
                    '" aria-expanded="false" aria-controls="collapse-' .
                    $section->section .
                    '">' . $sectionname .
                    '</a> ';
            } else if ($section->section == 0 && !is_null($section->name)) {
                $o .= $this->output->heading($sectionname, 3, 'sectionname' . $classes);
            }
            // End collapse toggle.

            $o .= '<div class="clearfix">';
            $o .= $this->section_availability($section) . '</div>';
            if ($section->uservisible || $section->visible) {
                // Show summary if section is available or has availability restriction information.
                // Do not show summary if section is hidden but we still display it because of course setting
                // "Hidden sections are shown in collapsed form".
                $o .= $this->section_summary($section, $course, null);
            }

        } else {
            $sectionname = $this->section_title_without_link($section, $course);

            // Add collapse toggle.
            if (course_get_format($course)->is_section_current($section)) {
                $o .= '<a class="sectiontoggle' .
                    '" data-toggle="collapse" data-parent=".accordion" href="#collapse-' .
                    $section->section .
                    '" aria-expanded="true" aria-controls="collapse-' .
                    $section->section .
                    '"></a> ';
            } else if ($section->section != 0) {
                $o .= '<a class="rui-sectiontoggle sectiontoggle collapsed' .
                    '" data-toggle="collapse" data-parent=".accordion" href="#collapse-' .
                    $section->section .
                    '" aria-expanded="false" aria-controls="collapse-' .
                    $section->section .
                    '"></a> ';
            }
            // End collapse toggle.

            $o .= '<div class="clearfix">' . $this->output->heading($sectionname, 3, 'sectionname' . $classes);
            $o .= $this->section_availability($section) . '</div>';
            if ($section->uservisible || $section->visible) {
                // Show summary if section is available or has availability restriction information.
                // Do not show summary if section is hidden but we still display it because of course setting
                // "Hidden sections are shown in collapsed form".
                $o .= $this->section_summary($section, $course, null);
            }
        }
        if (course_get_format($course)->is_section_current($section)) {
            $classes = 'collapse show mt-3';
        } else if ($section->section != 0) {
            $classes = 'collapse mt-3';
        } else {
            $classes = '';
        }
        $o .= '<div id="collapse-' .
            $section->section .
            '" class="' .
            $classes .
            '" role="tabpanel" aria-labelledby="heading' .
            $section->section .
            '">' .
            '<span class="hidden"><h4 class="section-title rui-main-content-title rui-main-content-title--h4">' . $sectionname . '</h4></span>';

        return $o;
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

        // Generate array with count of activities in this section:
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
            // No sections
            return '';
        }

        // Output section completion data
        if ($total > 0) {
            $a = new stdClass;
            $a->complete = $complete;
            $a->total = $total;

            $progress = '<span class="rui-progress--complete">' . $complete . '</span> / <span class="rui-progress--total">' . $total .' </span>' ;
        }

        $o = '';

        // Output section completion data.
        if ($total > 0) {
            $completion = new stdClass;
            $completion->complete = $complete;
            $completion->total = $total;

            $percent = 0;
            if ($complete > 0) {
                $percent = (int) (($complete / $total) * 100);

                $o .= html_writer::start_tag('div', array('class' => 'course-progressbar-wrapper mb-0 mt-3'));
                $o .= "<div class='rui-progress rui-progress-sm'>";
                $o .= "<div class='rui-progress-bar rui-progress-bar-info' role='progressbar' aria-valuenow='{$percent}' ";
                $o .= " aria-valuemin='0' aria-valuemax='100' style='width: {$percent}%;'>";
                $o .= "</div>";

                if ($complete > 10) {
                    $o .= '</div><div class="rui-progress-text rui-progress--value--'.$percent.'" style="left: calc('.$percent.'%)"><svg class="mr-1" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.75 12C4.75 7.99594 7.99594 4.75 12 4.75V4.75C16.0041 4.75 19.25 7.99594 19.25 12V12C19.25 16.0041 16.0041 19.25 12 19.25V19.25C7.99594 19.25 4.75 16.0041 4.75 12V12Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 12.75L10.1837 13.6744C10.5275 14.407 11.5536 14.4492 11.9564 13.7473L14.25 9.75"></path></svg>'. $progress .'%</div>';
                } else {
                    $o .= '</div><div class="rui-progress-text rui-progress--value--'.$percent.'" style="left: calc('.$percent.'% - 30px)"><svg class="mr-1" width="20" height="20" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.75 12C4.75 7.99594 7.99594 4.75 12 4.75V4.75C16.0041 4.75 19.25 7.99594 19.25 12V12C19.25 16.0041 16.0041 19.25 12 19.25V19.25C7.99594 19.25 4.75 16.0041 4.75 12V12Z"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 12.75L10.1837 13.6744C10.5275 14.407 11.5536 14.4492 11.9564 13.7473L14.25 9.75"></path></svg>'. $progress .'</div>';
                }

                $o .= html_writer::end_tag('div');
            }

        }

        // Output section activities summary:
        $o.= html_writer::start_tag('div', array('class' => 'rui-section-summary-activities'));
        foreach ($sectionmods as $mod) {
            $o.= html_writer::start_tag('span', array('class' => 'activity-count'));
            $o.= $mod['name'].': <strong>'.$mod['count'] . '</strong>';
            $o.= html_writer::end_tag('span');
        }
        $o.= html_writer::end_tag('div');

        return $o;
    }

}