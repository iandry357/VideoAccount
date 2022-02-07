<?php
function restart($conn)
{
    executeSqlFile('db-init.sql', $conn);
    // executeSqlFile('spipollhwXp22042021.sql', $conn);
}

function executeSqlFile($file_name, $conn)
/*{
$sql = file_get_contents($file_name);

$conn->exec($sql) or die ("reset DB error");
}
*/
{
    $file_content = file($file_name);
    $query = "";
    $a=0;
    foreach ($file_content as $sql_line) {
        if (trim($sql_line) != "" && strpos($sql_line, "--") === false) {
            $query .= $sql_line;
            if (substr(rtrim($query), -1) == ';') {
                try {
                    // debug("query from init-workflow");
                    // debug($query);
                    $conn->query($query);
                    
                } catch (PDOException $e) {
                    echo "<p>Headache ! <pre>$e</pre></p>";
                    echo $query."<br/>";
                    echo $file_name."<br/>";
                    print_r($conn->errorInfo());
                    exit;
                }
                $query = "";
            }
        }
    }
}

function ajout($conn){
    if(isset($_POST['Submit'])) {
        $userId = $_SESSION["userId"];    
        $vname = $_POST['vname'];
        $vlink = $_POST['vlink'];
        
                
        // checking empty fields
        if( empty($vname) || empty($vlink) ) {                
            if(empty($vname)) {
                echo '<font color="red">Video Name field is empty.</font><br>';
            }
            if(empty($pcode)) {
                echo '<font color="red">Video link field is empty.</font><br>';
            }
            
            
        } else { 
            // if all the fields are filled (not empty)             
            //insert data to database
            $stmt = $conn->prepare("insert into Videos (userId, name, link) values(:userId, :name,:link)");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $vname, PDO::PARAM_STR);
            $stmt->bindParam(':link', $vlink, PDO::PARAM_STR);
            $stmt->execute() or die(mysql_error());
            // $result = mysqli_query($conn, "INSERT INTO Videos(userId, vname, vlink) VALUES('$userId', '$vname','$vlink')");
                    
            //display success message
            echo '<font color="green">Data added successfully.</font>';
                
        }
        
    }
    addVideo($conn);
}

function getVideo($conn, $userId, $type){
    $res = "";
    if ($type == "owner"){
        // echo $userId;

        $query = "SELECT videoId, userId, name, link FROM Videos WHERE userId =".$userId;
        // echo '<br> '.$query;
        $result = $conn->query($query);
        // echo $result->rowCount();

        if ($result->rowCount() > 0) {
            $res .= '<table border="1" cellspacing="0" cellpadding="10">';
            $res .= '<tr>';
            $res .= '<th>Name</th>';
            $res .= '<th>Activité</th>';
            $res .= '</tr>';

            $sn = 1;
            while($data = $result->fetch(PDO::FETCH_ASSOC)) {
                $res .= " <tr> ";
                $res .= "<td>".$data['name']."</td>";
                $res .= "<td>".$data['name']."</td>";
                $res .= "<tr>";
                 $sn++;
                }

        }
        else{
            $res .= 'Aucune videos ajoutées';
        }
                    
        
        return $res;
    }
}