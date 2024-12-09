<?php
if(isset($_POST["submit"])) {
    $file_name = $_FILES['fileToUpload']['name'];
    $file_size = $_FILES['fileToUpload']['size'];
    $file_type = $_FILES['fileToUpload']['type'];
    $file_tmp = $_FILES["fileToUpload"]["tmp_name"];
    
    if($file_tmp !== false) {
        move_uploaded_file($file_tmp, 'C:/xampp/htdocs/Plagiarism/' . $file_name);
        
        $path = "C:/xampp/htdocs/Plagiarism/sss.py";
        $api = exec($path);
        $json_data = json_decode($api, true);
        ?>
        
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Plagiarism Report</title>
            <!-- Bootstrap CSS -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="style.css">
        </head>
        <body>
            <!-- Navbar -->
            <nav class="navbar navbar-dark bg-dark">
                <div class="container">
                    <span class="navbar-brand mb-0 h1">Plagiarism Report</span>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container mt-4">
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h3 class="card-title mb-0">Plagiarism Report</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">File Name</th>
                                                <th scope="col">Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(isset($json_data) && is_array($json_data)) {
                                                foreach($json_data as $key => $value) {
                                                    $percentage = $value * 100;
                                                    echo "<tr>";
                                                    echo "<td>$key</td>";
                                                    echo "<td>";
                                                    echo "<div class='progress'>";
                                                    echo "<div class='progress-bar ";
                                                    if($percentage >= 70) {
                                                        echo "bg-danger";
                                                    } elseif($percentage >= 40) {
                                                        echo "bg-warning";
                                                    } else {
                                                        echo "bg-success";
                                                    }
                                                    echo "' role='progressbar' style='width: $percentage%;' aria-valuenow='$percentage' aria-valuemin='0' aria-valuemax='100'>$percentage%</div>";
                                                    echo "</div>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='2' class='text-center'>No data available</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        </body>
        </html>
        
        <?php
    }
}
?>
