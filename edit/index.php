<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
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
        
        <?php
            INCLUDE '../algo.php';

            $selArray = array();                   
            if (isset($_GET["selstuff"]))
            {
                $sellstuff = $_GET["selstuff"];
                $selArray = explode(',', $sellstuff);
            }

            $txtName = "";
            $txtNum = "";
            $dbid = "";
            if (isset($_GET["btnDo"]) && isset($_GET["x"])) 
            {
                for ($i = 1; $i <= $_GET["x"]; $i++) 
                {
                    if (isset($_GET["txtName" . $i]) && isset($_GET["txtNum" . $i]) && isset($_GET["dbid" . $i]))
                    {
                        $txtName = $_GET["txtName" . $i];
                        $txtNum = $_GET["txtNum" . $i];
                        $dbid = $_GET["dbid" . $i];

                        writeEditToDb($dbid, $txtName, $txtNum);
                    }
                }
                header("LOCATION: ../");
            }
        ?>

        <div class = "main">
            <p>Einträge bearbeiten: </p>
            <br>
            <form action="index.php" methode="get">
                <?php
                    showEdit($selArray);
                ?>

                <input type="hidden" name="x" value="<?php print count($selArray); ?>">
                <input type="submit" name="btnDo" value="Go" />
            </form>
            <br>
            <?php
                
            ?>
        </div>
    </body>
</html>
