<?php

use App\DB;
use App\EmailSender;

$db = new DB();

function encryptPassword($plain_password): string
{
    return password_hash($plain_password, PASSWORD_DEFAULT);
}

/*
 * FETCH
 */

function fetchProfile($email): bool|string
{
    global $db;
    $sql = "SELECT * FROM account WHERE email='$email';";

    $customers = $db->fetchAllRow($sql);
    return json_encode($customers);
}

function fetchVacancy($search=''): bool|string
{
    global $db;
    $sql = "SELECT * FROM vacancy WHERE title LIKE '%$search%';";

    $vacancies = $db->fetchAllRow($sql);
    return json_encode($vacancies);
}

function fetchApply($email): bool|string
{
    global $db;
    $sql = "SELECT * FROM apply WHERE account='$email';";
    $applies = $db->fetchAllRow($sql);
    return json_encode($applies);
}


/*
 * VALIDATOR
 */


function validateLogin($email, $password): int
{
    global $db;
    $sql="SELECT * FROM account WHERE email='$email';";
    try {
        $isAccountExist = $db->isDataExists($sql);
        if ($isAccountExist) {
            $accountData = $db->fetchAllRow($sql);
            $accountVerified = $accountData[0]->verified;
            if($accountVerified == "0")
                return 400;

            $accountPassword = $accountData[0]->password;
            if (password_verify($password, $accountPassword)) {
                // Session storing
                // .....
                return 200;
            }
        }
        return 400;
    } catch (PDOException $e) {
        return 500;
    }
}


function validateSignup($name, $email, $password): int
{
    global $db;
    $encryptedPassword = encryptPassword($password);
    $sql = "INSERT INTO account (email, name, password) VALUES ('$email', '$name', '$encryptedPassword');";

    try {
        $isDataInsertedSuccessfully = $db->insertRow($sql);
        if ($isDataInsertedSuccessfully) {
            // Sending verification email
            $server = $_SERVER['SERVER_NAME'];
            $port = $_SERVER['SERVER_PORT'];
            $subject = "New Account Verification";
            $message = "Thank you for joining with us. One more step, we need to verify your account by clicking this link, http://$server:$port/api/account/verified?email=$email";
            validateSendEmail($email, $subject, $message);

            return 200;
        }
        return 400;
    } catch (PDOException $e) {
       return 500;
    }
}
function validateVerifyAccount($email): int
{
    global $db;
    $sql = "UPDATE account SET verified=1 WHERE email='$email';";

    try {
        $isVerifiedUpdated = $db->updateData($sql);
        if ($isVerifiedUpdated)
            return 200;

        return 400;
    } catch (PDOException $e) {
        return 500;
    }
}

function validateUpdateAccount($email, $name, $aboutme, $address, $linkedin): int
{
    global $db;
    $sql = "UPDATE account SET name='$name', aboutme='$aboutme', address='$address', linkedin='$linkedin' WHERE email='$email';";

    try {
        $isProfileUpdated = $db->updateData($sql);
        if ($isProfileUpdated)
            return 200;

        return 400;
    } catch (PDOException $e) {
        return 500;
    }
}


function validateUpdatePassword($email, $password): int
{
    global $db;
    $encryptedPassword = encryptPassword($password);
    $sql = "UPDATE account SET password='$encryptedPassword' WHERE email='$email';";

    try {
        $isPasswordUpdated = $db->updateData($sql);
        if ($isPasswordUpdated)
            return 200;

       return 400;
    } catch (PDOException $e) {
       return 500;
    }
}

function validateUpdateVacancy($id, $title, $description, $status): int
{
    global $db;
    $sql = "UPDATE vacancy SET title='$title', description='$description', status='$status' WHERE id='$id';";

    try {
        $isVacancyUpdated = $db->updateData($sql);
        if ($isVacancyUpdated)
            return 200;

        return 400;
    } catch (PDOException $e) {
        return 500;
    }
}


function validateApplyVacancy($id, $account, $vacancy): int
{
    global $db;
    $sqlCheckRow = "SELECT * FROM apply WHERE account='$account' AND vacancy='$vacancy';";
    $sqlInsert = "INSERT INTO apply (id, account, vacancy) VALUES ('$id', '$account', '$vacancy');";
    try {
        $isApplyExist = $db->isDataExists($sqlCheckRow);
        if ($isApplyExist)
            return 400;


        $isDataInserted = $db->insertRow($sqlInsert);
        if ($isDataInserted)
            return 200;

        return 400;
    } catch (PDOException $e) {
      return 500;
    }
}

function validateCreateVacancy($id, $title, $description, $status, $account): int
{
    global $db;
    $sql = "INSERT INTO vacancy (id, title, description, status, account) VALUES ('$id', '$title', '$description', '$status', '$account');";
    try {
        $isVacancyDataInserted = $db->insertRow($sql);
        if ($isVacancyDataInserted)
            return 200;

        return 400;
    } catch (PDOException $e) {
        return 500;
    }
}

function validateRemoveVacancy($id): int
{
    global $db;
    $sqlApplyTable = "DELETE FROM apply WHERE vacancy='$id';";
    $sqlVacancyTable = "DELETE FROM vacancy WHERE id='$id';";

    try {
        $isApplyTableDeleted = $db->deleteRow($sqlApplyTable);
        $isVancancyTableDeleted = $db->deleteRow($sqlVacancyTable);

        if ($isApplyTableDeleted && $isVancancyTableDeleted)
            return 200;

        return 400;
    } catch (PDOException $e) {
       return 500;
    }
}

function validateSendEmail($to, $subject, $message): int
{
    $email = new EmailSender();
    try {
        // email address - who to send
        $email->mail->addAddress($to);
        // email content
        $email->mail->isHTML(true);
        $email->mail->Subject = $subject;
        $email->mail->Body    = $message;
        // Send the email
        $email->mail->send();

        return 200;
    } catch (Exception $e) {
       return 500;
    }
}