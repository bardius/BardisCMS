# 1 - location of the build checkout - double quoted, no trailing slash
# 2 - relative Key path
# 3 - target IP
# 4 - target location

# building the unix path for the root directory
dirRoot=$(/usr/bin/cygpath -u $1)
echo "Unix path for TC working dir is:" $dirRoot

# rsync 
echo /usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/build/exclude.txt --stats $dirRoot/ -e \"/usr/bin/ssh -o StrictHostKeyChecking=no -i $dirRoot/$2\" bitnami@$3:$4 
/usr/bin/rsync -arivzt --delete --no-p --no-o --no-g --exclude-from=$dirRoot/build/exclude.txt --stats $dirRoot/ -e "/usr/bin/ssh -o StrictHostKeyChecking=no -i $dirRoot/$2" bitnami@$3:$4

# Setting folder owners and permissions
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chown -R bitnami:bitnami $4"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chown -R bitnami:bitnami $4"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo find $4 -type d -print0 | xargs -0 chmod 0755"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo find $4 -type d -print0 | xargs -0 chmod 0755"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo find $4 -type f -print0 | xargs -0 chmod 0644"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo find $4 -type f -print0 | xargs -0 chmod 0644"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/cache"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/cache"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/logs"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/logs"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4web/uploads"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4web/uploads"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "cd $4"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "cd $4"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo php $4app/console cache:clear --env=prod --no-debug"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo php $4app/console cache:clear --env=prod --no-debug"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo php $4app/console cache:clear --no-debug"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo php $4app/console cache:clear --no-debug"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo $4composer.phar dumpautoload -o"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo $4composer.phar dumpautoload -o"
echo /usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/cache"
/usr/bin/ssh -i $dirRoot/$2 bitnami@$3 "sudo chmod 0777 -R $4app/cache"