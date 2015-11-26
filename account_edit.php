<?php 

    // First we execute our common code to connection to the database and start the session 
    require("common.php"); 
    if (empty($_POST)){
      $_SESSION['errorcodes'] = array();
      $failed = 0;
    }
     
    // At the top of the page we check to see whether the user is logged in or not 
    
    if(empty($_SESSION['user'])) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
     
    // This if statement checks to determine whether the edit form has been submitted 
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        $_SESSION["errorcodes"] = array();
        $failed = 0;

        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            //die("Invalid E-Mail Address");
            $_SESSION["errorcodes"][] = "Invalid E-Mail address.";
            $failed = 1;
        } 
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] != $_SESSION['user']['email']) 
        { 
            // Define our SQL query 
            $query = " 
                SELECT 
                    1 
                FROM users 
                WHERE 
                    email = :email 
            "; 
             
            // Define our query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'] 
            ); 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                //die("Failed to run query: " . $ex->getMessage());
                //die("Failed to run query"); 
                $_SESSION["errorcodes"][] = "An error occured, please try again.";
                $failed = 1;
            } 
             
            // Retrieve results (if any) 
            $row = $stmt->fetch(); 
            if($row) 
            { 
                //die("This E-Mail address is already in use");
                $_SESSION["errorcodes"][] = "This E-Mail address is already in use.";
                $failed = 1;
            } 
        } 
         
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password']) && !empty($_POST['confirm-password'])) 
        { 
            if ($_POST['password'] == $_POST['confirm-password']){
               $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
                $password = hash('sha256', $_POST['password'] . $salt); 
                for($round = 0; $round < 65536; $round++) 
                { 
                    $password = hash('sha256', $password . $salt); 
                }  
            } else {
                $_SESSION["errorcodes"][] = "The passwords entered did not match.";
                $failed = 1;
            }
            
        } 
        else if ((!empty($_POST['password']) && empty($_POST['confirm-password'])) || (empty($_POST['password']) && !empty($_POST['confirm-password'])))
        {
            $_SESSION["errorcodes"][] = "Please fill in both password fields when changing your password.";
            $failed = 1;
        } 
        else
        { 
            // If the user did not enter a new password we will not update their old one. 
            $password = null; 
            $salt = null; 
        } 


        if ($failed == 0){
          // Initial query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'], 
                ':user_id' => $_SESSION['user']['id'], 
            ); 
             
            // If the user is changing their password, then we need parameter values 
            // for the new password hash and salt too. 
            if($password !== null) 
            { 
                $query_params[':password'] = $password; 
                $query_params[':salt'] = $salt; 
            } 
             
            // Note how this is only first half of the necessary update query.  We will dynamically 
            // construct the rest of it depending on whether or not the user is changing 
            // their password. 
            $query = " 
                UPDATE users 
                SET 
                    email = :email 
            "; 
             
            // If the user is changing their password, then we extend the SQL query 
            // to include the password and salt columns and parameter tokens too. 
            if($password !== null) 
            { 
                $query .= " 
                    , password = :password 
                    , salt = :salt 
                "; 
            } 
             
            // Finally we finish the update query by specifying that we only wish 
            // to update the one record with for the current user. 
            $query .= " 
                WHERE 
                    id = :user_id 
            "; 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                //die("Failed to run query: " . $ex->getMessage()); 
                $_SESSION["errorcodes"][] = "An error occured, please try again.";
            } 
             
            // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
            // array is stale; we need to update it so that it is accurate. 
            $_SESSION['user']['email'] = $_POST['email']; 
             
            // This redirects the user back to the members-only page after they register 
            header("Location: index.php"); 
             
            // Calling die or exit after performing a redirect using the header function 
            // is critical.  The rest of your PHP script will continue to execute and 
            // will be sent to the user if you do not die or exit. 
            die("Redirecting to index.php");   
        }
         
        
    } 
     
?> 

<html>
    <head><?php 
    $pagetitle = "Edit Account";
    include 'head.php'; ?>
    </head>
    <body>
        <?php 
            include 'navbar.php'; 
            include 'banner.php';
        ?>
        
        <div class="container grey">
            <h1>Edit Account</h1>
            <p class="top-lead lead text-muted">Update your account details.</p><?php
            if (isset($_SESSION["errorcodes"][0])){ ?>
                <div class="alert alert-danger" role="alert"><?php 
                    foreach ($_SESSION["errorcodes"] as $ec){  
                        echo '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> ' . $ec . '<br />';             
                    } ?>
                </div><?php 
            
            } ?> 
            <div class="row">
                <div class="col-sm-6">
                     
                    <form action="account_edit.php" method="post" class="form-horizontal">
                        <div class="form-group">
                          <label for="username" class="col-sm-3 control-label">Username</label>
                          <div class="col-sm-9">
                            <div style="margin-top: 7px;"><strong ><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></strong></div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="email" class="col-sm-3 control-label">E-Mail Address</label>
                          <div class="col-sm-9">
                            <input type="text" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" class="form-control"/>
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="password" class="col-sm-3 control-label">Password</label>
                          <div class="col-sm-9">
                            <input type="password" name="password" value="" class="form-control" />
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="confirm-password" class="col-sm-3 control-label">Retype Password</label>
                          <div class="col-sm-9">
                            <input type="password" name="confirm-password" value="" class="form-control" />
                            <i>(Leave both blank if you do not want to change your password)</i> 
                          </div>
                        </div>

                        <div class="form-group">
                          <label for="submit" class="col-sm-3 control-label"></label>
                          <div class="col-sm-9">
                            <input type="submit" value="Update Account" class="btn btn-primary btn-block" />  
                          </div>
                        </div>
                    </form>
                </div>

            <div>
            
        </div>
    </body>
</html>