define ("theme_baz/backtotop",["jquery","core/str"],function(a,b){"use strict";function c(){b.get_string("backtotop","theme_baz").then(function(b){a("#page-footer").after("<i class=\"fa fa-chevron-up d-print-none\"id=\"back-to-top\" aria-label=\""+b+"\"></i>");a(window).scroll(function(){if(220<a(document).scrollTop()){a("#back-to-top").fadeIn(250)}else{a("#back-to-top").fadeOut(250)}});a("#back-to-top").click(function(b){b.preventDefault();a("html, body").animate({scrollTop:0},250)})})}return{init:function init(){c()}}});
