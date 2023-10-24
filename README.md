# Symfony auth
Symfony 6 basic authentication system

### Installation 
**Step 1**: Setup your `.env` file 
``` 
MAILER_DSN=smtp://user:pass@smtp.example.com:port
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/database_name?serverVersion=5.7

ADMIN_EMAIL=admin@example.com
FROM_EMAIL=info@example.com
```
**Step 2** : If your database does not exists yet then run following command to create a new database for you. 

```
php bin/console doctrine:database:create
```
**Step 3**:  Lets run the migrations. 

```
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

It will create necessary tables into your database and create a admin user with `username` and `password` is **admin**

Step 4: Frontend Setup.
``` 
npm install
npm run build
```

Step 5: Run phpunit test
```
php bin/phpunit
``` 

**Congratulations! your site is now ready.**

### Features
1. Login
2. Registration
3. Forget password
4. Change Password
5. Profile Picture
6. Admin notify for new user registration
7. Profile info change for logged in user
8. Users list page for Admin user. 
9. Progressive Web Application
10. Offline Web Application

### Login Form

<img src="https://i.ibb.co/dP9CCjF/Screenshot-2020-07-29-at-10-19-28-PM.png" alt="Screenshot-2020-07-29-at-10-19-28-PM" border="0">

### Profile Page

<img src="https://i.ibb.co/8c4t7wb/Screenshot-2020-07-29-at-10-23-17-PM.png" alt="Screenshot-2020-07-29-at-10-23-17-PM" border="0">

### Users List (admin)

<img src="https://i.ibb.co/0V6fTv3/Screenshot-2020-07-29-at-10-24-11-PM.png" alt="Screenshot-2020-07-29-at-10-24-11-PM" border="0">
