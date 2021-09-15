<?php

//add_comment.php
$DATABASE_HOST = 'sql212.epizy.com';
$DATABASE_USER = 'epiz_29739673';
$DATABASE_PASS = 'obRMG8ZqmhRbNWX';
$DATABASE_NAME = 'epiz_29739673_komentarai';

try{
    $connect = new PDO('mysql:host=' .$DATABASE_HOST . ';dbname=' .$DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
}
catch(PDOException $exception){
    exit('Failed to connect to database');
}

$error = '';
$comment_name = '';
$comment_content = '';
$email = '';

if(empty($_POST["comment_name"]))
{
    $error .= '<p class="text-danger">Name is required</p>';
}
else
{
    $comment_name = $_POST["comment_name"];
}

if(empty($_POST["comment_content"]))
{
    $error .= '<p class="text-danger">Comment is required</p>';
}
else
{
    $comment_content = $_POST["comment_content"];
}

if(empty($_POST["email"]))
{
    $error .= '<p class="text-danger">Email is required</p>';
}
else
{
    $email = $_POST["email"];
}

if($error == '')
{
    $query = "
    INSERT INTO kduomenys 
    (ats_id, komentaras, vardas, epastas) 
    VALUES (:ats_id, :komentaras, :vardas, :epastas)
    ";
    $statement = $connect->prepare($query);
    $statement->execute(
        array(
            ':ats_id' => $_POST["id"],
            ':komentaras'    => $comment_content,
            ':vardas' => $comment_name,
            ':epastas' => $email,
            )
        );
        $error = '<label class="text-success">Comment Added</label>';
}

$data = array(
    'error'  => $error
);

echo json_encode($data);

?>