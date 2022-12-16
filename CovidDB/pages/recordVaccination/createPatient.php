<!DOCTYPE html>
<html>
<!-- Evan Kilburn -->
<head>
	<title>Covid</title>
	<meta charset="utf-8"/>
	<meta name="author" content="Evan Kilburn"/>
    <link rel="stylesheet" href="../../css/styles.css"> 
</head>
<body>
	<h2>Add New Patient</h2>
	<?php
	$OHIP = $_POST["OHIP"];
    $birthdate = $_POST["birthdate"];
    $firstName = $_POST["firstName"];
    $middleName = $_POST["middleName"];
    $lastName = $_POST["lastName"];
     
	include '../../inc/connectdb.php';

	$insertion = "insert into Patient values (:OHIP, :birthdate, :firstName, :middleName, :lastName)";
	$stmt = $connection->prepare($insertion);
	$stmt->bindParam(':OHIP', $OHIP);
    $stmt->bindParam(':birthdate', $birthdate);
    $stmt->bindParam(':firstName', $firstName);
    $stmt->bindParam(':middleName', $middleName);
    $stmt->bindParam(':lastName', $lastName);
    try{
        $result = $stmt->execute();
	
	    if ($result == true) {
            echo "Patient successfully created!";
        }

    }
    catch (Exception $e){
        echo "Failed to create patient";
        ?>
        <br/>
        <p>error message:</p>
        <br/>
        <?php echo $e;
    }
	?>
    <br/>
    <a href="/CovidDB/pages/covid.php"><button>Return Home</button></a>
</body>
</html>