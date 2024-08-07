<?php

use Rybel\backbone\Helper;

class ShiftHelper extends Helper
{
    public function deleteShift($id)
    {
        return $this->query('DELETE FROM shifts WHERE ShiftID = ?', $id);
    }

    public function getShifts($futureOnly = true)
    {
        if ($futureOnly) {
            $sql = 'SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND EventDate > NOW() ORDER BY EventDate';
        } else {
            $sql = 'SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID ORDER BY EventDate';
        }

        return $this->query($sql);
    }

    public function getShiftsByDate($date)
    {
        return $this->query('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND EventDate = ?', $date);
    }

    public function getShiftsByMonth($date)
    {
        return $this->query('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND MONTH(EventDate) = ? AND YEAR(EventDate) = ?', date('m', strtotime($date)), date('Y', strtotime($date)));
    }

    public function getShift($id)
    {
        return $this->query('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND ShiftID = ? LIMIT 1', $id);
    }

    public function updateShift($data)
    {
        if (!empty($data['startTime'])) {
            $data['startTime'] = date('H:i:s', strtotime($data['startTime']));
        } else {
            $data['startTime'] = null;
        }

        return $this->query('UPDATE shifts SET ShiftConfirmed = ?, StartTime = ? WHERE ShiftID = ?; UPDATE events, shifts SET EventHours = ? WHERE shifts.EventID = events.EventID AND ShiftID = ?', $data['confirmation'], $data['startTime'], $data['id'], $data['hours'], $data['id']);
    }

    public function createShift($data)
    {
        return $this->query('INSERT INTO shifts (EventID, ShiftConfirmed) VALUES (?, ?)', $data['event'], $data['confirmed'] == 'on' ? 1 : 0 );
    }

    public function render_newShiftForm()
    {
        $EventHelper = new EventHelper($this->config);
        include '../components/newShiftForm.php';
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    public function render_editShiftForm($id)
    {
        $data = $this->getShift($id);
        include '../components/editShiftForm.php';
    }

    public function render_upcoming_shifts()
    {
        echo '<h2 class="mt-4">Upcoming Shifts</h2>';
        echo '<table class="table table-hover"><tbody>';
        $data = $this->getShifts();

        $this->render_shifts($data);
    }

    public function render_all_shifts()
    {
        echo '<h2 class="mt-4">Shifts</h2>';
        echo '<table class="table table-hover"><tbody>';
        $data = $this->getShifts(false);

        $this->render_shifts($data);
    }

    private function render_shifts($data) {
        if (empty($data)) {
            echo '<tr><th>No shifts</th></tr>';
        } else {
            foreach ($data as $key) {
                ?>
            <tr class="clickable-row" onclick="window.location = '?action=edit&item=<?php echo $key['ShiftID']; ?>'" title="<?php echo $key['ShiftConfirmed'] == 1 ? 'Shift Confirmed' : 'Shift Pending' ?>">
                <?php

                if ($key['ShiftConfirmed'] == 1) {
                    echo '<td class="green">';
                } else {
                    echo '<td class="yellow">';
                }

                echo $key['JobTitle'] . ': ' . $key['EventTitle'] . ' ' . date('m/d/Y', strtotime($key['EventDate']));

                if (!is_null($key['StartTime'])) {
                    echo ' @ ' . date('h:i a', strtotime($key['StartTime']));
                }

                echo '</td></tr>';
            }
        }
        echo '</tbody></table>';
    }
}
