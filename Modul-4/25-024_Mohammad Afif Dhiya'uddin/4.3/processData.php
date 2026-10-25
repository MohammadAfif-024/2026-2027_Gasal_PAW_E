<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Data</title>
</head>
<body>
    <h3>Posted data:</h3>

    <?php
        foreach ($_POST as $key => $value) {
            echo "(" . htmlspecialchars($key) . ") => ("
                . htmlspecialchars($value) . ")<br>";
        }
    ?>
</body>
</html>