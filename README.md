agentiq
=======

Free and Open Source
Installs on your website - runs with PHP
Intelligent resource for responsive website design
Reports all Modernizr properties and more
Learns new devices
No third party resources required

INSTRUCTIONS

Installation

Download and extract the latest release to the root of your website.
Delete the included index.php and head.php if you do not require it - they are just for demo.
Create a Postgresql database and insert the included SQL file.
Modify agentiq/settings.php to suite your database and preferences.
Put <?php include ($_SERVER['DOCUMENT_ROOT']).'/agentiq/agentiq.php'; ?> at the very top of each page(no whitespace or comments above).
Put <?php include ($_SERVER['DOCUMENT_ROOT']).'/agentiq/results.php'; ?> in the head section of the document, below your meta charset declaration.
If your pages are .html and not .php, you will have to add these lines into your .htaccess file:
AddType application/x-httpd-php .htm
AddType application/x-httpd-php .html
Put <?php include ($_SERVER['DOCUMENT_ROOT']).'/agentiq/printdb.php'; ?> wherever you want to print a database table of user agents (optional)

Usage

Agent IQ returns all the properties as per the table on the home page of this site.

You can access these properties in PHP and Javascript in the following ways:

PHP:

    $agentIq['propertyname']

JAVASCRIPT:

    agentIq['propertyname']
    or
    agentIq.propertyname

