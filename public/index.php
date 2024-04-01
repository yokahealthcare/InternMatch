<?php

session_start();

use App\DB;
use App\EmailSender;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

/**
 * Instantiate App
 *
 * In order for the factory to work you need to ensure you have installed
 * a supported PSR-7 implementation of your choice e.g.: Slim PSR-7 and a supported
 * ServerRequest creator (included with Slim PSR-7)
 */
$app = AppFactory::create();

/**
 * The routing middleware should be added earlier than the ErrorMiddleware
 * Otherwise exceptions thrown from it will not be handled by the middleware
 */
$app->addRoutingMiddleware();

/**
 * Add Error Middleware
 *
 * @param bool                  $displayErrorDetails -> Should be set to false in production
 * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
 * @param bool                  $logErrorDetails -> Display error details in error log
 * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger
 *
 * Note: This middleware should be added last. It will not handle any exceptions/errors
 * for middleware added after it.
 */
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Initiate DB Instance
$db = new DB();

$app->get('/api', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Testing page, if you can see this page then it working!");
    return $response;
});

/*
 * FETCHING API
 */

$app->get('/api/fetch/profile', function (Request $request, Response $response) {
    global $db;
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     */

    $email = $input['email'];
    $sql = "SELECT * FROM account WHERE email='$email';";
    try {
        $customers = $db->fetchAllRow($sql);

        $response->getBody()->write(json_encode($customers));
        return $response->withHeader('content-type', 'application/json')->withStatus(200);
    } catch (PDOException $e) {
        $response->getBody()->write(json_encode(array(
            "message" => $e->getMessage()
        )));
        return $response->withHeader('content-type', 'application/json')->withStatus(500);
    }
});

$app->get('/api/vacancy', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM vacancy";
    try {
        $db = new Db();
        $vacancies = $db->fetchAllRow($sql);
        $response->getBody()->write(json_encode($vacancies));

        return $response->withHeader('content-type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});

/*
 * INDIRECTION API
 */

$app->post('/api/login', function (Request $request, Response $response, $args) {
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     * input['password']        : User password
     */

    $status = validateLogin($input['email'], $input['password']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }

    return $response;
});


$app->post('/api/signup', function (Request $request, Response $response, $args) {
    global $db;
    $input = json_decode($request->getBody(), True);
    /*
     * input['name']            : Name of the person
     * input['email']           : User email
     * input['password']        : User password
     */

    $status = validateSignup($input['name'], $input['email'], $input['password']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;
});

$app->get('/api/account/verified', function (Request $request, Response $response, $args) {
    // TOBE CONTINUED .....




    $input = json_decode($request->getBody(), True);
    print_r($request->getBody());
    /*
     * input['email']            : Email of the account
     */
    $status = validateVerifyAccount($input['email']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;
});

$app->post('/api/account/update', function (Request $request, Response $response, $args) {
    $input = json_decode($request->getBody(), True);
    /*
     * input['name']            : Name of the person
     * input['aboutme']         : Description about that person (length: 1000)
     * input['cv']              : CV path
     * input['address']         : Address of person
     * input['linkedin']        : Linkedin url
     */
});

$app->post('/api/password/update', function (Request $request, Response $response, $args) {
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     * input['password']        : User new password
     */

    $status = validateUpdatePassword($input['email'], $input['password']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;
});

$app->post('/api/vacancy/update', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['id']                  : Vacancy ID
     * input['title']               : Title of vacancy
     * input['description']         : Description of vacancy
     * input['status']              : Status of vacancy
     */

    $status = validateUpdateVacancy($input['id'], $input['title'], $input['description'], $input['status']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;
});


$app->post('/api/vacancy/apply', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['account']         : Account person who apply
     * input['vacancy']         : Vacancy ID that the person applied
     */

    $id = uniqid(); // Create new apply ID
    $status = validateApplyVacancy($id, $input['account'], $input['vacancy']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;

});

$app->post('/api/vacancy/create', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['title']           : Title of the vacancy
     * input['description']     : Description of the vacancy
     * input['status']          : Status of the vacancy
     * input['account']         : Account ID creator
     */

    $id = uniqid(); // Create new apply ID
    $status = validateCreateVacancy($id, $input['title'], $input['description'], $input['status'], $input['account']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;

});

$app->post('/api/vacancy/remove', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['id']         : Vacancy ID
     */

    $status = validateRemoveVacancy($input["id"]);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;
});












$app->post('/api/email/send', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['to']          : recipient email address
     * input['subject']     : email subject
     * input['message']     : email message
     */

    $email = new EmailSender();
    try {
        // email address - who to send
        $email->mail->addAddress($input['to']);
        // email content
        $email->mail->isHTML(true);
        $email->mail->Subject = $input['subject'];
        $email->mail->Body    = $input['message'];
        // Send the email
        $email->mail->send();

        // Return JSON-encoded response body
        $data = array(
            'status' => 'success',
            'message' => 'Email sent successfully'
        );
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (Exception $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});
$app->run();