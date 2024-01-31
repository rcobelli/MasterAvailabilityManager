<?php

include '../init.php';

$samlHelper->processSamlInput();

if (!$samlHelper->isLoggedIn()) {
    header("Location: index.php");
    die();
}

$config['type'] = Rybel\backbone\LogStream::console;

$helper = new EventHelper($config);

// Boilerplate
$page = new Rybel\backbone\page();
$page->addHeader("../includes/header.php");
$page->addFooter("../includes/footer.php");
$page->addHeader("../includes/navbar.php");

// Application logic
if ($_POST['submit'] == 'add') {
    if ($helper->createEvent(new EventObject(-1, $_POST['title'], $_POST['company'], $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'], $_POST['hours']))) {
        if ($_POST['createShift'] == 'on') {
            $shiftHelper = new ShiftHelper($config);
            if ($shiftHelper->createShift(array('event' => $helper->getLastEvent(), 'confirmed' => 'on'))) {
                $page->setSuccess(true);
                $_REQUEST['action'] = null;
            } else {
                $page->addError($helper->getErrorMessage());
            }
        } else {
            $page->setSuccess(true);
        }
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_POST['submit'] == 'edit') {
    if ($helper->updateEvent(new EventObject($_POST['id'], $_POST['title'], $_POST['company'], $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'], $_POST['hours']))) {
        $page->setSuccess(true);
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
} elseif ($_REQUEST['action'] == 'delete') {
    if ($helper->deleteEvent($_REQUEST['item'])) {
        $_REQUEST['action'] = null;
    } else {
        $page->addError($helper->getErrorMessage());
    }
}

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
$page->render($content);
