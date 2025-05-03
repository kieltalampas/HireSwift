// Global data store
const appData = {
    currentPage: 'dashboard',
    jobs: [
        { id: 1, title: 'Treasury Assistant' },
        { id: 2, title: 'Faculty Member' },
        { id: 3, title: 'Guidance Counselor' },
        { id: 4, title: 'Laboratory Assistant' }
    ],
    applicants: {
        treasury: [
            { rank: 1, name: 'Ethan Young', email: 'ethan.young@email.com', date: '2023-03-15', score: 95 },
            { rank: 2, name: 'Harper King', email: 'harper.king@email.com', date: '2023-03-16', score: 93 },
            { rank: 3, name: 'Lucas Wright', email: 'lucas.wright@email.com', date: '2023-03-16', score: 91 },
            { rank: 4, name: 'Ella Scott', email: 'ella.scott@email.com', date: '2023-03-15', score: 90 },
            { rank: 5, name: 'Henry Green', email: 'henry.green@email.com', date: '2023-03-16', score: 88 }
        ],
        faculty: [
            { rank: 1, name: 'Olivia Johnson', email: 'olivia.j@email.com', date: '2023-03-14', score: 97 },
            { rank: 2, name: 'Noah Williams', email: 'noah.w@email.com', date: '2023-03-15', score: 94 },
            { rank: 3, name: 'Emma Brown', email: 'emma.b@email.com', date: '2023-03-16', score: 92 }
        ],
        guidance: [
            { rank: 1, name: 'Sophia Davis', email: 'sophia.d@email.com', date: '2023-03-15', score: 96 },
            { rank: 2, name: 'Liam Miller', email: 'liam.m@email.com', date: '2023-03-16', score: 94 }
        ],
        laboratory: [
            { rank: 1, name: 'Ava Wilson', email: 'ava.w@email.com', date: '2023-03-14', score: 98 },
            { rank: 2, name: 'James Taylor', email: 'james.t@email.com', date: '2023-03-15', score: 95 },
            { rank: 3, name: 'Isabella Moore', email: 'isabella.m@email.com', date: '2023-03-16', score: 93 }
        ]
    },
    dashboardData: {
        stats: {
            employees: { count: 52, growth: 12.0 },
            applicants: { count: 266, growth: 36.0 },
            accepted: { count: 32, growth: 5.0 }
        },
        newApplicants: [28, 35, 37, 42, 56, 40, 65],
        applicantsPerOpening: [85, 47, 95, 49],
        applicantsStatus: [32, 213, 21]
    }
};

// Charts object to store chart instances
let charts = {};

// Initialize the application
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    setupEventListeners();
    
    // Initialize dashboard charts
    initDashboard();
    
    // Handle routing
    handleRouting();
});

// Set up event listeners
function setupEventListeners() {
    // Navigation
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const page = this.getAttribute('data-page');
            navigateTo(page);
        });
    });
    
    // Logout button (just for UI, doesn't do anything)
    const logoutBtn = document.getElementById('logout-btn');
    logoutBtn.addEventListener('click', function(e) {
        e.preventDefault();
        alert('Logout functionality is disabled in this demo.');
    });
    
    // Job position selector
    const positionSelect = document.getElementById('job-position');
    positionSelect.addEventListener('change', function() {
        loadApplicants(this.value);
    });
    
    // Add job button and modal
    const addJobBtn = document.getElementById('add-job-btn');
    const jobModal = document.getElementById('job-modal');
    const closeJobModalBtn = jobModal.querySelector('.close');
    
    addJobBtn.addEventListener('click', function() {
        jobModal.style.display = 'block';
    });
    
    closeJobModalBtn.addEventListener('click', function() {
        jobModal.style.display = 'none';
    });
    
    // Job form submission
    const jobForm = document.getElementById('job-form');
    jobForm.addEventListener('submit', function(e) {
        e.preventDefault();
        addNewJob();
    });
    
    // Generate link button
    const generateLinkBtn = document.getElementById('generate-link-btn');
    generateLinkBtn.addEventListener('click', function() {
        generateApplicationLink();
    });
    
    // Resume modal close button
    const resumeModal = document.getElementById('resume-modal');
    const closeResumeModalBtn = resumeModal.querySelector('.close');
    
    closeResumeModalBtn.addEventListener('click', function() {
        resumeModal.style.display = 'none';
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === jobModal) {
            jobModal.style.display = 'none';
        }
        if (event.target === resumeModal) {
            resumeModal.style.display = 'none';
        }
    });
}

