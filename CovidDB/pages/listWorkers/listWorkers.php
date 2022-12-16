<!DOCTYPE html>
<html>
<!-- Evan Kilburn -->
<head>
	<title>List Workers</title>
	<meta charset="utf-8"/>
	<meta name="author" content="Evan Kilburn"/>
    <link rel="stylesheet" href="../../css/styles.css"> 
</head>
<body>
    <a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
    <h2>List Workers for a Given Vaccination Site</h2>
	<?php

	include '../../inc/connectdb.php';

    $siteName = $_POST["siteName"];

	$query = "select Name from Vaccination_Site";
	$stmt = $connection->prepare($query);
	try{
		$result = $stmt->execute();
		if ($row = $stmt->fetch()) {
            ?>
            <form method="post" action="listWorkers.php">
                <label for="siteName">Choose a Vaccination Site:</label>
                <select id="siteName" name="siteName" class="input-field">
                <?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <option value="<?php echo $row["Name"] ?>"><?php echo $row["Name"] ?></option>
                    <?php
                }
            ?>
                <input type="submit" value="Submit" class="submit-button"/>
            </form>
            <?php
		}
        if ($siteName){
            $query2 = "SELECT Credential, First_Name, Middle_Name, Last_Name FROM Health_Care_Credentials as hcc INNER JOIN ((Select ID FROM Employed WHERE Name=:siteName) as e INNER JOIN Health_Care as hc ON e.ID=hc.ID) ON hc.ID=hcc.ID;";
	        $stmt2 = $connection->prepare($query2);
            $stmt2 ->bindParam(':siteName', $siteName);
            try{
                $credentialResults = $stmt2->execute();
                ?>
                <h3>Showing <?php echo $siteName ?> Site Employees</h3>
                <table>
                <tr>
                    <th>Credential</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                </tr>
                <?php
                while ($row2 = $stmt2->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row2["Credential"];?></td>
                        <td><?php echo $row2["First_Name"];?></td>
                        <td><?php echo $row2["Middle_Name"];?></td>
                        <td><?php echo $row2["Last_Name"];?></td>
                    </tr>
                <?php
                }
        
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
		
	}
	catch (Exception $e){
        echo "Look up failed";
        ?>
        <br/>
        <p>error message:</p>
        <br/>
        <?php echo $e;
    }
	?>
</body>
</html>