<!DOCTYPE html>
<html lang="en">
<!-- Evan Kilburn -->
<head>
	<title>List Workers</title>
	<meta charset="utf-8"/>
	<meta name="author" content="Evan Kilburn"/>
    <link rel="stylesheet" href="../../css/styles.css"> 
</head>
<body>
    <?php
        include '../../inc/connectdb.php';
        $Name = $_POST["Name"];
    ?>
    <a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
    <h1>Find a Vaccination Site</h1>
    <br>
    <?php
    $nameQuery = "select Name From Company";
	$stmtName = $connection->prepare($nameQuery);
    ?>
    <form method="post" action="displaySites.php">
        <label for="Name">Select Vaccine Type:</label>
        <select id="Name" name="Name" class="input-field">
        <?php
        try{
            $nameResult = $stmtName->execute();
            while ($nameRow = $stmtName->fetch()) {

                ?>
                    <option value="<?php echo $nameRow["Name"] ?>"><?php echo $nameRow["Name"]?></option>
                <?php
            }
            ?>
                <br>
                <input type="submit" value="Submit" class="submit-button"/>
            </form>
            <?php
        }
        catch (Exception $e){
            echo "Company name look up failed";
            ?>
            <br/>
            <p>error message:</p>
            <br/>
            <?php echo $e;
        }
        if ($Name){
            $query = "SELECT ld.Doses, ld.Name, Site_Dates.Site_Date FROM (SELECT l.Lot_Number, l.Doses, d.Name FROM (SELECT Lot_Number, Doses FROM Lot WHERE Company_Name=:Name) as l INNER JOIN Distributed_To as d ON l.Lot_Number=d.Lot_Number) as ld INNER JOIN Site_Dates ON ld.Name=Site_Dates.Name";
            $stmt = $connection->prepare($query);
            $stmt ->bindParam(':Name', $Name);

            try{
                $results = $stmt->execute();
                ?>
                <h3>Showing Site Dates for the <?php echo $Name?> Vaccine</h3>
                <table>
                <tr>
                    <th>Site Name</th>
                    <th>Date</th>
                    <th>Doses</th>
                </tr>
                <?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <tr>
                        <td><?php echo $row["Name"]?></td>
                        <td><?php echo $row["Site_Date"]?></td>
                        <td><?php echo $row["Doses"]?></td>
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
        ?>
</body>

</html>