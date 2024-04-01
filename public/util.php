<?php

use App\DB;
$db = new DB();

function encryptPassword($plain_password): string
{
    return password_hash($plain_password, PASSWORD_DEFAULT);
}


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
            // ........................................

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

function validateUpdateAccount(): int
{
    return 0;
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