<?php

require '../Athenticate/Authenticate.php';
require '../Response/Response.php';

$user = [];
$auth = new \php_components\Authenticate($user);
$response = new \php_components\Response();

$auth->_onEvent('*', function ($res)
{
  //the login didn't complete
  print_r($res);
});

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

$auth->login(function ($credentials, $callback) { // login function. Here you query yor database and return the response!
  $callback(null, ['password' => 'something']); // wait for the "completed" event.
});

?>

<form method="post">
  <input name="email" placeholder="email">
  <input name="password" placeholder="password">
  <button type="submit">Login</button>
</form>
