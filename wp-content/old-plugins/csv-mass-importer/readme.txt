=== CSV Mass Importer ===
Contributors: aleapp
Donate link: https://www.paypal.me/OBoyda
Tags: csv mass importer, csv, excel, update, import, export, multilingual, wpml compatible
Requires at least: 3.2
Tested up to: 4.5.2
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

CSV Mass Importer is a perfect solution for importing and exporting post data through CSV file.

== Description ==

CSV Mass Importer is a perfect solution for those looking to import and export post data in a massive manner through CSV file. It supports built-in wordpress types and custom post types.

CSV Mass Importer can import attachments and is compatible with WPML.

= EXPORTING DATA =

Before you hit the Export button you may want to adjust some settings in the Export section.
Here are the options you have:

--- Post type

You are free to choose between defaut wordpress post types or any custom post type registered.

--- Load fields

Use "Load fields" button to discover fields associated with the post type you selected previously, 
it includes entity fields, metas and taxonomies. If you don't invoke "Load fields" action or don´t check any of the loaded 
fields CSV Mass Importer will assume you are trying to export all the data associated with the post type.

--- Dates

This will allow you to export posts within a specific time period. Leave empty to export all dates.

--- Limit

Specify the number of posts you want to export or leave empty to export them all.

--- Destination

Here you select what to do with the exported file, download it or leave it on the server at /wp-content/cmi-data/data.csv.

Note that CSV Mass Importer does not export post's media attachments, but it does import them, see below.

= IMPORTING AND UPDATING DATA =

When importing data be sure to have a well structured data file. Its always a good idea to export a number of posts that will serve you as a template. These are the key points to keep in mind:

--- CSV file header (field names)

As usual, the first row in the CSV file represents field names.

There are three data types: entity data, meta data and taxonomy data. 

To distinguish meta data and taxonomy data from the entity data append :meta or :taxonomy to the field name respectively. 
This plugin version does not include fields mapping facility, therefore be sure to provide correct field system names in your CSV header.

--- Required fields and default values when importing new posts

[ID] (required). In case we are acreating new posts the ID field must contain "new" keyword for each post entry. 
If ID is an integer, CMI assumes its an update.

[post_type] (optional). If ommited or empty CMI will assume you are importing "post" type.

[post_content] (optional). If ommited or empty "&nbsp;" will be inserted as a dummy value.

--- Required fields when updating posts

[ID] (required). ID is the only required field when updating posts. This field expects an integer. Of course it makes no sense 
to have it alone, add whatever entity, meta or taxonomy field you want to update to your CSV file.

--- Values

Values may be simple or multiple. Multiple values always refer to metas or taxonomies and will expand across multiple rows until 
the next data row begins. 

--- Attachments

When importing posts with attachments you have to structure the import package differently.

In order to import posts with attachments place media files in /images folder. Then, add "attachments" column in the CSV file and 
fill it with media file names (no paths, just file names) for each post entry respectively. You can enter multiple values, one per cell.

Assigning thumbnail is simple. To do this, add a "thumbnail" column to your CSV file and fill it with file name for 
each post entry respectively. The attachment file must be one of the files you entered in the "attachments" column.

Requirements to the import package when importing data with attachments.

When importing posts with attachments it is important how you name the CSV file and where you place media files. 
The requirement is to name the CSV file as data.csv and place the attachment files in /images folder:

/data.csv
/images/myimage1.png
/images/myimage2.png
/images/myimage3.png
/images/myimage4.png
/images/myimage5.png

For direct upload method, compress data package as a zip archive.
If you prefer to upload files by FTP, place them in /wp-content/uploads/cmi-data folder of your wordpress installation.

--- Multilingual content with WPML

CSV Mass Importer can import post translations at the same time you import original post data in original language. 
To accomplish this create "lang" column and set language code for each post entry as a two-digit code (e.g. en, fr, de, es ...). 
Its important that translated posts go after the original post.	

--- Post taxonomies

CSV Mass Importer does not create taxonomy terms. Make sure taxonomy terms exist prior to the data import.

--- Deleting metas

Meta values and taxonomies will be deleted if value cell is empty. To prevent this "Safe mode" is enbaled by default.

== Troubleshooting ==

- Export button freezes. This problem is normally due to server side errors such as PHP memory limit being too low. 
Increase memory_limit in your server and try again.

== Installation ==

Upload CSV Mass Importer plugin to your wordpress installation and activate it.

== Changelog ==

= 1.0 =
* First release

= 1.1 =
* Add WPML multilingual support

= 1.2 =
* Add CSV separator selectors
