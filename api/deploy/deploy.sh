#!/bin/sh
# activate maintenance mode
cd /home/lux/lux_elit
# update source code
git pull


#cd api
## update PHP dependencies
#
#php artisan down
#
#
#composer install --no-interaction --no-dev --prefer-dist
## --no-interaction Do not ask any interactive question
## --no-dev  Disables installation of require-dev packages.
## --prefer-dist  Forces installation from package dist even for dev versions.
## update database
#php artisan migrate --force
## --force  Required to run when in production.
## stop maintenance mode
#php artisan up
#php artisan optimize

cd /home/lux/lux_elit/client/

pm2 kill
npm run build
pm2 start /home/lux/lux_elit/client/ecosystem.config.js
