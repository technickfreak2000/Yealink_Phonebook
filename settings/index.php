<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css.css">
        <title>Phonebook</title>
    </head>
    <body>
        <div class = "menu">
            <ul>
                <li><p>Phonebook</p></li>
                <li><a href="../index.php">Home</a></li>
                <li><a href="../settings/">Einstellungen</a></li>
                <li><div class="vl"></div></li>
                <li><a href="../create/">Eintrag hinzufügen</a></li>
            </ul>
        </div>
        
        <div class = "main">
            <p>Einstellungen:</p>
            <hr>
            <form action="index.php" methode="GET">
                <?php
                    global $overwrite;
                    $overwrite = true;
                    INCLUDE '../algo.php';
                    global $first;
        
                    global $servername;
                    global $username;
                    global $password;
                    global $dbname;

                    global $phonebook;
                ?>
                <p>Datenbank:</p>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <p>DB Servername:</p>
                            </td>
                            <td>
                                <input type="text" placeholder="localhost" name="txtDBHost" value="<?php print $servername; ?>"></input>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>DB Name:</p>
                            </td>
                            <td>
                                <input type="text" placeholder="phonebook" name="txtDBName" value="<?php print $dbname; ?>"></input>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>DB User:</p>
                            </td>
                            <td>
                                <input type="text" placeholder="root" name="txtDBUser" value="<?php print $username; ?>"></input>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>DB Passwort:</p>
                            </td>
                            <td>
                                <input type="text" placeholder="unverändert" name="txtDBPasswd"></input>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <hr>

                <p>Speicherort XML:</p>
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <p>Speicherort:</p>
                            </td>
                            <td>
                                <input type="text" placeholder="phonebook.xml" name="txtPhonebook" value="<?php print $phonebook; ?>"></input>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <input type="submit" name="btnSettings" value="Go" />
                <br>
                <?php
                    if (isset($_GET["btnSettings"])) {
                        if (isset($_GET["txtDBHost"]) && isset($_GET["txtDBName"]) && isset($_GET["txtDBUser"]) && isset($_GET["txtDBPasswd"]) && isset($_GET["txtPhonebook"]))
                        {
                            $servername = $_GET["txtDBHost"];
                            $username = $_GET["txtDBUser"];
                            $passwordNew = $_GET["txtDBPasswd"];
                            $dbname = $_GET["txtDBName"];
                            $phonebook = $_GET["txtPhonebook"];
                            if ($passwordNew != "")
                            {
                                $password = $passwordNew;
                            }
                            writeVar();
                            sleep(3);
                            header("LOCATION: ../");
                        }
                    }
                ?>
            </form>
        </div>
    </body>
</html>
