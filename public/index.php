<?php

session_start();

use App\DB;

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

$app->get('/api/profile/fetch', function (Request $request, Response $response) {
    $input = $request->getQueryParams();
    /*
     * input['email']           : User email
     */
    $customers = fetchProfile($input['email']);
    $response->getBody()->write($customers);
    return $response->withHeader('content-type', 'application/json')->withStatus(200);
});

$app->get('/api/vacancy/fetch', function (Request $request, Response $response, $args) {
    $input = $request->getQueryParams();
    /*
     * input['search']           : Search Vacancy by Query
     */
    if(isset($input['search']))
        $vacancies = fetchVacancy($input['search']);
    else
        $vacancies = fetchVacancy();

    $response->getBody()->write($vacancies);
    return $response->withHeader('content-type', 'application/json')->withStatus(200);
});

$app->get('/api/apply/fetch', function (Request $request, Response $response, $args) {
    $input = $request->getQueryParams();
    /*
     * input['email']           : User email
     */
    $applies = fetchApply($input['email']);
    $response->getBody()->write($applies);
    return $response->withHeader('content-type', 'application/json')->withStatus(200);
});




/*
 * VALIDATOR API
 */

$app->post('/api/login', function (Request $request, Response $response, $args) {
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
    /*
     * input['email']           : Email of the person
     * input['name']            : Name of the person
     * input['aboutme']         : Description about that person (length: 1000)
     * input['address']         : Address of person
     * input['linkedin']        : Linkedin url
     */

    $status = validateUpdateAccount($input['email'], $input['name'], $input['aboutme'], $input['address'], $input['linkedin']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;

});

$app->post('/api/password/update', function (Request $request, Response $response, $args) {
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
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
    $input = $request->getQueryParams();
    /*
     * input['to']          : recipient email address
     * input['subject']     : email subject
     * input['message']     : email message
     */

    $status = validateSendEmail($input['to'], $input['subject'], $input['message']);
    if ($status == 200) {
        $response->getBody()->write("200");
    } elseif ($status == 400) {
        $response->getBody()->write("400");
    } elseif ($status == 500) {
        $response->getBody()->write("500");
    }
    return $response;

});
$app->run();