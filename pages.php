<?php


function showLogin($conn)
{
    global $VIEW;

    
    $VIEW['MAIN'] .= homepage("");

    

}


function homepage($homeFr){
    
$homeFr .= "<div class='alert alert-success'>" . HTMLbr() . HTMLbr() . HTMLcenter(HTMLform("index.php", "POST", HTMLh1("Connectez-vous ou créez un compte") . HTMLbr() . HTMLinput("hidden", "mode", "login").HTMLinput("text", "username", null, "username", true) . HTMLbr() . HTMLinput("password", "password", null, "password", true) . HTMLbr() . HTMLinput("submit", "submit", "Se connecter !", null, false, 'btn btn-dark') . HTMLbr()));

    
$homeFr .= HTMLcenter(HTMLform("index.php", "POST", HTMLbr() . HTMLinput("hidden", "mode", "register") . HTMLinput("submit", "submit", "Créer un compte", null, false, 'btn btn-dark')));

if (isset($_SESSION["wrong"])) {
    if ($_SESSION["wrong"]) {
        $homeFr .= HTMLcenter(HTMLbr() . HTMLp("Erreur mot de passe ou username", array(
            "class" => "error"
        )));
    }
}

$homeFr .= "</div></td></tr></table>";


return $homeFr;
}

function showregister($conn, $bool)
{
    global $VIEW;
    $VIEW['MAIN'] .= HTMLbr();
    $VIEW['MAIN'] .= HTMLbr() . HTMLcenter(HTMLform("index.php", "POST", HTMLh1("Sign up for a new account") . HTMLbr() . HTMLinput("text", "username", null, "Username", true, '') . HTMLbr() . HTMLinput("password", "password", null, "Password", true) . HTMLbr() . HTMLinput("hidden", "mode", "registered") . HTMLbr() . HTMLinput("submit", "submit", "create account", null, false, 'btn btn-dark')));
    // $VIEW['MAIN'] .= HTMLform("index.php", "POST", HTMLbr() . HTMLinput("hidden", "mode", "login") . HTMLinput("submit", "submit", "back to log in page", null, false, 'btn btn-dark'));
    if ($bool) {
        $VIEW['MAIN'] .= HTMLbr() . HTMLp("Username already used", array(
            "class" => "error"
        ));
    }
}

function addVideo($conn)
{
    global $VIEW;
    $VIEW['MAIN'].=<<<EOL

    
    
    <form action="index.php" method="POST" name="form1">
        <table width="50%" border="0">
            <tbody><tr> 
                <td>Nom de la vidéo</td>
                <td><input type="text" name="vname"></td>
                </tr>
                <tr> 
                <td>Lien de la vidéo</td>
                <td><input type="text" name="vlink"></td>
                </tr>
                
                <tr> 
                <td></td>
                <td>
                    <input type="hidden" name="mode" value="ajout">
                    <input type="submit" name="Submit" value="Ajouter">
                </td>
                </tr>
                        
            </tbody>
        </table>
    </form>

    EOL;

    $VIEW['MAIN'] .= "<br><br>
    <h> Liste des vidéos ajoutées </h> <br>";

    $VIEW['MAIN'] .= getVideo($conn, $_SESSION["userId"], "owner");
    
}
