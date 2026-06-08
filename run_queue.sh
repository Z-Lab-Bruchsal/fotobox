#//bin/sh
while true
do
	/usr/bin/php artisan queue:listen --silent
done
