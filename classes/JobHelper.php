<?php

class JobHelper
{
    private $config;
    private $conn;

    public function __construct($config)
    {
        $this->config = $config;
        $this->conn = $config['dbo'];
    }

    public function deleteJob($id)
    {
        $handle = $this->conn->prepare('DELETE FROM jobs WHERE JobID = ?');
        $handle->bindValue(1, $id, PDO::PARAM_INT);
        if ($handle->execute()) {
            logMessage("Successfully deleted job");
            return true;
        } else {
            logMessage("Failed to delete job");
            return false;
        }
    }

    public function getJobs()
    {
        $handle = $this->conn->prepare('SELECT * FROM jobs ORDER BY JobTitle');
        $handle->execute();
        return $handle->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateJob($data)
    {
        $handle = $this->conn->prepare('UPDATE jobs SET JobTitle = ?, JobWage = ? WHERE JobID = ?');
        $handle->bindValue(1, $data['title']);
        $handle->bindValue(2, $data['wage']);
        $handle->bindValue(3, $data['id']);
        if ($handle->execute()) {
            logMessage("Successfully updated job");
            return true;
        } else {
            logMessage("Failed to update job");
            return false;
        }
    }

    public function createJob($data)
    {
        $handle = $this->conn->prepare('INSERT INTO jobs (JobTitle, JobWage) VALUES (?, ?)');
        $handle->bindValue(1, $data['title']);
        $handle->bindValue(2, $data['wage']);
        if ($handle->execute()) {
            logMessage("Successfully created new job");
            return true;
        } else {
            logMessage("Failed to create job");
            return false;
        }
    }

    public function render_newJobForm()
    {
        include '../components/newJobForm.php';
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    public function render_editJobForm($id)
    {
        $handle = $this->conn->prepare('SELECT * FROM jobs WHERE JobID = ?');
        $handle->bindValue(1, $id, PDO::PARAM_INT);
        $handle->execute();
        $data = $handle->fetchAll(PDO::FETCH_ASSOC)[0];

        include '../components/editJobForm.php';
    }

    public function render_jobs($edit = false)
    {
        echo '<h2 class="mt-4">Jobs</h2>';
        echo '<table class="table table-hover"><tbody>';
        $data = $this->getJobs();

        if (empty($data)) {
            echo '<tr><th>No jobs, you should get one</th></tr>';
        } else {
            foreach ($data as $key) {
                if ($edit) {
                    ?>
                    <tr class="clickable-row" onclick="window.location = '?action=edit&item=<?php echo $key['JobID']; ?>'" title="<?php echo $key['JobTitle']; ?>"><td>
                    <?php
                } else {
                    echo '<tr><td>';
                }
                echo $key['JobTitle'] . ' ($' . number_format($key['JobWage'], 2) . ')';
                echo '</td></tr>';
            }
        }
        echo '</tbody></table>';
    }
}
