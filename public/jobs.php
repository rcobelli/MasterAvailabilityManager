<?php

include '../init.php';

$samlHelper->processSamlInput();

if (!$samlHelper->isLoggedIn()) {
    header("Location: index.php");
    die();
}

$config['type'] = Rybel\backbone\LogStream::console;

$helper = new JobHelper($config);

// Boilerplate
$page = new Rybel\backbone\page();
$page->addHeader("../includes/header.php");
$page->addFooter("../includes/footer.php");
$page->addHeader("../includes/navbar.php");

// Application logic
if ($_POST['submit'] == 'add') {
    if ($helper->createJob($_POST)) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateJob($_POST)) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteJob($_REQUEST['item'])) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
}

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
$page->render($content);
