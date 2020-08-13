<?php


$period=7;
$now=time();
$year=date('Y',$now);
$month=date('m',$now);
$day=date('d',$now);
$beforeWeek=(int)$day-$period;
$to=date('Y-m-d',$now); // now or today
$previousWeek=date_create("$year-$month-$beforeWeek");
$from=date_format($previousWeek,'Y-m-d'); //old date from 7 days



function openConnection(){
    $hostName='localhost';
    $userName='root';
    $password='';
    $dbName='click_to_pass_internship';

    $connection=null;
    $connection=mysqli_connect($hostName,$userName,$password,$dbName);
    return ($connection)?$connection:null;
}

function getNotActiveUsers($from,$to){
    $sql="SELECT * FROM users WHERE id not in (SELECT DISTINCT users.id FROM users INNER JOIN posts ON users.id=posts.user_id WHERE posts.created_at BETWEEN '$from' and '$to')";
    $connection=openConnection();
    if($connection==null){
        die('error in connection');
    }else{
        $response=mysqli_query($connection,$sql);
        if($response){
            return $response;
        }
        return null;
    }
}


function sendEmailNotification($name,$email){
    $message="Hello $name, Long Time u didn't post at our App";
    $subject = 'Notification About Posts';
    $headers = "From: Click To Pass";
    if(mail($email,$subject,$message,$headers)){
        echo 'email send successfully';
    }else{
        echo 'failed to send email';
    }
}


$response=getNotActiveUsers($from,$to);

while($row=mysqli_fetch_assoc($response)){
   sendEmailNotification($row['name'],$row['email']);
}



// Make Cron Job To Run Script (index.php) Every 24 hour 
