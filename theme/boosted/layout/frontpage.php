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
 * Frontpage layout for boosted theme.
 *
 * @package    theme_boosted
 * @copyright  2022-2023 koditik.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot . '/course/lib.php');

// Add block button in editing mode.
$addblockbutton = $OUTPUT->addblockbutton();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
user_preference_allow_ajax_update('drawer-open-index', PARAM_BOOL);
user_preference_allow_ajax_update('drawer-open-block', PARAM_BOOL);

if (isloggedin()) {
    $courseindexopen = (get_user_preferences('drawer-open-index', true) == true);
    $blockdraweropen = (get_user_preferences('drawer-open-block') == true);
} else {
    $courseindexopen = false;
    $blockdraweropen = false;
}

if (defined('BEHAT_SITE_RUNNING')) {
    $blockdraweropen = true;
}

$extraclasses = ['uses-drawers'];
if ($courseindexopen) {
    $extraclasses[] = 'drawer-open-index';
}

$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = (strpos($blockshtml, 'data-block=') !== false || !empty($addblockbutton));
if (!$hasblocks) {
    $blockdraweropen = false;
}

$courseindex = core_course_drawer();

if (!$courseindex) {
    $courseindexopen = false;
}

$forceblockdraweropen = $OUTPUT->firstview_fakeblocks();

$secondarynavigation = false;
$overflow = '';

if ($PAGE->has_secondary_navigation()) {
    $secondary = $PAGE->secondarynav;

    if ($secondary->get_children_key_list()) {
        $tablistnav = $PAGE->has_tablist_secondary_navigation();
        $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs', true, $tablistnav);
        $secondarynavigation = $moremenu->export_for_template($OUTPUT);
        $extraclasses[] = 'has-secondarynavigation';
    }

    $overflowdata = $PAGE->secondarynav->get_overflow_menu_data();
    if (!is_null($overflowdata)) {
        $overflow = $overflowdata->export_for_template($OUTPUT);
    }
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions() && !$PAGE->has_secondary_navigation();

// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$header = $PAGE->activityheader;
$headercontent = $header->export_for_template($renderer);
$bodyattributes = $OUTPUT->body_attributes($extraclasses);

// Banner.
$showbannertext = false;
$showbannerbutton = false;
$bannertext = '';
$bannerbutton = '';
$bannerbuttonlink = '';
$showbanner = false;
$showbannerbutton = false;
$showbannermobile = '';

if (!empty($PAGE->theme->settings->bannerimage)) {
    $showbanner = true;
}

if (!empty($PAGE->theme->settings->bannertext)) {
    $showbannertext = true;
}

if (!empty($PAGE->theme->settings->bannerbutton)) {
    $showbannerbutton = true;
}

$bannertext = $PAGE->theme->settings->bannertext;
$bannertextvalign = $PAGE->theme->settings->bannertextvalign;
$bannerbutton = $PAGE->theme->settings->bannerbutton;
$bannerbuttonlink = $PAGE->theme->settings->bannerbuttonlink;

// Footer.
$hasfooterblocks = false;
$hassocialicons = false;
$showfootermobile = false;

$footerblocktitle1 = '';
$footerblocktitle2 = '';
$footerblocktitle3 = '';
$footerblocktitle4 = '';

$footerblockcontent1 = '';
$footerblockcontent2 = '';
$footerblockcontent3 = '';
$footerblockcontent4 = '';

$socialicons = '';

if (!empty($PAGE->theme->settings->footerblockcontent1)) {
    $hasfooterblocks = true;
}

if (!empty($PAGE->theme->settings->showfootermobile)) {
    $showfootermobile = true;
}

if (!empty($PAGE->theme->settings->socialiconslist)) {
    $hassocialicons = true;
    $socialiconslist = $PAGE->theme->settings->socialiconslist;
    $socialicons = $OUTPUT->social_icons($socialiconslist);
}

$footerblocktitle1 = $PAGE->theme->settings->footerblocktitle1;
$footerblocktitle2 = $PAGE->theme->settings->footerblocktitle2;
$footerblocktitle3 = $PAGE->theme->settings->footerblocktitle3;
$footerblocktitle4 = $PAGE->theme->settings->footerblocktitle4;

$footerblockcontent1 = $PAGE->theme->settings->footerblockcontent1;
$footerblockcontent2 = $PAGE->theme->settings->footerblockcontent2;
$footerblockcontent3 = $PAGE->theme->settings->footerblockcontent3;
$footerblockcontent4 = $PAGE->theme->settings->footerblockcontent4;

$footernote = $PAGE->theme->settings->footnote;

// Info Blocks.
$infoblock1 = false;
$infoblock2 = false;
$infoblock3 = false;
$infoblock4 = false;
$infoblocks = 4;

$infoblocks = $PAGE->theme->settings->infoblockslayout;

$infoblockcontent1 = $PAGE->theme->settings->infoblockcontent1;
$infoblockcontent2 = $PAGE->theme->settings->infoblockcontent2;
$infoblockcontent3 = $PAGE->theme->settings->infoblockcontent3;
$infoblockcontent4 = $PAGE->theme->settings->infoblockcontent4;

if (!empty($infoblockcontent1)) {
    $infoblock1 = true;
}
if (!empty($infoblockcontent2)) {
    $infoblock2 = true;
}
if (!empty($infoblockcontent3)) {
    $infoblock3 = true;
}
if (!empty($infoblockcontent4)) {
    $infoblock4 = true;
}

$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'courseindexopen' => $courseindexopen,
    'blockdraweropen' => $blockdraweropen,
    'courseindex' => $courseindex,
    'primarymoremenu' => $primarymenu['moremenu'],
    'secondarymoremenu' => $secondarynavigation ?: false,
    'mobileprimarynav' => $primarymenu['mobileprimarynav'],
    'usermenu' => $primarymenu['user'],
    'langmenu' => $primarymenu['lang'],
    'forceblockdraweropen' => $forceblockdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'overflow' => $overflow,
    'headercontent' => $headercontent,
    'addblockbutton' => $addblockbutton,
    'bannertext' => $bannertext,
    'bannertextvalign' => $bannertextvalign,
    'bannerbutton' => $bannerbutton,
    'bannerbuttonlink' => $bannerbuttonlink,
    'showbanner' => $showbanner,
    'showbannermobile' => $showbannermobile,
    'showbannertext' => $showbannertext,
    'showbannerbutton' => $showbannerbutton,
    'infoblock1' => $infoblock1,
    'infoblock2' => $infoblock2,
    'infoblock3' => $infoblock3,
    'infoblock4' => $infoblock4,
    'infoblockcontent1' => $infoblockcontent1,
    'infoblockcontent2' => $infoblockcontent2,
    'infoblockcontent3' => $infoblockcontent3,
    'infoblockcontent4' => $infoblockcontent4,
    'showfootermobile' => $showfootermobile,
    'hasfooterblocks' => $hasfooterblocks,
    'footernote' => $footernote,
    'footerblocktitle1' => $footerblocktitle1,
    'footerblocktitle2' => $footerblocktitle2,
    'footerblocktitle3' => $footerblocktitle3,
    'footerblocktitle4' => $footerblocktitle4,
    'footerblockcontent1' => $footerblockcontent1,
    'footerblockcontent2' => $footerblockcontent2,
    'footerblockcontent3' => $footerblockcontent3,
    'footerblockcontent4' => $footerblockcontent4,
    'socialicons' => $socialicons,
];

echo $OUTPUT->render_from_template('theme_boosted/frontpage', $templatecontext);