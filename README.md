# **IBS DEV Test**

This project is a test for the IBS Backend Laravel Developer Role.

Documentation for the API in this project can be found here:
https://documenter.getpostman.com/view/3148928/2s7YYvYgsY

## **Features**
- Users can sign in using email: **_ibs-dev@email.com_** and password: **_password_**
- Signed in users can create / publish books with authors
- Signed in users can add comments to books
- Users can fetch books, with author and number or comments
- Comments are retrieved along with IP address of comment owner

## **Commands to complete setup**
Proceed to run the following commands after cloning this repository:
1. `composer install`
2. `php artisan migrate` (Make sure to add your database config in `.env` before running migrations)
3. `php artisan db:seed`
4. `npm i` (Optional - if you wish to view project frontend built with `ReactJS`)
5. Run `npm run dev` if you did (4.) above 👆🏽


## **Testing**
- Feature tests have been written using [Pest PHP](https://pestphp.com/) and can be found in the **_tests_** directory.

## **Author**
Babalola Macaulay
🌏 [About Me](https://babs.dev)


## **Cheers 🥂**
