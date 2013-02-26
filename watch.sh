while inotifywait -qre close_write -e delete -e move -e create .
do
    php trunk/artisan minify
done