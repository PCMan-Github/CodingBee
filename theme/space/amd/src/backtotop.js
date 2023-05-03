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
 * Theme Boost Campus - JS code back to top button
 *
 * @package    theme_space
 * @copyright  2020 - Marcin Czaja RoseaThemes
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */



define(['jquery', 'core/str'], function($, str) {
    "use strict";

    /**
     * Initialising.
     */
    function initBackToTop() {
        // Get the string backtotop from language file.
        str.get_string('backtotop', 'theme_space').then(function (string) {
            // Add a fontawesome icon after the footer as the back to top button.
            $('#page-footer').after('<i class="fas fa-arrow-circle-up d-print-none"' +
                'id="back-to-top" aria-label="' + string + '"></i>');

            // This function fades the button in when the page is scrolled down or fades it out
            // if the user is at the top of the page.
            $(window).scroll(function() {
                if ($(document).scrollTop() > 220) {
                    $('#back-to-top').fadeIn(250);
                } else {
                    $('#back-to-top').fadeOut(250);
                }
            });

            // This function scrolls the page to top with a duration of 250ms.
            $('#back-to-top').click(function(event) {
                event.preventDefault();
                $('html, body').animate({scrollTop: 0}, 250);
            });
        });
    }

    return {
        init: function() {
            initBackToTop();
        }
    };
});
