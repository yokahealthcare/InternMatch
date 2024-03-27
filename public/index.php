<?php

use App\DB;
use App\EmailSender;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

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

$app->get('/api', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Testing page, if you can see this page then API is callable!");
    return $response;
});


$app->post('/api/post_test', function (Request $request, Response $response, $args) {
    try {
        // Get request converted to associative array
        $input = json_decode($request->getBody(), True);

        // Some logic here...
        $data = array('name' => 'Bob', 'age' => 40);
        $payload = json_encode($data);

        // Return JSON-encoded response body
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    } catch (Exception $e) {
        return $response->withStatus(400)->withHeader('X-Status-Reason', $e->getMessage());
    }
});

$app->get('/api/fetch_all', function (Request $request, Response $response) {
    $sql = "SELECT * FROM account";

    try {
        $db = new Db();
        $customers = $db->fetchAllRow($sql);

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});


/*
 * ADMINISTRATION API
 */

$app->post('/api/login', function (Request $request, Response $response, $args) {

});

$app->post('/api/signup', function (Request $request, Response $response, $args) {

});

$app->post('/api/update/password', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM account";

    try {
        $db = new Db();
        $customers = $db->fetchAllRow($sql);

        $response->getBody()->write(json_encode($customers));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
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

    //   TO BE CONTINUE........
    // must delete all the record corresponding with the record

    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
    * input['vacancy_id']               : vacancy ID
    */

    $vacancy_id = $input['vacancy_id'];
    $sqlApplyTable = "DELETE FROM apply WHERE vacancy='$vacancy_id';";
    $sqlVacancyTable = "DELETE FROM vacancy WHERE id='$vacancy_id';";

    try {
        $db = new Db();
        $isApplyTableDeleted = $db->deleteRow($sqlApplyTable);
        $isVancancyTableDeleted = $db->deleteRow($sqlVacancyTable);

        if ($isApplyTableDeleted && $isVancancyTableDeleted) {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'success',
                'message' => 'Job vacancy successfully deleted'
            );
        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'Job vacancy failed to be deleted'
            );
        }
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }
});



/*
 * VACANCY API
 */

$app->get('/api/vacancy', function (Request $request, Response $response, $args) {
    $sql = "SELECT * FROM vacancy";

    try {
        $db = new Db();
        $vacancies = $db->fetchAllRow($sql);
        $response->getBody()->write(json_encode($vacancies));

        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(200);
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(500);
    }
});





/*
 * UTILITIES API
 */

$app->post('/api/email/send', function (Request $request, Response $response, $args) {
    // Send email in general, it can verification email or forgot password email

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
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    } catch (Exception $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response
            ->withHeader('content-type', 'application/json')
            ->withStatus(400);
    }
});


$app->run();