[![Build Status](https://travis-ci.org/bardius/BardisCMS.svg?branch=master)](https://travis-ci.org/bardius/BardisCMS)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/493afa2c-bd25-4c8a-ae92-a3a596dfb042/mini.png)](https://insight.sensiolabs.com/projects/493afa2c-bd25-4c8a-ae92-a3a596dfb042)
[![Dependency Status](https://www.versioneye.com/user/projects/535c8e24fe0d073b48000126/badge.png)](https://www.versioneye.com/user/projects/535c8e24fe0d073b48000126)
[![Dependency Status](https://www.versioneye.com/user/projects/535c8e18fe0d073b4800011c/badge.png)](https://www.versioneye.com/user/projects/535c8e18fe0d073b4800011c)
[![Code Climate](https://codeclimate.com/github/bardius/BardisCMS/badges/gpa.svg)](https://codeclimate.com/github/bardius/BardisCMS)
[![Latest Stable Version](https://poser.pugx.org/bardis/cms-symfony2/v/stable.png)](https://packagist.org/packages/bardis/cms-symfony2)
[![Total Downloads](https://poser.pugx.org/bardis/cms-symfony2/downloads.png)](https://packagist.org/packages/bardis/cms-symfony2)
[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)
[![Gitter chat](https://badges.gitter.im/bardius/BardisCMS.png)](https://gitter.im/bardius/BardisCMS)
[![License](https://poser.pugx.org/bardis/cms-symfony2/license.png)](https://packagist.org/packages/bardis/cms-symfony2)

![](http://www.bardis.info/bardisCMS.png?)

Symfony2 (v2.8.11) distribution with integrated Zurb Foundation 6 (v6.2)
============================================================================

BardisCMS is a Symfony2 (v2.8.11) distribution with integrated Zurb Foundation 6 Framework.

All the major bundles are included and pre-configured (Sonata Admin, Sonata User, Sonata Media, FOSUser, KnpMenu, Guzzle) 
combined with my own bundles (Page, Settings, ContentBlocks, Blog, Comments, Tags, Categories) and overrides/extends
to UserBundle to provide a fully functional out of the box responsive CMS for websites with exceptional performance, 
usage of REST API's, filtering, user profiles and caching abilities.

A Skeleton Bundle is provided as part of the CMS so new content types/functionality that comply with 
the current architecture can easily be added.

Travis CI, Bower and Grunt with custom builds are included for better workflow on the Front End, while Foundation 6 is 
the framework of choice that has been integrated in all the templates/views, with any overrides that were required for 
the vendor bundles already in place. Cache busting is also been taken care of using Symfony2 assetic.

ESLint and SCSSLint has been set to ensure standards along with Babel to allow ES2015 code. Last but not least, Jasmine 
is in place for Unit testing. 

You can browse the Git repository, that I update with big releases every couple of months or so, and 
use freely for your projects.

You can find the requirements for Symfony2 here http://symfony.com/doc/current/reference/requirements.html
You can find the documentation for Symfony2 here http://symfony.com/doc/current/book/index.html
You can find the documentation for Zurb Foundation 6 here http://foundation.zurb.com/docs/

Requirements

* [PHP](http://www.php.net) 5.5 or later
* Installation via [Composer](http://getcomposer.org/)

SkeletonBundle is a fully structured bundle with simple functionality (similar to normal pages) so it 
can be cloned to create new bundles for new content types.


Quick Start
------------------------------------------------------

The fastest way to get everything running is (must have nodejs, ruby and sass gem installed):


	1. git clone https://github.com/bardius/BardisCMS.git
	2. cd BardisCMS
	3. create a database
	4. composer.phar install -o (set your db details when requested during install)
	5. npm install -g grunt grunt-cli bower
	6. npm run setup
	7. grunt cms_reset
	8. setup your vhost and access the URL in a browser. To login to the admin (/admin username:administrator, pass: Admin1234)


Manual Deployment / Local Installation
------------------------------------------------------

Please follow the steps below for a complete new install.

    1. You need to do a git clone of the git repo
    git clone
    2. Install composer
    http://getcomposer.org/download/    
    3. Install packagist (https://packagist.org)
    curl -s http://getcomposer.org/installer | php
    4. Setup your virtual host (see details in relevant section below).
    5. Setup a database and provide the details to the app/config/parameters.yml file (see details in relevant section below).
    Tip: Additionally in the same file you have to set the paths for sass, compass and java for each environment.
    6. Change the memory limit in your php.ini to 256M or more if required
    7. Set the intl PHP extension as enabled if not already (Symfony2 requirement)
    8. Run a composer install to get the vendor libraries files (composer update to get latest version)
    composer.phar install -o
    9. Run the CLI symphony2 commands
        * php app/console cache:clear [--env=prod]
        (to clear and warmup cache)
        * php app/console assets:install
        (to generate the bundle assets)
        * php app/console doctrine:schema:create
        (to create the database schema)
        * php app/console doctrine:fixtures:load
        (to load required/sample data to database)
        * php app/console sonata:media:sync-thumbnails sonata.media.provider.image intro
        * php app/console sonata:media:sync-thumbnails sonata.media.provider.image bgimage
        (to generate the required by sample data images)
        * php app/console assetic:dump [--env=prod]
        (to generate the assets for the front end)


### Front end Framework Setup ###

Due to the use of the Zurb Foundation Framework 6 (version 6.2) the need for the following steps is unavoidable unless 
you do not need the framework at all.

We need to install NodeJs, Node Packaged Modules, Ruby, GIT and bower dependency manager if they are not already 
installed to the system.

More information can be found below at their official web sites:

	http://git-scm.com/downloads				(GIT)
	http://nodejs.org/                          (NodeJs)
	https://npmjs.org/                          (Node Packaged Modules)
	http://www.rubyinstaller.org/               (Ruby)
	https://github.com/bower/bower				(Bower)
	http://foundation.zurb.com/sites/docs/	    (Foundation 6 - Sass based)

The command line steps are:

	1. [sudo] npm install -g grunt grunt-cli bower
	2. [sudo] npm run setup
	3. grunt dev [release] [watch]

Your project should work now and you can see your front end working, all the source files are found in the ui-src folder
along with the existing Grunt tasks.

Login to /admin/dashboard and alter your website settings and you are finally set to go.

Tip: In case you are behind a firewall and connection to git is refused, force https for all git connections with running 
this in your bash git config --global url."https://".insteadOf git://


parameters.yml File example contents
---------------------------------------------

Here is a sample setup for your parameters file

	parameters:
        database_driver:    pdo_mysql
        database_host:      localhost
        database_port:      3306
        database_name:      bardis_cms
        database_user:      root
        database_password:  ~
    
        pdo_service_dsn:    "mysql:host=%database_host%;port=%database_port%;dbname=%database_name%"
    
        mailer_transport:	smtp
        mailer_host:	    localhost
        mailer_user:	    ~
        mailer_password:	~
        mailer_encryption:	~
        mailer_port:	    ~
    
        locale:             en
        secret:             2WYgMKzMLqEVFNU245fLqEVFNvprjmRy0I4Q
    
        unix_socket:        ~ #/tmp/mysql.sock #for mac/linux environment
    
        userpass:           userpass
        adminpass:          adminpass
    
        s3_bucket_name:     s3_bucket_name
        s3_region:          s3_region
        s3_access_key:      s3_access_key
        s3_secret_key:      s3_secret_key
        s3_subfolder:       s3_subfolder

        cdn_server_path:    /uploads/media #for amazon S3 use 'https://s3-%s3_region%.amazonaws.com/%s3_bucket_name%/%s3_subfolder%/'
        media_providers_filesystem: 'sonata.media.filesystem.local' #for amazon S3 use 'sonata.media.filesystem.s3'




Virtual Host Settings
---------------------------------------------

Here is a sample setup for your virtual host configuration

	<VirtualHost *:80>

		DocumentRoot "c:/wamp/www/domainname/web"
		ServerName domain-name.prod
		ServerAlias domain-name.test
		ServerAlias domain-name.dev

        ErrorLog "logs/domain-name-error.log"
        CustomLog "logs/domain-name-access.log" common

		# Set some environment variables depending on host
		# if you do not want to do that in .htaccess
		# SetEnvIfNoCase Host domain-name\.prod domainname_env=prod
		# SetEnvIfNoCase Host domain-name\.dev domainname_env=dev
		# SetEnvIfNoCase Host domain-name\.test domainname_env=test

		<Directory c:/wamp/www/domainname/web>

			RewriteEngine On

			# Use the environment variables above to select correct
			# environment if you do not want to do that in .htaccess
			# RewriteCond %{REQUEST_FILENAME} !-f
			# RewriteCond %{ENV:domain-name_env} test
			# RewriteRule ^(.*)$ app_test.php [QSA,L]

			# RewriteCond %{REQUEST_FILENAME} !-f
			# RewriteCond %{ENV:domain-name_env} dev
			# RewriteRule ^(.*)$ app_dev.php [QSA,L]

			# RewriteCond %{REQUEST_FILENAME} !-f
			# RewriteCond %{ENV:domain-name_env} prod
			# RewriteRule ^(.*)$ app.php [QSA,L]

			Options +Indexes
			Order Allow,Deny
			Allow from all
			AllowOverride All
			# AllowOverride none

		</Directory>

	</VirtualHost>


Updating to the ci server and the live server
-------------------------------------------------------------------------

This can be done with simple steps in your SSH CLI

	git pull
	php app/console cache:clear --no-debug
	php doctrine:schema:update --force (this will drop DB tables and update their schema)
	php app/console assetic dump


For the production server the process is the same but you should use

	php app/console cache:clear --e=prod --no-debug
	php app/console assetic:dump --e=prod



Known Bugs / Issues / Extra Configuration
---------------------------------------------

If you run mac OS with mamp remember to set properly your php date.timezone settings
(http://stackoverflow.com/questions/6194003/timezone-with-symfony-2)

You should find your php.ini in /private/etc if it exists, otherwise:

	sudo cp /private/etc/php.ini.default /private/etc/php.ini

Edit /private/etc/php.ini and set date.timezone.


Skeleton Bundle Use instructions
-----------------------------------------------
The skeleton bundle is now ready to be used as base for the creation of new content bundles.

The process for this is to:

	01. Copy the SkeletonBundle folder and rename it properly (e.g. ProductsBundle)
	02. Edit the admin class file with the correct names for fields and variables.
	03. Edit the Controller files with correct namespaces and variable names
	04. Change the Dependency Injection configuration and extension to fit your bundle
	05. Edit the Entity file to fit your database needs
	06. Edit the repository file to suit your needs
	07. Change the bundles routing file to provide the required functional urls
	08. Alter the views
	09. Add the requested configuration values to the config.yml
	10. Add the bundle to the registered bundles list in AppKernel.php
	11. Clear cache
	12. Add the a service for the new bundle admin and add it to the sonata admin config
	13. Include the bundle routing file to the app routing
	14. Edit the menu entity so you can add menu items for that bundle
	15. Edit the tag entity so you can add menu items for that bundle
	16. Edit the category entity so you can add menu items for that bundle
	17. Edit the contentblocks entity so you can add menu items for that bundle
	18. Edit the AddMenuTypeFieldSubscriber to be able to create menu items for that bundle
	19. Edit the MenuBuilder to add the case for the link generation of your bundle
	20. doctrine:schema:update --force
	21. Create an Page in that bundle to display the filtered results with alias tagged

Your new bundle should now work.
(prerequisites are the PageBundle, SettingsBundle and MenuBundle)



Included Major Bundles List
------------------------------------------------------

	1. FOSUserBundle (https://github.com/FriendsOfSymfony/FOSUserBundle)
	2. SonataBlockBundle (http://sonata-project.org/bundles/block/master/doc/index.html)
	3. SonataUserBundle (http://sonata-project.org/bundles/user/master/doc/index.html)
	4. SonataMediaBundle (http://sonata-project.org/bundles/media/master/doc/index.html)
	5. SonataAdminBundle (http://sonata-project.org/bundles/admin/master/doc/index.html)
	6. SonataTimelineBundle (http://sonata-project.org/bundles/timeline/master/doc/index.html)
	7. KnpMenu (http://knpbundles.com/KnpLabs/KnpMenuBundle)
	8. Guzzle (https://github.com/misd-service-development/guzzle-bundle)
	9. StFalcon TinymceBundle (http://knpbundles.com/stfalcon/TinymceBundle)



Apache benchmark testing (30000 req, 1000 concurent)
----------------------------------------------
The test was run for the home page in a small AWS instance with Ubuntu 14 and Varnish.

    Concurrency Level:      1000
    Time taken for tests:   5.102 seconds
    Complete requests:      30000
    Failed requests:        0
    Keep-Alive requests:    30000
    Total transferred:      110880000 bytes
    HTML transferred:       97830000 bytes
    Requests per second:    5880.56 [#/sec] (mean)
    Time per request:       170.052 [ms] (mean)
    Time per request:       0.170 [ms] (mean, across all concurrent requests)
    Transfer rate:          21225.14 [Kbytes/sec] received

Connection Times (ms)

                    min  mean[+/-sd] median   max
     Connect:        0    3  17.0      0     125
     Processing:     6  156 809.7     11    4949
     Waiting:        6  156 809.7     11    4949
     Total:          6  160 824.9     11    5040

Percentage of the requests served within a certain time (ms)

    50%     11
    66%     12
    75%     12
    80%     12
    90%     13
    95%     15
    98%    4738
    99%    4892
    100%   5040 (longest request)



Useful Links and Documentation
----------------------------------------------

Symfony2 Documentation

http://symfony.com/doc/current/index.html

Doctrine2 ORM Documentation

http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/index.html

Symfony2 Cheatsheet

http://www.symfony2cheatsheet.com/

Website with listing of available Symfony2 Bundles

http://knpbundles.com/

Tutorial on how to build a Blog in Symfony2

http://tutorial.symblog.co.uk/

Links to Front end Frameworks (Zurb and Boostrap)

http://bootstrap.braincrafted.com/
http://foundation.zurb.com/

NodeJs, Node Packaged Modules, Ruby, compass, sass, foundation gems and GIT and bower dependency manager

http://git-scm.com/downloads				    (GIT)

http://nodejs.org/					            (NodeJs)

https://npmjs.org/					            (Node Packaged Modules)

http://www.rubyinstaller.org/				    (Ruby)

https://github.com/bower/bower				    (Bower)

http://sass-lang.com/install				    (Sass)

http://compass-style.org/install/			    (Compass)

http://foundation.zurb.com/sites/docs/		    (Foundation 6 - Sass based)
