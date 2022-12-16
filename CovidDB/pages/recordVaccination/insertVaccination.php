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
    <a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
	<h1>Insertion to Database</h1>
	<?php
	$OHIP = $_POST["OHIP"];
    $siteName = $_POST["siteName"];
    $lotNumber = $_POST["lotNumber"];
    $date = $_POST["dateAdministered"];
    $time = $_POST["timeAdministered"];
     
	include '../../inc/connectdb.php';

	$insertion = "insert into Vaccinated values (:siteName, :lotNumber, :OHIP, :date, :time)";
	$stmt = $connection->prepare($insertion);
	$stmt->bindParam(':siteName', $siteName);
    $stmt->bindParam(':lotNumber', $lotNumber);
    $stmt->bindParam(':OHIP', $OHIP);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':time', $time);
    try{
        $result = $stmt->execute();
	
	    if ($result == true) {#success
            $query = "SELECT Name, l.Lot_Number, Vaccinated_Date, Company_Name FROM (SELECT * FROM Vaccinated WHERE OHIP_Number=:OHIP) as v INNER JOIN Lot as l ON v.Lot_Number=l.Lot_Number;";
            $stmt = $connection->prepare($query);
            $stmt ->bindParam(':OHIP', $OHIP);
            $results = $stmt->execute();
            ?>
            <h2>Showing <?php echo $nameInfo["First_Name"]." ".$nameInfo["Middle_Name"]." ".$nameInfo["Last_Name"]." OHIP:".$OHIP ?> Vaccination Records</h2>
            <table>
            <tr>
                <th>Company</th>
                <th>Lot</th>
                <th>Site Name</th>
                <th>Date</th>
            </tr>
            <?php
            $doses = 0;
            while ($row = $stmt->fetch()) {
                $doses = $doses + 1;
                ?>
                <tr>
                    <td><?php echo $row["Company_Name"]?></td>
                    <td><?php echo $row["Lot_Number"]?></td>
                    <td><?php echo $row["Name"]?></td>
                    <td><?php echo $row["Vaccinated_Date"]?></td>
                </tr>
            <?php
            }
            ?>
            <h3>This patient has recieved <?php echo $doses ?> valid doses of a COVID-19 vaccine.</h3>
            <?php
        }

    }
    catch (Exception $e){
        echo "Failed insertion";
        ?>
        <br>
        <p>error message:</p>
        <br>
        <?php echo $e;
    }
	?>
    <br>
</body>
</html>