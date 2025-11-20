<?php

if(!isset($_GET['key']))
{
    message_set('Email Error', 'Missing email preferences hash.');
    header_redirect('/signup');
}

$query = 'SELECT *
    FROM emails
    WHERE hash = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) == 0)
{
    message_set('Email Error', 'Invalid email preferences hash provided.');
    header_redirect('/signup');
}

$record = mysqli_fetch_assoc($result);

$query = 'UPDATE emails SET
    socials = "no",
    advanced = "no",
    news = "no",
    updated_at = NOW()
    WHERE hash = "'.$_GET['key'].'"
    LIMIT 1';
mysqli_query($connect, $query);

message_set('Unsubscribe Success', 'You have been unsubscribed from all BrickMMO email lists.');
header_redirect('/signup');
