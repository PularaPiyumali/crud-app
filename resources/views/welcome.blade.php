<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
<div class="container mt-5 mb-5">
    <h4 class="text-center mb-3">Import Users from Excel</h4>
    <form action="{{ url('upload-excel') }}" method="POST" enctype="multipart/form-data" class="d-flex justify-content-center gap-2">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls,.csv" class="form-control w-50" required>
        <button type="submit" class="btn btn-dark">Upload & Import</button>
    </form>
    </div>
<div class="container mt-5">
    <h2 class="text-center mb-4">User Data</h2>

    <table class="table table-bordered table-striped text-center">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Created At</th>
        </tr>
        </thead>
        <tbody id="table-body">
        </tbody>
    </table>

    <div class="d-flex justify-content-center gap-3 mt-3">
        <button class="btn" style="background-color: black; color: white;" id="prevBtn">Previous</button>
        <button class="btn" style="background-color: black; color: white;" id="nextBtn">Next</button>
    </div>

    <div class="text-center mt-4">
        <a class="btn" style="background-color: black; color: white;" href="{{url('download')}}">Download Excel</a>
        <a class="btn" style="background-color: black; color: white;" href="{{url('view-users-pdf')}}">View PDF</a>
        <a class="btn" style="background-color: black; color: white;" href="{{url('export-users-pdf')}}">Download PDF</a>
    </div>
</div>

<script>
let apiURL = "http://localhost:8000/api/users";  

function loadUsers(url) {
    fetch(url)
        .then(res => res.json())
        .then(data => {
            const users = data.users.data;
            const tbody = document.getElementById("table-body");
            tbody.innerHTML = "";

            users.forEach(user => {
                const row = `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>${user.created_at}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            document.getElementById("nextBtn").disabled = data.users.next_page_url === null;
            document.getElementById("prevBtn").disabled = data.users.prev_page_url === null;

            document.getElementById("nextBtn").onclick = () => loadUsers(data.users.next_page_url);
            document.getElementById("prevBtn").onclick = () => loadUsers(data.users.prev_page_url);
        })
        .catch(err => console.error("Error:", err));
}

loadUsers(apiURL);
</script>

</body>
</html>
