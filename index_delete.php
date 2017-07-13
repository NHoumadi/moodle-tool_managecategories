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
 * The index of the page of d
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge : Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer : Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->libdir . '/adminlib.php'); // Moodle file.
require_once($CFG->dirroot . '/course/lib.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/coursecatlib.php');
require_once('./get.php');
require_once('coursecategory_form.php'); // Form.
global $DB;

$idparam = optional_param('idparam', '', PARAM_INT);
require_login(); // A login is require.
admin_externalpage_setup('toolmanagecategories_delete'); // The admin page.
require_capability('moodle/category:manage', context_system::instance()); // Manager role.

$stringcategoriesdeleted         = get_string('categoriesdeleted', 'tool_managecategories');
$stringdoesnotexist              = get_string('doesnotexist', 'tool_managecategories');
$stringline                      = get_string('line', 'tool_managecategories');
$stringnochanges                 = get_string('nochanges', 'tool_managecategories');
$stringbadsyntaxcategories       = get_string('badsyntaxcategories', 'tool_managecategories');
$stringerror                     = get_string('error', 'tool_managecategories');
$stringdeleted                   = get_string('deleted', 'tool_managecategories');
$stringnofile                    = get_string('nofile', 'tool_managecategories');
$stringleaveonecategory          = get_string('leaveonecategory', 'tool_managecategories');
$stringtaddtionalinformations    = get_string('addtionalinformations', 'tool_managecategories');
$stringrecallidnumberemptydelete = get_string('recallidnumberemptydelete', 'tool_managecategories');

