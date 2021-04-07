<?php

class EventHelper
{
    private $config;
    private $conn;

    public function __construct($config)
    {
        $this->config = $config;
        $this->conn = $config['dbo'];
    }

    public function deleteEvent($id)
    {
        $handle = $this->conn->prepare('DELETE FROM events WHERE EventID = ?');
        $handle->bindValue(1, $id, PDO::PARAM_INT);
        if ($handle->execute()) {
            logMessage("Successfully deleted event");
            return true;
        } else {
            logMessage("Failed to delete event");
            return false;
        }
    }

    public function getEvents($futureOnly = true)
    {
        if ($futureOnly) {
            $sql = 'SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID AND EventDate >= DATE(NOW()) ORDER BY EventDate';
        } else {
            $sql = 'SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID ORDER BY EventDate';
        }

        $handle = $this->conn->prepare($sql);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
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
            $handle = $this->conn->prepare("SELECT ShiftID FROM shifts WHERE EventID = ?");
            $handle->bindValue(1, $datum['EventID'], PDO::PARAM_INT);
            $handle->execute();
            if (empty($handle->fetchAll(PDO::FETCH_ASSOC))) {
                array_push($output, $datum);
            }
        }

        return $output;
    }

    public function getEventsByDate($date)
    {
        $handle = $this->conn->prepare('SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID AND EventDate = ?');
        $handle->bindValue(1, $date);
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

// --Commented out by Inspection START (5/2/20, 9:38 AM):
//    public function getEvent($id)
//    {
//        $handle = $this->conn->prepare('SELECT EventID, EventTitle, EventDate, EventHours, JobTitle FROM events, jobs WHERE jobs.JobID = events.JobID AND EventID = ?');
//        $handle->bindValue(1, $id);
//        $handle->execute();
//        return $handle->fetchAll(PDO::FETCH_ASSOC)[0];
//    }
// --Commented out by Inspection STOP (5/2/20, 9:38 AM)


    public function updateEvent(EventObject $data)
    {
        $handle = $this->conn->prepare('UPDATE events SET EventTitle = ?, JobID = ?, EventDate = ?, EventHours = ? WHERE EventID = ?');
        $handle->bindValue(1, $data->title);
        $handle->bindValue(2, $data->company);
        $handle->bindValue(3, $data->date);
        $handle->bindValue(4, $data->hours);
        $handle->bindValue(5, $data->id);
        if ($handle->execute()) {
            logMessage("Successfully updated event");
            return true;
        } else {
            print_r($handle->errorInfo());
            logMessage("Failed to update event");
            return false;
        }
    }

    public function createEvent(EventObject $data)
    {
        $handle = $this->conn->prepare('INSERT INTO events (EventTitle, JobID, EventDate, EventHours) VALUES (?, ?, ?, ?)');
        $handle->bindValue(1, $data->title);
        $handle->bindValue(2, $data->company);
        $handle->bindValue(3, $data->date);
        $handle->bindValue(4, $data->hours);
        if ($handle->execute()) {
            logMessage("Successfully created new event");
            return true;
        } else {
            logMessage("Failed to create event");
            return false;
        }
    }

    public function getLastEvent() {
       return $this->conn->lastInsertId();
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    public function render_newEventForm()
    {
        $JobHelper = new JobHelper($this->config);
        include '../components/newEventForm.php';
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    public function render_editEventForm($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM events WHERE EventID = ?');
        $handle->bindValue(1, $id, PDO::PARAM_INT);
        $handle->execute();
        $result = $handle->fetchAll(PDO::FETCH_ASSOC)[0];

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
