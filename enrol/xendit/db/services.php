<?php

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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    l
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.

$functions = array(
        'enrol_xendit_create_invoice' => array(
                'classname'   => 'enrol_xendit_external',
                'methodname'  => 'create_invoice',
                'classpath'   => 'enrol/xendit/externallib.php',
                'description' => 'Return Xendit Invoice URL',
                'type'        => 'read',
                'ajax'          => 'true',
        ),
        'enrol_xendit_create_recurring' => array(
                'classname'   => 'enrol_xendit_external',
                'methodname'  => 'create_recurring',
                'classpath'   => 'enrol/xendit/externallib.php',
                'description' => 'Return Xendit Recurring URL',
                'type'        => 'read',
                'ajax'          => 'true',
        )
);

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'My service' => array(
                'functions' => array ('enrol_xendit_create_invoice', 'enrol_xendit_create_recurring'),
                'restrictedusers' => 0,
                'enabled'=>1,
        )
);
