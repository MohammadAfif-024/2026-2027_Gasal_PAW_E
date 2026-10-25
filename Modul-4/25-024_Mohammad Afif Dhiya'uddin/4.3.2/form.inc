
<form name="myForm" action="processData_form.php" method="POST">
    <fieldset>
        <legend>Person Details</legend>

        Surname:
        <input type="text" name="surname"
        value="<?php if (isset($_POST['surname'])) echo htmlspecialchars($_POST['surname']); ?>">
        <br><br>

        Email address:
        <input type="text" name="email"
        value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>">
        <br><br>

        Password:
        <input type="password" name="passwd">
        <br><br>

        Street Address:
        <textarea name="address"><?php
        if (isset($_POST['address'])) echo htmlspecialchars($_POST['address']);
        ?></textarea>
        <br><br>

        State:
        <select name="state">
            <option value="NSW"
            <?php if (isset($_POST['state']) && $_POST['state'] == 'NSW') echo 'selected'; ?>>
                New South Wales
            </option>

            <option value="VIC"
            <?php if (isset($_POST['state']) && $_POST['state'] == 'VIC') echo 'selected'; ?>>
                Victoria
            </option>

            <option value="QLD"
            <?php if (isset($_POST['state']) && $_POST['state'] == 'QLD') echo 'selected'; ?>>
                Queensland
            </option>

            <option value="WA"
            <?php if (isset($_POST['state']) && $_POST['state'] == 'WA') echo 'selected'; ?>>
                Western Australia
            </option>
        </select>
        <br><br>

        <input type="hidden" name="country" value="Australia">

        Gender:
        <input type="radio" name="sex" value="Male"
        <?php if (isset($_POST['sex']) && $_POST['sex'] == 'Male') echo 'checked'; ?>>
        Male

        <input type="radio" name="sex" value="Female"
        <?php if (isset($_POST['sex']) && $_POST['sex'] == 'Female') echo 'checked'; ?>>
        Female
        <br><br>

        <input type="checkbox" name="vegetarian" value="Yes"
        <?php if (isset($_POST['vegetarian'])) echo 'checked'; ?>>
        Vegetarian?
        <br><br>

        <input type="submit" value="Submit">
        <input type="reset" value="Reset">

    </fieldset>
</form>
