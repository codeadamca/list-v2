<?php

$data = array();

if(!isset($_POST['hash']))
{
    $data['error'] = 'Hash is required';
    return;
}

$query = 'SELECT *
    FROM emails
    WHERE hash = "'.addslashes($_POST['hash']).'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) == 0)
{
    $data['error'] = 'Invalid hash';
    return;
}

$query = 'UPDATE emails SET
    news = "'.(isset($_POST['news']) && $_POST['news'] == 'yes' ? 'yes' : 'no').'",
    socials = "'.(isset($_POST['socials']) && $_POST['socials'] == 'yes' ? 'yes' : 'no').'",
    advanced = "'.(isset($_POST['advanced']) && $_POST['advanced'] == 'yes' ? 'yes' : 'no').'"
    WHERE hash = "'.addslashes($_POST['hash']).'"
    LIMIT 1';
mysqli_query($connect, $query);

$data['success'] = true;
$data['message'] = 'Email settings updated successfully';
