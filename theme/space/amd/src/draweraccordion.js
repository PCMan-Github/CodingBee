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
 * Contain the logic for a draweraccordion.
 *
 * @package    theme_space
 * @copyright  2016 Damyon Wiese
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/custom_interaction_events', 'core/log', 'core/pubsub'],
     function($, CustomEvents, Log, PubSub) {

    var SELECTORS = {
        TOGGLE_REGION: '[data-region="draweraccordion-toggle"]',
        TOGGLE_ACTION: '[data-action="toggle-draweraccordion"]',
        TOGGLE_TARGET: 'aria-controls',
        TOGGLE_SIDE: 'left',
        BODY: 'body',
        SECTION: '.list-group-item[href*="#section-"]',
        DRAWER: '#nav-draweraccordion'
    };

    var small = $(document).width() < 768;

    /**
     * Constructor for the DrawerAccordion.
     *
     * @param {object} root The root jQuery element for the modal
     */
    var DrawerAccordion = function() {

        if (!$(SELECTORS.TOGGLE_REGION).length) {
            Log.debug('Page is missing a draweraccordion region');
        }
        if (!$(SELECTORS.TOGGLE_ACTION).length) {
            Log.debug('Page is missing a draweraccordion toggle link');
        }
        $(SELECTORS.TOGGLE_REGION).each(function(index, ele) {
            var trigger = $(ele).find(SELECTORS.TOGGLE_ACTION);
            var draweraccordionid = trigger.attr('aria-controls');
            var draweraccordion = $(document.getElementById(draweraccordionid));
            var hidden = trigger.attr('aria-expanded') == 'false';
            var side = trigger.attr('data-side');
            var body = $(SELECTORS.BODY);
            var preference = trigger.attr('data-preference');
            if (small) {
                M.util.set_user_preference(preference, 'false');
            }

            if (!hidden) {
                body.addClass('draweraccordion-open-' + side);
                trigger.attr('aria-expanded', 'true');
            } else {
                trigger.attr('aria-expanded', 'false');
            }
        }.bind(this));

        this.registerEventListeners();
        if (small) {
            this.closeAll();
        }
    };

    DrawerAccordion.prototype.closeAll = function() {
        $(SELECTORS.TOGGLE_REGION).each(function(index, ele) {
            var trigger = $(ele).find(SELECTORS.TOGGLE_ACTION);
            var side = trigger.attr('data-side');
            var body = $(SELECTORS.BODY);
            var draweraccordionid = trigger.attr('aria-controls');
            var draweraccordion = $(document.getElementById(draweraccordionid));
            var preference = trigger.attr('data-preference');

            trigger.attr('aria-expanded', 'false');
            body.removeClass('draweraccordion-open-' + side);
            draweraccordion.attr('aria-hidden', 'true');
            draweraccordion.addClass('closed');
            if (!small) {
                M.util.set_user_preference(preference, 'false');
            }
        });
    };

    /**
     * Open / close the blocks draweraccordion.
     *
     * @method toggleDrawerAccordion
     * @param {Event} e
     */
    DrawerAccordion.prototype.toggleDrawerAccordion = function(e) {
        var trigger = $(e.target).closest('[data-action=toggle-draweraccordion]');
        var draweraccordionid = trigger.attr('aria-controls');
        var draweraccordion = $(document.getElementById(draweraccordionid));
        var body = $(SELECTORS.BODY);
        var side = trigger.attr('data-side');
        var preference = trigger.attr('data-preference');
        if (small) {
            M.util.set_user_preference(preference, 'false');
        }

        body.addClass('draweraccordion-ease');
        var open = trigger.attr('aria-expanded') == 'true';
        if (!open) {
            // Open.
            trigger.attr('aria-expanded', 'true');
            draweraccordion.attr('aria-hidden', 'false');
            draweraccordion.focus();
            body.addClass('draweraccordion-open-' + side);
            draweraccordion.removeClass('closed');
            if (!small) {
                M.util.set_user_preference(preference, 'true');
            }
        } else {
            // Close.
            body.removeClass('draweraccordion-open-' + side);
            trigger.attr('aria-expanded', 'false');
            draweraccordion.addClass('closed').delay(500).queue(function() {
                $(this).attr('aria-hidden', 'true').dequeue();
            });
            if (!small) {
                M.util.set_user_preference(preference, 'false');
            }
        }

        // Publish an event to tell everything that the draweraccordion has been toggled.
        // The draweraccordion transitions closed so another event will fire once teh transition
        // has completed.
        PubSub.publish('nav-draweraccordion-toggle-start', open);
    };

    /**
     * Prevent the page from scrolling when the draweraccordion is at max scroll.
     *
     * @method preventPageScroll
     * @param  {Event} e
     */
    DrawerAccordion.prototype.preventPageScroll = function(e) {
        var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.originalEvent.detail,
            bottomOverflow = (this.scrollTop + $(this).outerHeight() - this.scrollHeight) >= 0,
            topOverflow = this.scrollTop <= 0;

        if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {
            e.preventDefault();
        }
    };

    /**
     * Set up all of the event handling for the modal.
     *
     * @method registerEventListeners
     */
    DrawerAccordion.prototype.registerEventListeners = function() {

        $(SELECTORS.TOGGLE_ACTION).each(function(index, element) {
            CustomEvents.define($(element), [CustomEvents.events.activate]);
            $(element).on(CustomEvents.events.activate, function(e, data) {
                this.toggleDrawerAccordion(data.originalEvent);
                data.originalEvent.preventDefault();
            }.bind(this));
        }.bind(this));

        $(SELECTORS.SECTION).click(function() {
            if (small) {
                this.closeAll();
            }
        }.bind(this));

        // Publish an event to tell everything that the draweraccordion completed the transition
        // to either an open or closed state.
        $(SELECTORS.DRAWER).on('webkitTransitionEnd msTransitionEnd transitionend', function(e) {
            var draweraccordion = $(e.target).closest(SELECTORS.DRAWER);
            var open = draweraccordion.attr('aria-hidden') == 'false';
            PubSub.publish('nav-draweraccordion-toggle-end', open);
        });
    };

    return {
        'init': function() {
            return new DrawerAccordion();
        }
    };
});
