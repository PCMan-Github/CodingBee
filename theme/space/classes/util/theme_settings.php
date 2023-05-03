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
 * Mustache helper to load a theme configuration.
 *
 * @package    theme_space
 * @copyright  Copyright © 2018 onwards, Marcin Czaja | RoseaThemes, rosea.io - Rosea Themes
 * @license    Commercial https://themeforest.net/licenses
 */

namespace theme_space\util;

use theme_config;

defined('MOODLE_INTERNAL') || die();

/**
 * Helper to load a theme configuration.
 *
 * @package    theme_space
 * @copyright Copyright © 2018 onwards, Marcin Czaja | RoseaThemes, rosea.io - Rosea Themes
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class theme_settings {

  /**
   * Get config theme footer itens
   *
   * @return array
   */
  public function sidebar_custom_block() {
      $theme = theme_config::load('space');

      $templatecontext = [];
      $sidebaritems = [
        'SidebarCustomBox', 'SidebarCustomNav', 'showmycourses', 'hiddensidebar', 'showsidebarlogo', 'removesidebar', 'removesidebarnav', 'SidebarCustomHTML', 'customrooturl'
      ];

      foreach ($sidebaritems as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
      }

      $sidebaritemshtml = [
        'SidebarCustomHeading', 'SidebarCustomText', 'SidebarCustomNavTitle', 'SidebarButtonIconOpen', 'SidebarCustomNavigationLinks', 'SidebarButtonIconClose'
      ];

      foreach ($sidebaritemshtml as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
        }
      }

      if (!empty($theme->setting_file_url('customlogosidebar', 'customlogosidebar'))) {
        $templatecontext['customlogosidebar'] = $theme->setting_file_url('customlogosidebar', 'customlogosidebar');
      }

      return $templatecontext;
  }

  public function login_block() {
      $theme = theme_config::load('space');

      $templatecontext = [];

      $loginfiles = [
        'loginbg'
      ];

      foreach ($loginfiles as $setting) {
        if (!empty($theme->setting_file_url($setting, $setting))) {
            $templatecontext[$setting] = $theme->setting_file_url($setting,$setting);
        }
      }

      $loginitems = [
        'showlbg'
      ];

      foreach ($loginitems as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
      }

      return $templatecontext;
  }


  /**
   * Get config theme team and urls
   *
   * @return array
   */
  public function team() {
      $theme = theme_config::load('space');

      $templatecontext = [];
      $teamitems = [
        'FPTeam', 'FPTeamIntroProperties', 'FPTeamFooterContent', 'teammemberno', 'teamslidesperrow', 'teamslider', 'sliderteamintervalenabled', 'sliderteaminterval', 'sliderteamloop'
      ];

      foreach ($teamitems as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
      }

      $teamitemshtml = [
        'FPTeamSubHeading', 'FPTeamHeading', 'FPTeamText'
      ];

      foreach ($teamitemshtml as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
        }
      }

      $teamcount = $theme->settings->teamcount;

      for ($i = 1, $j = 0; $i <= $teamcount; $i++, $j++) {
          $teamimage = "teamimage{$i}";
          $teamurl = "teamurl{$i}";
          $teamname = "teamname{$i}";
          $teamtext = "teamtext{$i}";
          $teamcustomhtml = "teamcustomhtml{$i}";

          if (!empty($image = $theme->setting_file_url($teamimage, $teamimage))) {
            $templatecontext['team'][$j]['image'] = $image;
          }

          // image
          $teamimage = "teamimage{$i}";
          if (!empty($image = $theme->setting_file_url($teamimage, $teamimage))) {
            $templatecontext['team'][$j]['image'] = $image;
          }

          if (!empty($theme->settings->$teamurl)) {
            $templatecontext['team'][$j]['teamurl'] = $theme->settings->$teamurl;
          }

          if (!empty($theme->settings->$teamname)) {
            $templatecontext['team'][$j]['teamname'] = format_text(($theme->settings->$teamname),FORMAT_HTML, array('noclean' => true));
          }

          if (!empty($theme->settings->$teamtext)) {
            $templatecontext['team'][$j]['teamtext'] = format_text(($theme->settings->$teamtext),FORMAT_HTML, array('noclean' => true));
          }

          if (!empty($theme->settings->$teamcustomhtml)) {
            $templatecontext['team'][$j]['teamcustomhtml'] = format_text(($theme->settings->$teamcustomhtml),FORMAT_HTML, array('noclean' => true));
          }

      }

      return $templatecontext;
  }

   /**
   * Get config theme siemaSlider and urls
   *
   * @return array
   */
   public function siemaSlider() {
       global $OUTPUT;
       $theme = theme_config::load('space');

       $templatecontext = [];

       $slideritems = [
        'sliderenabled', 'sliderfwenabled', 'sliderclickable', 'sliderintervalenabled', 'sliderinterval', 'rtlslider', 'imgslidesonly'
       ];

       foreach ($slideritems as $setting) {
         if (!empty($theme->settings->$setting)) {
             $templatecontext[$setting] = $theme->settings->$setting;
         }
       }

       $slidercount = $theme->settings->slidercount;

       for ($i = 1, $j = 0; $i <= $slidercount; $i++, $j++) {
           $sliderimage = "sliderimage{$i}";
           $slidertitle = "slidertitle{$i}";
           $slidersubtitle = "slidersubtitle{$i}";
           $slidercap = "slidercap{$i}";
           $mobileheroslideheight = "mobileheroslideheight{$i}";
           $sliderhtml = "sliderhtml{$i}";
           $sliderurl = "sliderurl{$i}";

           $templatecontext['slides'][$j]['key'] = $j;
           $templatecontext['slides'][$j]['active'] = false;

           $image = $theme->setting_file_url($sliderimage, $sliderimage);
           if (empty($image)) {
               $image = $OUTPUT->image_url('slide_default', 'theme');
           }
           $templatecontext['slides'][$j]['image'] = $image;

           if (!empty($theme->settings->$sliderurl)) {
            $templatecontext['slides'][$j]['url'] = $theme->settings->$sliderurl;
           }

           if (!empty($theme->settings->$slidertitle)) {
            $templatecontext['slides'][$j]['title'] = format_text(($theme->settings->$slidertitle),FORMAT_HTML, array('noclean' => true));
           }

           if (!empty($theme->settings->$slidersubtitle)) {
            $templatecontext['slides'][$j]['subtitle'] = format_text(($theme->settings->$slidersubtitle),FORMAT_HTML, array('noclean' => true));
           }

           if (!empty($theme->settings->$slidercap)) {
            $templatecontext['slides'][$j]['caption'] = format_text(($theme->settings->$slidercap),FORMAT_HTML, array('noclean' => true));
           }

           if (!empty($theme->settings->$mobileheroslideheight)) {
            $templatecontext['slides'][$j]['slideheight'] = $theme->settings->$mobileheroslideheight;
           }

           if (!empty($theme->settings->$sliderhtml)) {
            $templatecontext['slides'][$j]['html'] = $theme->settings->$sliderhtml;
           }

           if ($i === 1) {
               $templatecontext['slides'][$j]['active'] = true;
           }
       }

       return $templatecontext;
   }

  public function logos() {
	  $theme = theme_config::load('space');

    $templatecontext = [];
    $logositems = [
      'FPLogos', 'FPLogosFooterContent', 'FPLogosProperties', 'ShowFPLogosIntro', 'logosslidesperrow', 'logosslider'
    ];

    foreach ($logositems as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $logositemshtml = [
      'FPLogosSubHeading', 'FPLogosHeading', 'FPLogosText'
    ];

    foreach ($logositemshtml as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

		$logoscount = $theme->settings->logoscount;

		for ($i = 1, $j = 0; $i <= $logoscount; $i++, $j++) {
		  $logosurl = "logosurl{$i}";
		  $logosname = "logosname{$i}";

      // image
      $logosimage = "logosimage{$i}";
      if (!empty($image = $theme->setting_file_url($logosimage, $logosimage))) {
        $templatecontext['logos'][$j]['image'] = $image;
      }

      if (!empty($theme->settings->$logosurl)) {
        $templatecontext['logos'][$j]['logosurl'] = $theme->settings->$logosurl;
      }

      if (!empty($theme->settings->$logosname)) {
        $templatecontext['logos'][$j]['logosname'] = format_text(($theme->settings->$logosname),FORMAT_HTML, array('noclean' => true));
      }


		}

		return $templatecontext;
  }

	/**
	* Get config theme Custom Nav and urls
	*
	* @return array
	*/
	public function customnav() {
		$theme = theme_config::load('space');

    $templatecontext = [];

    $customnav = [
      'CustomNavIcon', 'ShowCustomNav'
    ];

    foreach ($customnav as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $customnavhtml = [
      'CustomNavHTML', 'ExtraCustomNavHTML'
    ];

    foreach ($customnavhtml as $setting) {
      if (!empty($theme->settings->$setting)) {
        $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

		return $templatecontext;
	}


  public function hero() {
		$theme = theme_config::load('space');

    $templatecontext = [];
    $hero = [
      'herofwenabled', 'herovideofwenabled', 'heroshadow', 'heroboxshadow', 'herovideoenabled', 'showherologo', 'heroimgenabled', 'heroimgonly', 'HeroURL', 'HeroURL2', 'HeroVideoURL', 'HeroVideoURL2'
    ];

    foreach ($hero as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $herohtml = [
      'HeroHeading', 'HeroText', 'HeroText2', 'HeroLabel', 'HeroLabel2', 'HeroVideoHeading', 'HeroVideoText', 'HeroVideoText2', 'HeroVideoLabel', 'HeroVideoLabel2', 'herovideocontent'
    ];

    foreach ($herohtml as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    $herofiles = [
      'heroimg', 'herovideomp4',  'herovideowebm', 'heroshadowimg', 'heroshadowimg'
    ];

    foreach ($herofiles as $setting) {
      if (!empty($theme->setting_file_url($setting, $setting))) {
          $templatecontext[$setting] = $theme->setting_file_url($setting,$setting);
      }
    }

    return $templatecontext;
  }


  public function blockcategories() {
    $theme = theme_config::load('space');

    $templatecontext = [];
    $blockcategories = [
      'FPHTMLCustomCategoryBlock', 'FPHTMLCustomCategoryIcon', 'FPHTMLCustomCategoryBlockHTML3'
    ];

    foreach ($blockcategories as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $blockcategorieshtml = [
      'FPHTMLCustomCategoryHeading', 'FPHTMLCustomCategoryContent', 'FPHTMLCustomCategoryBlockHTML1', 'FPHTMLCustomCategoryBlockHTML2'
    ];

    foreach ($blockcategorieshtml as $setting) {
      if (!empty($theme->settings->$setting)) {
         $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    return $templatecontext;
  }


  public function block1() {
    $theme = theme_config::load('space');

    $templatecontext = [];

    $block1 = [
      'FPHTMLBlock1', 'ShowFPBlock1Intro', 'FPHTMLBlock1IntroProperties'
    ];

    foreach ($block1 as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $block1html = [
      'FPBlock1Title', 'FPBlock1Content', 'FPBlock1FooterContent'
    ];

    foreach ($block1html as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    $fpblock1count = $theme->settings->FPHTMLBlock1Count;

    for ($i = 1, $j = 0; $i <= $fpblock1count; $i++, $j++) {
      $fpblock1icon = "FPHTMLBlock1Icon{$i}";
      $fpblock1heading = "FPHTMLBlock1Heading{$i}";
      $fpblock1itemproperties = "FPHTMLBlock1ItemProperties{$i}";
      $fpblock1itemblockproperties = "FPHTMLBlock1ItemBlockProperties{$i}";
      $fpblock1text = "FPHTMLBlock1Text{$i}";

      $FPBlock1No = $i;

      $templatecontext['block1'][$j]['FPHTMLBlock1Count'] = $FPBlock1No;

      if (!empty($theme->settings->$fpblock1icon)) {
        $templatecontext['block1'][$j]['FPHTMLBlock1Icon'] = format_text(($theme->settings->$fpblock1icon),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock1itemblockproperties)) {
        $templatecontext['block1'][$j]['FPHTMLBlock1ItemBlockProperties'] = format_text(($theme->settings->$fpblock1itemblockproperties),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock1itemproperties)) {
        $templatecontext['block1'][$j]['FPHTMLBlock1ItemProperties'] = format_text(($theme->settings->$fpblock1itemproperties),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock1heading)) {
        $templatecontext['block1'][$j]['FPHTMLBlock1Heading'] = format_text(($theme->settings->$fpblock1heading),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock1text)) {
        $templatecontext['block1'][$j]['FPHTMLBlock1Text'] = format_text(($theme->settings->$fpblock1text),FORMAT_HTML, array('noclean' => true));
      }

      // image
      $fpblock1image = "fpblock1image{$i}";
			if (!empty($image = $theme->setting_file_url($fpblock1image, $fpblock1image))) {
        $templatecontext['block1'][$j]['image'] = $image;
      }

    }

    return $templatecontext;
  }

  public function block2() {
    $theme = theme_config::load('space');

    $templatecontext = [];

    $block2 = [
      'FPHTMLBlock2', 'ShowFPBlock2Intro', 'FPBlock2FooterContent', 'FPHTMLBlock2IntroProperties'
    ];

    foreach ($block2 as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $block2html = [
      'FPBlock2Title', 'FPBlock2Content'
    ];

    foreach ($block2html as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

		$fpblock2count = $theme->settings->FPHTMLBlock2Count;

		for ($i = 1, $j = 0; $i <= $fpblock2count; $i++, $j++) {
			$FPBlock2Heading = "FPHTMLBlock2Heading{$i}";
      $FPBlock2ShowImage = "FPHTMLBlock2ShowImage{$i}";
      $FPBlock2SubHeading = "FPHTMLBlock2SubHeading{$i}";
			$fpblock2text = "FPHTMLBlock2Text{$i}";
			$FPBlock2Label = "FPHTMLBlock2Label{$i}";
      $FPBlock2URL = "FPHTMLBlock2URL{$i}";
      $FPBlock2ItemProperties = "FPHTMLBlock2ItemProperties{$i}";
      $FPBlock2ItemBlockProperties = "FPHTMLBlock2ItemBlockProperties{$i}";
      $FPBlock2No = $i;
      $templatecontext['block2'][$j]['FPHTMLBlock2Count'] = $FPBlock2No;

      if (!empty($theme->settings->$FPBlock2ShowImage)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2ShowImage'] = format_text(($theme->settings->$FPBlock2ShowImage),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2ItemBlockProperties)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2ItemBlockProperties'] = format_text(($theme->settings->$FPBlock2ItemBlockProperties),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2ItemProperties)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2ItemProperties'] = format_text(($theme->settings->$FPBlock2ItemProperties),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2Heading)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2Heading'] = format_text(($theme->settings->$FPBlock2Heading),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2SubHeading)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2SubHeading'] = format_text(($theme->settings->$FPBlock2SubHeading),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock2text)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2Text'] = format_text(($theme->settings->$fpblock2text),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2Label)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2Label'] = format_text(($theme->settings->$FPBlock2Label),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$FPBlock2URL)) {
        $templatecontext['block2'][$j]['FPHTMLBlock2URL'] = $theme->settings->$FPBlock2URL;
      }

      //image
      $fpblock2image = "fpblock2image{$i}";
			if (!empty($image = $theme->setting_file_url($fpblock2image, $fpblock2image))) {
        $templatecontext['block2'][$j]['image'] = $image;
			}


		}
    return $templatecontext;
  }

  public function block3() {
    $theme = theme_config::load('space');

    $templatecontext = [];

    $block3 = [
      'FPHTMLBlock3', 'FPHTMLBlock3Icon', 'FPHTMLBlock3URL'
    ];

    foreach ($block3 as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $block3html = [
      'FPHTMLBlock3Heading', 'FPHTMLBlock3Text', 'FPHTMLBlock3Label'
    ];

    foreach ($block3html as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    if (!empty($image = $theme->setting_file_url('fphtmlblock3bgimg', 'fphtmlblock3bgimg'))) {
      $templatecontext['fphtmlblock3bgimg'] = $image;
    }

    return $templatecontext;
  }

  public function block4() {
    $theme = theme_config::load('space');
    $templatecontext = [];

    $block4 = [
      'FPHTMLBlock4'
    ];

    foreach ($block4 as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }
    $block4html = [
      'FPHTMLBlock4Subheading', 'FPHTMLBlock4Heading', 'FPHTMLBlock4Text', 'FPHTMLBlock4Content', 'FPHTMLBlock4Properties'
    ];

    foreach ($block4html as $setting) {
      if (!empty($theme->settings->$setting)) {
         $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    return $templatecontext;
  }

/**
   * Get config theme Block #10 and urls
   *
   * @return array
   */
  public function block10() {
    $theme = theme_config::load('space');

    $templatecontext = [];

    $block10 = [
      'fpblock10', 'showfpblock10intro', 'fpblock10introproperties'
    ];

    foreach ($block10 as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
    }

    $block10html = [
      'fpblock10title', 'fpblock10content', 'block10footercontent'
    ];

    foreach ($block10html as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
        }
    }

    if (empty($templatecontext['fpblock10'])) {
      return $templatecontext;
    }

    $fpblock10count = $theme->settings->fpblock10count;

    for ($i = 1, $j = 0; $i <= $fpblock10count; $i++, $j++) {
      $fpblock10question = "fpblock10question{$i}";
      $fpblock10answer = "fpblock10answer{$i}";
      $fpblock10no = $i;

      $templatecontext['block10'][$j]['fpblock10no'] = $fpblock10no;

      if (!empty($theme->settings->$fpblock10question)) {
        $templatecontext['block10'][$j]['fpblock10question'] = format_text(($theme->settings->$fpblock10question),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock10answer)) {
        $templatecontext['block10'][$j]['fpblock10answer'] = format_text(($theme->settings->$fpblock10answer),FORMAT_HTML, array('noclean' => true));
      }
    }

    return $templatecontext;
}

    /**
   * Get config theme Block #11 and urls
   *
   * @return array
   */
  public function block11() {
    $theme = theme_config::load('space');

    $templatecontext = [];
    $block11 = [
      'fpblock11', 'showfpblock11intro', 'fpblock11slidesperrow', 'fpblock11slider'
    ];

    foreach ($block11 as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $block11html = [
      'fpblock11title', 'fpblock11content', 'block11footercontent', 'fphtmlblock11introclass'
    ];

    foreach ($block11html as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    $fpblock11count = $theme->settings->fpblock11count;

    for ($i = 1, $j = 0; $i <= $fpblock11count; $i++, $j++) {
      $fpblock11badge = "fpblock11badge{$i}";
      $fpblock11coursetitle = "fpblock11coursetitle{$i}";
      $fpblock11desc = "fpblock11desc{$i}";
      $fpblock11url = "fpblock11url{$i}";
      $showfpblock11subsection = "showfpblock11subsection{$i}";
      $fpblock11subsectioncontent = "fpblock11subsectioncontent{$i}";
      $fpblock11no = $i;

      $templatecontext['block11'][$j]['fpblock11no'] = $fpblock11no;

      if (!empty($theme->settings->$fpblock11badge)) {
        $templatecontext['block11'][$j]['fpblock11badge'] = format_text(($theme->settings->$fpblock11badge),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock11url)) {
        $templatecontext['block11'][$j]['fpblock11url'] = $theme->settings->$fpblock11url;
      }

      if (!empty($theme->settings->$fpblock11coursetitle)) {
        $templatecontext['block11'][$j]['fpblock11coursetitle'] = format_text(($theme->settings->$fpblock11coursetitle),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$fpblock11desc)) {
        $templatecontext['block11'][$j]['fpblock11desc'] = format_text(($theme->settings->$fpblock11desc),FORMAT_HTML, array('noclean' => true));
      }

      if (!empty($theme->settings->$showfpblock11subsection)) {
        $templatecontext['block11'][$j]['showfpblock11subsection'] = $theme->settings->$showfpblock11subsection;
      }

      if (!empty($theme->settings->$fpblock11subsectioncontent)) {
        $templatecontext['block11'][$j]['fpblock11subsectioncontent'] = format_text(($theme->settings->$fpblock11subsectioncontent),FORMAT_HTML, array('noclean' => true));
      }

      // image
      $fpblock11image = "fpblock11image{$i}";
      if (!empty($image = $theme->setting_file_url($fpblock11image, $fpblock11image))) {
        $templatecontext['block11'][$j]['image'] = $image;
      }

    }

    return $templatecontext;
}



/**
 * Get config theme Block #12 and urls
 *
 * @return array
 */
public function block12() {
      $theme = theme_config::load('space');

      $templatecontext = [];
      $block12 = [
        'fpblock12', 'showfpblock12intro', 'fpblock12introclass', 'fpblock12slidesperrow'
      ];

      foreach ($block12 as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
      }

      $block12html = [
        'fpblock12title', 'fpblock12content', 'block12footercontent'
      ];

      foreach ($block12html as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
        }
      }

      $fpblock12count = $theme->settings->fpblock12count;

      for ($i = 1, $j = 0; $i <= $fpblock12count; $i++, $j++) {
          $fpblock12first = "fpblock12first{$i}";
          $fpblock12second = "fpblock12second{$i}";
          $fpblock12third = "fpblock12third{$i}";
          $fpblock12html = "fpblock12html{$i}";

          if (!empty($theme->settings->$fpblock12html)) {
            $templatecontext['block12'][$j]['fpblock12html'] = $theme->settings->$fpblock12html;
          }

          if (!empty($theme->settings->$fpblock12first)) {
            $templatecontext['block12'][$j]['fpblock12first'] = format_text(($theme->settings->$fpblock12first),FORMAT_HTML, array('noclean' => true));
          }

          if (!empty($theme->settings->$fpblock12second)) {
            $templatecontext['block12'][$j]['fpblock12second'] = format_text(($theme->settings->$fpblock12second),FORMAT_HTML, array('noclean' => true));
          }

          if (!empty($theme->settings->$fpblock12third)) {
            $templatecontext['block12'][$j]['fpblock12third'] = format_text(($theme->settings->$fpblock12third),FORMAT_HTML, array('noclean' => true));
          }

          // image
          $fpblock12image = "fpblock12image{$i}";
          if (!empty($image = $theme->setting_file_url($fpblock12image, $fpblock12image))) {
            $templatecontext['block12'][$j]['image'] = $image;
          }

      }

      return $templatecontext;
}



  public function top_bar_custom_block() {
    $theme = theme_config::load('space');

    $templatecontext = [];
    $topbarcustomblock = [
      'ShowTopBarUserName'
    ];

    foreach ($topbarcustomblock as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $topbarcustomblockhtml = [
      'TopBarText', 'customtopnavhtml', 'topBarOffsetTop'
    ];

    foreach ($topbarcustomblockhtml as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
      }
    }

    if (!empty($image = $theme->setting_file_url('customlogotopbar', 'customlogotopbar'))) {
      $templatecontext['customlogotopbar'] = $image;
    }

    if (!empty($image = $theme->setting_file_url('mobiletopbarlogo', 'mobiletopbarlogo'))) {
      $templatecontext['mobiletopbarlogo'] = $image;
    }

    return $templatecontext;
  }

    public function frontpage_elements() {
      $theme = theme_config::load('space');

      $templatecontext = [];
      $headelements = [
        'displaynavdrawerfp'
      ];

      foreach ($headelements as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = $theme->settings->$setting;
        }
      }

      return $templatecontext;
  }

  public function head_elements() {
    $theme = theme_config::load('space');

    $templatecontext = [];
    $headelements = [
      'googlefonturl', 'googlefontname', 'CustomWebFont', 'CustomWebFontSH', 'CustomWebFontHTML',
      'customfontregularname', 'customfontlightname', 'customfontmediumname', 'customfontboldname', 'showauthorinfo', 'additionalheadhtml', 'additionalcustomfont'
    ];

    foreach ($headelements as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $fontshead = [
      'customfontregulareot', 'customfontregularwoff', 'customfontregularwoff2',
      'customfontregularttf', 'customfontregularsvg', 'customfontlighteot', 'customfontlightwoff',
      'customfontlightwoff2', 'customfontlightttf', 'customfontlightsvg', 'customfontmediumeot',
      'customfontmediumwoff', 'customfontmediumwoff2', 'customfontmediumttf', 'customfontmediumsvg',
      'customfontboldeot', 'customfontboldwoff', 'customfontboldwoff2', 'customfontboldttf', 'customfontboldsvg'
    ];

    foreach ($fontshead as $setting) {
      if (!empty($theme->setting_file_url($setting, $setting))) {
          $templatecontext[$setting] = $theme->setting_file_url($setting,$setting);
      }
    }

    return $templatecontext;
}


  /**
   * Get config theme footer itens
   *
   * @return array
   */
  public function footer_items() {
      $theme = theme_config::load('space');

      $templatecontext = [];

      $footersettings = [
          'showsociallist', 'facebook', 'twitter', 'googleplus', 'linkedin', 'youtube', 'instagram',
          'cwebsiteurl', 'website', 'mobile', 'mail', 'customsocialicon', 'CustomAlert', 'additionalfooterhtml', 'CustomModal', 'CustomModalContentHTML', 'showcoursecarddescheight'
      ];

      foreach ($footersettings as $setting) {
          if (!empty($theme->settings->$setting)) {
              $templatecontext[$setting] = $theme->settings->$setting;
          }
      }

      $footersettingshtml = [
        'footercustomnav', 'CustomFooterText', 'copyrightText', 'CustomAlertContent', 'CustomAlertButton', 'CustomModalContent'
      ];

      foreach ($footersettingshtml as $setting) {
        if (!empty($theme->settings->$setting)) {
            $templatecontext[$setting] = format_text(($theme->settings->$setting),FORMAT_HTML, array('noclean' => true));
        }
      }

      return $templatecontext;
  }


  public function fonts() {
    $theme = theme_config::load('space');

    $templatecontext = [];

    $fonts = [
      'morefonts'
    ];

    foreach ($fonts as $setting) {
      if (!empty($theme->settings->$setting)) {
          $templatecontext[$setting] = $theme->settings->$setting;
      }
    }

    $fontcount = $theme->settings->fontcount;

    for ($i = 1, $j = 0; $i <= $fontcount; $i++, $j++) {
      $langcode = "langcode{$i}";
      $additionalfontname = "additionalfontname{$i}";

      if (!empty($theme->settings->$langcode)) {
        $templatecontext['fonts'][$j]['langcode'] = $theme->settings->$langcode;
      }

      if (!empty($theme->settings->$additionalfontname)) {
        $templatecontext['fonts'][$j]['additionalfontname'] = $theme->settings->$additionalfontname;
      }

    }

    return $templatecontext;
  }

}
