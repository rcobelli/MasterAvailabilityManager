<?php

use Rybel\backbone\Helper;

class JobHelper extends Helper
{
    public function deleteJob($id)
    {
        return $this->query('DELETE FROM jobs WHERE JobID = ?', $id);
    }

    public function getJobs()
    {
        return $this->query('SELECT * FROM jobs ORDER BY JobTitle');
    }

    public function updateJob($data)
    {
        return $this->query('UPDATE jobs SET JobTitle = ?, JobWage = ? WHERE JobID = ?', $data['title'], $data['wage'], $data['id']);
    }

    public function createJob($data)
    {
        return $this->query('INSERT INTO jobs (JobTitle, JobWage) VALUES (?, ?)', $data['title'], $data['wage']);
    }

    public function render_newJobForm()
    {
        include '../components/newJobForm.php';
    }

    /** @noinspection PhpUnusedLocalVariableInspection */
    public function render_editJobForm($id)
    {
        $data = $this->query('SELECT * FROM jobs WHERE JobID = ? LIMIT 1', $id);

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
