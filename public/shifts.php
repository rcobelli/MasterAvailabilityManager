<?php

include '../init.php';

$helper = new ShiftHelper($config);
$actionSuccess = false;

// Application login
if ($_POST['submit'] == 'add') {
    if ($helper->createShift($_POST)) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to create shift";
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateShift($_POST)) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to update shift";
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteShift($_REQUEST['item'])) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to delete shift";
    }
}

// Site/page boilerplate
$site = new site('MAM | Shifts', $errors, $actionSuccess);
init_site($site);

$page = new page();
$site->setPage($page);
$_GET['sidebar-page'] = 4;
$site->addHeader("../includes/navbar.php");

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
$page->setContent($content);

$site->render();
?>
