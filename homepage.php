<?php
session_start();
include("connect.php");

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Handle job form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["job-title"])) {
    $title = mysqli_real_escape_string($conn, $_POST["job-title"]);
    $description = mysqli_real_escape_string($conn, $_POST["job-description"]);
    $requirements = mysqli_real_escape_string($conn, $_POST["job-requirements"]);

    $sql = "INSERT INTO jobs (title, description, requirements) VALUES ('$title', '$description', '$requirements')";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Job added successfully');</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HireWift - Recruitment Dashboard</title>
    <link rel="stylesheet" href="hp-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="nav-menu">
                <a href="#dashboard" class="nav-item active" data-page="dashboard">
                    <i class="fas fa-th-large"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#ranking" class="nav-item" data-page="ranking">
                    <i class="fas fa-chart-bar"></i>
                    <span>Ranking</span>
                </a>
                <a href="#manage-job" class="nav-item" data-page="manage-job">
                    <i class="fas fa-briefcase"></i>
                    <span>Manage Job</span>
                </a>
            </nav>
            <div class="logout">
                <a href="logout.php" id="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log out</span>
                </a>

            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Page -->
            <div id="dashboard-page" class="page active">
                <!-- Stats Cards -->
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3>Total Employees</h3>
                            <div class="stat-badge positive">+12.0%</div>
                        </div>
                        <div class="stat-value">52</div>
                        <div class="stat-label">Employee</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3>Total Applicants</h3>
                            <div class="stat-badge positive">+36.0%</div>
                        </div>
                        <div class="stat-value">266</div>
                        <div class="stat-label">Applicants</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <h3>Accepted</h3>
                            <div class="stat-badge positive">+5.0%</div>
                        </div>
                        <div class="stat-value">32</div>
                        <div class="stat-label">Accepted Applicants</div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="chart-section">
                    <h3>Number of New Applicants (Past 7 Days)</h3>
                    <div class="chart-container">
                        <canvas id="applicantsChart"></canvas>
                    </div>
                </div>

                <!-- Bottom Charts -->
                <div class="bottom-charts">
                    <div class="chart-box">
                        <h3>Applicants per Openings (Past 7 Days)</h3>
                        <div class="chart-container">
                            <canvas id="openingsChart"></canvas>
                        </div>
                    </div>
                    <div class="chart-box">
                        <h3>Applicants Status (Past 7 Days)</h3>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ranking Page -->
            <div id="ranking-page" class="page">
                <h2>Ranking</h2>
                <div class="position-selector">
                    <select id="job-position">
                        <option value="treasury">Treasury Assistant</option>
                        <option value="faculty">Faculty Member</option>
                        <option value="guidance">Guidance Counselor</option>
                        <option value="laboratory">Laboratory Assistant</option>
                    </select>
                </div>
                <div class="applicants-table">
                    <table>
                        <thead>
                            <tr>
                                <th>RANK</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Date Submitted</th>
                                <th>Job Compatibility</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="applicants-list">
                            <!-- Applicants will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manage Job Page -->
            <div id="manage-job-page" class="page">
                <div class="manage-header">
                    <h2>Manage Jobs</h2>
                    <button id="add-job-btn" class="btn btn-primary">Add Job</button>
                </div>

                <!-- Jobs Table -->
                <div class="jobs-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="jobs-list">
                        <?php
                            if (isset($_SESSION['email'])) {
                                $email = $_SESSION['email'];
                                $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                                $stmt->bind_param("s", $email);
                                $stmt->execute();
                                $user_result = $stmt->get_result();
                                $user_id = $user_result->fetch_assoc()['id'];

                                $jobs_stmt = $conn->prepare("SELECT * FROM jobs WHERE user_id = ?");
                                $jobs_stmt->bind_param("i", $user_id);
                                $jobs_stmt->execute();
                                $result = $jobs_stmt->get_result();

                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td><button onclick='deleteJob(" . $row['id'] . ")'>Delete</button></td>";
                                    echo "<td><button onclick='editJob(" . $row['id'] . ")'>Edit</button></td>";
                                    echo "</tr>";
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                    
                </div>

                <button id="generate-link-btn" class="btn btn-secondary">Generate Link</button>
            </div>
        </div>
    </div>

    <!-- Add Job Modal -->
    <div id="job-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Job</h2>
            <form id="job-form" method="POST" action="homepage.php">
                <div class="form-group">
                    <label for="job-title">Job Title</label>
                    <input type="text" id="job-title" name="job-title" required>
                </div>
                <div class="form-group">
                    <label for="job-description">Job Description</label>
                    <textarea id="job-description" name="job-description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="job-requirements">Requirements</label>
                    <textarea id="job-requirements" name="job-requirements" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Job</button>
            </form>
        </div>
    </div>

    <!-- Resume Modal -->
    <div id="resume-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Applicant Resume</h2>
            <div id="resume-content"></div>
        </div>
    </div>

    <script src="hp-script.js"></script>
</body>
</html>
