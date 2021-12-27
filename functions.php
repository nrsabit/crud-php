<?php
// setting up the database file.
define('DB_NAME','database.txt');

// function for seeding all data into the database.
function seed(){
    $data = [
        [
            'id'=>1,
           'fname'=>'Hamid',
           'lname'=>'Ahmed',
           'roll'=>5
        ],
        [
            'id'=>2,
            'fname'=>'Tanvir',
            'lname'=>'Hussain',
            'roll'=>9
        ],
        [
            'id'=>3,
            'fname'=>'Hasib',
            'lname'=>'Khan',
            'roll'=>8
        ],
        [
            'id'=>4,
            'fname'=>'Emon',
            'lname'=>'Hasan',
            'roll'=>5
        ],
        [
            'id'=>5,
            'fname'=>'Foysal',
            'lname'=>'Mahamud',
            'roll'=>11
        ],
        [
            'id'=>6,
            'fname'=>'Shamim',
            'lname'=>'Hussain',
            'roll'=>15
        ]
    ];
    $serializedData = serialize($data);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

// function for seeing all students.
function generateReport(){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
?>
    <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
            <th width="25%">Action</th>
        </tr>
        <?php
        foreach ($students as $student){
            ?>
            <tr>
                <td><?php printf('%s %s', $student['fname'], $student['lname'])?></td>
                <td><?php printf('%s',$student['roll'])?></td>
                <td><?php printf('<a href="index.php?task=edit&id=%s">Edit<a/> | <a class="delete" href="index.php?task=delete&id=%s">Delete<a/>',$student['id'],$student['id'])?></td>
            </tr>
            <?php
        }
        ?>
    </table>
<?php
}

// function for add a new student.
function addStudent($fname, $lname, $roll)
{
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $_student) {
        if ($_student['roll'] == $roll) {
            $found = true;
        }
    }
    if (!$found) {
        $newId = getNewId($students);
        $student = [
            'id' => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll' => $roll
        ];
        array_push($students, $student);

        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}


// function for selecting a student.
function getStudent($id)
{
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);
    foreach ($students as $student) {
        if ($student['id'] == $id) {
            return $student;
        }
    }
    return false;
}

// function for updating a existing student.
function updateStudent($id, $fname, $lname, $roll){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $_student) {
        if ($_student['roll'] == $roll && $_student['id'] != $id) {
            $found = true;
        }
    }
    if (!$found) {
        $students[$id - 1]['fname'] = $fname;
        $students[$id - 1]['lname'] = $lname;
        $students[$id - 1]['roll'] = $roll;
        $serializedData = serialize($students);
        file_put_contents(DB_NAME, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}

// function for deleting a student.
function deleteStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = unserialize($serializedData);

    foreach ($students as $offset=>$student) {
        if ($student['id'] == $id) {
         unset($students[$offset]);
        }
    }
    $serializedData = serialize($students);
    file_put_contents(DB_NAME, $serializedData, LOCK_EX);
}

function getNewId($students){
    $maxId = max(array_column($students,'id'));
    return $maxId+1;
}
