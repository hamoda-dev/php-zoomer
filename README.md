
# PHP-Zoomer

PHP-Zoomer is a simple Zoom API library for using to work with Webiners and it give you OAuth implemntation and Server-to-Server OAuth.

## Installation
via composer run 
```
composer require hamoda-dev/php-zoomer
```

## How to Use

### Initialize
Initialize the library with the required credentials
```php
$zoomer = new Zoomer('clientID', 'clientSecret', 'credenialPath');
```

### Autheriztion
#### OAuth via URL
1 - Add the redirect URI
```php
$zoomer->setRedirectUri('redirectURI');
```
2 - Get OAuth URL
```php
$zoomer->getOAuthUrl();
```
#### Server to Server OAuth
1 - Add the Account ID
```php

```