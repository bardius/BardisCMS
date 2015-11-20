echo "Starting Symfony2 BardisCMS Deployment"

echo "Clearing Dev Cache"

php app/console cache:clear --env=dev --no-debug --no-warmup

php app/console cache:warmup --env=dev --no-debug

echo "Clearing Prod Cache"

php app/console cache:clear --env=prod --no-debug --no-warmup

php app/console cache:warmup --env=prod --no-debug

echo "Done!"

echo "Generating Assets With Grunt"

grunt deploy

echo "Done!";

echo "Generating Bundle Assets"

php app/console assets:install ./web/ --env=prod --no-debug

echo "Done!";

echo "Generating Front End Assets"

php app/console assetic:dump --env=dev --no-debug

php app/console assetic:dump --env=prod --no-debug

echo "Done!"

echo "You can now use Symfony2 BardisCMS"