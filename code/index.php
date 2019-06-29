<?php
$output = '';

if ($_GET["op"] == "data") {
    $conn = connect();
    $output = data($conn);
} else if ($_GET["op"] == "placeholder") {
    $output = placeholder();
} else if (isset($_GET["op"])) {
    $output = "Unknown operation.";
} else {
    $output = "No operation specified.";
}

function data($conn) {
    $stat = pg_connection_status($conn);
    if ($stat === PGSQL_CONNECTION_OK) {
        $result = pg_query($conn, "SELECT nm FROM names");
        if (!$result) {
            return "Query failed.";
        }
        $names = "";
        while ($row = pg_fetch_row($result)) {
            $names = $names." ".$row[0];
        }
        return $names;
    } else {
        return "Connection failed.";
    }
}

function connect() {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $dbname = getenv('POSTGRES_DB');
    $user = getenv('POSTGRES_USER');
    $password = getenv('POSTGRES_PASSWORD');
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
    return $conn;
}

function placeholder() {
    $service_url = 'https://jsonplaceholder.typicode.com/todos/1';
    $curl = curl_init($service_url);
//    Authorization Example (Basic Auth)
//    $base64 = base64_encode("$user:$password");
//    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' ,"Authorization: Basic ".$base64 ));
//    For some APIs USERAGENT is required..
//    $t_vers = curl_version();
//    curl_setopt( $curl, CURLOPT_USERAGENT, 'curl/' . $t_vers['version'] );
    $curl_response = curl_exec($curl);
    return $curl_response;
}
?>

Output: <?php echo $output ?>