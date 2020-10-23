<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css.css">
        <title>Phonebook</title>
    </head>
    <body>
    <form action="index.php" methode="get" onkeydown="return event.key != 'Enter';">
        <div class = "menu">
            <ul>
                <?php
                    $txtsuch = "";
                    if (isset($_GET["txtSuch"]))
                    {
                        $txtsuch = $_GET["txtSuch"];
                    }
                ?>
                <li><p>Phonebook</p></li>
                <li><a href="./index.php">Home</a></li>
                <li><a href="./settings/">Einstellungen</a></li>
                <li><div class="vl"></div></li>
                <li><a href="./create/">Eintrag hinzufügen</a></li>
                <li><a href="?xml=xml">Einträge auf Telefone übernehmen</a></li>
            </ul>
                <input type="text" class="right" placeholder="Suchen" name="txtSuch" value = "<?php print $txtsuch; ?>"></input>
                <input type="submit" class="right2" name="btnSuch" value="Go" />
        </div>
        
        <div class = "main">
            <p>Einträge: </p>

            <?php
                INCLUDE 'config.php';
                GLOBAL $first;
                if ($first == true)
                {
                    header("LOCATION: ./setup.php");
                }

                INCLUDE 'algo.php';

                if (isset($_GET["xml"])) {
                    echo "createxml";
                    createXML();
                    header("LOCATION: .");
                }

                $selNumber = "50";                   
                if (isset($_GET["selNumber"]))
                {
                    $selNumber = $_GET["selNumber"];
                }

                if ($txtsuch == "")
                {
                    $anzahl = getNumberOfUsers();
                    $anzSeiten = ceil($anzahl / $selNumber);
                }
                else
                {
                    $anzahl = getNumberOfUsers2($txtsuch);
                    $anzSeiten = ceil($anzahl / $selNumber);
                }

                $txtSeite = "1";                   
                if (isset($_GET["txtSeite"]))
                {
                    $txtSeite = $_GET["txtSeite"];
                    if (isset($_GET["btnNumber"]) || $txtSeite < 1 || isset($_GET["btnSuch"]))
                    {
                        $txtSeite = 1;
                    }
                    if ($txtSeite > $anzSeiten)
                    {
                        $txtSeite = $anzSeiten;
                    }
                }

                if (isset($_GET["btnP"]) && $txtSeite < $anzSeiten)
                {
                    $txtSeite++;
                }

                if (isset($_GET["btnM"]) && $txtSeite > 1)
                {
                    $txtSeite--;
                }
                
                $x = 1;
                if (isset($_GET["x"]))
                {
                    $x = $_GET["x"];
                }

                $selStuff = array();
                if (isset($_GET["btnEdit"]) || isset($_GET["btnDel"]))
                {
                    for ($i = 1; $i <= $x; $i++) {  
                        if (isset($_GET["checkbox$i"]))
                        {
                            array_push($selStuff, $_GET["checkbox$i"]);
                        }
                    }
                }

                if (isset($_GET["btnDel"]))
                {
                    delEntry($selStuff);
                    header("LOCATION: ./index.php");
                }

                if (isset($_GET["btnEdit"]))
                {
                    $selStuffStr = "";
                    foreach ($selStuff as $i => $value)
                    {
                        $selStuffStr = $selStuffStr . $selStuff[$i] . ",";
                    }
                    $selStuffStr = substr($selStuffStr, 0, -1);
                    if ($selStuffStr != "")
                    {
                        header("LOCATION: ./edit/index.php?selstuff=$selStuffStr");
                    }
                }

                if ($txtSeite < 1)
                {
                    $txtSeite = 1;
                }

                if ($anzSeiten < 1)
                {
                    $anzSeiten = 1;
                }
            ?>
            <br>
                <label for="selNumber">Elemente auf einer Seite: </label>
                <select name="selNumber">
                <option selected="selected"><?php print $selNumber; ?></option>
                    <option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
                <input type="submit" value="Ok" name="btnNumber">
                <br>
                <br>
                <input type="submit" value="-" name="btnM">
                Seite: <input type="number" name="txtSeite" value="<?php print $txtSeite; ?>" min="1" max="<?php print $anzSeiten; ?>"></input>
                von: <?php print $anzSeiten; ?>
                <input type="submit" value="+" name="btnP">
                <input type="submit" value="Ok">
            <br>
            <br>
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Nummern</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        listDB($txtsuch, $txtSeite);
                    ?>
                </tbody>
            </table>
            <br>
            <p>Ausgewählte Elemente: </p>
                <input type="submit" name="btnEdit" value="> Bearbeiten"/>
                <input type="submit" name="btnDel" value="> Löschen"/>

                <input type="hidden" name="x" value="<?php print $x - 1; ?>">
        </div>
    </form>
    </body>
</html>
