<?php

//getting data
$type = isset($_POST['type']) ? $_POST['type'] : "";
$input = isset($_POST['input']) ? $_POST['input'] : "";
$filePage = $_POST['filePage'];
$data = null;
$totalRows = 10;
if(isset($_POST['nextValue'])){
    $nextValue = $_POST['nextValue'];
}else{
    $nextValue = 0;
}
$limitValue = ($nextValue * $totalRows);
try {
    require "./../../connection.php";

    $statement = $connection->prepare("SELECT * FROM birth_data_deleted WHERE $type like '%$input%' ORDER BY id DESC LIMIT $limitValue , $totalRows");
    $statement->execute();
    $statement->setFetchMode(PDO::FETCH_ASSOC);
    $data = $statement->fetchAll();

    $lastRow = $connection->prepare("SELECT id FROM birth_data_deleted WHERE $type like '%$input%' ORDER BY id DESC LIMIT 1");
    $lastRow->execute();
    $lastRow->setFetchMode(PDO::FETCH_ASSOC);
    $lastRow = $lastRow->fetchAll();
    if(!empty($lastRow[0])){
        $lastRow = $lastRow[0];
    }else{
        $lastRow = 0;
    }
    if(empty($lastRow['id'])){

    }else{
        $lastRowno = (int)$lastRow['id'];
    }
    //$lastRowno = (int)$lastRow['id'];

    $firstRow = $connection->prepare("SELECT id FROM birth_data_deleted WHERE $type like '%$input%' ORDER BY id ASC LIMIT 1");
    $firstRow->execute();
    $firstRow->setFetchMode(PDO::FETCH_ASSOC);
    $firstRow = $firstRow->fetchAll();
    if(!empty($firstRow[0])){
        $firstRow = $firstRow[0];
    }else{
        $firstRow = 0;
    }
    if(empty($firstRow['id'])){
        
    }else{
        $firstRowno = (int)$firstRow['id'];
    }
    
} catch (PDOException $e) {
    echo "<br>please insert text to search<br>";
}
$html = '';
if($filePage == 'deleteDetails.php'){
    $html .= '<button type="button" value="DELETE" class="btn btn-dark" data-mdb-toggle="modal" data-mdb-target="#exampleModal1">DELETE</button>';
}

//opening table
$html .= '
<button type="button" onclick="recoverDeleted()" value="RECOVER" class="btn btn-success">RECOVER DATA</button>
<span class="p-5"></span>
<button type="button" onclick="deletePermanently()" value="DELETE PERMANENTLY" class="btn btn-danger">DELETE PERMANENTLY</button>
            <div class="table-responsive">
            <table class="table table-striped table-hover " style="cursor: pointer;">
                <thead>
                    <tr>
                        ';
                        if($filePage == 'recoverDetails.php'){
                            $html .= '<th scope="col"></th>';
                        }
                        $html .='
                        <th scope="col">#</th>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">?????????</th>
                        <th scope="col">Sex</th>
                        <th scope="col">DOB</th>
                        <th scope="col">Birth Place</th>
                        <th scope="col">???????????????????????????</th>
                        <th scope="col">Father Name</th>
                        <th scope="col">???????????????????????? ?????????</th>
                        <th scope="col">Mother Name</th>
                        <th scope="col">???????????? ?????????</th>
                        <th scope="col">Father\'s Aadhaar No.</th>
                        <th scope="col">Mother\'s Aadhaar No.</th>
                        <th scope="col">Parents Address During Birth</th>
                        <th scope="col">???????????????????????? ??????????????????????????? ???????????????????????? ???????????????</th>
                        <th scope="col">Parents permenant Address</th>
                        <th scope="col">Reg. Date</th>
                    </tr>
                </thead>
            <tbody>
';
$prevbtndisable = 0;
$nextbtndisable = 0;
// adding data to table
if ($data != '') {
    if (count($data) == 0) {
        echo "<br><center><h4 class='text-danger'>No Match found</h4></center><br>";
        $nextbtndisable = 2;
        $prevbtndisable = 2;
        
    } else {
        $sr = 0;
        foreach ($data as $row) {
            $sr++;
            $html .= "<tr onclick='selectRow(this, {$row['id']})'>";
            if($filePage == 'recoverDetails.php'){
                $html .= "<td><input type='checkbox' name='checkboxes[]' onclick='changeColor(this)' value='{$row['id']}' id='{$row['id']}'> </th>";
            }
            $html .= "
                <td scope='row'>$sr</td>
                <td scope='row'>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td class='hinditext'>{$row['name_m']}</td>
                <td>{$row['sex']}</td>
                <td>{$row['DOB']}</td>
                <td>{$row['placeOfBirth']}</td>
                <td class='hinditext'>{$row['placeOfBirth_m']}</td>
                <td>{$row['nameOfFather']}</td>
                <td class='hinditext'>{$row['nameOfFather_m']}</td>
                <td>{$row['nameOfMother']}</td>
                <td class='hinditext'>{$row['nameOfMother_m']}</td>
                <td>{$row['fatherAadharNo']}</td>
                <td>{$row['motherAadharNo']}</td>
                <td>{$row['addressDuringBirth']}</td>
                <td class='hinditext'>{$row['addressDuringBirth_m']}</td>
                <td>{$row['permanentAddressOfParents']}</td>
                <td>{$row['dateOfRegistration']}<td>
            ";
            if($lastRowno == $row['id']){
                $prevbtndisable = 1;
            }
            if($firstRowno == $row['id']){
                $nextbtndisable = 1;
            }
            $html .= '</tr>';
        }

        // closing table
        $html .= '
                </tbody>
            </table>
            </div>
        ';
        if($filePage == 'deleteDetails.php'){
            $html .= '<button type="button" value="DELETE" class="btn btn-dark my-3" data-mdb-toggle="modal" data-mdb-target="#exampleModal1">DELETE</button>';
        }
        $html .= ' 
            <div class="my-3">
                <button type="button" onclick="recoverDeleted()" value="RECOVER" class="btn btn-success">RECOVER DATA</button>
                <span class="p-5"></span>
                <button type="button" onclick="deletePermanently()" value="DELETE" class="btn btn-danger">DELETE PERMANENTLY</button>
            </div>
        ';
        
            // printing table
        echo $html;
    }
} else {
    echo "<br><center><h4 class='text-danger'>No Match found</h4></center><br>";
    $nextbtndisable = 2;
    $prevbtndisable = 2;    
}
echo '<input value="'.$prevbtndisable.'" name="lastrowno" id="lastrowno" hidden>';
echo '<input value="'.$nextbtndisable.'" name="firstrowno" id="firstrowno" hidden>';
