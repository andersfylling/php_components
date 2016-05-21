# php_components
PHP 7.0 or above!

Different php scripts to speed up your programming time.

With this I'm trying to let inexperienced scripters or people tired of writing the same script over and over again, to spend less time by using my classes. Hopefully you'll get more time on scripts that matters for you, rather than these old usual ones.


Example of loging a user in:
```php
$user = [];
$auth = new \php_components\Authenticate($user); //passed as a reference

$auth->_onEvent('login_completed', function ($response) //will be called on logged in.
{ 
	if ($response[0] === 200)
	{
		$_SESSION['user'] = $user; // save user to session or something.
	}
});

$auth->login(function ($credentials, $callback) { 
  	// login function. Here you query yor database and return the response!
  	$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
  	$stmt->execute([
    		':username' => $credentials['username']
 	]);
  
	$callback(null, $stmt->fetch()); // wait for the "login_completed" event.
});

```

Perhaps you wish to send some data, may it be text, html file, json.
No more need to worry abour correct headers, content length, content type,
Response even handles allowed request methods for you by default!
```php
$json_array = ['name' => 'anders'];
$response->json($json_array, 200 /* http code */);
```
