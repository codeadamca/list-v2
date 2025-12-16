<?php

security_check();
admin_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']))
{
    message_set('Email Error', 'There was an error with the provided email.');
    header_redirect('/admin/dashboard');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['email']) || !validate_email($_POST['email']))
    {
        message_set('Email Error', 'There was an error with the provided email.', 'red');
        header_redirect('/admin/dashboard');
    }
    
    $query = 'UPDATE emails SET
        email = "'.addslashes($_POST['email']).'",
        news = "'.(isset($_POST['news']) && $_POST['news'] == 'yes' ? 'yes' : 'no').'",
        socials = "'.(isset($_POST['socials']) && $_POST['socials'] == 'yes' ? 'yes' : 'no').'",
        advanced = "'.(isset($_POST['advanced']) && $_POST['advanced'] == 'yes' ? 'yes' : 'no').'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Email Success', 'Email has been successfully updated.');
    header_redirect('/admin/dashboard');
    
}

define('APP_NAME', 'Mailing List');
define('PAGE_TITLE', 'Edit Email');
define('PAGE_SELECTED_SECTION', 'list');
define('PAGE_SELECTED_SUB_PAGE', '/admin/edit');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT *
    FROM emails
    WHERE id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);
$record = mysqli_fetch_assoc($result);

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/list.png"
        height="50"
        style="vertical-align: top"
    />
    Mailing List
</h1>
<p>
    <a href="<?=ENV_DOMAIN?>/admin/dashboard">Mailing List</a> / 
    Edit Email
</p>

<hr>

<h2>Edit Email: <?=$record['email']?></h2>

<!-- Edit form -->
<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="email" 
        class="w3-input w3-border" 
        type="email" 
        id="email" 
        autocomplete="off"
        value="<?=$record['email']?>"
    />
    <label for="email" class="w3-text-gray">
        Email <span id="email-error" class="w3-text-red"></span>
    </label>

    <div class="w3-margin-top">
        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="news" value="yes" <?=($record['news'] == 'yes' ? 'checked' : '')?>> 
            <strong>News</strong>
            <br>
            <small>General updates on the Smart City project, funding, events, and application launches.</small>
        </label>

        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="socials" value="yes" <?=($record['socials'] == 'yes' ? 'checked' : '')?>> 
            <strong>Socials</strong>
            <br>
            <small>Social drop-ins for anyone new to LEGO&reg; or LEGO&reg; experts.</small>
        </label>

        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="advanced" value="yes" <?=($record['advanced'] == 'yes' ? 'checked' : '')?>> 
            <strong>Advanced</strong>
            <br>
            <small>Drop-in sessions for LEGO&reg; experts or aspiring LEGO&reg; experts.</small>
        </label>
    </div>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-pencil fa-padding-right"></i>
        Update Email
    </button>

</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            email_error.innerHTML = "(valid email is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

