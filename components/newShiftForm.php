<?php
/** @var $EventHelper EventHelper */
?>
<hr/>
<form method="post">
    <h2 class="mt-4">New Shift</h2>
    <div class="form-group">
        <label for="input3">Event</label>
        <select class="form-control" id="input3" name="event">
            <?php
                $jobs = $EventHelper->getEvents($_GET['all'] != 'true');
                foreach ($jobs as $job) {
                    echo '<option value="' . $job['EventID'] . '" ' . ($job['EventID'] == $_GET['eventID'] ? ' selected' : '') . '>' . $job['JobTitle'] . ': ' . $job['EventTitle'] . ' ' . date('m/d/Y', strtotime($job['EventDate'])) . '</option>';
                }
            ?>
        </select>
    </div>
    <div class="form-group">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="customSwitch1" name="confirmed">
            <label class="custom-control-label" for="customSwitch1">Confirmed</label>
        </div>
    </div>
    <input type="hidden" name="submit" value="add">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
