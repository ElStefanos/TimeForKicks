<?php


use dashboard\{getAssets, getModules};

$assets = new getAssets();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time for Kicks?</title>
    <link rel="stylesheet" href="<?php echo $assets->getAssetsLink("/dashboard/modules/login/css/login.css");?>">
</head>
<body>
    <div class="login_div">
        <div class="wrap_login">
            
            <form class="form_login" action="<?php echo __URL__.'/dashboard/jobs/signIn.php';?>" method="post">
                <span class="text_login">Time for Kicks?</span>

                <div class="wrap_input">
                    <input class="input" type="text" name="email" required="">
                    <span class="focus_input" data-placeholder="Username"></span>
                </div>
                <div class="wrap_input">
                    <input class="input" type="password" name="password" required="">
                    <span class="focus_input" data-placeholder="Password"></span>
                </div>
                
                <div class="button_div">
                    <div class="wrap_button">
                        <div class="bgbtn"></div>
                        <button class="login_button" type="submit" name="login">
                            Login 
                        </button>
                    </div>
                </div>
            </form>
        
        </div>
    </div>
    
</body>
</html>