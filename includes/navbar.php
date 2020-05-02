<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-3 pl-2 pr-2">
    <div class="container">
        <a class="navbar-brand">Master Availability Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?php
                                        if ($_GET['sidebar-page'] == 1) {
                                            echo 'active';
                                        }
                                    ?>">
                    <a class="nav-link" href="../public/index.php">Dashboard</a>
                </li>
                <li class="nav-item <?php
                                        if ($_GET['sidebar-page'] == 2) {
                                            echo 'active';
                                        }
                                    ?>">
                    <a class="nav-link" href="../public/jobs.php">Manage Jobs</a>
                </li>
                <li class="nav-item <?php
                                        if ($_GET['sidebar-page'] == 3) {
                                            echo 'active';
                                        }
                                    ?>">
                    <a class="nav-link" href="../public/events.php">Manage Events</a>
                </li>
                <li class="nav-item <?php
                                        if ($_GET['sidebar-page'] == 4) {
                                            echo 'active';
                                        }
                                    ?>">
                    <a class="nav-link" href="../public/shifts.php">Manage Shifts</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
