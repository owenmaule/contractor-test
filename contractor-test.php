<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>Owen Maule submission</title>
<meta name="description" content="PHP, SQL, Javascript &amp; jQuery test for a contract role." />
<meta name="author" content="Owen Maule <o@owen-m.com>" />
<meta name="copyright" content="Copyright Owen Maule 2015" />
</head>
<body>
<?php
# Copyright 2015 Owen Maule <o@owen-m.com> http://owen-m.com/
# PHP, SQL, Javascript & jQuery test for a contract role.

try
{
	# Fill these in!
	define( 'TEST_DATABASE', 'YOUR_DATABASE' );
	define( 'TEST_DATABASE_USER', 'YOUR_DATABASE_USER' );
	define( 'TEST_DATABASE_PASSWORD', 'YOUR_DATABASE_PASSWORD' );

	define( 'BR', '<br />' . PHP_EOL );

	# Title
	echo '<h2>Owen Maule <a href="mailto:o@owen-m.com">o@owen-m.com</a> <a href="http://owen-m.com/" target="_blank">owen-m.com</a> Contractor Test</h2>';

	# Q1. PHP - Complete remove_nonAtoZ() below

	function remove_nonAtoZ( $input )
	{
		if( ! is_string( $input ) )
		{
			throw new Exception( 'Invalid parameter "input" to remove_nonAtoZ(): ' . var_export( $input, true ) );
		}

		// example data: $input = "BA'3Ndf$%^A&*(nN)A"; $output = "BANANA";
		return preg_replace( '/[^A-Z]/' , '', $input );
	}

	# Test Q1
	$input = <<<INPUT
BA'3Ndf$%^A&*(nN)A
INPUT;
	$expectedOutput = 'BANANA';

	echo '<h3>Q1. PHP - Complete remove_nonAtoZ()</h3>example data: $input = "', htmlspecialchars( $input ), '"; $output = "',
		htmlspecialchars( remove_nonAtoZ( $input ) ),  '";', BR,
		'Test ', ( $expectedOutput == remove_nonAtoZ( $input ) ? 'passed' : 'failed' ), BR,
		BR;

	# Q2. PHP - Complete table_columns() below

	function table_columns( $input, $cols )
	{
		if( ! is_array( $input ) )
		{
			throw new Exception( 'Invalid parameter "input" to table_columns(): ' . var_export( $input, true ) );
		}
		# Array is allowed to be empty and will be processed
		if( ! (int) $cols )
		{
			throw new Exception( 'Invalid parameter "cols" to table_columns(): ' . var_export( $cols, true ) );
		}
		$cols = (int) $cols;

		// problem: array of values are ordered to fill an html table by *column* (ie second value should appear beneath the first etc)
		// task: reorder array so that the values are in the order they will be output in html, filling each *row* of table at a time
		// example inputs: $input = array('apple', 'orange', 'monkey', 'potato', 'cheese', 'badger', 'turnip'), $cols = 2
		// example output: array(array('apple', 'cheese'), array('orange','badger'),...)

		# Calculate number of rows
		$rowCount = ceil( count( $input ) / $cols );
		$result = array ( );
		
		/* php_version() >= 5.5 for array_column() - could use with array_chunk()
		# UNTESTED
		# Split data into rows
		$rowArray = array_chunk( $input, $rowCount );
		
		# Extract columns and add to result, until depleted
		for( $colLoop = 0;
			count( $columnArray = array_column( $rowArray, $colLoop ) );
			++$colLoop )
		{
			$result[] = $columnArray;
		}
		*/

		$rowIndex = 0;
		$colIndex = 0;
		foreach( $input as $value )
		{
			$result[ $rowIndex ][] = $value;
			if( ++$rowIndex == $rowCount )
			{
				++$colIndex;
				$rowIndex = 0;
			}
		}
		return $result;
	}
	
	# Test Q2
	$input = array ( 'apple', 'orange', 'monkey', 'potato', 'cheese', 'badger', 'turnip' );
	$cols = 2;
	$expectedOutput = array (
		array ( 'apple', 'cheese' ),
		array ( 'orange', 'badger' ),
		array ( 'monkey', 'turnip' ),
		array ( 'potato' ),
	);

	echo '<h3>Q2. PHP - Complete table_columns()</h3>example inputs: $input = ', htmlspecialchars( var_export( $input, true ) ), ', $cols = ', (int) $cols, BR,
		'example output: ', htmlspecialchars( var_export( table_columns( $input, $cols ), true ) ), BR,
		'Test ', ( $expectedOutput == table_columns( $input, $cols ) ? 'passed' : 'failed' ), BR,
		BR;

	# Q3. PHP - Complete first_day_of_next_month() below

	function first_day_of_next_month( $date )
	{
		if( ! is_string( $date ) )
		{
			throw new Exception( 'Invalid parameter "date" to first_day_of_next_month(): ' . var_export( $date, true ) );
		}
		
		// Expect and validate $date in in the format "dd/mm/yyyy"
		// Note that dd and mm of the provided date may be either one or two digits in length
		// Return false if provided date is not in the expected format or is not a valid date
		// Convert to the first day of the next month
		// return the string of the same date in the format "yyyy-mm-dd 00:00:00" (month and day should be two digits always)

		$dateParts = explode( '/', $date );
		foreach( $dateParts as &$datePart )
		{
			$datePart = (int) $datePart;
		}
		
		# Validate since Unix Epoch (January 1 1970 00:00:00 GMT)
		# Set no upper limit on year - avoid the Y10K bug!
		$daysInMonth = 0;
		if( 3 != count( $dateParts )
			|| $dateParts[ 2 ] < 1970
			|| $dateParts[ 1 ] < 1 || $dateParts[ 1 ] > 12
			|| $dateParts[ 0 ] < 1
			|| $dateParts[ 0 ] > ( $daysInMonth = cal_days_in_month( CAL_GREGORIAN, $dateParts[ 1 ], $dateParts[ 2 ] ) )
		)
		{
			throw new Exception( 'Invalid parameter "date" to first_day_of_next_month(): ' . var_export( $date, true )
				. ' evaluted to ' . implode( '/', $dateParts ) );
		}
		
		if( ! $daysInMonth )
		{
			throw new Exception( 'Call to cal_days_in_month() failed in first_day_of_next_month(): ' . var_export( $date, true )
				. ' evaluted to ' . implode( '/', $dateParts ) );
		}
		
		# First day
		$dateParts[ 0 ] = 1;
		# of next month
		if( 13 == ++$dateParts[ 1 ] )
		{
			# Increment year also
			$dateParts[ 1 ] = 1;
			++$dateParts[ 2 ];
		}
		
		$dateTime = new DateTime();
		
		if( false === $dateTime->setDate( $dateParts[ 2 ], $dateParts[ 1 ], $dateParts[ 0 ] ) )
		{
			throw new Exception( 'Call to DateTime::setDate() failed in first_day_of_next_month(): ' . var_export( $date, true )
				. ' evaluted to ' . implode( '/', $dateParts ) );
		}

		# No need to include time of day in format string (H:i:s)
		return $dateTime->format( 'Y-m-d 00:00:00' );
	}

	# Test Q3
	$testData = array ( '5/8/1975' => '1975-09-01 00:00:00',
		'5/12/1975' => '1976-01-01 00:00:00' );

	echo '<h3>Q3. PHP - Complete first_day_of_next_month()</h3>';
	foreach( $testData as $date => $expectedOutput )
	{
		echo 'test data: $date = ', htmlspecialchars( var_export( $date, true ) ), BR,
			'test output: ', htmlspecialchars( var_export( first_day_of_next_month( $date, $cols ), true ) ), BR,
			'Test ', ( $expectedOutput == first_day_of_next_month( $date, $cols ) ? 'passed' : 'failed' ), BR,
			BR;
	}

	/*
	Q4. MySQL Write a query to summarise tasks for a team of develpers
	CREATE TABLE developers (id int auto_increment, name varchar(255), PRIMARY KEY (id));
	CREATE TABLE tasks (id int auto_increment, developer_id int, subject varchar(255), priority enum ('1','2','3'), status enum('queued','completed', 'approved'), PRIMARY KEY (id));

	Write a query to join the two tables together to get a listing:
	- Where each row represents a developer/status combination
	- Excludes those that are 'approved'
	- Shows a count of tasks
	- All 'queued' are listed before all 'completed'
	- The list is ordered by developer name within a status
	(ie The entire answer to this question is a single query that satisfys all of the above)
	*/
	echo '<h3>Q4. MySQL - Write a query to summarise tasks for a team of develpers</h3>
';

	$createTables = <<<CREATE_SQL_TABLES
CREATE TABLE developers (id int auto_increment, name varchar(255), PRIMARY KEY (id));
CREATE TABLE tasks (id int auto_increment, developer_id int, subject varchar(255), priority enum ('1','2','3'), status enum('queued','completed', 'approved'), PRIMARY KEY (id));
CREATE_SQL_TABLES;

	$skipQ4 = false;
	try
	{
		$database = new PDO( 'mysql:host=localhost;charset=utf8;dbname=' . TEST_DATABASE, TEST_DATABASE_USER, TEST_DATABASE_PASSWORD, 
			array ( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC ) );
	}
	catch ( Exception $e )
	{
		echo 'You must set up your MySQL database credentials at the start of this file, to test this answer!', BR;
		$skipQ4 = true;
	}

	if( ! $skipQ4 )
	{
		$database->exec( 'DROP TABLE developers; DROP TABLE tasks;' );
		$database->exec( $createTables );
		
		# Create some test data
		
		$statementInsertDeveloper = $database->prepare( 'INSERT INTO `developers` SET name=?' );
		$statementInsertTask = $database->prepare(
			'INSERT INTO `tasks` (developer_id, subject, priority, status) VALUES (:developer_id, :subject, :priority, :status)' );
		$statusValues = array ( 'queued', 'completed', 'approved' );

		for( $devLoop = 1; $devLoop != 4; ++$devLoop )
		{
			$devName = 'Dev ' . chr( ord( 'A' ) + $devLoop - 1 );
			$statementInsertDeveloper->execute( array ( $devName ) );
			$taskCount = mt_rand( 6, 11 );
			for( $taskLoop = 1; $taskLoop != $taskCount; ++$taskLoop )
			{
				$statementInsertTask->execute( array (
					':developer_id' => $devLoop,
					':subject' => $devName . '\'s task #' . $taskLoop,
					':priority' => (string) mt_rand( 1, 3 ),
					':status' => $statusValues[ mt_rand( 0, 2 ) ],
					) );
			}
		}

		# Display test data
		$result = $database->query(
			'SELECT `developers`.`name`, `tasks`.`status`, `tasks`.`subject`
			FROM `tasks`
			JOIN `developers`
			ON `developers`.`id` = `tasks`.`developer_id`
			ORDER BY `developers`.`name` ASC, `tasks`.`status`;
			'
		);

		$resultRows = $result->fetchAll();
		echo '<h4>Random test data</h4><table><thead><tr><th>Developer Name</th><th>Status</th><th>Task Subject</th></thead><tbody>';
		foreach( $resultRows as $row )
		{
			echo '<tr><td>', $row[ 'name' ], '</td><td>', $row[ 'status' ], '</td><td>', $row[ 'subject' ], '</td></tr>';
		}
		echo '</tbody></table>';

		$result = $database->query(
			'SELECT `tasks`.`status`, `developers`.`name`, COUNT( * ) AS `task_count`
			FROM `tasks`
			JOIN `developers`
			ON `developers`.`id` = `tasks`.`developer_id`
			WHERE `tasks`.`status` != "approved"
			GROUP BY `tasks`.`status`, `tasks`.`developer_id`
			ORDER BY `tasks`.`status`, `developers`.`name` ASC;
			'
		);

		$resultRows = $result->fetchAll();
		echo '<h4>Report</h4><table><thead><tr><th>Status</th><th>Developer Name</th><th>Task Count</th></thead><tbody>';
		foreach( $resultRows as $row )
		{
			echo '<tr><td>', $row[ 'status' ], '</td><td>', $row[ 'name' ], '</td><td>', $row[ 'task_count' ], '</td></tr>';
		}
		echo '</tbody></table>';
	}
	
	/*
	Q5. Javascript (JQuery optional)

	<form class="add_option_form">
		<input type="text" class="option_name" name="option_name" />
		<input type="submit" class="add_button" value="Add" />
	</form>
	<div class="checklist">
		<label><input type="checkbox" /><span class="option_name">Option 1</span></label>
		<label><input type="checkbox" checked="checked" /><span class="option_name">Option 2</span></label>
	</div>
	<div class="selected_options"></div>

	Write javascript that will:
	(a) Allow adding new checkboxes to div.checklist by using the form at the top
	(b) Make sure that div.selected_options always contains text consisting of the names of all selected checkboxes (including those added dynamically)

	(Note that you can use JQuery if you prefer OR use pure Javascript)
	*/

	$useJQuery = isset( $_GET[ 'jquery' ] );

	echo
'	<h3>Q5. Javascript (JQuery optional)</h3>
	Method selected: ', ( $useJQuery ? 'jQuery <a href="?">Switch</a>' : 'pure Javascript <a href="?jquery">Switch</a>' ),
'	<form class="add_option_form">
		<input type="text" class="option_name" name="option_name" />
		<input type="submit" class="add_button" value="Add" />
	</form>
	<div class="checklist">
		<label><input type="checkbox" /><span class="option_name">Option 1</span></label>
		<label><input type="checkbox" checked="checked" /><span class="option_name">Option 2</span></label>
	</div>
	<div class="selected_options"></div>
';

	if( $useJQuery )
	{
		// jQuery
		echo
'	<script type="text/javascript" src="//code.jquery.com/jquery-2.1.4.min.js">
	</script>
	<script type="text/javascript">
		function showSelectedOptions()
		{
			var selectedOptions = $( "div.selected_options" ),
				selectedOptionsText = "";
			selectedOptions.empty();
			$( "div.checklist input:checked" ).each( function() {
				if( selectedOptionsText.length )
				{
					selectedOptionsText += ", ";
				}
				selectedOptionsText += $( this ).next().html();
			} );
			selectedOptions.html( selectedOptionsText );
		}

		$( function() {
			showSelectedOptions();
			$( "form.add_option_form input[type=submit]" ).click( function() {
				var value = $( "form.add_option_form input[name=\'option_name\']" ).val();
				$( "div.checklist" ).append( \'<label><input type="checkbox" /><span class="option_name">\' + value + \'</span></label>\' );
				$( "div.checklist label:last-child input" ).click( function() {
					showSelectedOptions();
					return true;
				} );
				return false;
			} );
			$( "div.checklist input[type=checkbox]" ).click( function() {
				showSelectedOptions();
				return true;
			} );
		} );
	</script>
';
	} else {
		// Pure Javascript
		echo
'	<script type="text/javascript">
		function showSelectedOptions()
		{
			var selectedOptions = document.getElementsByClassName( "selected_options" )[ 0 ],
				checklist = document.getElementsByClassName( "checklist" )[ 0 ],
				checkboxes = checklist.getElementsByTagName( "input" ),
				selectedOptionsText = "";

			while( selectedOptions.firstChild )
			{
				selectedOptions.removeChild( selectedOptions.firstChild );
			}

			for( var index = 0; index != checkboxes.length; ++index )
			{
				if( checkboxes[ index ].checked )
				{
					if( selectedOptionsText.length )
					{
						selectedOptionsText += ", ";
					}
					selectedOptionsText += checkboxes[ index ].nextSibling.innerHTML;
				}
			}
			selectedOptions.innerHTML = selectedOptionsText;
		}

		showSelectedOptions();
		var addOptionForm = document.getElementsByClassName( "add_option_form" )[ 0 ],
			addOptionInputs = addOptionForm.getElementsByTagName( "input" ),
			checklist = document.getElementsByClassName( "checklist" )[ 0 ];

		for( var index = 0; index != addOptionInputs.length; ++index )
		{
			if( "submit" == addOptionInputs[ index ].type )
			{
				addOptionInputs[ index ].onclick = function() {
					for( var index = 0; index != addOptionInputs.length; ++index )
					{
						if( "text" == addOptionInputs[ index ].type )
						{
							var label = document.createElement( "label" ),
								input = document.createElement( "input" ),
								span = document.createElement( "span" );

							input.type = "checkbox";
							input.onclick = function() { showSelectedOptions(); return true; }
							span.className = "option_name";
							span.innerHTML = addOptionInputs[ index ].value;
							label.appendChild( input );
							label.appendChild( span );
							checklist.appendChild( label );
						}
					}
					return false;
				}
			}
		}
		
		var checkboxes = checklist.getElementsByTagName( "input" );
		
		for( var index = 0; index != checkboxes.length; ++index )
		{
			checkboxes[ index ].onclick = function() { showSelectedOptions(); return true; }
		}
	</script>
';		
	}
}
catch ( Exception $e )
{
	# Could call trigger_error()
	die( 'Caught exception: ' . htmlspecialchars( nl2br( $e->getMessage() ) ) );
}
?>
</body>
</html>
