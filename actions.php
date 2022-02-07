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