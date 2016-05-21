# php_components
PHP 7.0 or above!

Different php scripts to speed up your programming time.

With this I'm trying to let inexperienced scripters or people tired of writing the same script over and over again, to spend less time by using my classes. Hopefully you'll get more time on scripts that matters for you, rather than these old usual ones.


#### Authenticating a user login
```php
$user = [];
$auth = new \php_components\Authenticate($user); //passed as a reference

// Runs when login completed
$auth->_onEvent('login_completed', function ($response)
{ 
	if ($response[0] === 200)
	{
		$_SESSION['user'] = $user; // save user to session or something.
	}
});

// method for logging a user in, before "login_completed" event.
$auth->login(function ($credentials, $callback) { 
  	// login function. Here you query yor database and return the response!
  	$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  	$stmt->execute([
    		':username' => $credentials['username']
 	]);
  
	$callback(null, $stmt->fetch()); // wait for the "login_completed" event.
});

```

#### Sending a response
Perhaps you wish to send some data, may it be text, html file, json.

No more need to worry abour correct headers, content length, content type,
Response even handles allowed request methods for you by default!
```php
$json_array = ['name' => 'anders'];
$response->json($json_array);

RESPONSE HEADERS
Access-Control-Allow-Credentials:true
Access-Control-Allow-Methods:GET, HEAD, POST, PUT, DELETE
Cache-Control:public, max-age=0, no-cache
Connection:Keep-Alive
Content-Length:31
Content-Type:application/json; charset: UTF-8
Date:Sat, 21 May 2016 05:44:18 GMT
Keep-Alive:timeout=5, max=99
Server:Apache/2.4.18 (Unix) OpenSSL/1.0.2g PHP/7.0.4 mod_perl/2.0.8-dev Perl/v5.16.3
X-Powered-By:PHP/7.0.4
```
