# This script will use rsync to
# copy the files for the Jenkins job workspace to the provided target directory, 
# excluding the files listed in exclude.txt (regular expression list)
# setting proper file owner and permissions
# clearing the Symfony2 cache
# generating optimized autoload

# 1 - Location of the build checkout - double quoted, no trailing slash
# 2 - Relative path to be deployed - no trailing slash
# 3 - Target server location - full server path
# 4 - Target server sudo user


# building the unix path for the root directory
dirRoot=$1
echo -e "\n\n\e[0;34mUnix path for Jenkins Workspace working dir is:\e[0m" $dirRoot
echo -e "\n\e[0;34mUnix path for Deployable files dir is:\e[0m" $dirRoot/$2

echo -e "\n\n\e[0;34m********** Start Synchronising files with Rsync **********\e[0m" 
echo sudo -H -u $4 bash -c "sudo /usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/build/exclude.txt --stats $dirRoot/$2/ $3" 
sudo -H -u $4 bash -c "sudo /usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/build/exclude.txt --stats $dirRoot/$2/ $3"

echo -e "\n\n\e[0;34m********** Set folder owners and permissions **********\e[0m"
echo sudo -H -u $4 bash -c "sudo chown -R www-data:www-data $3"
sudo -H -u $4 bash -c "sudo chown -R www-data:www-data $3"

echo -e "\n\n\e[0;34m********** Set permissions to folders **********\e[0m"
echo sudo -H -u $4 bash -c "sudo find $3 -type d -print0 | xargs -0 sudo chmod 0755"
sudo -H -u $4 bash -c "sudo find $3 -type d -print0 | xargs -0 sudo chmod 0755"

echo -e "\n\n\e[0;34m********** Set permissions to files **********\e[0m"
echo sudo -H -u $4 bash -c "sudo find $3 -type f -print0 | xargs -0 sudo chmod 0644"
sudo -H -u $4 bash -c "sudo find $3 -type f -print0 | xargs -0 sudo chmod 0644"

echo -e "\n\n\e[0;34m********** Set permissions to uploads folder **********\e[0m"
echo sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/web/uploads"
sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/web/uploads"

echo -e "\n\n\e[0;34m********** Set permissions to cache folder **********\e[0m"
echo sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/app/cache"
sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/app/cache"

echo -e "\n\n\e[0;34m********** Set permissions to logs folder **********\e[0m"
echo sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/app/logs"
sudo -H -u $4 bash -c "sudo chmod 0755 -R $3/app/logs"

echo -e "\n\n\e[0;34m********** Clear Cache **********\e[0m"
echo sudo -H -u $4 bash -c "sudo php $3/app/console cache:clear --no-debug"
sudo -H -u $4 bash -c "sudo php $3/app/console cache:clear --no-debug"

echo sudo -H -u $4 bash -c "sudo php $3/app/console cache:clear --env=prod --no-debug"
sudo -H -u $4 bash -c "sudo php $3/app/console cache:clear --env=prod --no-debug"

echo -e "\n\n\e[0;34m********** Generate optimized autoload **********\e[0m"
echo sudo -H -u $4 bash -c "sudo $3/composer.phar dumpautoload -o"
sudo -H -u $4 bash -c "sudo $3/composer.phar dumpautoload -o"

# Purge cache if you use a CDN
# echo -e "\n\n\e[0;34m********** Purge CDN Cache **********\e[0m"
# echo php curl_exec(curl_init("https://www.cloudflare.com/api_json.html?a=fpurge_ts&tkn=TOKEN&email=your@email.com&z=yourdomain.com&v=1"));
# php curl_exec(curl_init("https://www.cloudflare.com/api_json.html?a=fpurge_ts&tkn=TOKEN&email=your@email.com&z=yourdomain.com&v=1"));
