<?php

include 'connect.php';

if(isset($_POST['signUp'])){
    $userName=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

    $checkEmail="SELECT * From users where email='$email'";
    $result=$conn->query($checkEmail);
    if ($result->num_rows>0){
       echo "Email already exists";
    }
    else{
        $insertQuery="INSERT INTO users (username,email,password) 
                      VALUES ('$userName','$email','$password')";
          if($conn->query($insertQuery)==TRUE){
            header("Location: login.php");
          }
          else{
            echo "Error:".$conn->error;
          }
    }


}

if(isset($_POST['login'])){
    $userName=$_POST['username'];
    $password=$_POST['password'];
    $password=md5($password);

    $sql="SELECT * FROM users WHERE username='$userName' and  password='$password'";
    $result=$conn->query($sql);
    if ($result->num_rows>0){
       session_start();
       $row=$result->fetch_assoc();
       $_SESSION['username']=$row['username'];
       header("Location: Group exercise.php");
       exit();
    }
    else{
        echo "Not Found, Incorrect Username or Password";
    }


}
?>