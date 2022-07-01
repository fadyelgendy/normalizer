# Database Normalizer
Simple PHP/ MySQL app for normalizing database tables

## Setup
- You May set up virtual host or your own [xampp, wamp, ...etc], or you can run it using php server as follows:
    - Create database for the test, call it anything
    - clone project files into your machine
    - `cd` into project files
    - open project into text editor of choice.
    - add MySQL connection details into `.env` file.
    - run `php -S localhost:5000` to start php built in server

- Once your server started, you will have to setions in page,
    - form: to add test data into database
    - table: have all existing database data

- `Form section`: has simple form with [title, desctiption, and normalize], fill in data to test, if you want to normalize data on the fly before get saved to database **check** normalize checkbox, otherwise data will be saved the way it is.

- `table section`: have two important buttons:
    - `Load test data`: inset test data into database.
    - `normalize`: normalize existing data, and updates database
