<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iCloudEMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Upload CSV File</h1>
    <div id="response"></div>

    <div class="card">
        <div class="card-body">
            <form id="csvUploadForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="csvFile" class="form-label">Choose CSV File</label>
                    <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
                </div>
                <button type="submit" class="btn btn-primary" id="btn-upload">Upload</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $('#csvUploadForm').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        const fileInput = $('#csvFile')[0].files[0];
        if (!fileInput) {
            alert('Please select a CSV file.');
            return;
        }

        const formData = new FormData();
        formData.append('csvFile', fileInput);
        formData.append('action', 'upload');

        $.ajax({
            url: 'action.php', // Backend endpoint
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function() {
            	$('#btn-upload').text('Uploading...').prop('disabled', true);
            },
            success: function (response) {
                console.log(response);
                $('#csvFile').val(''); // Reset file input
                $('#response').html(`<div class="alert alert-success">${response}</div>`);
            },
            error: function (xhr, status, error) {
                console.log(xhr, status, error);
                $('#response').html(`<div class="alert alert-danger">Error: ${error}</div>`);
            },
            complete: function() {
		        $('#btn-upload').text('Upload').prop('disabled', false);
		    }
        });
    });
});
</script>
</body>
</html>
