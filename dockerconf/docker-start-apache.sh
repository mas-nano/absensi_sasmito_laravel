#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

# path project. see on Dockerfile 
path_project=/var/www/html

printf "\nstarting execute composer dump-autoload...\n"
composer dump-autoload
printf "\nstarting execute php artisan config:clear...\n"
php artisan config:clear
printf "\nstarting execute php artisan cache:clear...\n"
php artisan cache:clear
printf "\nstarting execute php artisan route:clear...\n"
php artisan route:clear


if [ "$role" = "app" ]; then

    printf "\nstart apache2...\n"
    apache2ctl -D FOREGROUND

elif [ "$role" = "queue" ]; then

    echo "Executing queue..."
	sleep 60
    echo "Running the queue..."
    php $path_project/artisan queue:work redis --verbose --daemon

elif [ "$role" = "scheduler" ]; then

    while [ true ]
    do
	  now=$(date +"%Y-%m-%d %T")
	  echo "[$now] Executing cron..."
      php $path_project/artisan schedule:run --verbose --no-interaction &
      sleep $((60 - $(date +%s) % 60))
    done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi
