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
 * All categories managers forms
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge : Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer : Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// This file reference all managers's forms.
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
require_once('./get.php');


/**
 * class upload_manager_form
 * upload forms with a csv file
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class upload_manager_form extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $mform->addelement('header', 'uploadheader', get_string('uploadeditmanagerhead', 'tool_managecategories')); // Add title.
        $mform->addelement('filepicker', 'coursefile', get_string('file')); // Filepicker to upload the csv file.
        $mform->addrule('coursefile', null, 'required'); // The file is required.
        $this->add_action_buttons(false, get_string('uploadmanager', 'tool_managecategories')); // Confirm the upload of categories.
    }
}


/**
 * class upload_manager_form_sucess
 * the forms that is print when the upload is a success
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// The forms that is print when the upload is a success.
class upload_manager_form_sucess extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $mform->addelement('header', 'uploadheader', get_string('reportchanges', 'tool_managecategories'));
        if (filesize("internal_file/report.txt") > 1) {
            // The lenght of the report file is > 1 if the user upload a file.
            // Print the content of the notification file.
            $fp     = fopen('internal_file/report.txt', 'r');
            $return = fread($fp, filesize("internal_file/report.txt"));
            fclose($fp);
            file_put_contents('internal_file/report.txt', '');
            $mform->addelement('html', $return); // Print the report form a file through html.
        }
        $mform->addelement('submit', 'downloadbutton', get_string('downloadreporting', 'tool_managecategories'));
        // Button to validate the file.
        $this->add_action_buttons(false, get_string('uploadreturntomainpage', 'tool_managecategories'));
    }
}


/**
 * class export_manager_form
 * export form
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class export_manager_form extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $mform->addelement('header', 'exportheader', get_string('exportmanagerhead', 'tool_managecategories')); // Add title.
        $this->add_action_buttons(false, get_string('exportmanagercsv', 'tool_managecategories')); // Button to export the file.
    }
}


/**
 * class delete_manager_form
 * delete form
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_manager_form extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $stringreallyconfirmmanagers = get_string('reallyconfirmmanagers', 'tool_managecategories');
        $stringwritedelete           = get_string('writedelete', 'tool_managecategories');
        $stringdelete                = get_string('deletemanagerbutton', 'tool_managecategories');
        $mform                       = $this->_form;
        $mform->addelement('header', 'deleteheader', get_string('deletemanagerhead', 'tool_managecategories'));
        $mform->addelement('filepicker', 'coursefile', get_string('file'));
        $mform->addrule('coursefile', null, 'required');
        $mform->addelement('text', 'deletetext', $stringwritedelete, array(
            'autocomplete' => 50
        )); // Input text.
        $mform->settype('deletetext', PARAM_TEXT);
        $attributes = array(
            'onclick' => "return confirm(\"$stringreallyconfirmmanagers\")"
        ); // Add javascript dialog box.
        $mform->addelement('submit', 'deletebutton', $stringdelete, $attributes);
    }
}


/**
 * class delete_manager_form_success
 * notification of the delete if the admin put 'delete'
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_manager_form_success extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $mform->addelement('header', 'deleteheader', get_string('reportchanges', 'tool_managecategories')); // Add title.
        if (filesize("internal_file/report.txt") > 1) { // The report file is > 1 if the user upload a file.
            // Print the content of the notification file.
            $fp     = fopen('internal_file/report.txt', 'r');
            $return = fread($fp, filesize("internal_file/report.txt"));
            fclose($fp);
            file_put_contents('internal_file/report.txt', '');
            $mform->addelement('html', $return); // Print the report form a file through html.
        }
        $mform->addelement('submit', 'downloadbutton', get_string('downloadreporting', 'tool_managecategories'));
        $this->add_action_buttons(false, get_string('uploadreturntomainpage', 'tool_managecategories')); // Button to validate the file.
    }
}


/**
 * class delete_manager_form_no_success
 * notification of the delete if the admin put another text
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class delete_manager_form_no_success extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;
        $mform->addelement('header', 'deleteheader', get_string('reportchanges', 'tool_managecategories'));
        $mform->addelement('static', 'result', get_string('incorrecttext', 'tool_managecategories'));
        $this->add_action_buttons(false, get_string('uploadmanagerreturntomainpage', 'tool_managecategories'));
    }
}