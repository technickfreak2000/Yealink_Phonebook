<?php
    INCLUDE 'config.php';
    GLOBAL $overwrite;
    if (!$overwrite)
    {
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // First initialization
        if ($first === true)
        {
            revertSQLfile('entry.sql');
            revertSQLfile('numbers.sql');
            $first = false;
        }

        $conn->close();

        $overwrite = false;
    }
    
    function listDB($txtSuche, $txtSeite)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        global $selNumber;
        global $anzahl;

        if ($txtSuche == ""){
            $anzahl = getNumberOfUsers();
            $seiten = ceil($anzahl / $selNumber);
            $objects = ($txtSeite - 1) * $selNumber;
            $sql = "SELECT id, name FROM entry ORDER BY name LIMIT $objects, $selNumber";
        }
        else
        {
            $anzahl = getNumberOfUsers2($txtSuche);
            $seiten = ceil($anzahl / $selNumber);
            $objects = ($txtSeite -1) * $selNumber;
            $sql = "SELECT id, name FROM entry WHERE LOCATE('" . $txtSuche . "', name)>0 ORDER BY name LIMIT $objects, $selNumber";
        }

        $result = $conn->query($sql);

        global $x;
        $x = 1;

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $sql2 = "SELECT number FROM numbers, entry WHERE numbers.id_entry=entry.id AND entry.id=\"" . $row["id"] . "\"";
                $result2 = $conn->query($sql2);

                echo "<tr><td><input type=\"checkbox\" name=\"checkbox" . $x . "\" value=\"" . $row["id"] . "\"></td>" . "<td>" . $row["id"]. "</td><td>" . $row["name"]. "</td>";
                $x++;
                if ($result2->num_rows > 0) {
                    // output data of each row
                    echo "<td>";
                    while($row2 = $result2->fetch_assoc()) {
                        echo $row2["number"] . "<br>";
                    }}            
                echo "</td></tr>";
            }
        } else {
        echo "0 results";
        }
        $conn->close();
    }

    function revertSQLfile($filename)
    {
                // Temporary variable, used to store current query
                $templine = '';
                // Read in entire file
                $lines = file($filename);
                // Loop through each line
                foreach ($lines as $line)
                {
                // Skip it if it's a comment
                if (substr($line, 0, 2) == '--' || $line == '')
                    continue;
        
                // Add this line to the current segment
                $templine .= $line;
                // If it has a semicolon at the end, it's the end of the query
                if (substr(trim($line), -1, 1) == ';')
                {
                    // Perform the query
                    global $conn;
                    $conn->query($templine) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
                    // Reset temp variable to empty
                    $templine = '';
                }
                }
                echo "Tables imported successfully";
    }

    function createXML()
    {
        $xml = new SimpleXMLElement('<YeastarIPPhoneDirectory/>');

        global $first;
        
        global $servername;
        global $username;
        global $password;
        global $dbname;

        global $phonebook;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name FROM entry";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $sql2 = "SELECT number FROM numbers, entry WHERE numbers.id_entry=entry.id AND entry.id=\"" . $row["id"] . "\"";
                $result2 = $conn->query($sql2);

                $DirectoryEntry = $xml->addChild('DirectoryEntry');
                $DirectoryEntry->addChild('Name', $row["name"]);

                if ($result2->num_rows > 0) {
                    while($row2 = $result2->fetch_assoc()) {
                        $DirectoryEntry->addChild('Telephone', $row2["number"]);
                    }}
            }
        } else {
        echo "0 results";
        }
        $conn->close();

        // Header('Content-type: text/xml');*/
        $xml->asXML($phonebook);
    }

    function writeVar()
    {
        global $first;
        
        global $servername;
        global $username;
        global $password;
        global $dbname;

        global $phonebook;
        
        $var_first = var_export($first, true);
        $var_servername = var_export($servername, true);
        $var_username = var_export($username, true);
        $var_password = var_export($password, true);
        $var_dbname = var_export($dbname, true);
        $var_phonebook = var_export($phonebook, true);

    $var = "<?php\n
    \$first = $var_first; \n
    // Database
    \$servername = $var_servername; 
    \$username = $var_username; 
    \$password = $var_password; 
    \$dbname = $var_dbname; \n
    // Phonebook
    \$phonebook = $var_phonebook;
    \n?>";

        file_put_contents('../config.php', $var);
    }

    function insertDB($name, $numbers)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Cleanup numbers
        $numbers = str_replace(" ", "", $numbers);
        $numbers = str_replace(PHP_EOL, "", $numbers);
        
        if ($numbers == "" || $name == "")
        {
            print "Enter Values!";
            exit();
        }

        $numbers = array_filter(explode(",", $numbers));

        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "INSERT INTO `entry`(`name`) VALUES ('" . $name . "')";
        $conn->query($sql) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
        $insertID = mysqli_insert_id($conn);

        foreach ($numbers as $itemnumber)
        {
            $sql = "INSERT INTO `numbers`(`id_entry`, `number`) VALUES ('" . $insertID . "','" . $itemnumber . "')";
            $conn->query($sql) or print('Error performing query \'<strong>' . $templine . '\': ' . $conn->error . '<br /><br />');
        }

        $conn->close();
    }

    function getNumberOfUsers()
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT COUNT(*) AS anzahl FROM entry";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["anzahl"];
        }
        } else {
            return "0";
        }

        $conn->close();
    }

    function getNumberOfUsers2($txtSuche)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT COUNT(*) AS anzahl FROM entry WHERE LOCATE('" . $txtSuche . "', name)>0";
        
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            return $row["anzahl"];
        }
        } else {
            return "0";
        }
        
        $conn->close();
    }

    function delEntry($selStuff)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        foreach ($selStuff as $i => $value) {
            $dbId = $selStuff[$i];
            $sql = "DELETE FROM `numbers` WHERE numbers.id_entry = '$dbId'";
            $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');

            $sql = "DELETE FROM `entry` WHERE entry.id = '$dbId'";
            $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');
        }

        $conn->close();
    }

    function compileEditHtml($dbId, $number, $name, $telNumbers)
    {
        $compiled = '
        Name: <input type="text" placeholder="Name" name="txtName' . $number . '" value="' . $name . '"></input>
        <br><br>
        <p>Nummern können kommagetrennt eingegeben werden:</p>
        <textarea name="txtNum' . $number . '" cols="60" rows="8" placeholder="BSP: 1234,56789,12345">' . $telNumbers . '</textarea>
        <input type="hidden" name="dbid' . $number . '" value="' . $dbId . '">
        <br><br><hr><br>';

        return $compiled;
    }

    function getNameNumbersDb($dbId)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        $output = array();

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name FROM entry WHERE entry.id = '$dbId'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                
                $sql2 = "SELECT number FROM numbers, entry WHERE numbers.id_entry=entry.id AND entry.id=\"" . $row["id"] . "\"";
                $result2 = $conn->query($sql2);

                array_push($output, $row["name"]);

                if ($result2->num_rows > 0) {
                    while($row2 = $result2->fetch_assoc()) {
                        array_push($output, $row2["number"]);
                    }}
            }
        } else {
        echo "0 results";
        }
        $conn->close();

        return $output;
    }

    function showEdit($array)
    {
        foreach ($array as $i => $value)  
        {
            $dbId = $array[$i];
            $dbArray = getNameNumbersDb($dbId);
            $numbers = "";
            for ($x = 1; $x <= count($dbArray) - 1; $x++)
            {
                $numbers = $numbers . $dbArray[$x] . ",";
            }
            echo compileEditHtml($dbId, $i + 1, $dbArray[0], $numbers);
        }
    }

    function writeEditToDb($dbId, $txtName, $numbers)
    {
        global $first;
        global $servername;
        global $username;
        global $password;
        global $dbname;

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        }

        if ($txtName == "")
        {
            $sql = "DELETE FROM `entry` WHERE entry.id = '$dbId'";
            $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');

            $sql = "DELETE FROM `numbers` WHERE numbers.id_entry = '$dbId'";
            $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');
        }
        else
        {
            // Cleanup numbers
            $numbers = str_replace(" ", "", $numbers);
            $numbers = str_replace(PHP_EOL, "", $numbers);
            if ($numbers != "")
            {
                $sql = "UPDATE `entry` SET `name`='" . $txtName ."' WHERE entry.id = '$dbId'";
                $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');

                $sql = "DELETE FROM `numbers` WHERE numbers.id_entry = '$dbId'";
                $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');

                $numbers = array_filter(explode(",", $numbers));

                foreach ($numbers as $itemnumber)
                {
                    $sql = "INSERT INTO `numbers`(`id_entry`, `number`) VALUES ('" . $dbId . "','" . $itemnumber . "')";
                    $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');
                }
            }
            else
            {
                $sql = "DELETE FROM `entry` WHERE entry.id = '$dbId'";
                $conn->query($sql) or print('Error performing query: ' . $conn->error . '<br /><br />');
            }
        }

        $conn->close();
    }
?>