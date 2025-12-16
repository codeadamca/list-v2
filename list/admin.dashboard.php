<?php

security_check();
admin_check();

if (isset($_GET['delete'])) 
{
    $query = 'DELETE FROM emails 
        WHERE id = '.$_GET['delete'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Delete Success', 'Email has been deleted.');
    header_redirect('/admin/dashboard');
}

define('APP_NAME', 'Mailing List');
define('PAGE_TITLE', 'Dashboard');
define('PAGE_SELECTED_SECTION', 'admin-dashboard');
define('PAGE_SELECTED_SUB_PAGE', '/admin/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');    

$query = 'SELECT * 
    FROM emails
    ORDER BY created_at DESC';    
$result = mysqli_query($connect, $query);

$emails_count = mysqli_num_rows($result);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/list.png"
        height="50"
        style="vertical-align: top"
    />
    Mailing List
</h1>

<p>
    Number of emails: <span class="w3-tag w3-blue"><?=$emails_count?></span>    
</p>

<hr />

<h2>Email List</h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th>Email</th>
        <th class="bm-table-icon">News</th>
        <th class="bm-table-icon">Socials</th>
        <th class="bm-table-icon">Advanced</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while ($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <?=$record['email'] ?>
            </td>
            <td class="w3-center">
                <?php if($record['news'] == 'yes'): ?>
                    <i class="fa-solid fa-check w3-text-green"></i>
                <?php else: ?>
                    <i class="fa-solid fa-xmark w3-text-red"></i>
                <?php endif; ?>
            </td>
            <td class="w3-center">
                <?php if($record['socials'] == 'yes'): ?>
                    <i class="fa-solid fa-check w3-text-green"></i>
                <?php else: ?>
                    <i class="fa-solid fa-xmark w3-text-red"></i>
                <?php endif; ?>
            </td>
            <td class="w3-center">
                <?php if($record['advanced'] == 'yes'): ?>
                    <i class="fa-solid fa-check w3-text-green"></i>
                <?php else: ?>
                    <i class="fa-solid fa-xmark w3-text-red"></i>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?=ENV_DOMAIN?>/admin/edit/<?=$record['id'] ?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
            <td>
                <a href="#" onclick="return confirmModal('Are you sure you want to delete the email <?=$record['email'] ?>?', '/admin/dashboard/delete/<?=$record['id'] ?>');">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a
    href="<?=ENV_DOMAIN?>/admin/add"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-pen-to-square fa-padding-right"></i> Add Email
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
