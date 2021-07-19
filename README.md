SDK OAUTH2.0 Project (ESGI 3IW3)
=
**By Antoine Saunier & Christian Mohindo**

### An Easy and configurable, all in one place SDK for integrate OAUTH login of all your favorite provider.

> **NB : For Karl Marques, Suite à des problèmes de configuration de gitkraken mes premiers commits (Antoine Saunier) n'était pas signé, je vous avez envoyé un email pour vous prévenir.**

Default Provider :
=
* Facebook
* Oauth Server ESGI
* GitHub
* Google
*********************

# How to start the project :

1. After the git clone you need to Copy/Paste the /app/config.php.example file and name it config.php

2. Now just launch this command in the project directory :

`docker-compose up -d`

3. You can now access it and testing the default providers (if there are any in the config.php) at this url : https://localhost/auth

## How to add a new provider

1. For adding a provider first you need to add it's configuration in the /app/config.php file

>'example_provider' => [
>
>     'client_id' => '', The client ID of your provider you need to recover it in the parameters of your oauth app.
>            
>     'client_secret' => '', The client secret ID of your provider you need to recover it in the parameters of your oauth app.
>            
>     'auth_url' => '', The logging url of the provider
>            
>     'access_token_url' => '', The url for getting the the authorization code needed for getting the data from the provider
>            
>     'user_info_url' => '', The url of the provider for getting the data for oauth the user data
>            
>     'redirect_url' => '', The redirect url of yout app, in this project the url is https://localhost/auth
>            
>     Here you can add optional parameters if your provider need additional parameters
>     ['' => '', '' => ''],
>            
>     When your provider need header parameters
>     'headers' => ['', '', '']        
>
> ],

2. After that go to the /app/ExampleProvider.php

You need to copy & paste this file for adding your provider, and changing the filename & the classname

See the comment in the file for more description on how handle additionnal parameters.

3. Go to /app/index.php and follow the instruction you need to add 4 lines :

* `$exampleProvider = $collection->getProvider('example');`
* `echo $app->getAuthLink();`
* `$user = $exampleProvider->getTest($code);`
* `var_dump($user);`

4. Test your new Oauth provider at https://localhost/auth click on the 'Login with ...' Link, login to the providers and get the user data you need !