<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>kbc assignment</title>
</head>
<body>
    <style>
        img{
            height: 30px;
            width: 30px;
        }

    </style>
   <div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
                <h1>Input Directory Path</h1>
                <form method="post" action="<?php $_PHP_SELF ?>" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <label> Enter Directory</label>
                         <input class="form-control"type="text" name="path">
                    </div>
                    <br>
                    <div>
                        <button  class="btn btn-primary" type="submit">Extract</button>
                    </div>
                      
                </form>
        </div>
        <div class="col-md-8">
                <h1>Files Table</h1>
                    <table class="table">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">File Name</th>
                                <th scope="col">File  Size</th>
                                <th scope="col">Last Modified</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                    $id = 1; // the serial number
                                    
                                    //  submitting of the input data
                                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                                
                                                $path = $_REQUEST["path"];

                                                if(is_dir($path)) {

                                                    $servername = "localhost";
                                                    $username = "root";
                                                    $password = "";
                                                    $dbname = "kbc";

                                                    $connection = new mysqli($servername, $username, $password, $dbname);

                                                    if($connection->connect_error){
                                                    die("Connection failed: " . $conn->connect_error);
                                                    };

                                                    $sql = "CREATE TABLE IF NOT EXISTS files ( id INT AUTO_INCREMENT PRIMARY KEY,filename VARCHAR(255) NOT NULL,filesize VARCHAR(255) NOT NULL,last_modified DATETIME NOT NULL)";
                                                    if ($connection->query($sql) === FALSE) {
                                                        echo 'Table created';
                                                    }

                                                    $files = scandir($path);
                                                    foreach ($files as $file) {
                                                        // echo $file;

                                                        if($file != "." && $file != ".."){

                                                            $filePath = $path . "/" . $file;
                                                            
                                                            // $filesize = filesize($filePath);
                                                            $filesize = human_filesize(filesize($filePath));
                                                            
                                                            $lastModified = date("Y-m-d H:i:s", filemtime($filePath));
                                                            $insertSQL = "INSERT INTO files (filename, filesize, last_modified) VALUES ('$file', '$filesize', '$lastModified')";

                                                            // check if error occured
                                                            if ($connection->query($insertSQL) === FALSE) {
                                                                echo "Error inserting record: " . $conn->error;
                                                            }
                                                        }
                                                    }
                                                
                                                            
                                                //Fetching Database Table;
                                                
                                                $query = "SELECT * FROM `files`";
                                                $sql = $connection->query($query);
                                                if ($sql) {
                                                    while($row = mysqli_fetch_assoc($sql)) {
                                                        echo "<tr>";
                                                        echo "<th>". $id; $id++ ."</th>";
                                                        echo "<td>"."<img src='assets/file_icon.png'>" . $row['filename'] ."</td>";
                                                        echo"<td>" . $row['filesize'] . "</td>";
                                                        echo"<td>". $row['last_modified'] . "</td>";
                                                        echo"</tr>";
                                                    }
                                                }else{
                                                    echo "Read not Responding";
                                                }
                                            
                                            
                                                }else{
                                                    echo "this is not a directory";
                                                }
                                            }
                                            
                                        
                                        


                                                function human_filesize($bytes, $decimals = 2) {
                                                    $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
                                                    $factor = floor((strlen($bytes) - 1) / 3);
                                                    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
                                                }   
                                ?>
                            </tbody>
                    </table>
        </div>
    </div>
   </div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>