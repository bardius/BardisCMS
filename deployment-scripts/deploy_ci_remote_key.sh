#!/usr/bin/env bash
# This script will use rsync to
# Enable maintenance mode
# copy the files for the Jenkins job workspace to the provided target server:directory,
# copy the asset files for the Jenkins job workspace to the provided target server:directory,
# excluding the files listed in exclude.txt (regular expression list)
# setting proper file owner and permissions
# clearing the Symfony2 cache
# generating optimized autoload
# disable maintenance mode

# 1 - Location of the build checkout - double quoted, no trailing slash
# 2 - Relative path to be deployed - with trailing slash
# 3 - Target server IP
# 4 - Target server location - full server path no trailing slash
# 5 - Target server sudo user

# building the unix path for the root directory
dirRoot=$1
echo -e "\n\n\e[0;34mUnix path for Jenkins Workspace working dir is:\e[0m" $dirRoot
echo -e "\n\e[0;34mUnix path for Deployable files dir is:\e[0m" $dirRoot/$2
echo -e "\n\e[0;34mUnix path target host path dir is:\e[0m" $5@$3 $4

echo -e "\n\n\e[0;34m********** Start Synchronising files with Rsync **********\e[0m"
echo /usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/deployment-scripts/exclude.txt --stats $dirRoot/$2 -e \"/usr/bin/ssh\" --rsync-path=\"sudo /usr/bin/rsync\" $5@$3:$4
sudo -H -u $5 bash -c "/usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/deployment-scripts/exclude.txt --stats $dirRoot/$2 -e '/usr/bin/ssh' --rsync-path='sudo /usr/bin/rsync' $5@$3:$4"

echo -e "\n\n\e[0;34m********** Start Synchronising files in user upload/assets folders with Rsync **********\e[0m"
echo /usr/bin/rsync -arivzt --no-p --no-o --no-g --exclude-from=$dirRoot/deployment-scripts/exclude.txt --stats $dirRoot/$2web/uploads/ -e \"/usr/bin/ssh\" --rsync-path=\"sudo /usr/bin/rsync\" $5@$3:$4/web/uploads
sudo -H -u $5 bash -c "/usr/bin/rsync -arivzt --no-p --no-o --no-g --exclude-from=$dirRoot/deployment-scripts/exclude.txt --stats $dirRoot/$2web/uploads/ -e '/usr/bin/ssh' --rsync-path='sudo /usr/bin/rsync' $5@$3:$4/web/uploads"

echo -e "\n\n\e[0;34m********** Put maintenance mode on **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo mv $4/web/.index.html $4/web/index.html"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo mv $4/web/.index.html $4/web/index.html'"

echo -e "\n\n\e[0;34m********** Set folder owners and permissions **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo chown -R ubuntu:ubuntu $4"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo chown -R ubuntu:ubuntu $4'"

echo -e "\n\n\e[0;34m********** Create cache folder if not existing **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo mkdir -p $4/app/cache"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo mkdir -p $4/app/cache'"

echo -e "\n\n\e[0;34m********** Create logs folder if not existing **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo mkdir -p $4/app/logs"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo mkdir -p $4/app/logs'"

echo -e "\n\n\e[0;34m********** Set permissions to folders **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo find $4 -type d -print0 | sudo xargs -0 chmod 0755"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo find $4 -type d -print0 | sudo xargs -0 chmod 0755'"

echo -e "\n\n\e[0;34m********** Set permissions to files **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo find $4 -type f -print0 | sudo xargs -0 chmod 0644"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo find $4 -type f -print0 | sudo xargs -0 chmod 0644'"

echo -e "\n\n\e[0;34m********** Clear Cache **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo php $4/app/console cache:clear --no-debug"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo php $4/app/console cache:clear --no-debug'"

echo /usr/bin/ssh $5@$3 "sudo php $4/app/console cache:clear --env=prod --no-debug"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo php $4/app/console cache:clear --env=prod --no-debug'"

echo -e "\n\n\e[0;34m********** Set permissions to cache folder **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo chmod 0777 -R $4/app/cache"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo chmod 0777 -R $4/app/cache'"

echo -e "\n\n\e[0;34m********** Set permissions to cache folder files **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo find $4/app/cache -type f -print0 | sudo xargs -0 chmod 0755"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo find $4/app/cache -type f -print0 | sudo xargs -0 chmod 0755'"

echo -e "\n\n\e[0;34m********** Set permissions to logs folder **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo chmod 0777 -R $4/app/logs"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo chmod 0777 -R $4/app/logs'"

echo -e "\n\n\e[0;34m********** Set permissions to logs folder files **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo find $4/app/logs -type f -print0 | sudo xargs -0 chmod 0755"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo find $4/app/logs -type f -print0 | sudo xargs -0 chmod 0755'"

echo -e "\n\n\e[0;34m********** Set permissions to uploads folder **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo chmod 0777 -R $4/web/uploads"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo chmod 0777 -R $4/web/uploads'"

echo -e "\n\n\e[0;34m********** Set permissions to uploads folder files **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo find $4/web/uploads -type f -print0 | sudo xargs -0 chmod 0755"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo find $4/web/uploads -type f -print0 | sudo xargs -0 chmod 0755'"

echo -e "\n\n\e[0;34m********** Generate optimized autoload **********\e[0m"
echo /usr/bin/ssh $5@$3 "cd $4 && sudo php $4/composer.phar dumpautoload -o"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'cd $4 && sudo php $4/composer.phar dumpautoload -o'"

echo -e "\n\n\e[0;34m********** Disable maintenance mode **********\e[0m"
echo /usr/bin/ssh $5@$3 "sudo mv $4/web/index.html $4/web/.index.html"
sudo -H -u $5 bash -c "/usr/bin/ssh $5@$3 'sudo mv $4/web/index.html $4/web/.index.html'"
