<hr/>
<form method="post">
    <h2 class="mt-4">New Event</h2>
    <div class="form-group">
        <label for="input1">Title</label>
        <input name="title" type="text" class="form-control" id="input1" placeholder="Sports Ball Game" required>
    </div>
    <div class="form-group">
        <label for="input2">Shift Hours</label>
        <input name="hours" type="number" class="form-control" id="input2" placeholder="8" required>
    </div>
    <label>Date</label>
    <div class="form-row">
        <div class="col-2">
            <input name="month" type="number" max="12" min="1" aria-describedby="help2" class="form-control" placeholder="10" required value="<?php echo !empty($_GET['date']) ? date('m', strtotime($_GET['date'])) : '' ?>">
            <small id="help2" class="form-text text-muted">Month</small>
        </div>
        /
        <div class="col-2">
            <input name="day" type="number" max="31" min="1" aria-describedby="help2" class="form-control" placeholder="15" required value="<?php echo !empty($_GET['date']) ? date('d', strtotime($_GET['date'])) : '' ?>">
            <small id="help2" class="form-text text-muted">Day</small>
        </div>
        /
        <div class="col-2">
            <input name="year" type="number" max="2099" min="2019" aria-describedby="help2" class="form-control" placeholder="<?php echo date('Y'); ?>" required  value="<?php echo !empty($_GET['date']) ? date('Y', strtotime($_GET['date'])) : '' ?>">
            <small id="help2" class="form-text text-muted">Year</small>
        </div>
    </div>
    <div class="form-group">
        <label for="input3">Company</label>
        <select class="form-control" id="input3" name="company">
            <?php
                $jobs = $JobHelper->getJobs();
                foreach ($jobs as $job) {
                    echo '<option value="' . $job['JobID'] . '">' . $job['JobTitle'] . '</option>';
                }
            ?>
        </select>
    </div>
    <input type="hidden" name="submit" value="add">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
