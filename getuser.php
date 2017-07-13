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
 * References all users traitements with two classes
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/**
 * Class user
 * We use this class to clone the user through the database
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class user {
    public $id; // User id copy.
    public $username; // Username copy.
    public $firstname; // Firstname copy.
    public $lastname; // Lastname copy.
    public $email; // Email copy.
    public function __construct($id, $username, $firstname, $lastname, $email) {
        $this->id        = $id;
        $this->username  = $username;
        $this->firstname = $firstname;
        $this->lastname  = $lastname;
        $this->email     = $email;
    }
}

/**
 * Class getusertab
 * We use this class to help the correspondence of userid and user information
 * @package    tool_managecategories
 * @copyright 2016, University of La Reunion, Person in charge:Didier Sebastien <didier.sebastien@univ-reunion.fr>, Developer:Nakidine Houmadi <n.houmadi@rt-iut.re>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class getusertab {
    public $tab;
    /*
     * getusertab constructor.
     * get the data that we need from the database
     */
    public function __construct() {
        global $DB;
        $thedb    = $DB->get_recordset('user'); // Return a mysqli_native_moodle_recordset.
        $tmptab   = array(); // Temporary categories table.
        $counttab = 0;
        // Put all users in the table.
        foreach ($thedb as $record) {
            // One user in each line.
            $tmptab[$counttab] = new user($record->id, $record->username, $record->firstname, $record->lastname, $record->email);
            $counttab++;
        }
        $this->tab = $tmptab; // The traitement is done, put the data in the final table.
        $thedb->close();
    }

    /**
     * Return the userid through the username
     * @param $username
     * @return mixed
     */
    public function getidwithusername($username) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->username, $username) == 0) {
                return $tmptab[$i]->id;
            }
        }
    }

    /**
     * Return the firstname through the username
     * @param $username
     * @return mixed
     */
    public function getfirstnamewithusername($username) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->username, $username) == 0) {
                return $tmptab[$i]->firstname;
            }
        }
    }

    /**
     * Return the lastname throung the username
     * @param $username
     * @return mixed
     */
    public function getlastnamewithusername($username) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->username, $username) == 0) {
                return $tmptab[$i]->lastname;
            }
        }
    }

    /**
     * Return the username through the userid
     * @param $id
     * @return mixed
     */
    public function getusernamewithid($id) {
        $tmptab = $this->tab;
        for ($i = 0; $i < count($this->tab); $i++) {
            if (strcmp($tmptab[$i]->id, $id) == 0) {
                return $tmptab[$i]->username;
            }
        }
    }
}