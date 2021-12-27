<?php
// loading the main  function file.
require_once "functions.php";

// getting the task variable.
$task = '';
if (isset($_GET['task'])){
    $task = $_GET['task'];
}

// getting the seeding information.
$info = '';
if ($task == 'seed'){
    seed();
    $info = 'Seeding is Complete';
}

// getting the error code.
$error = '';
if (isset($_GET['error'])){
    $error = $_GET['error'];
}else{
    $error = 0;
}

// calling the delete function which is been created for deleting a student.
if ($task == 'delete'){
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
    if ($id>0){
        deleteStudent($id);
        header('Location: index.php?task=report');
    }
}

// getting the input field values after authentication.
$fname = '';
$lname = '';
$roll = '';
if (isset($_POST['submit'])){
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, 'roll', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_STRING);


    // Update the existing student.
    if ($id){
        if ($fname != '' && $lname != '' && $roll != ''){
            $result = updateStudent($id, $fname, $lname, $roll);
            if ($result){
                header('Location: index.php?task=report');
            }else{
                $error = 1;
            }
        }
    }else{

        // Add a new student
        if ($fname != '' && $lname != '' && $roll != ''){
            $result = addStudent($fname, $lname, $roll);
            if ($result){
                header('Location: index.php?task=report');
            }else{
                $error = 1;
            }

        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <title>Crud Project</title>
    <style>
        body {
            margin-top: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column column-60 column-offset-20">
            <h2>CRUD Project</h2>
            <p>A Sample Project To perform CRUD Operations using Plain text and PHP</p>
            <?php
            // including the link page
            include_once "nav.php";
            ?>
            <hr>
            <?php
            if ($task != ''){
                // seeding result
                echo $info;
            }
            ?>
        </div>
    </div>
    <?php
    // presenting all students.
    if ($task == 'report') :?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <?php generateReport()?>
            </div>
        </div>
    <?php endif ?>

    <?php
    // showing the error message.
    if ($error == 1) :?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <blockquote>Duplicate Roll Number</blockquote>
            </div>
        </div>
    <?php endif ?>
    <?php
    if ($task == 'add') :
        // showing the add form.
        ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <form action="index.php?task=add" method="POST">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $fname?>">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" value="<?php echo $lname?>">
                    <label for="roll">Roll</label>
                    <input type="number" id="roll" name="roll" value="<?php echo $roll?>">
                    <input type="submit" name="submit" class="button-primary" value="SAVE">
                </form>
            </div>
        </div>
    <?php endif ?>
    <?php
    if ($task == 'edit') :
        // showing the edit form.
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
        $student = getStudent($id);
        if ($student):
        ?>
        <div class="row">
            <div class="column column-60 column-offset-20">
                <form method="POST">
                    <input type="hidden" value="<?php echo $id?>" name="id">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" value="<?php echo $student['fname']?>">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" value="<?php echo $student['lname']?>">
                    <label for="roll">Roll</label>
                    <input type="number" id="roll" name="roll" value="<?php echo $student['roll']?>">
                    <input type="submit" name="submit" class="button-primary" value="update">
                </form>
            </div>
        </div>
    <?php
        endif ;
    endif
    ?>
</div>
<script type="text/javascript" src="script.js"></script>
</body>
</html>