<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Testing page, if you can see this page then it working!");
    return $response;
});




/*
 * ADMINISTRATION API
 */

$app->post('/api/login', function (Request $request, Response $response, $args) {

});

$app->post('/api/signup', function (Request $request, Response $response, $args) {

});

$app->post('/api/update/password', function (Request $request, Response $response, $args) {
    // Forgot password update system
});





/*
 * USER API
 */

$app->post('/api/update/profile', function (Request $request, Response $response, $args) {

});





/*
 * JOB API
 */

$app->post('/api/job/apply', function (Request $request, Response $response, $args) {

});

$app->post('/api/job/remove', function (Request $request, Response $response, $args) {

});



/*
 * VACANCY API
 */

$app->post('/api/vacancy/', function (Request $request, Response $response, $args) {

});





/*
 * UTILITIES API
 */

$app->post('/api/email/send', function (Request $request, Response $response, $args) {
    // Send email in general, it can verification email or forgot password email
});


$app->run();