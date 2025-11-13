<?php

$data = array();

if(!isset($_GET['hash']))
{
    $data['error'] = 'Hash is required';
    return;
}

$query = 'SELECT *
    FROM emails
    WHERE hash = "'.addslashes($_GET['hash']).'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) == 0)
{
    $data['error'] = 'Invalid hash';
    return;
}

$query = 'UPDATE emails SET
    news = "'.(isset($_GET['news']) && $_GET['news'] == 'yes' ? 'yes' : 'no').'",
    socials = "'.(isset($_GET['socials']) && $_GET['socials'] == 'yes' ? 'yes' : 'no').'",
    advanced = "'.(isset($_GET['advanced']) && $_GET['advanced'] == 'yes' ? 'yes' : 'no').'"
    WHERE hash = "'.addslashes($_GET['hash']).'"
    LIMIT 1';
mysqli_query($connect, $query);

$data['success'] = true;
$data['message'] = 'Email settings updated successfully';
