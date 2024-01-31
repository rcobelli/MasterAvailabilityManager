<?php

include '../init.php';

$samlHelper->processSamlInput();

if (!$samlHelper->isLoggedIn()) {
    header("Location: index.php");
    die();
}

$config['type'] = Rybel\backbone\LogStream::console;

$helper = new ShiftHelper($config);

// Boilerplate
$page = new Rybel\backbone\page();
$page->addHeader("../includes/header.php");
$page->addFooter("../includes/footer.php");
$page->addHeader("../includes/navbar.php");

// Application logic
if ($_POST['submit'] == 'add') {
    if ($helper->createShift($_POST)) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateShift($_POST)) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteShift($_REQUEST['item'])) {
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
    <button class="btn btn-success float-right" onclick="window.location = '?action=add'">New Shift</button>
    <?php
}
?>
<h1>Manage Shifts</h1>

<?php

if ($_REQUEST['action'] == 'add') {
    $helper->render_newShiftForm();
} elseif ($_REQUEST['action'] == 'edit') {
    $helper->render_editShiftForm($_REQUEST['item']);
}

if ($_GET['all'] == 'true') {
    echo '<a href="?all=false" class="float-right">Upcoming Shifts</a>';
    $helper->render_all_shifts();
} else {
    echo '<a href="?all=true" class="float-right">All Shifts</a>';
    $helper->render_upcoming_shifts();
}

// End rendering the content
$content = ob_get_clean();
$page->render($content);
