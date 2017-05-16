# Translation System (Laravel & Vue JS)
Before install, please make sure your php version is greater than 5.6.4.
    
### Install

1. Open your terminal.

2. Clone this repo:

        git clone https://github.com/andreypp28/rebelwall-translation.git
    
3. Once finished, change directory to rebelwall-translation:

        cd rebelwall-translation
    
4. Install all laravel dependencies:

        composer install
        
5. Check you db connection in .env file

6. Run migration table:

        php artisan migrate

7. Run seeders:

        php artisan db:seed

8. Serve the app:

        php artisan serve

9. Open `http://localhost:8000/` in your web browser.

        user: admin@gmail.com
        password: password

### Development

1. Open your terminal and change directory to `rebelwall-translation`.

2. Make sure you have [nodejs](https://nodejs.org/en/download/) installed and run this command:

        npm install

3. Make sure you have [bower](https://bower.io/#install-bower) installed and run this command:

        bower install

4. Make sure you have [gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md) installed and run this command:

        gulp watch
    
5. All files (components, etc) are located in `resources/assets`.
