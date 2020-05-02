<?php
/** @var $data array */
?>
<hr/>
<form method="post">
    <h2>Edit Job</h2>
    <div class="form-group">
        <label for="input1">Company</label>
        <input name="title" type="text" class="form-control" id="input1" placeholder="Piece of Shit LLC" value="<?php echo $data['JobTitle']; ?>" required>
    </div>
    <div class="form-group">
        <label for="input2">Wage ($)</label>
        <input name="wage" type="number" step="0.01" class="form-control" id="input2" placeholder="7.25" value="<?php echo number_format($data['JobWage'], 2); ?>" required>
    </div>
    <input type="hidden" name="submit" value="edit">
    <input type="hidden" name="id" value="<?php echo $_REQUEST['item']; ?>">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
<button class="btn btn-danger" onclick="window.location = '?action=delete&item=<?php echo $_REQUEST['item']; ?>'">Delete Job</button>
<hr/>
