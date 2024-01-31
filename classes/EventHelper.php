<?php

use Rybel\backbone\Helper;

class EventHelper extends Helper
{
    public function deleteEvent($id)
    {
        return $this->query('DELETE FROM events WHERE EventID = ?', $id);
    }

    public function getEvents($futureOnly = true)
    {
        if ($futureOnly) {
            $sql = 'SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID AND EventDate >= DATE(NOW()) ORDER BY EventDate';
        } else {
            $sql = 'SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID ORDER BY EventDate';
        }

        return $this->query($sql);
    }

    public function getUnusedEvents($futureOnly = true)
    {
        $data = $this->getEvents($futureOnly);

        if ($data === false) {
            return false;
        }

        $output = array();

        foreach ($data as $datum) {
            // Check to see if there is already a shift for this event
            $handle = $this->query("SELECT ShiftID FROM shifts WHERE EventID = ?", $datum['EventID']);
            if (empty($handle)) {
                array_push($output, $datum);
            }
        }

        return $output;
    }

    public function getEventsByDate($date)
    {
        return $this->query('SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID AND EventDate = ?', $date);
    }


    public function updateEvent(EventObject $data)
    {
        return $this->query('UPDATE events SET EventTitle = ?, JobID = ?, EventDate = ?, EventHours = ? WHERE EventID = ?', $data->title, $data->company, $data->date, $data->hours, $data->id);
    }

    public function createEvent(EventObject $data)
    {
        return $this->query('INSERT INTO events (EventTitle, JobID, EventDate, EventHours) VALUES (?, ?, ?, ?)', $data->title, $data->company, $data->date, $data->hours);
    }

    public function getLastEvent() {
       return $this->conn->lastInsertId();
    }

    public function render_newEventForm()
    {
        $JobHelper = new JobHelper($this->config);
        include '../components/newEventForm.php';
    }

    public function render_editEventForm($id)
    {
        $result = $this->query('SELECT * FROM events WHERE EventID = ?', $id);

        if (!empty($result)) {
            $data = new EventObject($result['EventID'], $result['EventTitle'], $result['JobID'], $result['EventDate'], $result['EventHours']);
            $JobHelper = new JobHelper($this->config);
            include '../components/editEventForm.php';
        }
    }

    public function render_upcoming_events()
    {
        echo '<h2 class="mt-4">Upcoming Events</h2>';
        $this->render_events($this->getEvents());
    }

    public function render_all_events()
    {
        echo '<h2 class="mt-4">Events</h2>';
        $this->render_events($this->getEvents(false));
    }

    private function render_events($data)
    {
        echo '<table class="table table-hover"><tbody>';
        if (empty($data)) {
            echo '<tr><th>No Events</th></tr>';
        } else {
            echo '<tr><th style="width: 12.5%;">Date</th><th style="width: 17.5%;">Company</th><th>Event</th><th style="width: 5%;">Hours</th></tr>';
            foreach ($data as $key) {
                ?>
                <tr class="clickable-row" onclick="window.location = '?action=edit&item=<?php echo $key['EventID']; ?>'" title="<?php echo $key['EventTitle']; ?>"><td>
                <?php

                echo date('m/d/Y', strtotime($key['EventDate'])) . '</td><td>';
                echo $key['JobTitle'] . '</td><td>';
                echo $key['EventTitle'] . '</td><td>';
                echo $key['EventHours'] . '</td></tr>';
            }
        }
        echo '</tbody></table>';
    }
}
