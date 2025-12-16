<?php

security_check();
admin_check();

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    // Build WHERE clause based on selected checkboxes
    $conditions = [];
    
    if (isset($_POST['news']) && $_POST['news'] == 'yes') {
        $conditions[] = 'news = "yes"';
    }
    
    if (isset($_POST['socials']) && $_POST['socials'] == 'yes') {
        $conditions[] = 'socials = "yes"';
    }
    
    if (isset($_POST['advanced']) && $_POST['advanced'] == 'yes') {
        $conditions[] = 'advanced = "yes"';
    }
    
    // Build query
    $query = 'SELECT * FROM emails';
    if (!empty($conditions)) {
        $query .= ' WHERE ' . implode(' OR ', $conditions);
    }
    $query .= ' ORDER BY created_at ASC';
    
    $result = mysqli_query($connect, $query);
    
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="mailing-list-export-' . date('Y-m-d') . '.csv"');
    
    // Output CSV
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Email', 'Hash', 'News', 'Socials', 'Advanced', 'Created At', 'Updated At']);
    
    while ($record = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $record['id'],
            $record['email'],
            $record['hash'],
            $record['news'],
            $record['socials'],
            $record['advanced'],
            $record['created_at'],
            $record['updated_at']
        ]);
    }
    
    fclose($output);
    exit;
}

define('APP_NAME', 'Mailing List');
define('PAGE_TITLE', 'Export Emails');
define('PAGE_SELECTED_SECTION', 'list');
define('PAGE_SELECTED_SUB_PAGE', '/admin/export');

include('../templates/html_header.php');
include('../templates/nav_header.php');
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
    Export Emails
</p>

<hr>

<h2>Export Emails</h2>

<p>Select which mailing lists to export:</p>

<!-- Export form -->
<form
    method="post"
    id="main-form"
>

    <label style="display: block; margin-bottom: 10px;">
        <input type="checkbox" name="news" value="yes" checked id="news"> 
        <strong>News</strong>
        <br>
        General updates on the Smart City project, funding, 
        events, and application launches.
    </label>

    <label style="display: block; margin-bottom: 10px;">
        <input type="checkbox" name="socials" value="yes" checked id="socials"> 
        <strong>Socials</strong>
        <br>
        Social drop-ins for anyone new to LEGO&reg; or 
        LEGO&reg; experts. 
    </label>

    <label style="display: block; margin-bottom: 20px;">
        <input type="checkbox" name="advanced" value="yes" checked id="advanced"> 
        <strong>Advanced</strong>
        <br>
        Drop-in sessions for LEGO&reg; experts or 
        aspiring LEGO&reg; experts.
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-download fa-padding-right"></i>
        Export Emails
    </button>

</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let news = document.getElementById("news");
        let socials = document.getElementById("socials");
        let advanced = document.getElementById("advanced");

        if (!news.checked && !socials.checked && !advanced.checked) {
            alertModal('Please select at least one mailing list to export.');
            return false;
        }

        if (errors) return false;
    }

</script>

<?php

include('../templates/main_footer.php');
include('../templates/html_footer.php');

?>
