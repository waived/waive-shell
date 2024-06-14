WAIVE SHELL:
    This is a Denial-of-Service shell coded in PHP. It supports UDP, TCP, TLS, and HTTP floods. WaiveShell has a
    clickable interface and also supports direct API calls via HTTP-GET. The layer-4 attack vectors have dynamic
    data buffers, ranging from 1000 to 1200 bytes per packet (which can be modified). The TLS exhaustion attack
    is of a fixed-length, however the data buffer is dynamic as the byte-padding is randomized per each packet.

    This shell is NOT obfuscated!
    Use ChatGPT (https://chatgpt.com/) or other proprietary software for a custom obfuscation to be applied.

    Shells are uploaded to hacked websites via RFI, LFI, SQL Injection, etc.

    WaiveShell takes four parameters: TYPE, HOST, PORT, and TIME

        ---- TYPE: the type is specified by "udp", "tcp", "tls" or "http"
        ---- HOST: WaiveShell accepts either an IP address of a Domain Name. Do NOT enter a complete URL!
        ---- PORT: ports range from 1 to 65,535. Ensure your endpoint has the specified port open before attacking.
                   For the TLS attack, the default HTTPS port is 443 and should be used unless an alternate HTTPS
                   port is being used, ex: port 8080 https-proxy
        ---- TIME: the duration in seconds for the attack to run. Once WaiveShell is executed, the attacks cannot 
                   be aborted!

    Example: http://www.vulnerable.com/files/waiveshell.php?type=udp&host=www.example.com&port=80&time=300
