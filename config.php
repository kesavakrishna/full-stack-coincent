<?php
session_start();
$server = 'localhost:3308';
$username ='root';
$password = '';
$database = 'jobs';
$conn = mysqli_connect($server,$username,$password,$database) or die("Connection Failed:".$conn->connect_error);   

//REGISTER 
if(isset($_POST['register_submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['phone_no'];
    $password = $_POST['password'];

    $sql = "INSERT INTO `users`(`Name`, `email`, `password`, `phone_no`) VALUES ('$name','$email','$password','$number')";
    mysqli_query($conn, $sql);
}


//LOGIN 
if(isset($_POST['login_submit'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE `email` = '$email' AND `password` = '$password'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);    
    if(mysqli_num_rows($result)){
        $_SESSION['email'] = $email;
        header("location: index.php");
    }
    else{
        $error  = 'email or password is incorrect';
    }
}

//POSTING JOBS
if(isset($_POST['jobs_submit'])){
    $cname = $_POST['company_name'];
    $position = $_POST['position'];
    $job_desc = $_POST['job_desc'];
    $skills = $_POST['skills'];
    $ctc = $_POST['CTC'];

    $query2 = "INSERT INTO `jobs`(`company_name`, `position`, `job_desc`, `skills`,`CTC`) VALUES ('$cname','$position','$job_desc','$skills','$ctc')";
    $result = mysqli_query($conn, $query2);   
    if($result){
        echo "Inserted Jobs successfully";
    }
    else{
        $error  = 'email or password is incorrect';
    }
}

//CANDIDATES APPLIED
if(isset($_POST['apply_now'])){
    $name = $_POST['name'];
    $apply = $_POST['apply'];
    $qualification = $_POST['qualification'];
    $year = $_POST['year'];
    $file = $_FILES['file'];
    // print_r($file);
    // RESUME UPLOAD VALIDATION
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileTmpName = $_FILES['file']['tmp_name'];

    $fileExt = explode('.', $fileName);
    // echo "<br><br>";
    //print_r($fileExt);

    $fileActualExt = strtolower(end($fileExt));
    //echo "<br><br>";
    //print_r($fileActualExt);

    $fileTypeAllowed = array('pdf', 'doc','docx');

    if (in_array($fileActualExt, $fileTypeAllowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                //Creates Random name for file
                $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                // $filestart = explode('.', $fileNameNew);
                // $fileActualstart = strtolower(reset($filestart)).".".strtolower(next($filestart));
                //Uploads Original Name
                //$fileNameNew =$fileName;
                $fileDestination = 'uploads/' . $fileNameNew;
                move_uploaded_file($fileTmpName, $fileDestination);
                echo "File Uploaded Successfully " . $fileNameNew;
                $query3 = "INSERT INTO `candidates`(`name`, `apply`, `qualification`, `year`, `resume`) VALUES ('$name','$apply','$qualification','$year','$fileNameNew')";
                $result = mysqli_query($conn, $query3); 
                } 
            else {
                echo "Your file size is TOO big ";
            }
        } 
        else {
            echo "There is an error in uploading your file!!!";
        }
    } 
    else {
        echo "You can't upload the file of this type";
    }
      
    if($result){
        echo "Inserted Applicants successfully";
    }
    else{
        $error  = 'incorrect';
    }
}
?>