<?php
include('../functions.php');
include('functions1.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
   
    <style>
        /* Custom styles go here */
        .container-fluid {
            margin-top: 20px;
        }

        .sidenav {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #d1d5d8;
            padding-top: 20px;
        }

        .sidenav a {
            padding: 8px 8px 8px 16px;
            text-decoration: none;
            font-size: 18px;
            color: #007bff;
            display: block;
        }

        .sidenav a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidenav">
            <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
            <a class="nav-link" href="index.php">Students</a>
            <a class="navbar-brand" href="results.php">Results</a>
            <a class="nav-link" href="attendance.php">Attendance</a>
            <!-- Add more navigation links as needed -->
        </nav>

        <main role="main" class="col-md-10 ml-sm-auto">
            <div class="container mt-4">
                <h1>Student Results</h1>

                <!-- Add New Result Button (Opens Modal) -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addResultModal">
                    Add New Result
                </button>

                <!-- Table -->
                <table class="table table-bordered table-striped mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>studentID</th>
                        <th>Name</th>
                        <th>Unit</th>
                        <th>Semester</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Include functions.php and authenticateUser() here
                    authenticateUser();

                    $conn = connectToDatabase();
                    $sql = "SELECT * FROM results";
                    $result = $conn->query($sql);
                    $results = $result->fetch_all(MYSQLI_ASSOC);
                    $conn->close();

                    foreach ($results as $result):
                        ?>
                        <tr>
                            <td><?php echo $result['student_id']; ?></td>
                            <td><?php echo $result['student_name']; ?></td>
                            <td><?php echo $result['unit']; ?></td>
                            <td><?php echo $result['semester']; ?></td>
                            <td><?php echo $result['grade']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                        onclick="editResultModal(<?php echo $result['student_id']; ?>)">Edit
                                </button>
                                <button class="btn btn-danger btn-sm"
                                        onclick="deleteResult(<?php echo $result['student_id']; ?>)">Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </main>
    </div>
</div>

<!-- Add New Result Modal -->
<div class="modal fade" id="addResultModal" tabindex="-1" role="dialog" aria-labelledby="addResultModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addResultModalLabel">Add New Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your Add New Result Form Goes Here -->
                <form id="addResultForm">
                <div class="form-group">
                        <label for="unit">Student Id:</label>
                <select class="form-control" id="studentId">
                    <?php
                    $conn=connectToDatabase();
                    $sql="select * from users";
                    $query=mysqli_query($conn,$sql);
                    while($row=mysqli_fetch_assoc($query))
                    {
                        echo "<option value='".$row['id']."'>".$row['id']."</option>";
                    }
                    ?>
                    </select>
                </div>
                <div class="form-group">
                        <label for="unit">Student Name:</label>
                <select class="form-control" id="studentName">
                    <?php
                    $conn=connectToDatabase();
                    $sql="select * from users";
                    $query=mysqli_query($conn,$sql);
                    while($row=mysqli_fetch_assoc($query))
                    {
                        echo "<option value='".$row['name']."'>".$row['name']."</option>";
                    }
                    ?>
                    </select>
                </div>
                    <div class="form-group">
                        <label for="unit">Unit:</label>
                        <input type="text" class="form-control" id="unit" name="unit" required>
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester:</label>
                        <input type="text" class="form-control" id="semester" name="semester" required>
                    </div>
                    <div class="form-group">
                        <label for="grade">Grade:</label>
                        <input type="text" class="form-control" id="grade" name="grade" required>
                    </div>
                    <!-- Add more form fields as needed -->
                    <button type="button" class="btn btn-primary" onclick="addNewResult()">Add Result</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Result Modal -->
<div class="modal fade" id="editResultModal" tabindex="-1" role="dialog" aria-labelledby="editResultModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editResultModalLabel">Edit Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your Edit Result Form Goes Here -->
                <form id="editResultForm">
                    <input type="hidden" id="editResultId" name="editResultId">
                    <div class="form-group">
                        <label for="editUnit">Unit:</label>
                        <input type="text" class="form-control" id="editUnit" name="editUnit" required>
                    </div>
                    <div class="form-group">
                        <label for="editSemester">Semester:</label>
                        <input type="text" class="form-control" id="editSemester" name="editSemester" required>
                    </div>
                    <div class="form-group">
                        <label for="editGrade">Grade:</label>
                        <input type="text" class="form-control" id="editGrade" name="editGrade" required>
                    </div>
                    <!-- Add more form fields as needed -->
                    <button type="button" class="btn btn-primary" onclick="saveEditedResult()">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script>
    function editResultModal(resultId) {
        $.ajax({
            type: 'POST',
            url: 'get_result.php',
            data: { id: resultId },
            dataType: 'json', // Specify JSON data type
            success: function (response) {
                // Populate the modal fields with the received data
                $('#editResultId').val(response.id);
                $('#editUnit').val(response.unit);
                $('#editSemester').val(response.semester);
                $('#editGrade').val(response.grade);
                $('#editResultModal').modal('show');
            }
        });
    }

    function deleteResult(resultId) {
        // Confirm deletion and handle via AJAX
        if (confirm('Are you sure you want to delete this result?')) {
            $.ajax({
                type: 'POST',
                url: '../functions.php',
                data: {action: 'deleteResult', id: resultId},
                success: function () {
                    // Reload the page or update the table without the deleted result
                    location.reload();
                }
            });
        }
    }

    function saveEditedResult() {
        // Collect edited data and handle via AJAX
        var editedData = {
            id: $('#editResultId').val(),
            unit: $('#editUnit').val(),
            semester: $('#editSemester').val(),
            grade: $('#editGrade').val()
        };

        $.ajax({
            type: 'POST',
            url: 'functions.php',
            data: { action: 'saveEditedResult', data: editedData },
            success: function (response) {
                // Handle the response from the server
                if (response === 'success') {
                    // If successful, close the modal and provide feedback to the user
                    $('#editResultModal').modal('hide');
                    alert('Result edited successfully');
                    // Reload the page or update the table with the edited result data
                    location.reload();
                } else {
                    // If an error occurred, you might want to display an error message or log the error
                    alert('Error editing result: ' + response);
                }
            }
        });
    }

    // Function to add a new result
    function addNewResult(){
        // Collect data from the form
        var resultData = {
            unit: $('#unit').val(),
            semester: $('#semester').val(),
            grade: $('#grade').val(),
            studentId:$('#studentId').val(),
            studentName:$('#studentName').val()
        };

        // Handle the data via AJAX
        $.ajax({
            type: 'POST',
            url: '../functions.php', // Adjust the URL based on your file structure
            data: { action: 'addNewResult', data: resultData },
            success: function (response) {
                // Handle the response from the server
                if (response === 'success') {
                    // If successful, you might want to provide feedback to the user or perform other actions
                    alert('Result added successfully');
                    // Reload the page or update the table with the new result
                    location.reload();
                } else {
                    // If an error occurred, you might want to display an error message or log the error
                    alert('Error adding result: ' + response);
                }
            }
        });
    }
</script>

</body>
</html>
