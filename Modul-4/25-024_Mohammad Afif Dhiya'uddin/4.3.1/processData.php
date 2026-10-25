<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Data</title>
</head>
<body>

    <h1>Processing Data</h1>

    <?php
    require_once 'validate.inc';

    $errors = array();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Memeriksa surname
        validateName($errors, $_POST, 'surname');

        if (empty($errors)) {

            echo '<p>Data OK!</p>';

            foreach ($_POST as $key => $value) {
                echo htmlspecialchars($key) . ' => '
                    . htmlspecialchars((string)$value)
                    . '<br>';
            }

        } else {

            echo '<h3>Errors:</h3>';

            foreach ($errors as $field => $error) {
                echo htmlspecialchars($field) . ': '
                    . htmlspecialchars($error)
                    . '<br>';
            }

            echo '<p><a href="processData_form.html">Back to Form</a></p>';
        }

    } else {

        echo '<p>No data submitted.</p>';
        echo '<p><a href="processData_form.html">Back to Form</a></p>';

    }
    ?>

</body>
</html>