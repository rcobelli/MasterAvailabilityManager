<?php
/** @var $data EventObject */
/** @var $JobHelper JobHelper */
?>
<hr/>
<form method="post">
    <h2>Edit Event</h2>
    <div class="form-group">
        <label for="input1">Title</label>
        <input name="title" type="text" class="form-control" id="input1" placeholder="Sports Ball Game" value="<?php echo $data->title; ?>"  required>
    </div>
    <div class="form-group">
        <label for="input2">Shift Hours</label>
        <input name="hours" type="number" class="form-control" id="input2" placeholder="8" value="<?php echo $data->hours; ?>" required>
    </div>
    <label>Date</label>
    <div class="form-row">
        <div class="col-2">
            <input name="month" type="number" max="12" min="1" aria-describedby="help2" class="form-control" placeholder="10" value="<?php echo $data->getMonth(); ?>" required>
            <small id="help2" class="form-text text-muted">Month</small>
        </div>
        /
        <div class="col-2">
            <input name="day" type="number" max="31" min="1" aria-describedby="help2" class="form-control" placeholder="15" value="<?php echo $data->getDay(); ?>" required>
            <small id="help2" class="form-text text-muted">Day</small>
        </div>
        /
        <div class="col-2">
            <input name="year" type="number" max="2099" min="<?php echo date('Y'); ?>" aria-describedby="help2" class="form-control" placeholder="<?php echo date('Y'); ?>" value="<?php echo $data->getYear(); ?>"  required>
            <small id="help2" class="form-text text-muted">Year</small>
        </div>
    </div>
    <div class="form-group">
        <label for="input3">Job</label>
        <select class="form-control" id="input3" name="company">
            <?php
                $jobs = $JobHelper->getJobs();
                foreach ($jobs as $job) {
                    echo '<option value="' . $job['JobID'] . '" ';
                    if ($job['JobID'] == $data->company) {
                        echo 'selected';
                    }
                    echo '>' . $job['JobTitle'] . '</option>';
                }
            ?>
        </select>
    </div>
    <input type="hidden" name="submit" value="edit">
    <input type="hidden" name="id" value="<?php echo $_REQUEST['item']; ?>">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
<button class="btn btn-danger" onclick="window.location = '?action=delete&item=<?php echo $_REQUEST['item']; ?>'">Delete Event</button>
<hr/>
