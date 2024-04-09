<?php

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
 * UTILITIES API
 */

$app->post('/api/email/send_reset_password', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['email']           : Recipient email
     */

    sendResetPasswordEmail($input['email']);
    send200("../../email_verification.php", "reset_password_email_sent_successfully");
});



/*
 * VALIDATOR API
 */

$app->post('/api/login', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['email']           : User email
     * input['password']        : User password
     */

    $status = validateLogin($input['email'], $input['password']);
    if ($status == 200) {
        send200("../dashboard.php", "login_success");
    } elseif ($status == 400) {
        send400("../login.php", "invalid_credential_or_account_has_not_been_verified");
    } elseif ($status == 500) {
        send500("../login.php");
    }

    return $response;
});

$app->get('/api/logout', function (Request $request, Response $response, $args) {
    validateLogout();
    send200("../login.php", "logout_success");
});


$app->post('/api/signup', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['name']            : Name of the person
     * input['email']           : User email
     * input['password']        : User password
     */

    $status = validateSignup($input['name'], $input['email'], $input['password']);
    if ($status == 200) {
        send200("../login.php", "signup_sucess_verified_your_account_now");
    } elseif ($status == 400) {
        send400("../signup.php", "signup_failed");
    } elseif ($status == 500) {
        send500("../signup.php");
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
        send200("../../login.php", "verification_success");
    } elseif ($status == 400) {
        send400("../../login.php", "verification_failed");
    } elseif ($status == 500) {
        send500("../../login.php");
    }

    return $response;
});

$app->post('/api/account/update', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['email']           : Email of the person
     * input['name']            : Name of the person
     * input['aboutme']         : Description about that person (length: 1000)
     * input['address']         : Address of person
     * input['linkedin']        : Linkedin url
     */

    $status = validateUpdateAccount($input['email'], $input['name'], $input['aboutme'], $input['address'], $input['linkedin']);
    if ($status == 200) {
        send200("../../profile.php", "update_success");
    } elseif ($status == 400) {
        send400("../../profile.php", "update_failed");
    } elseif ($status == 500) {
        send500("../../profile.php");
    }
    return $response;

});

$app->post('/api/password/update', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['email']           : User email
     * input['password']        : User new password
     */

    $status = validateUpdatePassword($input['email'], $input['password']);
    if ($status == 200) {
        send200("../../login.php", "update_password_success");
    } elseif ($status == 400) {
        send400("../../login.php", "update_password_failed");
    } elseif ($status == 500) {
        send500("../../login.php");
    }
    return $response;
});

$app->post('/api/vacancy/update', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['id']                  : Vacancy ID
     * input['title']               : Title of vacancy
     * input['description']         : Description of vacancy
     * input['status']              : Status of vacancy
     */

    $status = validateUpdateVacancy($input['id'], $input['title'], $input['description'], $input['status']);
    if ($status == 200) {
        send200("../../vacancy.php", "update_vacancy_success");
    } elseif ($status == 400) {
        send400("../../vacancy.php", "update_vacancy_failed");
    } elseif ($status == 500) {
        send500("../../vacancy.php");
    }
    return $response;
});


$app->post('/api/vacancy/apply', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['account']         : Account person who apply
     * input['vacancy']         : Vacancy ID that the person applied
     */

    $id = uniqid(); // Create new apply ID
    $status = validateApplyVacancy($id, $input['account'], $input['vacancy']);
    if ($status == 200) {
        send200("../../vacancy.php", "apply_vacancy_success");
    } elseif ($status == 400) {
        send400("../../vacancy.php", "apply_vacancy_failed");
    } elseif ($status == 500) {
        send500("../../vacancy.php");
    }
    return $response;

});

$app->post('/api/vacancy/create', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['title']           : Title of the vacancy
     * input['description']     : Description of the vacancy
     * input['status']          : Status of the vacancy
     * input['account']         : Account ID creator
     */

    $id = uniqid(); // Create new apply ID
    $status = validateCreateVacancy($id, $input['title'], $input['description'], $input['status'], $input['account']);
    if ($status == 200) {
        send200("../../vacancy.php", "create_vacancy_success");
    } elseif ($status == 400) {
        send400("../../vacancy.php", "create_vacancy_failed");
    } elseif ($status == 500) {
        send500("../../vacancy.php");
    }
    return $response;

});

$app->post('/api/vacancy/remove', function (Request $request, Response $response, $args) {
    $input = (array)$request->getParsedBody();
    /*
     * input['id']         : Vacancy ID
     */

    $status = validateRemoveVacancy($input["id"]);
    if ($status == 200) {
        send200("../../vacancy.php", "remove_vacancy_success");
    } elseif ($status == 400) {
        send400("../../vacancy.php", "remove_vacancy_failed");
    } elseif ($status == 500) {
        send500("../../vacancy.php");
    }
    return $response;
});

$app->run();