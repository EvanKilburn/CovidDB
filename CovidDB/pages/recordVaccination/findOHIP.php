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
	<?php
	$OHIP = $_POST["checkOHIP"];

	include '../../inc/connectdb.php';

	if ($OHIP){
		?>
		<a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
		<h1>OHIP Search Result</h1>
		<?php
		$query = "select * from Patient where OHIP_Number = :OHIP";
		$stmt = $connection->prepare($query);
		$stmt->bindParam(':OHIP', $OHIP);
		try{
			$result = $stmt->execute();
			if ($row = $stmt->fetch()) {
				$siteQuery = "Select Name FROM Vaccination_Site";
				$stmt2 = $connection->prepare($siteQuery);
				$siteResult = $stmt2->execute();
				?>
				<p>Patient with OHIP: <?php echo $row["OHIP_Number"]?> exists. You can go ahead and record the vaccine:
				<form method="post" action="insertVaccination.php">
					<label for="OHIP">OHIP:</label>
					<input type="text" name="OHIP"  class="input-field" value='<?php echo $row["OHIP_Number"]?>' maxlength="10" readonly/>
					<br/>
					<label for="siteName">Vaccination Site Name:</label>
					<select id="siteName" name="siteName" class="input-field">
					<?php
                		while ($row2 = $stmt2->fetch()) {
							?>
							<option value="<?php echo $row2["Name"] ?>"><?php echo $row2["Name"] ?></option>
							<?php
						}
						
					?>
					</select>
					<br/>
					<label for="lotNumber">Lot Number:</label>
					<input type="text" name="lotNumber" class="input-field"/>
					<br/>
					<label for="dateAdministered">Date Administered:</label>
					<input type="date" name="dateAdministered" class="input-field"/>
					<br/>
					<label for="timeAdministered">Time Administered:</label>
					<input type="time" name="timeAdministered" class="input-field"/>
					<br/>
					<input type="submit" value="Submit" class="submit-button"/>
				</form>
				<?php
			}
			else{ #no user exists for given OHIP
				?><p>Patient with OHIP: <?php echo $OHIP?> does not exist</p>
				<form method="post" action="createPatient.php">
					<label for="OHIP">OHIP</label>
					<input type="text" name="OHIP" value='<?php echo $OHIP?>' maxlength="10" readonly class="input-field"/>
					<br/>
					<label for="birthdate">Birthdate</label>
					<input type="date" name="birthdate" class="input-field"/>
					<br/>
					<label for="firstName">First Name</label>
					<input type="text" name="firstName" class="input-field"/>
					<br/>
					<label for="middleName">Middle Name</label>
					<input type="text" name="middleName" class="input-field"/>
					<br/>
					<label for="lastName">Last Name</label>
					<input type="text" name="lastName" class="input-field"/>
					<br/>
					<input type="submit" value="Submit" class="submit-button"/>
				</form>
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
	else{
		?>
		<a href="/CovidDB/pages/covid.php"><button class="home-button">Return Home</button></a>
		<h1>Record Vaccination</h1>
		<br>
		<form id="checkPatient" method="post" action="findOHIP.php">
			<label for="checkOHIP">Enter OHIP of Patient</label>
			<input type="text" name="checkOHIP" id="checkOHIP" maxlength="10" class="input-field"/>
			<input type="submit" value="Submit" id="checkPatientSubmit" class="submit-button"/>
		</form>
		<?php
	}
	?>
</body>
</html>