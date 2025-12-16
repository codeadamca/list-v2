<?php

security_check();
admin_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['email']) || !validate_email($_POST['email']))
    {
        message_set('Email Error', 'There was an error with the provided email.', 'red');
        header_redirect('/admin/dashboard');
    }

    // Check if email already exists
    $query = 'SELECT id FROM emails WHERE email = "'.addslashes($_POST['email']).'" LIMIT 1';
    $result = mysqli_query($connect, $query);
    
    if (mysqli_num_rows($result) > 0) {
        message_set('Email Error', 'This email already exists in the system.', 'red');
        header_redirect('/admin/dashboard');
    }

    // Save email details to the database
    $hash = string_random();
    $query = 'INSERT INTO emails (
            email, 
            hash, 
            news,
            socials,
            advanced,
            created_at,
            updated_at
        ) VALUES (
            "'.addslashes($_POST['email']).'",
            "'.$hash.'",
            "'.(isset($_POST['news']) && $_POST['news'] == 'yes' ? 'yes' : 'no').'", 
            "'.(isset($_POST['socials']) && $_POST['socials'] == 'yes' ? 'yes' : 'no').'", 
            "'.(isset($_POST['advanced']) && $_POST['advanced'] == 'yes' ? 'yes' : 'no').'",           
            NOW(),
            NOW()
        )';
    mysqli_query($connect, $query);

    message_set('Email Success', 'Email has been successfully added.');
    header_redirect('/admin/dashboard');
}

define('APP_NAME', 'Mailing List');
define('PAGE_TITLE', 'Add Email');
define('PAGE_SELECTED_SECTION', 'list');
define('PAGE_SELECTED_SUB_PAGE', '/admin/add');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

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
    Add Email
</p>

<hr>

<h2>Add Email</h2>

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
    />
    <label for="email" class="w3-text-gray">
        Email <span id="email-error" class="w3-text-red"></span>
    </label>

    <div class="w3-margin-top">
        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="news" value="yes" checked> 
            <strong>News</strong>
            <br>
            <small>General updates on the Smart City project, funding, events, and application launches.</small>
        </label>

        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="socials" value="yes" checked> 
            <strong>Socials</strong>
            <br>
            <small>Social drop-ins for anyone new to LEGO&reg; or LEGO&reg; experts.</small>
        </label>

        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="advanced" value="yes" checked> 
            <strong>Advanced</strong>
            <br>
            <small>Drop-in sessions for LEGO&reg; experts or aspiring LEGO&reg; experts.</small>
        </label>
    </div>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-plus fa-padding-right"></i>
        Add Email
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

?>