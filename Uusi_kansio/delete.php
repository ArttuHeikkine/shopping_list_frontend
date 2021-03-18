<?php
require_once 'inc/functions.php';
require_once 'inc/headers.php';

$input = json_decode(file_get_contents('php://input'));
$id = filter_var($input->id,FILTER_SANITIZE_NUMBER_INT);

try {
    $db = openDb();
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $query = $db->prepare('delete from item where id=(:id)');
    $query->bindValue(':id',$id,PDO::PARAM_INT);
    $query->execute();

    echo header('HTTP/1.1 200 OK');
    $data = array('id' => $id);
    echo json_encode($data);
}
catch (PDOException $pdoex) {
    echo header('HTTP/1.1 500 Internal Server Error');
    $error = array('error' => $pdoex->getMessage());
    echo json_encode($error);
    exit;
}