##Authenticate class##
#####For authenticating users with less code

It's rather simple to login someone!
```php
$user = [];
$auth = new \php_components\Authenticate($user);

$auth->_onEvent('login_completed', function ($response) { //will be called on logged in.
	if ($response[0] === 200)
	{
		$_SESSION['user'] = $user; // save user to session or something.
	}
	else
	{
		echo $response[1]; //wrong username or password, and a 403 error code at [0]
	}
});

$auth->login(function ($credentials, $callback) { // login function. Here you query yor database and return the response!
	$callback(null, ['password' => 'something']); // wait for the "completed" event.
});

```
That's it!


A little more details here.

```php
$user 		= []; 			// To be updated on successfull registering or logging in.
$csrfToken 	= '...';		// a CSRF Token if you have one, Optional.
$config 	= ['ajax' => false];	// Specify if its ajax or not. Optional, showing default.

// Initiate a new Authenticate object
$auth = new \php_components\Authenticate($user, $csrfToken, $config);

// Lets login..
//  First we need a listener that tells us when login completed or a failure happened. 
//   See the Authenticate login method description for event types!
//   $response is an array consisting of http response code and a response message. [int, string].
$auth->_onEvent('*', function ($response) {  // handle all events thats not specified..
	var_dump($response); 
});
$auth->_onEvent('login_completed', function ($response) {
	// Login completed. Either the username / password was incorrect or login was successfull.
	var_dump($response);
});

// Now let's login
$auth->login(function ($credentials, $callback) {
	// Here you query the database using the $credentials
	//  provided. The result is returned to the script.
	// ... // however you query your database.

	$result 	= [...];	// result from the database query.
	$err 		= null;		// Error that happened from database qerying.
	$callback($err, $result);	// return the result to the script. Now just wait for a event to trigger!
});

// Thats all there is to it. This can also be used as is on the same files as the form.
//  eg. lets say we have a form to display in the same php. Just echo it out with the same code above.
//  No problem!

echo
'
<form method="post">
<input name="email" placeholder="email" value="rft43">
<input name="password" placeholder="password" value="p4ionf3">
<button type="submit">test</button>
</form>
';

```
