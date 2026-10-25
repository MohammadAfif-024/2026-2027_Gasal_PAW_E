
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Data</title>
</head>
<body>

<h1>Register</h1>

    <?php
        if (isset($_POST['surname'])) {

            require 'validate.inc';

            $errors = array();

            validateName($errors, $_POST, 'surname');

            validateEmail($errors, $_POST, 'email');

            validateRequired($errors, $_POST, 'passwd');

            validateRequired($errors, $_POST, 'address');

            validateRequired($errors, $_POST, 'sex');

            if ($errors) {

                echo '<h1>Invalid, correct the following errors:</h1>';

                foreach ($errors as $field => $error) {
                    echo htmlspecialchars($field) . ' '
                        . htmlspecialchars($error) . '<br>';
                }

                // Menampilkan kembali form
                include 'form.inc';

            } else {

                echo '<h1>Form submitted successfully with no errors</h1>';

            }

        } else {

            include 'form.inc';

        }

    ?>


</body>
</html>
