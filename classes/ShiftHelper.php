<?php

class ShiftHelper
{
    private $config;
    private $conn;

    public function __construct($config)
    {
        $this->config = $config;
        $this->conn = $config['dbo'];
    }

    public function deleteShift($id)
    {
        $handle = $this->conn->prepare('DELETE FROM shifts WHERE ShiftID = ?');
        $handle->bindValue(1, $id, PDO::PARAM_INT);
        if ($handle->execute()) {
            logMessage("Successfully deleted shift");
            return true;
        } else {
            logMessage("Failed to delete shift");
            return false;
        }
    }

    public function getShifts($futureOnly = true)
    {
        if ($futureOnly) {
            $sql = 'SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND EventDate > NOW() ORDER BY EventDate';
        } else {
            $sql = 'SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID ORDER BY EventDate';
        }

        $handle = $this->conn->prepare($sql);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShiftsByDate($date)
    {
        $handle = $this->conn->prepare('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND EventDate = ?');
        $handle->bindValue(1, $date);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShiftsByMonth($date)
    {
        $handle = $this->conn->prepare('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND MONTH(EventDate) = ? AND YEAR(EventDate) = ?');
        $handle->bindValue(1, date('m', strtotime($date)));
        $handle->bindValue(2, date('Y', strtotime($date)));
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShift($id)
    {
        $handle = $this->conn->prepare('SELECT ShiftID, ShiftConfirmed, EventTitle, EventDate, EventHours, JobTitle, JobWage, StartTime FROM shifts, events, jobs WHERE events.EventID = shifts.EventID AND jobs.JobID = events.JobID AND ShiftID = ?');
        $handle->bindValue(1, $id);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function updateShift($data)
    {
        if (!empty($data['startTime'])) {
            $data['startTime'] = date('H:i:s', strtotime($data['startTime']));
        } else {
            $data['startTime'] = null;
        }

        $handle = $this->conn->prepare('UPDATE shifts SET ShiftConfirmed = ?, StartTime = ? WHERE ShiftID = ?; UPDATE events, shifts SET EventHours = ? WHERE shifts.EventID = events.EventID AND ShiftID = ?');
        $handle->bindValue(1, $data['confirmation']);
        $handle->bindValue(2, $data['startTime']);
        $handle->bindValue(3, $data['id']);
        $handle->bindValue(4, $data['hours']);
        $handle->bindValue(5, $data['id']);
        if ($handle->execute()) {
            logMessage("Successfully updated shift");
            return true;
        } else {
            logMessage("Failed to update shift");
            return false;
        }
    }

    public function createShift($data)
    {
        $handle = $this->conn->prepare('INSERT INTO shifts (EventID) VALUES (?)');
        $handle->bindValue(1, $data['event']);
        if ($handle->execute()) {
            logMessage("Successfully created new shift");
            return true;
        } else {
            logMessage("Failed to create shift");
            return false;
        }
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
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
