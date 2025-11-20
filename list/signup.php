<?php

define('APP_NAME', 'List');
define('PAGE_TITLE', 'Signup');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/main_header.php');
include('../templates/message.php');

?>

<main>
    
    <div class="w3-center">
        <h1>BrickMMO Email List</h1>
    </div>

    <hr>

    <div class="w3-container w3-center">

        <input 
            class="w3-input w3-border w3-margin-bottom" 
            type="text" 
            placeholder="email@address.com" 
            id="email" 
            name="email"
            style="max-width: 400px; margin: auto; text-align: center;">

        <a
            href="#" 
            class="w3-button w3-white w3-border w3-margin-bottom"
            
            onclick="return validateForm();">
            <i class="fa-solid fa-envelope"></i> Submit
        </a>

        <div id="email-error" style="color: #f00;"></div>

    </div>

    <script>

    function validateForm() {
        let errors = 0;

        let email_pattern = "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$";
        let email = document.getElementById("email");
        let email_error = document.getElementById("email-error");
        email_error.innerHTML = "";
        if (email.value == "") {
            email_error.innerHTML = "(email is required)";
            errors++;
        } else if (!email.value.match(email_pattern)) {
            email_error.innerHTML = "(email is invalid)";
            errors++;
        }

        if (errors) return false;

        fetch('/api/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'email=' + encodeURIComponent(email.value),
        })
        .then(response => response.json())
        .then(data => { 
            // confirmModal('Your email address has been aded to the BrickMMO email list');
            window.location.href = "/preferences/" + data.email.hash;
        });

        return false
        
    }

    </script>

</main>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');