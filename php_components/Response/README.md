
Need to send a response, but don't want to bother with the headers, parsing, and such?

Well, now theres a component for that as well!
```php
$json_array = ['name' => 'anders'];
$response->json($json_array, 200); // http code as the second argument is optional. default.
```

Need a fast way to set default allowed request methods?
```php
$response = new \php_compoennts\Response();
$response->add_request_method('options'); //will get formatted to uppercase.

// Or disable a default request method.
$response->disable_request_method('post');

// or remove it so that when a post request is made,
//  the status "method unknown" will be sent.
£response->remove_request_method('post');

// Default allowed request methods are:
GET, HEAD, POST, PUT, DELETE

// Every time a $response method is use to send a message now, all 
//  the allowed http request methods are included in the header by default.
// Content length is also set by the component, and so is content type.
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

##Q & A

Q: The file is rather large, is it slow?
A: If the response time is above 6-10ms on localhost (on my machine ofc), I rethink how I've written the code.

Q: Does this support streaming?
A: Not yet, sorry.

Q: Why did you create this?
A: I tend to have bad habbits when I exams are closing in, so I just try to make stuff.

Q: How can this be used in php 5.6?
A: Just remove the type hinting for each parameter.
