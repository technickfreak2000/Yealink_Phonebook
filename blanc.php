<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css.css">
        <title>Phonebook</title>
    </head>
    <body>
        <?php
            if (isset($_GET["status"]))
            {
                echo "Status: " . $_GET["status"]);
            }
        ?>
    </body>
</html>