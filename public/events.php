<?php

include '../init.php';

$helper = new EventHelper($config);
$actionSuccess = false;

// Application login
if ($_POST['submit'] == 'add') {
    if ($helper->createEvent(new EventObject(-1, $_POST['title'], $_POST['company'], $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'], $_POST['hours']))) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to create event";
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateEvent(new EventObject($_POST['id'], $_POST['title'], $_POST['company'], $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'], $_POST['hours']))) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to update event";
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteEvent($_REQUEST['item'])) {
        $actionSuccess = true;
        $_REQUEST['action'] = null;
    } else {
        $errors[] = "Unable to delete event";
    }
}


// Site/page boilerplate
$site = new site('MAM | Events', $errors, $actionSuccess);
init_site($site);

$page = new page();
$site->setPage($page);
$_GET['sidebar-page'] = 3;
$site->addHeader("../includes/navbar.php");

// Start rendering the content
ob_start();

if ($_REQUEST['action'] != 'add') {
    ?>
    <button class="btn btn-success float-right" onclick="window.location = '?action=add'">New Event</button>
    <?php
}
?>

<h1>Manage Events</h1>

<?php
if ($_REQUEST['action'] == 'add') {
    $helper->render_newEventForm();
} elseif ($_REQUEST['action'] == 'edit') {
    $helper->render_editEventForm($_REQUEST['item']);
}

if ($_GET['all'] == 'true') {
    echo '<a href="?all=false" class="float-right">Upcoming Events</a>';
    $helper->render_all_events();
} else {
    echo '<a href="?all=true" class="float-right">All Events</a>';
    $helper->render_upcoming_events();
}

// End content rendering

$content = ob_get_clean();
$page->setContent($content);

$site->render();
?>
