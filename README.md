git init .
git remote add -t \* -f origin git@103.250.188.226:li/licoin.git
git checkout -f master

git remote set-url origin git@103.90.44.169:li/licoin.git

# Send Test Email
\Mail::send([],[], function($message) { $message->to("niravjadatiya@gmail.com")->subject("Testing email"); });

# Run queue:listen in background

    https://laravel.com/docs/5.6/queues#supervisor-configuration

    sudo apt-get install supervisor
    sudo cp /home/ubuntu/www/licoin/laravel-worker.conf /etc/supervisor/conf.d/
    sudo supervisorctl reread

    sudo supervisorctl update

    sudo supervisorctl start laravel-worker:*

    sudo supervisorctl restart laravel-worker:*

# Run schedule:run in Crontab

     php /home/ubuntu/www/artisan schedule:run >> /home/ubuntu/www/scheduleCron.log 2> /dev/null


# Live Server After git Pull Settings
git pull
npm run production
sudo supervisorctl restart laravel-worker:*

composer dump-autoload && php artisan cache:clear && php artisan view:clear && php artisan config:clear && php artisan route:clear && php artisan config:cache && sudo chmod -R 777 storage/logs/

composer install --optimize-autoloader --no-dev
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

php artisan queue:work --timeout=0

git status
git reset --hard <commit id>