// Show Add Job Modal
document.getElementById('add-job-btn').addEventListener('click', function () {
    document.getElementById('job-modal').style.display = 'block';
});

// Close Modals
document.querySelectorAll('.modal .close').forEach(btn => {
    btn.onclick = () => {
        btn.closest('.modal').style.display = 'none';
    };
});

// Submit Job Form
document.getElementById('job-form').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('add_job.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        if (response === "success") {
            alert("Job added successfully!");
            this.reset();
            document.getElementById('job-modal').style.display = 'none';
            loadJobs();
        } else {
            alert("Error adding job.");
        }
    });
});

// Load Jobs
function loadJobs() {
    fetch('get_jobs.php')
    .then(res => res.json())
    .then(jobs => {
        const tbody = document.getElementById('jobs-list');
        tbody.innerHTML = '';
        jobs.forEach(job => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${job.id}</td>
                <td>${job.title}</td>
                <td>
                    <button onclick="editJob(${job.id}, '${job.title.replace(/'/g, "\\'")}')">Edit Job</button>
                    <button onclick="deleteJob(${job.id})">Delete Job</button>
                </td>
            `;


            tbody.appendChild(tr);
        });
    });

}


// Delete Job
function deleteJob(id) {
    if (confirm("Are you sure you want to delete this job?")) {
        fetch('delete_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + id
        })
        .then(res => res.text())
        .then(response => {
            if (response === "success") {
                alert("Job deleted.");
                loadJobs();
            } else {
                alert("Failed to delete.");
            }
        });
    }
}

function editJob(id, title) {
    const newTitle = prompt("Edit job title:", title);
    if (newTitle !== null && newTitle.trim() !== "") {
        const formData = new URLSearchParams();
        formData.append('id', id);
        formData.append('title', newTitle);

        fetch('edit_job.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        })
        .then(res => res.text())
        .then(response => {
            if (response === "success") {
                alert("Job updated.");
                loadJobs();
            } else {
                alert("Failed to update.");
            }
        });
    }
}


document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function (e) {
        e.preventDefault();


        document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
        document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));


        this.classList.add('active');

        const pageId = this.getAttribute('data-page') + '-page';
        const page = document.getElementById(pageId);
        if (page) {
            page.classList.add('active');
        }
    });
});
