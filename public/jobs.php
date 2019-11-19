<?php

include '../init.php';

$helper = new JobHelper($config);
$actionSuccess = false;

// Application login
if ($_POST['submit'] == 'add') {
    if ($helper->createJob($_POST)) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to create job";
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateJob($_POST)) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to update job";
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteJob($_REQUEST['item'])) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to delete job";
    }
}

// Site/page boilerplate
$site = new site('MAM | Jobs', $errors, $actionSuccess);
init_site($site);

$page = new page();
$site->setPage($page);
$_GET['sidebar-page'] = 2;
$site->addHeader("../includes/navbar.php");

// Start rendering the content
ob_start();

if ($_REQUEST['action'] != 'add') {
    ?>
    <button class="btn btn-success float-right" onclick="window.location = '?action=add'">New Job</button>
    <?php
}
?>
<h1>Manage Jobs</h1>

<?php

if ($_REQUEST['action'] == 'add') {
    $helper->render_newJobForm();
} elseif ($_REQUEST['action'] == 'edit') {
    $helper->render_editJobForm($_REQUEST['item']);
}

$helper->render_jobs(true);

// End rendering the content
$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>
