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
 * @package    tool
 * @subpackage managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


$string['pluginname'] = 'Manage course categories';


// Categories.
$string['uploadeditcoursecategories']      = 'Upload/Edit course categories';
$string['uploadeditcoursecategories_help'] = "
Courses categories can be added through a CSV file.\n
You have to follow this syntax : idNumber;name;parent;description\n
-idNumber : idNumber of the category\n
-name : name of the category\n
-parent : parent of the category\n
-description : description of the catagory(optional)";

$string['uploadedit']             = 'Upload/Edit';
$string['uploadcategories']       = 'Upload categories';
$string['uploadsuccess']          = 'Success : the categories was added';
$string['uploadreturntomainpage'] = 'Return to the main page';
$string['invalidparent']          = 'has an invalid parent';
$string['treatementstop']         = "You can veriry the order of the list of your category csv file. The treatment of the CSV file was stopped at this line to avoid any structuration problem.";
$string['categoriesadded']        = 'Categories added :';
$string['categoriesmodified']     = 'Categories modified :';
$string['namerequired']           = 'Category name is required';


$string['exportcoursecategories']      = 'Export course categories';
$string['exportcoursecategories_help'] = "You can backup all moodle course categories in a CSV file to use it for a moodle server";
$string['export']                      = 'Export';
$string['exportcsv']                   = 'Export CSV categories';

$string['deletecoursecategories']      = 'Delete course categories';
$string['deletecoursecategories_help'] = "
Courses categories can be deleted through a CSV file.\n
You have to follow this syntax : idNumber;name;parent;description\n
-idNumber : idNumber of the category\n
-name : name of the category\n
-parent : parent of the category\n
-description : description of the catagory(optional)\n
!!!Be carrefull, if a category has courses, all courses will be deleted!!!";

$string['delete']                  = 'Delete';
$string['deletecategories']        = 'Delete categories';
$string['categoriesdeleted']       = 'Categories deleted :';
$string['leaveonecategory']        = "
is the last category\n
You cannot delete all categories with this program because that is dangerous for the system, you have to leave one category.\n
You can delete the last category manually, Moodle will create a default category 'Miscellaneous' to avoid system error.";
$string['reallyconfirmcategories'] = '!!!Warning : if a category has courses, all courses will be deleted!!! Really want to delete?';
$string['defaultcategory']         = 'This plugin cannot delete the default category to avoid system error, you can delete this category manually';

// Categories.
$string['added']    = 'Added :';
$string['modified'] = 'Modified :';
$string['deleted']  = 'Deleted :';

// Categories Manager.
$string['uploadeditmanager']      = 'Upload/Edit categories managers';
$string['uploadeditmanager_help'] = "
Categories managers can be added through a CSV file.\n
You have to follow this syntax : idNumber;userName;role\n
-idNumber: idNumber of the category\n
-userName : userName of the user\n
-role : role of the user";

$string['uploadeditmanagerhead']         = 'Upload/Edit';
$string['uploadmanager']                 = 'Upload categories managers';
$string['uploadmanagersuccess']          = 'Success : the categories was added';
$string['uploadmanagerreturntomainpage'] = 'Return to the main page';
$string['idNumber']                      = 'the idNumber';
$string['doesnotexist']                  = "does not exist";
$string['line']                          = 'line';


$string['exportmanager']      = 'Export categories managers';
$string['exportmanager_help'] = 'You can backup all moodle categories managers in a CSV file to use it for a moodle server';
$string['exportmanagerhead']  = 'Export';
$string['exportmanagercsv']   = 'Export CSV categories managers';


$string['deletemanager']         = 'Delete categories managers';
$string['deletemanager_help']    = "
Categories managers can be deleted through a CSV file.\n
You have to follow this syntax : idNumber;userName;role\n
-idNumber: idNumber of the category\n
-userName : userName of the user\n
-role : role of the user\n";
$string['deletemanagerhead']     = 'Delete';
$string['deletemanagerbutton']   = 'Delete categories managers';
$string['reallyconfirmmanagers'] = 'Really want to delete?';


// Managers.
$string['newmanagers']           = 'New managers :';
$string['newcoursecreators']     = 'New course creators :';
$string['oldmanagers']           = 'Old managers :';
$string['oldcoursecreators']     = 'Old course creators :';
$string['managersadded']         = 'Managers added :';
$string['coursecreatorsadded']   = 'Coursecreators added :';
$string['managersdeleted']       = 'Managers deleted :';
$string['coursecreatorsdeleted'] = 'Coursecreators deleted :';



// General.
$string['writedelete']               = 'Write "DELETE" to confirm :';
$string['incorrecttext']             = 'You have entered an incorrect text';
$string['addtionalinformations']     = 'Additional informations(category without idNumber) :';
$string['recallidnumberemptycreate'] = 'For update, this plugin do not work with category without idNumber to collision avoidance because idNumber is the clean identification of a category. If a category have not idNumber, a new category will be create with the parameters defined.';
$string['recallidnumberemptydelete'] = 'For delete, this plugin do not work with category without idNumber to collision avoidance because idNumber is the clean identification of a category. If a category have not idNumber, you should delete it with the intern interface of Moodle.';
$string['exist']                     = 'exist';
$string['nochanges']                 = 'No changes were made in the database. Maybe your file is empty or the changes you want to make have already been made';
$string['nofile']                    = 'Insert a CSV file';
$string['reportchanges']             = 'Report changes';

$string['badsyntaxcategories'] = "
You have used a bad syntax.\n
You have to follow this syntax : idNumber;name;parent;description\n
-idNumber : idNumber of the category\n
-name : name of the category\n
-parent : parent of the category\n
-description : description of the catagory(optional);
";

$string['badsyntaxmanager'] = "
You have used a bad syntax.\n
You have to follow this syntax : idNumber;userName;role\n
-idNumber: idNumber of the category\n
-userName : userName of the user\n
-role : role of the user";

$string['error']             = 'Errors :';
$string['reportingcreated']  = 'A file reporting was created';
$string['downloadreporting'] = 'Download file reporting';
$string['leaveonecategory']  = "
is the last category\n
You cannot delete all categories with this program because that is dangerous for the system, you have to leave one category.\n
You can delete it manually if you want really want to delete all categories, Moodle will create a default category 'Miscellaneous' to avoid system error.";