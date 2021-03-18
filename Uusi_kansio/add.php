<?php
require_once 'inc/functions.php';
require_once 'inc/headers.php';

$input = json_decode(file_get_contents('php://input'));
$description = filter_var($input->description,FILTER_SANITIZE_STRING);
$amount = filter_var($input->amount,FILTER_SANITIZE_NUMBER_INT);
try {
    $db = openDb();
    $query = $db->prepare('insert into item(description, amount) values (:description, :amount)');
    $query->bindValue(':description',$description,PDO::PARAM_STR);
    $query->bindValue(':amount',$amount,PDO::PARAM_INT);
    $query->execute();
    echo header('HTTP/1.1 200 OK');
    $data = array('id' => $db->lastInsertId(), 'description' => $description, 'amount' => $amount);
    echo json_encode($data);
}
catch (PDOException $pdoex) {
    echo header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    echo json_encode($error);
    exit;
}