if (empty($idparam)) {
    $aformdeletesuccess = new delete_form_success();
    // Check if the user have upload a file and if we need to display a report.
    if ($formreturn = $aformdeletesuccess->get_data()) { // The user has clicked in the button of download csv report changes.
        if (isset($formreturn->downloadbutton)) {
            $csvfile = 'internal_file/reportcsv.csv';
            if (file_exists($csvfile)) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="' . basename($csvfile) . '"');
                readfile($csvfile);
                die; // Stop the script.
            }
        }
    }
    if (isset($_GET['str'])) { // Print if the traitement is a success.
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_managecategories'), 'deletecoursecategories', 'tool_managecategories');
        $aformdeletesuccess->display();
        echo $OUTPUT->footer();
        die;
    }
    $aformdelete = new delete_form(); // Form instance.
    if ($formdata = $aformdelete->get_data()) {
        if (strcmp($formdata->deletetext, 'DELETE') == 0) { // If the admin write the good text "DELETE", the delete traitement can begin.
            // Contain the csv report, For futur fonctionnality (download a report changes).
            $reporttab     = array(
                array(
                    'idNumber',
                    'name'
                )
            );
            $reportdeleted = array(
                array(
                    'categories deleted',
                    ''
                )
            );
            // Report printed.
            $megastring    = '';
            $deleted       = '</br><strong>' . $stringcategoriesdeleted . '</strong></br>';
            $error         = '</br><strong>' . $stringerror . '</strong></br>';
            $addtionalinfo = '</br><strong>' . $stringtaddtionalinformations . '</strong></br>';
            $reporting     = '';
            // End of report printed.
            $filename      = 'internal_file/import.csv';
            $content       = $aformdelete->get_file_content('coursefile'); // The file that we want to import.
            // Put the content on a internal file to allow easier access on the csv.
            file_put_contents($filename, $content);
            $datatab = array(); // Content of the csv.
            $catetab = new getcatetab(); // All categories.
            $catetab->createcsv('backupdelete'); // Backup file (for future fonctionnality).
            $allid = $catetab->getallid(); // All categories ids.
            // We recuperates content.
            if (($handle = fopen($filename, "r")) !== false) {
                while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                    array_push($datatab, $data);
                }
                fclose($handle);
            }
            $counterror          = 0; // Number of error.
            $countdeleted        = 0; // Number of categories that are deleted.
            $countadditionalinfo = 0; // Number of categories that are deleted.
            if ($datatab) {
                $syntaxtest = $datatab[0];
                if ($catetab->deletesyntaxverification($syntaxtest) == 1) { // Check if the syntax is good.
                    /* Begin the process of delete */
                    for ($i = 1; $i < count($datatab); $i++) {
                        if (empty($datatab[$i][0])) { // Check if the category have not idnumber, a message will be printed else we continue the program.
                            $addtionalinfo .= ' (' . $stringline . ' ' . ($i + 1) . ') ' . $datatab[$i][1] . '</br>';
                            $countadditionalinfo++;
                        } else {
                            $id    = $catetab->getidwithidnumber($datatab[$i][0]);
                            $child = $catetab->haschild($id); // For print all sons that we delete with the traitement.
                            // Only delete the categoy.
                            if (empty($child)) {
                                // If the category have not sons, we simply delete it.
                                $currentcattab = new getcatetab(); // Current categories tables.
                                $currentallid  = $currentcattab->getallid(); // Current categories id tables.
                                if (in_array($id, $currentallid)) { // Check if the category exist to avoid system error.
                                    $deletecat = coursecat::get($id, MUST_EXIST);
                                    // Make sure that we can delete and the current category is not the last category.
                                    if (($deletecat->can_delete_full()) && ($catetab->counttable() > 1)) { // Check if we can delete the categories and sons, context(...).
                                        $deletecat->delete_full(false); // Delete the categories, sons, context(...).
                                        $deleted .= $catetab->getidnumberwithid($id) . ' ; ' . $catetab->getnamewithid($id) . '<br/>'; // Report.
                                        $countdeleted++; // report.
                                        array_push($reportdeleted, array(
                                            $catetab->getidnumberwithid($id),
                                            $catetab->getnamewithid($id)
                                        )); // Csv report.
                                    } else if ($catetab->counttable() <= 1) {
                                        $error .= ' (' . $stringline . ' ' . ($i + 1) . ') ' . $datatab[$i][0] . '-' . $datatab[$i][1] . ' ' . $stringleaveonecategory;
                                        $counterror++;
                                        break;
                                    }
                                } else if ((in_array($id, $allid) == false) && (in_array($id, $currentallid) == false)) { // Notify the category does not exist.
                                    $error .= $datatab[$i][0] . ' ' . $stringdoesnotexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                                    $counterror++;
                                } // End of delete only the category.
                            } else {
                                // Delete the category and his sons.
                                $currentcattab = new getcatetab();
                                $currentallid  = $currentcattab->getallid();
                                if (in_array($id, $currentallid)) {
                                    $deletecat = coursecat::get($id, MUST_EXIST);
                                    if (($deletecat->can_delete_full()) && ($catetab->counttable() > 1)) {
                                        $deletecat->delete_full(false); // Delete the category and his sons.
                                        $deleted .= $catetab->getidnumberwithid($id) . ' ; ' . $catetab->getnamewithid($id) . '<br/>'; // Report.
                                        $countdeleted++; // Report.
                                        array_push($reportdeleted, array(
                                            $catetab->getidnumberwithid($id),
                                            $catetab->getnamewithid($id)
                                        )); // Csv report.
                                        // Report the delete of all sons.
                                        for ($j = 0; $j < count($child); $j++) {
                                            if (in_array($child[$j], $currentallid)) { // Check if the child currently exist.
                                                $deleted .= $catetab->getidnumberwithid($child[$j]) . ' ; ' . $catetab->getnamewithid($child[$j]) . '<br/>'; // Report.
                                                $countdeleted++; // Report.
                                                array_push($reportdeleted, array(
                                                    $catetab->getidnumberwithid($child[$j]),
                                                    $catetab->getnamewithid($child[$j])
                                                )); // Csv report.
                                            }
                                        }
                                    } else if ($catetab->counttable() <= 1) {
                                        $error .= ' (' . $stringline . ' ' . ($i + 1) . ') ' . $datatab[$i][0] . '-' . $datatab[$i][1] . ' ' . $stringleaveonecategory;
                                        break;
                                    }
                                    // End of delete the category and his sons.
                                } else if ((in_array($id, $allid) == false) && (in_array($id, $currentallid) == false)) { // Category not exist.
                                    $error .= $datatab[$i][0] . ' ' . $stringdoesnotexist . ' (' . $stringline . ' ' . ($i + 1) . ')</br>';
                                    $counterror++;
                                }
                            }
                        }
                    }
                    // Reporting.
                    $reporting .= $stringdeleted . ' ' . $countdeleted . '</br>'; // Reporting numbers.
                    $reporting .= $stringerror . ' ' . $counterror . '</br>';
                    $megastring .= $reporting;
                    // Report changes.
                    if ($counterror > 0) {
                        $error .= '</br>'; // To seperate error reporting and traitement.
                        $megastring .= $error;
                    }
                    if (($countdeleted) > 0) {
                        $megastring .= $deleted;
                        foreach ($reportdeleted as $line) {
                            array_push($reporttab, $line);
                        }
                    } // Avoid unnecessery space.
                    if ($countadditionalinfo > 0) {
                        $addtionalinfo .= $stringrecallidnumberemptydelete . '</br>';
                        $megastring .= $addtionalinfo;
                    }
                    if (($countdeleted + $counterror) == 0) {
                        $megastring .= $stringnochanges; // Notifie the admin that there are not changes.
                    }
                } else { // We notifie the admin that the syntax is bad.
                    $megastring .= $stringbadsyntaxcategories;
                }
            } else {
                $megastring .= $stringnofile;
            }
            // Erase import file content.
            file_put_contents('internal_file/import.csv', '');
            // Report changes printed.
            file_put_contents('internal_file/report.txt', $megastring);
            // Report changes that you can download.
            $catetab->createreportcsv($reporttab); // For futur fonctionnality.
            header('location: index_delete.php?str=yes');
        } else { // We did not delete and display to the user that the text is wrong.
            $aformdeletenosuccess = new delete_form_no_success();
            echo $OUTPUT->header();
            echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_managecategories'), 'deletecoursecategories', 'tool_managecategories');
            $aformdeletenosuccess->display();
            echo $OUTPUT->footer();
        }
    } else {
        echo $OUTPUT->header();
        echo $OUTPUT->heading_with_help(get_string('deletecoursecategories', 'tool_managecategories'), 'deletecoursecategories', 'tool_managecategories');
        $aformdelete->display();
        echo $OUTPUT->footer();
        die;
    }
}