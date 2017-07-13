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
 * The index of the page of categories upload
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('./get.php');
require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle file.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/coursecatlib.php');
require_once('coursecategory_form.php'); // Form.
require_once ('./get.php');

global $DB;
$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolmanagecategories_upload'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

$stringcategoriesadded           = get_string('categoriesadded', 'tool_managecategories');
$stringcategoriesmodified        = get_string('categoriesmodified', 'tool_managecategories');
$stringinvalidparent             = get_string('invalidparent', 'tool_managecategories');
$stringline                      = get_string('line', 'tool_managecategories');
$stringnochanges                 = get_string('nochanges', 'tool_managecategories');
$stringbadsyntaxcategories       = get_string('badsyntaxcategories', 'tool_managecategories');
$stringerror                     = get_string('error', 'tool_managecategories');
$stringadded                     = get_string('added', 'tool_managecategories');
$stringmodified                  = get_string('modified', 'tool_managecategories');
$stringtreatementstop            = get_string('treatementstop', 'tool_managecategories');
$stringtaddtionalinformations    = get_string('addtionalinformations', 'tool_managecategories');
$stringrecallidnumberemptycreate = get_string('recallidnumberemptycreate', 'tool_managecategories');
$stringnamerequired              = get_string('namerequired', 'tool_managecategories');

