<?php


$query = 'SELECT *
    FROM emails
    WHERE hash = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result) == 0)
{
    header_redirect('/signup');
}

$record = mysqli_fetch_assoc($result);

define('APP_NAME', 'Mailing List');
define('PAGE_TITLE', 'Update Settings');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/main_header.php');
include('../templates/message.php');

?>

<main>

    <div class="w3-center">
        <h1>List Preferences</h1>
    </div>

    <hr>

    <p>
        You are updating settings for:
        <br>
        <strong>
            <?=$record['email']?>
        </strong>
    </p>

    <label style="display: block; margin-bottom: 10px;">
        <input type="checkbox" name="news" id="news" value="yes" 
            <?=($record['news'] == 'yes' ? 'checked' : '')?>
        > <strong>News</strong>
        <br>
        General updates on the Smart City project, funding, 
        events, and application launches.
    </label>

        <label style="display: block; margin-bottom: 10px;">
            <input type="checkbox" name="socials" id="socials" value="yes" 
                <?=($record['socials'] == 'yes' ? 'checked' : '')?>
            > <strong>Socials</strong>
            <br>
            Social drop-ins for anyone new to LEGO&reg; or 
            LEGO&reg; experts. 
        </label>        <label style="display: block; margin-bottom: 20px;">
            <input type="checkbox" name="advanced" id="advanced" value="yes" 
                <?=($record['advanced'] == 'yes' ? 'checked' : '')?>
            > <strong>Advanced</strong>
            <br>
            Drop-in sessions for LEGO&reg; experts or 
            aspiring LEGO&reg; experts.
        </label>    <a href="#" onclick="return updateSettings(event);" type="submit" class="w3-button w3-white w3-border">
        <i class="fa-solid fa-floppy-disk fa-padding-right"></i>
        Update Settings
    </a>


</main>

<script>
function updateSettings(event) {
    event.preventDefault();
    
    const news = document.getElementById('news').checked ? 'yes' : 'no';
    const socials = document.getElementById('socials').checked ? 'yes' : 'no';
    const advanced = document.getElementById('advanced').checked ? 'yes' : 'no';
    const hash = '<?=$_GET['key']?>';
    
    const formData = new FormData();
    formData.append('hash', hash);
    formData.append('news', news);
    formData.append('socials', socials);
    formData.append('advanced', advanced);
    
    fetch('/api/update', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alertModal('Settings updated successfully!');
            } else {
                alertModal('Error: ' + (data.error || 'Failed to update settings'));
            }
        })
        .catch(error => {
            alertModal('Error: Failed to update settings');
            console.error('Error:', error);
        });
    
    return false;
}
</script>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');