// Handle routing
function handleRouting() {
    // Get the hash from the URL
    let hash = window.location.hash.substring(1);
    
    // If there's no hash, use the current page
    if (!hash) {
        hash = appData.currentPage;
        window.location.hash = hash;
    }
    
    // Navigate to the page
    navigateTo(hash);
    
    // Listen for hash changes
    window.addEventListener('hashchange', function() {
        const newHash = window.location.hash.substring(1);
        navigateTo(newHash);
    });
}

// Navigate to a page
function navigateTo(page) {
    // Hide all pages
    const pages = document.querySelectorAll('.page');
    pages.forEach(p => p.classList.remove('active'));
    
    // Show the selected page
    const selectedPage = document.getElementById(`${page}-page`);
    if (selectedPage) {
        selectedPage.classList.add('active');
        appData.currentPage = page;
        
        // Update active nav item
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('data-page') === page) {
                item.classList.add('active');
            }
        });
        
        // Initialize page content
        initPageContent(page);
    }
}

// Initialize page content
function initPageContent(page) {
    switch (page) {
        case 'dashboard':
            initDashboard();
            break;
        case 'ranking':
            initRanking();
            break;
        case 'manage-job':
            initManageJob();
            break;
    }
}

// Initialize dashboard
function initDashboard() {
    // Create charts if they don't exist
    if (!charts.applicantsChart) {
        createApplicantsChart();
    }
    
    if (!charts.openingsChart) {
        createOpeningsChart();
    }
    
    if (!charts.statusChart) {
        createStatusChart();
    }
}