if (empty($idparam)) {
    $aformuploadsuccess = new upload_form_sucess();
    // Check if the user have upload a file and if we need to display a report.
    if ($formreturn = $aformuploadsuccess->get_data()) { // The user has clicked in the button of download csv report changes.
        if (isset($formreturn->downloadbutton)) {
            $csvfile = 'internal_file/reportcsv.csv';
            if (file_exists($csvfile)) {
                header('content-type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . basename($csvfile) . '"');
                readfile($csvfile);
                die; // Stop the script.
            }
        }
    }
    if (isset($_GET['str'])) { // Print this form is the uploading of categories is a success.
        echo $OUTPUT->header(); // Header.
        echo $OUTPUT->heading_with_help(get_string('uploadeditcoursecategories', 'tool_managecategories'), 'uploadeditcoursecategories', 'tool_managecategories');
        $aformuploadsuccess->display(); // Print it.
        echo $OUTPUT->footer(); // Footer.
        die;
    }
    // Upload.
    $aformupload = new upload_form(); // Else you begin for process of creating.
    if ($formdata = $aformupload->get_data()) {
        $datatab  = array(); // Contain the content of the csv file.
        $filename = 'internal_file/import.csv';
        $content  = $aformupload->get_file_content('coursefile'); // The file to upload categories.
        // Put the content on a internal file to allow easier access on the csv.
        file_put_contents($filename, $content);
        // Get the content.
        if (($handle = fopen($filename, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                array_push($datatab, $data);
            }
            fclose($handle);
        }
        // Contain the csv report, For futur fonctionnality (download a report changes).
        $reporttab           = array(
            array(
                'idNumber',
                'name'
            )
        );
        $reportadded         = array(
            array(
                'categories added',
                ''
            )
        );
        $reportmodified      = array(
            array(
                'categories modified',
                ''
            )
        );
        // End of download report changes.
        // Report changes printed.
        $megastring          = '';
        $added               = '</br><strong>' . $stringcategoriesadded . '</strong> </br>';
        $modified            = '</br> <strong>' . $stringcategoriesmodified . ' </strong></br>';
        $error               = '</br><strong>' . $stringerror . '</strong></br>';
        $addtionalinfo       = '</br><strong>' . $stringtaddtionalinformations . '</strong></br>';
        $reporting           = '';
        $counterror          = 0; // Number of error.
        $countadded          = 0; // Number of categories that are added.
        $countmodified       = 0; // Number of categories that are modified.
        $countadditionalinfo = 0; // Additional informations.
        // End of report change printed.
        $catetab             = new getcatetab();
        $syntaxtest          = $datatab[0];

        if ($catetab->syntaxverification($syntaxtest) == 1) { // Check if the syntax is good.
            /* Begin the process of creation */
            for ($i = 1; $i < count($datatab); $i++) {
                if (in_array($datatab[$i][0], $catetab->allidnumber) && (empty($datatab[$i][0]) == false)) { // Check if the category exist and if it have a idnumber.
                    // Updating.
                    if (empty($datatab[$i][2])) { // Check if the category the category have a parent.
                        $id                    = $catetab->getidwithidnumber($datatab[$i][0]); // We need the id to update the category.
                        $category              = new stdclass(); // We create a object that database can recognize.
                        $category->id          = $id;
                        $category->name        = $datatab[$i][1];
                        $category->parent      = 0;
                        $category->idnumber    = $datatab[$i][0];
                        $category->description = $datatab[$i][3];
                        $update                = coursecat::get($category->id, MUST_EXIST);
                        if (strcmp($update->name, $category->name) + strcmp($update->parent, $category->parent) + strcmp($update->description, $category->description) != 0) { // Check if there are an update.
                            $update->update($category); // Update the category with the new values.
                            $modified .= $category->idnumber . ' ; ' . $category->name . '</br>';
                            $countmodified++;
                            array_push($reportmodified, array(
                                $category->idnumber,
                                $category->name
                            ));
                        }
                        unset($category);
                    } else {
                        $currentcattab = new getcatetab(); // The current table.
                        $id            = $currentcattab->getidwithidnumber($datatab[$i][0]);
                        $idp           = $currentcattab->getidwithidnumber($datatab[$i][2]); // Get the parent id.
                        if (!isset($idp)) { // Check if the parent exist.
                            $error .= $datatab[$i][0] . ';' . $datatab[$i][1] . ' ' . $stringinvalidparent . ' (' . $stringline . ' ' . ($i + 1) . ')</br>' . $stringtreatementstop . '</br>';
                            $counterror++;
                            break; // Stop the traitement to avoid any structuration problem.
                        }
                        $category              = new stdclass();
                        $category->id          = $id;
                        $category->name        = $datatab[$i][1];
                        $category->parent      = $idp;
                        $category->idnumber    = $datatab[$i][0];
                        $category->description = $datatab[$i][3];
                        $update                = coursecat::get($category->id, MUST_EXIST);
                        if (strcmp($update->name, $category->name) + strcmp($update->parent, $category->parent) + strcmp($update->description, $category->description) != 0) { // Check if there are an update.
                            $update->update($category);
                            $modified .= $category->idnumber . ' ; ' . $category->name . '</br>';
                            $countmodified++;
                            array_push($reportmodified, array(
                                $category->idnumber,
                                $category->name
                            ));
                        }
                        unset($category);
                        unset($currentcattab);
                    }
                } else {
                    if (empty($datatab[$i][0])) { // Recall when a category have not idnumber.
                        $addtionalinfo .= $datatab[$i][1] . ' (' . $stringline . ' ' . ($i + 1) . ')</br>'; // Display the category without idnumber.
                        $countadditionalinfo++;
                    }

                    if (empty($datatab[$i][2])) { // No parent in the csv.
                        $category              = new stdclass();
                        $category->parent      = 0; // Place the category on the top.
                        $category->name        = $datatab[$i][1];
                        $category->idnumber    = $datatab[$i][0];
                        $category->description = $datatab[$i][3];
                        if (empty($category->name) == false) {
                            coursecat::create($category);
                            $added .= $category->idnumber . ' ; ' . $category->name . "</br>";
                            $countadded++;
                            array_push($reportadded, array(
                                $category->idnumber,
                                $category->name
                            ));
                        } else {
                            $error .= $stringnamerequired . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                            $counterror++;
                        }
                        unset($category);
                    } else {
                        $currentcattab = new getcatetab(); // The current table.
                        $idp           = $currentcattab->getidwithidnumber($datatab[$i][2]);
                        if (!isset($idp)) {
                            $error .= $datatab[$i][0] . ';' . $datatab[$i][1] . ' ' . $stringinvalidparent . ' (' . $stringline . ' ' . ($i + 1) . ')</br>' . $stringtreatementstop . '</br>';
                            $counterror++;
                            break;
                        }
                        $category              = new stdclass();
                        $category->name        = $datatab[$i][1];
                        $category->parent      = $idp;
                        $category->idnumber    = $datatab[$i][0];
                        $category->description = $datatab[$i][3];
                        if (empty($category->name) == false) {
                            coursecat::create($category);
                            $added .= $category->idnumber . ' ; ' . $category->name . "</br>";
                            $countadded++;
                            array_push($reportadded, array(
                                $category->idnumber,
                                $category->name
                            ));
                        } else {
                            $error .= $stringnamerequired . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                            $counterror++;
                        }
                        unset($category);
                        unset($currentcattab);
                    }

                }
            }
            // Reporting.
            $reporting .= $stringadded . ' ' . $countadded . '</br>'; // reporting numbers.
            $reporting .= $stringmodified . ' ' . $countmodified . '</br>';
            $reporting .= $stringerror . ' ' . $counterror . '</br>';
            $megastring .= $reporting;
            // Report changes.
            if ($counterror > 0) {
                $error .= '</br>'; // To seperate error reporting and traitement.
                $megastring .= $error;
            }
            if ($countadded > 0) {
                $megastring .= $added; // For the report changes printed.
                foreach ($reportadded as $line) { // Csv report changes.
                    array_push($reporttab, $line);
                }
            } // Avoid unnecessery space.
            if ($countmodified > 0) {
                $megastring .= $modified;
                foreach ($reportmodified as $line) {
                    array_push($reporttab, $line);
                }
            }
            if ($countadditionalinfo > 0) {
                $addtionalinfo .= $stringrecallidnumberemptycreate . '</br>';
                $megastring .= $addtionalinfo;
            }
            if (($countadded + $countmodified + $counterror) == 0) {
                $megastring .= $stringnochanges; // Notify the admin that there are not changes.
            }
        } else { // We notify the admin that the syntax is bad.
            $megastring .= $stringbadsyntaxcategories;
        }
        // Erase import file content.
        file_put_contents('internal_file/import.csv', '');
        // Report changes printed.
        file_put_contents('internal_file/report.txt', $megastring);
        // Report changes that you can download.
        $catetab->createreportcsv($reporttab); // For futur fonctionnality.
        header('location: index_upload.php?str=yes');
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('uploadeditcoursecategories', 'tool_managecategories'), 'uploadeditcoursecategories', 'tool_managecategories');
        $aformupload->display();
        echo $OUTPUT->footer();
        die;
    }
}