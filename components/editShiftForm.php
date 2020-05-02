<?php
/** @var $data array */
?>
<hr/>
<form method="post">
    <h2>Edit Shift</h2>
    <div class="form-group">
        <label for="input3">Event</label>
        <input type="text" readonly class="form-control" id="input3" value="<?php echo $data['JobTitle'] . ': ' . $data['EventTitle'] . ' ' . date('m/d/Y', strtotime($data['EventDate'])); ?>">
    </div>
    <div class="form-group">
        <label for="input4">Hours</label>
        <input type="text" class="form-control" id="input4" value="<?php echo $data['EventHours']; ?>" name="hours" placeholder="8">
    </div>
    <div class="form-group">
        <label for="input5">Start Time</label>
        <input type="time" class="form-control" id="input5" value="<?php echo $data['StartTime']; ?>" name="startTime" placeholder="11:59 PM">
    </div>
    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="confirmation" id="exampleRadios1" value="1" <?php if ($data['ShiftConfirmed'] == 1) {
    echo 'checked';
}?>>
            <label class="form-check-label" for="exampleRadios1">
                Confirmed
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="confirmation" id="exampleRadios2" value="0" <?php if ($data['ShiftConfirmed'] == 0) {
    echo 'checked';
}?>>
            <label class="form-check-label" for="exampleRadios2">
                Not Confirmed
            </label>
        </div>
    </div>

    <input type="hidden" name="submit" value="edit">
    <input type="hidden" name="id" value="<?php echo $_REQUEST['item']; ?>">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
<button class="btn btn-danger" onclick="window.location = '?action=delete&item=<?php echo $_REQUEST['item']; ?>'">Delete Shift</button>
<hr/>
