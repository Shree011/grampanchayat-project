<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['typePassword'])) {
        $typePassword = $_POST['typePassword'];
        if (isset($_SESSION['userLogin'])) {
            $data = $_SESSION['userLogin'];
            $data = json_decode($data);
            $data = json_decode($data);
            if (!password_verify($typePassword, $data->password)) {
                $_SESSION["deleteSuccess"] = 2;
                echo '
                <script> 
                    location.href="../deleteDetails.php";
                </script>
                ';
            } else {
                if (isset($_POST['checkboxes'])) {

                    $value = $_POST['checkboxes'];
                    // print_r($value);

                    if ($value != "") {
                        require "./../../connection.php";
                        try {
                            require "./../../connection.php";

                            foreach ($value as $id) {
                                //gettind data
                                $statement = $connection->prepare("SELECT * FROM birth_data WHERE id='$id';");
                                $statement->execute();
                                $statement->setFetchMode(PDO::FETCH_ASSOC);
                                $data = $statement->fetchAll();

                                if (count($data) != 0) {
                                    $data = $data[0];

                                    $id                             =    $data['id'];
                                    $name                           =    $data['name'];
                                    $name_m                         =    $data['name_m'];
                                    $sex                            =    $data['sex'];
                                    $DOB                            =    $data['DOB'];
                                    $placeOfBirth                   =    $data['placeOfBirth'];
                                    $placeOfBirth_m                 =    $data['placeOfBirth_m'];
                                    $nameOfMother                   =    $data['nameOfMother'];
                                    $nameOfMother_m                 =    $data['nameOfMother_m'];
                                    $nameOfFather                   =    $data['nameOfFather'];
                                    $nameOfFather_m                 =    $data['nameOfFather_m'];
                                    $motherAadharNo                 =    $data['motherAadharNo'];
                                    $fatherAadharNo                 =    $data['fatherAadharNo'];
                                    $addressDuringBirth             =    $data['addressDuringBirth'];
                                    $addressDuringBirth_m           =    $data['addressDuringBirth_m'];
                                    $permanentAddressOfParents      =    $data['permanentAddressOfParents'];
                                    $permanentAddressOfParents_m    =    $data['permanentAddressOfParents_m'];
                                    $remark                         =    $data['remark'];
                                    $dateOfRegistration             =    $data['dateOfRegistration'];
                                    $dateOfIssue                    =    $data['dateOfIssue'];

                                    //inserting datat to birth_data_deleted
                                $query = "INSERT INTO birth_data_deleted(
                                id,
                                name, 
                                name_m,
                                sex,
                                DOB,
                                placeOfBirth,
                                placeOfBirth_m,
                                nameOfMother,
                                nameOfMother_m,
                                nameOfFather,
                                nameOfFather_m,
                                motherAadharNo,
                                fatherAadharNo,
                                addressDuringBirth,
                                addressDuringBirth_m,
                                permanentAddressOfParents,
                                permanentAddressOfParents_m,
                                remark,
                                dateOfRegistration,
                                dateOfIssue
                            ) VALUES(
                                '$id',
                                '$name', 
                                '$name_m',
                                '$sex',
                                '$DOB',
                                '$placeOfBirth',
                                '$placeOfBirth_m',
                                '$nameOfMother',
                                '$nameOfMother_m',
                                '$nameOfFather',
                                '$nameOfFather_m',
                                '$motherAadharNo',
                                '$fatherAadharNo',
                                '$addressDuringBirth',
                                '$addressDuringBirth_m',
                                '$permanentAddressOfParents',
                                '$permanentAddressOfParents_m',
                                '$remark',
                                '$dateOfRegistration',
                                '$dateOfIssue'
                            );";

                                    $stm = $connection->prepare($query);
                                    $stm->execute();

                                    $sql = "DELETE FROM birth_data WHERE id=$id";
                                    $connection->exec($sql);
                                    $_SESSION["deleteSuccess"] = 1;
                                }
                            }

                            echo '
                            <script> 
                                location.href="../deleteDetails.php";
                            </script>
                            ';
                        } catch (PDOException $e) {
                            echo "Operation failed<br>" . $e;
                        }
                    } else {
                        echo '
                        <script> 
                            location.href="../deleteDetails.php";
                        </script>
                        ';
                    }
                } else {
                    echo '
                    <script> 
                        location.href="../deleteDetails.php";
                    </script>
                    ';
                }
            }
        } else {
            echo "<script>location.href = '/'<script>";
        }
    } else {
        echo '
        <script> 
            location.href="../deleteDetails.php";
        </script>
        ';
    }
} else {
    echo '
    <script> 
        location.href="../deleteDetails.php";
    </script>
    ';



    // if (isset($_GET['id'])) {
    //     $id = $_GET['id'];

    // } else {
    //showErr
    // }

}
