moodle-tool_managecategories

ManageCategories is a Moodle plugin create by the University of La Reunion, the project is in charge of Didier Sebastien and develop by Nakidine Houmadi.

It is a admin/tool plugin for managing course categories.

ManageCategories allows the administration of Moodle course categories through a adapted graphical interface.
With this plugin you can manage course categories and categories managers through a  simple CSV file.

Features :
	- Accessible graphical user interface
	- Manage course categories and categories managers
	- Support CRUD (Create/Read/Upload/Delete) Fonctionnalities
	- Work with CSV files

There are a Documentation of the plugin to learn easely how you can use it.


==============================
      Installation          
==============================
You can install the plugin by download directly the zip package through the Moodle plugins directory or in our GitLab created specialy for the plugin.
You need to install the plugin through the plugin installation page of your Moodle site with the zip package.


==============================
      Manage course         
==============================

Courses categories can be added/modified/deleted through a CSV file.
You have to follow this syntax : idNumber;name;parent;description
-idNumber : idNumber of the category
-name : name of the category
-parent : parent of the category
-description : description of the catagory(optional);


CSV example(Create with a text editor or office software)
==============================


idNumber;name;parent;description
STS;Sciences Technologies et Santé;;
M1STS;M1 – Master 1ère année STS;STS;
M1INFO;M1 INFORMATIQUE;M1STS;
M1BEST;M1 BEST;M1STS;


Upload categories
==============================


## Home
Site Administration -> Course -> ManageCategories -> Categories -> Upload/Edit course categories

This page is the home of the upload/edit course categories, you have a help button.

The help button explain you how you can add course categories and the syntax that you have to use on the CSV file to add correctly courses categories.

## Report

After downloading the CSV file, the system displays a report of the action that the program has performed.The report display any errors and warnings  encountered in the program. You can download a report file that references all the changes the program has made.

## Report errors
If there are errors in the csv file the system will display it in the report.


We use the same graphic interface for the rest of the system.

## Example of reporting file

idNumber;name
"Categories added";
SHS;"Sciences Humaines et Sociales"
SANTE;Sante
AB4BS1;"M1 BIOLOGIE SANTE"
AB5BS1;"M2 BIOLOGIE SANTE"
UFRDRETECO;"UFR Droit Et Economie"


Export categories
==============================

## Home
Site Administration ->  Course -> ManageCategories ->  Categories -> Export course categories

To export a file with all course categories, you have to click simply in a button and the system will download it in your default “downloads” folder.


## Example of an export csv file (You can open it with a text editor or office software)

idNumber;name;parent;description
STS;"Sciences Technologies et Santé";;
M1STS;"M1 – Master 1ère année STS";STS;
M1INFO;"M1 INFORMATIQUE";M1STS;

Delete categories
==============================

## Home
Site Administration -> Course -> ManageCategories -> Categories -> Delete course categories

Same syntax to delete but for the confirm of the delete there are a input text for write the text “DELETE” and a dialog box. If you write a bad text, the program stop the treatment.

You have the same report and error check.




==============================
      Manage managers       
==============================

Categories managers can be added/modified/deleted through a CSV file.
You have to follow this syntax : idNumber;username;role
-idNumber: idNumber of the category
-username : username of the user
-role : role of the user (manager or coursecretor)


##For the manage of managers we use the same graphics interface. We also use the same reportchanges  and errors check.


CSV Example
==============================


idNumber;userName;role
M1INFO;tournoux;coursecreator
M1INFO;aneli;manager
UFRDRETECO;fred;manager


Upload managers
==============================

Site Administration ->  Course -> ManageCategories ->  Managers -> Upload/Edit categories managers

Export managers
==============================

Site Administration ->  Course -> ManageCategories ->  Managers -> Export categories managers


Delete managers
==============================

Site Administration ->  Course -> ManageCategories ->  Managers -> Delete categories managers

