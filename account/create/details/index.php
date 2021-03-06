<!DOCTYPE html>
<html>
<?php
$root = $_SERVER['DOCUMENT_ROOT'];
$title = "Register Account";
include($root.'/includes/head.php');
include($root.'/includes/connect.php');
?>
<body>

  <main class="body-wrap">
    <?php
    include($root.'/includes/navbar.php');
    ?>


    <?php


    if (isset($_POST['SubmitCheck']))
    {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $email = $_POST['email'];//passes value from HTML input box
      $password = password_hash ( $_POST['password'], PASSWORD_BCRYPT); //hashes password
      $region = $_POST['region'];
      $workOrSchool = $_POST['workOrSchool'];
      $joinYear = (integer)date("Y");
      $accountType = $_POST['accountType'];

      $escapedEmail = preg_quote ($email);
      if (print preg_match( "[a-zA-Z0-9_%\+-]+(\.[a-zA-Z0-9_%\+-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z]+)+", $escapedEmail) === 1)
      {
        //checks for existing account with same Email
        $Query = sqlsrv_query($conn, "SELECT email FROM accounts WHERE email = '$email'");
        if (sqlsrv_has_rows($Query)) {
          print "<h5>This account aleady exists</h5>";
        }
        else
        {
          //escapes user inputed values with " ' \
          $escapedFirstName = addslashes($firstName);
          $escapedLastName = addslashes($lastName);
          $escapedEmail = addslashes($password);
          $s1Password = addslashes($password);
          $escapedWorkOrSchool = addslashes($workOrSchool);

          //escapes $
          $escapedPassword = "";
          $passwordArray = str_split ($s1Password); //splits string into array
          for ($letter=0; $letter < strlen ($s1Password); $letter++)
          {
            if ($passwordArray[$letter] == "$") //if char is == to special character
            {
              $escapedPassword .= '\\' . $passwordArray[$letter];
            }
            else
            {
              $escapedPassword .= $passwordArray[$letter];
            }

          }

          print $escapedPassword;
          print "Executed Query";
          $insert = sqlsrv_query($conn, "INSERT INTO accounts (firstName, lastName, saltedPassword, email, accountType, joinYear, region, workOrSchool) values ($escapedFirstName, $escapedLastName, QUOTENAME($escapedPassword), $escapedEmail, $accountType, $joinYear, $region, $escapedWorkOrSchool) ");
          // echo '<script>window.location.href="/account/index.php";</script>';
          if( ($errors = sqlsrv_errors() ) != null) {
            foreach( $errors as $error ) {
              echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
              echo "code: ".$error[ 'code']."<br />";
              echo "message: ".$error[ 'message']."<br />";
            }
          }
        }
      }
      else
      {
        echo "Please enter a valid email";
      }
    }
    $accountType = $_GET['type'];
    ?>

    <container>

      <section class="slice sct-color-1">
        <div class="container container-lg">
          <div class="row align-items-center cols-xs-space cols-sm-space cols-md-space">
            <div class="col-lg-6">
              <form class="form-default form-material" method="post" id="create">

                <input type="hidden" name="SubmitCheck" value="sent">
                <input type="hidden" name="accountType" value="<?php echo $accountType?>">

                <div class="row col-12">
                  <h3>Register for the Program</h3>
                </div>

                <div class="row">
                  <div class="col-md-6 ml-lg-auto">
                    <div class="form-group has-floating-label">
                      <label class="control-label">Forename</label>
                      <input type="text" class="form-control form-control-lg" name="firstName">
                      <span class="bar"></span>
                    </div>
                  </div>

                  <div class="col-md-6 ml-lg-auto">
                    <div class="form-group has-floating-label">
                      <label class="control-label">Surname</label>
                      <input type="text" class="form-control form-control-lg" name="lastName">
                      <span class="bar"></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <div class="form-group has-floating-label">
                      <label class="control-label">Email</label>
                      <input type="text" class="form-control form-control-lg" name="email">
                      <span class="bar"></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <div class="form-group has-floating-label">
                      <label class="control-label">Password</label>
                      <input type="password" class="form-control form-control-lg" name="password">
                      <span class="bar"></span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <?php
                    if ($accountType == "Student") { //changes question for student or mentor
                      echo '<label>Select the Region of the Island You Live In:</label>';
                    }
                    else {
                      echo '<label>Select the Region of the Island You Work In:</label>';
                    }
                  ?>
                  <select class="form-control selectpicker select2-hidden-accessible" name="region" data-minimum-results-for-search="Infinity" tabindex="-1" aria-hidden="true">
                    <option value="North">North</option>
                    <option value="Center">Centre</option>
                    <option value="South">South</option>
                  </select>
                </div>

                <?php
                 if ($accountType == "Student")
                 { //changes question for student or mentor
                   echo '
                    <div class="form-group">
                      <label class="control-label">Enter the Name of the School You Attend</label>
                      <select class="form-control selectpicker select2-hidden-accessible" name="workOrSchool" data-minimum-results-for-search="Infinity" tabindex="-1" aria-hidden="true">
                        <option value="RGS">Ramsey Grammar School</option>
                        <option value="CRHS">Castle Rushen High School</option>
                        <option value="QEII">Queen Elizabeth II High School</option>
                        <option value="BHS">Ballakermeen High School</option>
                        <option value="SNHS">St Ninians High School</option>
                        <option value="UCM">University College Isle of Man</option>
                      </select>
                    </div>';
                  }
                  else
                  {
                    echo '
                    <div class="row">
                      <div class="col-12">
                        <div class="form-group has-floating-label">
                          <label class="control-label">Enter the Name of the Company You Work For</label>
                          <input type="text" class="form-control form-control-lg" name="workOrSchool">
                        <span class="bar"></span>
                      </div>
                    </div>
                  </div>';
                  }
                ?>


                <div class="row cols-xs-space cols-sm-space cols-md-space align-items-center text-left">

                  <div class="col-lg-3 col-md-4">
                    <div class="mt-4">
                      <input type="submit" class="btn btn-styled btn-base-1 btn-circle" name="create">
                    </div>
                  </div>

                  <div class="col-md-6 ml-lg-auto">
                    <div class="mt-4">
                      <a href ="/account/login/">Already have an account? Sign in here.</a>
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </section>

    </container>

    <?php
      include($root.'/includes/footer.php');
    ?>

</main>

<?php include($root.'/includes/scripts.php'); ?>

</body>
</html>
