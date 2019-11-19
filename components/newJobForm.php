<hr/>
<form method="post">
    <h2 class="mt-4">New Job</h2>
    <div class="form-group">
        <label for="input1">Company</label>
        <input name="title" type="text" class="form-control" id="input1" placeholder="Piece of Shit LLC" required>
    </div>
    <div class="form-group">
        <label for="input2">Wage ($)</label>
        <input name="wage" type="number" step="0.01" class="form-control" id="input2" placeholder="7.25" required>
    </div>
    <input type="hidden" name="submit" value="add">
    <button type="submit" class="btn btn-primary mt-3">Submit</button>
</form>
