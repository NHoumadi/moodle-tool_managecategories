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
 * The index of the page of categories export
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle files.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once('coursecategory_form.php'); // Moodle Forms.
require_once('./get.php');

$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolmanagecategories_export'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

if (empty($idparam)) {
    $aformexport = new export_form(); // Form instance.
    if ($formdata = $aformexport->get_data()) {
        $categorietable = new getcatetab(); // Create object that contains all categories.
        $categorietable->createcsv('export'); // Create the csv file.
        $categorietable->downloadexportcsv(); // Download the csv file.
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('exportcoursecategories', 'tool_managecategories'), 'exportcoursecategories', 'tool_managecategories');
        $aformexport->display();
        echo $OUTPUT->footer();
        die;
    }
}