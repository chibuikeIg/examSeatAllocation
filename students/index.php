<?php 
include  ('../core/server.php'); 
include ('../core/header.php');

$sql = "SELECT * FROM trisub ORDER BY department";
$squery = $db->query($sql);
// $sql = "SELECT  FROM MyGuests";
// $result = $conn->query($sql);

?>
<?php 
//   session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: ../index.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="header">
	<h2>Home Page</h2>
</div>
<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>

<?php
     $server='localhost';
     $dbuser='root';
     $dbpass='';
     $db='examseatallocation';

     $conn=mysqli_connect($server,$dbuser,$dbpass,$db);//database connection
     $student_id = isset($_SESSION['student_id'])? $_SESSION['student_id']:'';
     $sql        = "SELECT * FROM seatno WHERE student_id='$student_id' LIMIT 1";
     $query      = $conn->query($sql);
     $seatNum    = mysqli_fetch_assoc($query);
    
    ?>
    
     <div text-align ="center">
     
     </div>
     <center>
      <p>Hall:<?= $seatNum['hallname']; ?></p>
      <p>Department:<?= $seatNum['department']; ?></p>
      <p>Exam Date: <?= $seatNum['examdate']; ?></p>
      Seat No: <span class="glyphicon glyphicon-eye-open"><?= $seatNum['seat_no'] ?></span>
     </center>

    <?php  if(isset($_POST["submit"])){
    $roll=$_POST["rollno"];
    $sql = "SELECT rollno,stdname,department,year from studenttable where rollno=$roll"; //rollno,studentname department and year is selected here
      $retval = mysqli_query( $conn,$sql );
    $row=mysqli_fetch_array($retval);
        $rollno=$row[0]; #rollnumber
        $stdname=$row[1];#student name
        $department=$row[2]; #department
        $year=$row[3]; #student year
     ?>
     
        <?php
          $sub="SELECT distinct subject from subjecttable where department='$department' AND year='$year'";
          $retvalsubject = mysqli_query( $conn,$sub);
          $count=mysqli_num_rows($retvalsubject);
         while($rows=mysqli_fetch_assoc($retvalsubject))
        {
             $subject=$rows['subject'];
             $number=$rollno;
             $sub=$subject;
             #date retrival
             $dateq="SELECT examdate from trisub where enrollnumber='$number' AND subject='$sub'";
             $retvaldate=mysqli_query($conn,$dateq);
            $rowd=mysqli_fetch_assoc($retvaldate);
               $edate=$rowd['examdate'];
               #$endtime=$rowet['endtime'];
               #starttime is calculated
             $starttimeq="SELECT starttime from trisub where enrollnumber='$rollno' AND subject='$subject'";
             $retvalstarttime=mysqli_query($conn,$starttimeq);
               $rowst=mysqli_fetch_array($retvalstarttime);
               $starttime=$rowst['starttime'];
               #end time calculated
             $endtimeq="SELECT endtime from trisub where enrollnumber='$rollno' AND subject='$subject'";
             $retvalendtime=mysqli_query($conn,$endtimeq);
               $rowet=mysqli_fetch_array($retvalendtime);
               $endtime=$rowet['endtime'];
               #classroom evaluation
             $classq="SELECT classroom from trisub where enrollnumber='$rollno' AND subject='$subject'";
             $retvalclass=mysqli_query($conn,$classq);
             $rowclass=mysqli_fetch_assoc($retvalclass);
             $class=$rowclass['classroom'];
          ?><div class="panel panel-default">
            <div class="panel-body ">
              <form class="form-horizontal  content" role="form" method="post" action="<?php $_PHP_SELF?>">
               <div class="form-group">
                <table class="table table-hover">
                  <tr>
                    <td><b>Classroom :</b><td>
                    <td class="textstyle"><?php echo $class ?></td><!--classroom is display here-->
                  </tr>
                    <tr>
                      <td><b>Exam Date :</b><td>
                      <td class="textstyle"><?php echo $edate ?></td><!--date is display here-->
                    </tr>
                    <tr>
                      <td><b>Time :</b><td>
                      <td class="textstyle"><?php echo $starttime." to ".$endtime ?></td><!--start time and end time is display here-->
                    </tr>
                    <tr>
                      <td><b>Subject Name :</b><td>
                      <td class="textstyle"><?php echo $subject ?></td><!--subject is display here-->
                    </tr>
                  </table>
                </div>
         </form>
  </div>
  </div>
  <?php
} }?>
  </div>
  </div>
  </div>
		
</body>
</html>