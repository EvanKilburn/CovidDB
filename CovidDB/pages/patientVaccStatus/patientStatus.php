<!DOCTYPE html>
<html lang="en">
<!-- Evan Kilburn -->
<head>
	<title>Patient Status</title>
	<meta charset="utf-8"/>
	<meta name="author" content="Evan Kilburn"/>
    <link rel="stylesheet" href="../../css/styles.css"> 
</head>
<body>
    <?php
        include '../../inc/connectdb.php';
        $OHIP = $_POST["OHIP"];
    ?>
    <a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
    <h1>Check Vaccination Status</h1>
    <br>
    <?php
    $nameQuery = "SELECT OHIP_Number, First_Name, Middle_Name, Last_Name FROM Patient";
	$stmtName = $connection->prepare($nameQuery);
    ?>
    <form method="post" action="patientStatus.php">
        <label for="OHIP">Patient:</label>
        <select id="OHIP" name="OHIP" class="input-field">
        <?php
        try{
            $nameResult = $stmtName->execute();
            while ($nameRow = $stmtName->fetch()) {

                ?>
                    <option value="<?php echo $nameRow["OHIP_Number"] ?>"><?php echo $nameRow["First_Name"]." ".$nameRow["Middle_Name"]." ".$nameRow["Last_Name"]." -> OHIP:".$nameRow["OHIP_Number"] ?></option>
                <?php
            }
            ?>
                <br>
                <input type="submit" value="Submit" class="submit-button"/>
            </form>
            <?php
        }
        catch (Exception $e){
            echo "Look up failed";
            ?>
            <br/>
            <p>error message:</p>
            <br/>
            <?php echo $e;
        }
        if ($OHIP){
            $query = "SELECT Name, l.Lot_Number, Vaccinated_Date, Company_Name FROM (SELECT * FROM Vaccinated WHERE OHIP_Number=:OHIP) as v INNER JOIN Lot as l ON v.Lot_Number=l.Lot_Number;";
            $stmt = $connection->prepare($query);
            $stmt ->bindParam(':OHIP', $OHIP);

            $nameQuery2 = "SELECT OHIP_Number, First_Name, Middle_Name, Last_Name FROM Patient WHERE OHIP_NUMBER = :OHIP";
            $stmt2 = $connection->prepare($nameQuery2);
            $stmt2 ->bindParam(':OHIP', $OHIP);
            try{
                $results = $stmt->execute();
                $results2 = $stmt2->execute();
                $nameInfo = $stmt2->fetch();
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
            catch (Exception $e){
                echo "Look up failed";
                ?>
                <br/>
                <p>error message:</p>
                <br/>
                <?php echo $e;
            }  
        }
        ?>
</body>

</html>