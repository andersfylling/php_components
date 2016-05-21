<?php

require '../Athenticate/Authenticate.php';
require '../Response/Response.php';

$user = [];
$auth = new \php_components\Authenticate($user);
$response = new \php_components\Response();

// an event that fires when any other event thats not specified below
//  fires! This is great for catching different error messages at once.
$auth->_onEvent('*', function ($res)
{
  //the login didn't complete
  print_r($res);
});

// Listen for when the login has completed.
$auth->_onEvent('login_completed', function ($res) use ($response)
{
  $statusCode = $res[0]; //http status code
  if ($statusCode === 200) // success
  {
    // $user = ['username' => 'something', 'password' => 'something'];
    $_SESSION['user'] = $user;
    header('Location: /home.php');
    exit();
  }
  
  echo $res[1]; //response message, most likely wrong username / password
});

// Here the login logic works, you only need to specify the database query logic.
$auth->login(function ($credentials, $callback) 
{
  //database query here
  
  $callback(null, ['password' => 'something']); // $callback(errors, result)
});

?>

<form method="post">
  <input name="email" placeholder="email">
  <input name="password" placeholder="password">
  <button type="submit">Login</button>
</form>
