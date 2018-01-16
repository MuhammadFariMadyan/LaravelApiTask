## Laravel Base Project

<p align="center">
<a href="https://gitlab.com/WebMobTechnologies/Base_Project_Web/tree/laravel_base_project"><img src="https://img.shields.io/badge/license-WMT-00BCD4.svg?style=flat-square" alt="License"></a>
<a href="https://laravel.com/docs/5.5/"><img src="https://img.shields.io/badge/laravel%20version-5.5.28-F4645F.svg?style=flat-square" alt="License"></a>
</p>

## About Laravel Base Project

Laravel Base Project is designed with the intention to save the time of API developers which they invest while setting up environment for any new application.

Based on past projects this base project is shipped with few of the basic and common things that a developer needs while creating any RESTful API such as:


- [JSON Web Token Authentication for Laravel](https://github.com/tymondesigns/jwt-auth) as an default authentication tool for Laravel Base Project.
- [uuid by Ramsey](https://github.com/ramsey/uuid) for generating uuid using uuid4().
- ```FirebaseNotification``` trait for generating and sending push notification using [Laravel-FCM](https://github.com/brozot/Laravel-FCM)
- [Spatie's Laravel URL Signer](https://github.com/spatie/laravel-url-signer) for validating URL's and its lifetime.
- Updated ```EmojiRemover``` trait for filtering emojis before interacting with database.
- Custom token generator for email verification and forgot password using ```GetTokens``` class.
- ``` ApiResponse``` trait for generating templating api responses in an standard way.



## About JSON Web Token

Every API needs something like token to authenticate the user who is requesting access its endpoints. However, for the purpose Laravel itself ships with API Passport plugin but it can be useful when you need oAuth2 authentication. 

Until now we need to create custom token using encryptDecrypt or JSON Web Token by your own but with this base project JSON Web Token (JWT) has been integrated to provide the authentication on fly.

For JWT installation and usage [check here](https://github.com/tymondesigns/jwt-auth).

Laravel Base Projects provides support for default JWT tokens and as well as custom JWT tokens 

**Default:** Laravel Base Project supports multiple access token mechanism and does not encourage to save these tokens into DB for further use, however as per your requirement you are free to do whatever you like to!


**Custom Claim JWT** 

For custom claims JWT use ```customClaimJWT()``` function like this

```php
$claims =['id'=>$user->id,'userType' =>'user'];
$token = $this->customClaimJWT($claims);
```

For verifying these tokens Laravel Base Project has the common `auth()` function in VerifyJWTToken middleware so for verifying custom claim tokens simply uncomment the custom claim `auth()` 

Though, single access token mechanism is not at all a preferable choice while considering this project still Laravel Base Project has a sleeping support for single access token for single access token mechanism you need to do some of edits like:

- You need to use ```singleLogin()``` function instead of default ```multipleLogin()``` and for that make appropriate changes in ```routes/api.php``` file also. single login mechanism is just a replica of multiple login with an only difference of allowing the user to login only from one device at a time.

- Another change for single token mechanism is in logout.For logging off the user you need to call ```singleLogout()``` function.

> Note: 
> You can change these methods name as per your preference however do refactor everything before changing.


## uuid By Ramsey

Gone were days when you need to pass your primary key as an `id` in request url for requesting the access. Viewing the security concerns Laravel Base Project provides integration with [this](https://github.com/ramsey/uuid) repo for generating ```uuids``` for making secure request.

```php
$uuid4 = Uuid::uuid4();
$uuid = $uuid4->toString();
```

## Laravel-FCM

For push notification, Laravel Base Project ships with ```FirebaseNotification``` trait which supports push notifications to single and as well as group notifications.

For further digging check the [official repo](https://github.com/brozot/Laravel-FCM) and our ```testPushNotification()``` function inside ```Api/v1/users/UserController```

## Spatie URL Signer

Laravel Base Project ships with Spatie URL Signer which will validate url before accessing it basically it is used for forgot password. Currently, it allows user to reset their password within a span of 24 hours.
For its installation and usage [click here](https://github.com/spatie/laravel-url-signer)

## Mails

Laravel Base Project recommends the use of Laravel Mailables, by this you can even test how your mails are working and will look to the end user.

Since, we are using Laravel's mailable therefore [mailtrap.io](https://mailtrap.io/) will be our default smtp as recommended by Laravel.

```bash
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=mailtrapusername 
MAIL_PASSWORD=mailtrappassword
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=testingemailaddress@domain.com
MAIL_FROM_NAME="Project Name"
```

For the ease purpose Laravel Base Project ships with verifying email and reset password mail class along with their jobs.

It is recommend to use jobs for generating and delivering mails for ease.

For further digging on laravel mailables refer the official [docs](https://laravel.com/docs/5.5/mail)

For markdown files that are used in laravel mails refer to [this article](https://laravel.io/forum/01-31-2014-markdown-reference)


## Laravel Horizon


Laravel Horizon support added.