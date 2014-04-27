[![Build Status](https://travis-ci.org/bardius/BardisCMS.svg?branch=master)](https://travis-ci.org/bardius/BardisCMS)
[![Dependency Status](https://www.versioneye.com/user/projects/535c8e24fe0d073b48000126/badge.png)](https://www.versioneye.com/user/projects/535c8e24fe0d073b48000126)
[![Dependency Status](https://www.versioneye.com/user/projects/535c8e18fe0d073b4800011c/badge.png)](https://www.versioneye.com/user/projects/535c8e18fe0d073b4800011c)
[![Built with Grunt](https://cdn.gruntjs.com/builtwith.png)](http://gruntjs.com/)
[![Coverage Status](https://coveralls.io/repos/bardius/BardisCMS/badge.png)](https://coveralls.io/r/bardius/BardisCMS)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/)](https://scrutinizer-ci.com/)
[![Packagist Stable Version]
[![License]

![](http://www.bardis.info/bardisCMS.png)

Symfony2 (v.2.4.3) distribution with integrated Zurb Foundation 5 
============================================================================

BardisCMS is a Symfony2 (v.2.4.3) distribution with integrated Zurb Foundation 5 Framework.

Travis CI, Bower and Grunt with custom builds are included for better workflow on front end.

All the major bundles are pre-configured (Sonata Admin, Sonata User, Sonata Media, FOSUser, KnpMenu, Guzzle) combined with my own bundles (Page, Settings, ContentBlocks, Blog, Comments, Tags, Categories) to provide a fully functional out of the box responsive CMS for websites with exceptional performance, usage of REST API's and caching abilities.

A Skeleton Bundle is provided as part of the CMS so new content types/functionality that comply with the current architecture can easily be added.

You can browse the Git repository, that I update with big releases every couple of months or so, and use freely for your projects.

You can find the requirements for Symfony2 here http://symfony.com/doc/current/reference/requirements.html  
You can find the documentation for Symfony2 here http://symfony.com/doc/current/book/index.html  
You can find the documentation for Zurb Foundation 5 here http://foundation.zurb.com/docs/
  
The CMS requires the existence of 3 pages to work. These are the homepage, the 404 page and the tagged page.  
  
SkeletonBundle is a fully structured bundle with simple functionalities (similar to normal pages) so it can be cloned to create new bundles for new content types.  
  

Quick Start
------------------------------------------------------

The fastest way to get everything running is:

	1. git clone https://github.com/bardius/BardisCMS.git
	2. composer self-update
	3. composer install
	4. npm install -g bower grunt-cli
	5. npm install
	6. bower install
	7. bardisCMS-deploy.sh
  

Bundle List
------------------------------------------------------

	1. FOSUserBundle
	2. SonataBlockBundle
	3. SonataUserBundle
	4. SonataMediaBundle
	5. SonataAdminBundle
	6. SonataTimelineBundle
	7. KnpMenu
	8. Guzzle
  

Deployment / Local Installation
------------------------------------------------------

Please follow the steps below for a complete new install.  

1. You need to do a git clone of the git repo  
git clone

2. Create the a new folder called uploads within your web directory if not existing (with write rights)

3. Install composer  
http://getcomposer.org/download/

3. Install packagist (https://packagist.org)  
curl -s http://getcomposer.org/installer | php

4. Setup your virtual host (see details in relevant section below).  
Tip: Remember to create the log folder that you added in the virtual host settings (if you did set one).

5. Setup a database and provide the details to the app/config/parameters.yml file (see details in relevant section below).  
Tip: Additionally in the same file you have to set the paths for sass, compass and java for each environment.

6. Change the memory limit in your php.ini to 512M if possible

7. Set the intl PHP extension as enabled if not already (Symfony2 requirement)

8. Run a composer install to get the vendor libraries files (composer update to get latest version)  
composer.phar install

9. Run the CLI symphony2 commands  

	* php app/console cache:clear [--env=prod]
	(to clear and warmup cache)
	* php app/console assets:install  
	(to generate the bundle assets)
	* php app/console doctrine:schema:create  
	(to create the database schema)
	* php app/console doctrine:fixtures:load  
	(to load required/sample data to database)
	* php app/console sonata:media:sync-thumbnails sonata.media.provider.image  
	(to generate the required by sample data images)
	* php app/console assetic:dump [--env=prod]
	(to generate the assets for the front end)

 
### Front end Framework Setup ###

Due to the use of the Zurb Foundation Framework 5 (version 5.2.2) the need for the following steps is unavoidable unless you do not need the framework at all. 
  
We need to install NodeJs, Node Packaged Modules, Ruby, compass, sass, foundation gems and GIT and bower dependency manager if they are not already installed to the system. 
  
More information can be found below at their official web sites:  
  
	http://git-scm.com/downloads				(GIT)  
	http://nodejs.org/							(NodeJs)  
	https://npmjs.org/							(Node Packaged Modules)  
	http://www.rubyinstaller.org/				(Ruby)  
	https://github.com/bower/bower				(Bower)  
	http://sass-lang.com/install				(Sass)  
	http://compass-style.org/install/			(Compass)  
	http://foundation.zurb.com/docs/sass.html	(Foundation 5 - Sass based)  
  
The command line steps are:  
	
	1. [sudo] npm install -g bower grunt-cli
	2. gem update --system
	3. gem install sass
	4. gem install compass
	5. gem install foundation
	6. [sudo] npm install
	7. bower install
	8. grunt deploy [watch]

Tip: In case you are behind a firewall and connection to git is refused force https for all git connections with running this in your bash git config --global url."https://".insteadOf git://

	php app/console assetic:dump  [--env=prod]
  
Your project should work now and you can see your front end working.  
Please Login to /admin/dashboard and alter your website settings and you are finally set to go.


parameters.yml File example contents
---------------------------------------------

Here is a sample setup for your parameters file

	parameters:

		database_driver:   pdo_mysql
		database_host:     localhost
		database_port:     ~
		database_name:     dbname
		database_user:     root
		database_password: ~

		mailer_transport:  smtp
		mailer_host:       localhost
		mailer_user:       ~
		mailer_password:   ~

		locale:            en
		secret:            ThisTokenIsNotSoSecretChangeIt

		javapath:          C:\Program Files\Java\jre7\bin       #usr/bin/java
		compass.bin:       C:\Program Files\Ruby193\bin\compass #usr/bin/compass
		sass.bin:          C:\Program Files\Ruby193\bin\sass    #usr/bin/sass

		unix_socket:       ~ #for your db connection for mac users



Virtual Host Settings
---------------------------------------------

Here is a sample setup for your virtual host configuration

	<VirtualHost *:80>

		DocumentRoot "c:/wamp/www/domainname/web"
		ServerName domainname.prod
		ServerAlias domainname.test
		ServerAlias domainname.dev

		# set some environment variables depending on host
		SetEnvIfNoCase Host domainname\.prod domainname_env=prod
		SetEnvIfNoCase Host domainname\.dev domainname_env=dev
		SetEnvIfNoCase Host domainname\.test domainname_env=test

		# consider a json formatted log string 
		LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-agent}i\"" custom

		# remove image file noise from access logs
		SetEnvIf Request_URI \.(jgp|gif|png|css|js) static
		CustomLog c:/wamp/www/domainname/log/domainname-access_log custom env=!static
		CustomLog c:/wamp/www/domainname/log/domainname-static_log custom env=static

		# LogLevel debug can be useful but any php warnings
		# will always and only appear in the 'error' level
		LogLevel info
		ErrorLog c:/wamp/www/domainname/log/domainname-error_log

		# level 0 is off. Use only for debugging rewrite rules
		RewriteLogLevel 0
		RewriteLog c:/wamp/www/domainname/domainname-rewrite_log


		# for profiling information. Should not be used in production
		Alias /xhprof_html /usr/local/share/php/share/pear/xhprof_html

		<Directory c:/wamp/www/domainname/web>

			RewriteEngine On

			# use the environment variables above to select correct 
			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{ENV:domainname_env} test
			RewriteRule ^(.*)$ app_test.php [QSA,L]

			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{ENV:domainname_env} dev
			RewriteRule ^(.*)$ app_dev.php [QSA,L]

			RewriteCond %{REQUEST_FILENAME} !-f
			RewriteCond %{ENV:domainname_env} prod
			RewriteRule ^(.*)$ app.php [QSA,L]

			Options +Indexes
			Order Allow,Deny
			Allow from all

			# this is best left to 'none' rather than 'All' to 
			# avoid the apache process looking for htaccess files all the way 
			# up the file system tree. in this configuration we avoid 5 stat calls 
			AllowOverride none

		</Directory>

	</VirtualHost>


Updating to the ci server and the live server
-------------------------------------------------------------------------

This can be done with simple steps in your SSH CLI

	git pull
	php app/console cache:clear
	php doctrine:schema:update --force
	php app/console assetic dump


For the production server the process is the same but you should use

	php app/console cache:clear --env=prod
	php app/console assetic:dump --env=prod



Known Bugs / Issues / Extra Configuration
---------------------------------------------

If you run mac OS with mamp remember to set properly your php date.timezone settings
(http://stackoverflow.com/questions/6194003/timezone-with-symfony-2)

You should find your php.ini  in /private/etc if it exists, otherwise:

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
(prequisites are the PageBundle, SettingsBundle and MenuBundle)




Installed bundles
----------------------------------------------

Sonata Admin Documentation
http://sonata-project.org/bundles/admin/2-1/doc/index.html

Sonata Media Documentation
http://sonata-project.org/bundles/media/2-1/doc/index.html

FOS User Bundle Documentation
https://github.com/FriendsOfSymfony/FOSUserBundle

Knp Menu Documentation
http://knpbundles.com/KnpLabs/KnpMenuBundle

TinymceBundle Documentation
(http://knpbundles.com/stfalcon/TinymceBundle)




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

http://git-scm.com/downloads				(GIT)

http://nodejs.org/							(NodeJs)

https://npmjs.org/							(Node Packaged Modules)

http://www.rubyinstaller.org/				(Ruby)

https://github.com/bower/bower				(Bower)

http://sass-lang.com/install				(Sass)

http://compass-style.org/install/			(Compass)

http://foundation.zurb.com/docs/sass.html	(Foundation 5 - Sass based)