<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

    if(!isset($_POST['email']) || empty($_POST['email']))
    {

        $data = array(
            'message' => 'Email is required.',
            'error' => true,
        );
        return;

    }

    $email = mysqli_real_escape_string($connect, $_POST['email']);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {

        $data = array(
            'message' => 'Invalid email format.',
            'error' => true,
        );
        return;

    }

    $query = 'SELECT *
        FROM emails
        WHERE email = "'.$email.'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $record = mysqli_fetch_assoc($result);

        $data = array(
            'message' => 'Email already exists.',
            'error' => false,
            'email' => array(
                'email' => $record['email'],
                'hash' => $record['hash'],
                'id' => $record['id'],
            ),
        );

    }
    else
    {

        $hash = string_random();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';

        $query = 'INSERT INTO emails (
                email,
                hash,
                news,
                socials, 
                advanced,
                ip,
                created_at,
                updated_at
            ) VALUES (
                "'.$email.'",
                "'.$hash.'",
                "yes",
                "yes",
                "yes",
                "'.$ip.'",
                NOW(),
                NOW()
            )';
        
        mysqli_query($connect, $query);
        
        $data = array(
            'message' => 'Email added successfully.',
            'error' => false,
            'email' => array(
                'email' => $email,
                'hash' => $hash,
                'id' => mysqli_insert_id($connect),
            ),
        );
    }

}
else
{

    $data = array(
        'message' => 'Invalid request method. POST required.',
        'error' => true,
    );

}