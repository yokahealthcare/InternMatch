<?php

use App\DB;
use App\EmailSender;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';      # Utility functions

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

$app->get('/api/request_test', function (Request $request, Response $response) {

//    $data = array(
//        "to" => "erwin.yonata@my.sampoernauniversity.ac.id",
//        "subject" => "Test subject",
//        "message" => "Message to be tested!"
//    );
//    send_request("/api/email/send", json_encode($data));


});


/*
 * ADMINISTRATION API
 */
$app->get('/api/profile', function (Request $request, Response $response) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     */

    $email = $input['email'];
    $sql = "SELECT * FROM account WHERE email='$email';";

    try {
        $db = new Db();
        $customers = $db->fetchAllRow($sql);

        // Return JSON-encoded response body
        $data = array(
            'status' => 'success',
            'message' => 'User data retrieved'
        );

        $response->getBody()->write(json_encode($customers));
        return $response->withHeader('content-type', 'application/json');
    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );

        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});


$app->post('/api/login', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     * input['password']        : User password
     */

    $email = $input['email'];
    $password = $input['password'];
    $encrypted_password = encrypt_password($password);

    $sqlAccount = "SELECT * FROM account WHERE email='$email';";
    try {
        $db = new Db();
        $isAccountExist = $db->isDataExists($sqlAccount);
        if ($isAccountExist) {
            $accountData = $db->fetchAllRow($sqlAccount);
            $accountPassword = $accountData[0]->password;
            if (password_verify($password, $accountPassword)) {
                // Return JSON-encoded response body
                $data = array(
                    'status' => 'success',
                    'message' => 'User is validated'
                );
            } else {
                // Return JSON-encoded response body
                $data = array(
                    'status' => 'failed',
                    'message' => 'User data is wrong'
                );
            }

        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'User not found'
            );
        }

        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});

$app->post('/api/signup', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['name']            : Name of the person
     * input['email']           : User email
     * input['password']        : User password
     */

    $name = $input['name'];
    $email = $input['email'];
    $password = $input['password'];
    $encrypted_password = encrypt_password($password);
    $query = "INSERT INTO account (email, name, password) VALUES ('$email', '$name', '$encrypted_password');";

    try {
        $db = new Db();
        $isDataInsertedSuccessfully = $db->insertRow($query);

        if ($isDataInsertedSuccessfully) {
            // Sending verification email
            // ........................................

            // Return JSON-encoded response body
            $data = array(
                'status' => 'success',
                'message' => 'New user registered. Verification link has been sent to your email.'
            );
        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'New user failed to register'
            );
        }
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});

/*
 * UPDATE API
 */

$app->post('/api/update/password', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['email']           : User email
     * input['password']        : User new password
     */

    $email = $input['email'];
    $password = $input['password'];
    $encrypted_password = encrypt_password($password);
    $query = "UPDATE account SET password='$encrypted_password' WHERE email='$email';";

    try {
        $db = new Db();
        $isPasswordUpdatedSuccessfully = $db->updateData($query);

        if ($isPasswordUpdatedSuccessfully) {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'success',
                'message' => 'Password has been updated'
            );
        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'Password failed to be updated'
            );
        }
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }

});

$app->post('/api/update/profile', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['name']            : Name of the person
     * input['aboutme']         : Description about that person (length: 1000)
     * input['cv']              : CV path
     * input['address']         : Address of person
     * input['linkedin']        : Linkedin url
     */

});

$app->post('/api/update/vacancy', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['title']               : Title of vacancy
     * input['description']         : Description of vacancy
     * input['status']              : Status of vacancy
     */


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

        return $response->withHeader('content-type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});

$app->post('/api/vacancy/apply', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['account']         : Account person who apply
     * input['vacancy']         : Vacancy ID that the person applied
     */

    $id = uniqid();
    $account = $input['account'];
    $vacancy = $input['vacancy'];
    $query = "INSERT INTO apply (id, account, vacancy) VALUES ('$id', '$account', '$vacancy');";

    try {
        $db = new Db();
        $isDataInsertedSuccessfully = $db->insertRow($query);

        if ($isDataInsertedSuccessfully) {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'success',
                'message' => 'Successfully applied to job!'
            );
        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'Failed to applied to job'
            );
        }
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }

});

$app->post('/api/vacancy/create', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['title']           : Title of the vacancy
     * input['description']      : Description of the vacancy
     * input['status']          : Status of the vacancy
     */

    $id = uniqid();
    $title = $input['title'];
    $description = $input['description'];
    $status = $input['status'];
    $account = $input['account'];


    $query = "INSERT INTO vacancy (id, title, description, status, account) VALUES ('$id', '$title', '$description', '$status', '$account');";
    try {
        $db = new Db();
        $isVacancyDataInserted = $db->insertRow($query);

        if ($isVacancyDataInserted) {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'success',
                'message' => 'Job vacancy successfully created'
            );
        } else {
            // Return JSON-encoded response body
            $data = array(
                'status' => 'failed',
                'message' => 'Job vacancy failed to be created.'
            );
        }
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});

$app->post('/api/vacancy/remove', function (Request $request, Response $response, $args) {
    // Get request converted to associative array
    $input = json_decode($request->getBody(), True);
    /*
     * input['vancancy_id']         : Vacancy ID
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
        return $response->withHeader('Content-Type', 'application/json');

    } catch (PDOException $e) {
        $error = array(
            "message" => $e->getMessage()
        );
        $response->getBody()->write(json_encode($error));
        return $response->withHeader('content-type', 'application/json');
    }
});


/*
 * UTILITIES API
 */

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