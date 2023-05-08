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
 * Newblock block caps.
 *
 * @package    block_cloudswyft
 * @copyright  Andrian <andrian@pcman.co.id>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_cloudswyft extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_cloudswyft');
    }

    function get_content() {
        global $USER;

        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';

        $user = [
            'firstname' => $USER->firstname,
            'lastname' => $USER->lastname,
            'email' => $USER->email,
        ];

        $user = json_encode($user);

        $value = "value='".$user."'";

        $this->content->text = '<form action="https://codingbeesandbox.southeastasia.cloudapp.azure.com:8601/" method="post" target="_blank">
            <input type="hidden" name="payload" '.$value.'>
            <input type="submit" value="Scratch" class="btn btn-block btn-primary">
          </form>';

        $this->content->text .= '<form action="https://codingbeesandbox.southeastasia.cloudapp.azure.com/" method="post" target="_blank">
            <input type="hidden" name="payload" '.$value.'>
            <input type="submit" value="App Inventor" class="btn btn-block btn-info">
          </form>';

        return $this->content;
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats() {
        return array('my' => true);
    }

    public function instance_allow_multiple() {
          return true;
    }

    function has_config() {return true;}
}