// Create applicants chart
function createApplicantsChart() {
    const applicantsCtx = document.getElementById('applicantsChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (charts.applicantsChart) {
        charts.applicantsChart.destroy();
    }
    
    charts.applicantsChart = new Chart(applicantsCtx, {
        type: 'line',
        data: {
            labels: ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7'],
            datasets: [{
                label: 'New Applicants',
                data: appData.dashboardData.newApplicants,
                borderColor: '#6bde84',
                backgroundColor: 'rgba(107, 222, 132, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#6bde84',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Create openings chart
function createOpeningsChart() {
    const openingsCtx = document.getElementById('openingsChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (charts.openingsChart) {
        charts.openingsChart.destroy();
    }
    
    charts.openingsChart = new Chart(openingsCtx, {
        type: 'bar',
        data: {
            labels: ['Treasury Assistant', 'Faculty Member', 'Guidance Counselor', 'Laboratory Assistant'],
            datasets: [{
                label: 'Applicants',
                data: appData.dashboardData.applicantsPerOpening,
                backgroundColor: '#6bde84',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Create status chart
function createStatusChart() {
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (charts.statusChart) {
        charts.statusChart.destroy();
    }
    
    charts.statusChart = new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Accepted', 'Pending', 'Rejected'],
            datasets: [{
                label: 'Applicants',
                data: appData.dashboardData.applicantsStatus,
                backgroundColor: ['#6bde84', '#4a90e2', '#e25c5c'],
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#a0a8c0'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
}

// Initialize ranking page
function initRanking() {
    // Load applicants for the selected position
    const positionSelect = document.getElementById('job-position');
    loadApplicants(positionSelect.value);
}

// Load applicants for a specific position
function loadApplicants(position) {
    const applicantsList = document.getElementById('applicants-list');
    applicantsList.innerHTML = '';
    
    const applicants = appData.applicants[position];
    
    applicants.forEach(applicant => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${applicant.rank}</td>
            <td>${applicant.name}</td>
            <td>${applicant.email}</td>
            <td>${applicant.date}</td>
            <td><div class="compatibility-score">${applicant.score}</div></td>
            <td><button class="btn btn-secondary view-resume-btn">View Resume</button></td>
        `;
        applicantsList.appendChild(row);
        
        // Add event listener to view resume button
        const viewResumeBtn = row.querySelector('.view-resume-btn');
        viewResumeBtn.addEventListener('click', function() {
            viewResume(applicant);
        });
    });
}

// View an applicant's resume
function viewResume(applicant) {
    const resumeModal = document.getElementById('resume-modal');
    const resumeContent = document.getElementById('resume-content');
    
    // Generate resume content
    resumeContent.innerHTML = `
        <h3>${applicant.name}</h3>
        <p><strong>Email:</strong> ${applicant.email}</p>
        <p><strong>Application Date:</strong> ${applicant.date}</p>
        <p><strong>Compatibility Score:</strong> ${applicant.score}%</p>
        <p><strong>Education:</strong> Bachelor's Degree in Business Administration</p>
        <p><strong>Experience:</strong> 3+ years in related field</p>
        <p><strong>Skills:</strong> Microsoft Office, Communication, Team Management</p>
        <p><strong>References:</strong> Available upon request</p>
    `;
    
    // Show the modal
    resumeModal.style.display = 'block';
}

// Initialize manage job page
function initManageJob() {
    // Load jobs
    loadJobs();
}

// Load jobs
function loadJobs() {
    const jobsList = document.getElementById('jobs-list');
    jobsList.innerHTML = '';
    
    appData.jobs.forEach(job => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${job.id}</td>
            <td>${job.title}</td>
            <td>
                <button class="btn btn-secondary open-btn">Open</button>
                <button class="btn btn-primary manage-btn">Manage</button>
                <button class="btn btn-danger remove-btn">Remove</button>
            </td>
        `;
        jobsList.appendChild(row);
        
        // Add event listeners to buttons
        const openBtn = row.querySelector('.open-btn');
        const manageBtn = row.querySelector('.manage-btn');
        const removeBtn = row.querySelector('.remove-btn');
        
        openBtn.addEventListener('click', function() {
            alert(`Opening job: ${job.title} (ID: ${job.id})`);
        });
        
        manageBtn.addEventListener('click', function() {
            alert(`Managing job: ${job.title} (ID: ${job.id})`);
        });
        
        removeBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this job?')) {
                removeJob(job.id);
            }
        });
    });
}

// Add a new job
function addNewJob() {
    const jobTitle = document.getElementById('job-title').value;
    const jobDescription = document.getElementById('job-description').value;
    const jobRequirements = document.getElementById('job-requirements').value;
    
    // Create new job object
    const newJob = {
        id: appData.jobs.length + 1,
        title: jobTitle,
        description: jobDescription,
        requirements: jobRequirements
    };
    
    // Add to jobs array
    appData.jobs.push(newJob);
    
    // Close modal and reset form
    document.getElementById('job-modal').style.display = 'none';
    document.getElementById('job-form').reset();
    
    // Reload jobs list
    loadJobs();
}

// Remove a job
function removeJob(jobId) {
    // Filter out the job with the given ID
    appData.jobs = appData.jobs.filter(job => job.id !== jobId);
    
    // Reload jobs list
    loadJobs();
}

// Generate application link
function generateApplicationLink() {
    // Generate a random string for the link
    const randomString = Math.random().toString(36).substring(2, 10);
    const link = `https://hirewift.example.com/apply/${randomString}`;
    
    // Show the link
    alert(`Application link generated: ${link}`);
}