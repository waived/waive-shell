WAIVED SHELL:
    This is an interfaceless Denial-of-Service shell coded in PHP. It supports UDP, TCP, and HTTP floods.
    The layer-4 attack vectors have dynamic data buffers, ranging from 1000 to 1200 bytes per packet (which
    can be modified). This shell is NOT obfuscated. Use ChatGPT (https://chatgpt.com/) for a custom
    obfuscation to be applied.

    Shells are uploaded to hacked websites via RFI, LFI, SQL Injection, etc.

    WaiveShell takes four parameters: TYPE, HOST, PORT, and TIME

        ---- TYPE: the type is specified by "udp", "tcp" or "http"
        ---- HOST: WaiveShell accepts either an IP address of a Domain Name. Do NOT enter a complete URL!
        ---- PORT: ports range from 1 to 65,535. Ensure your endpoint has the specified port open before attacking.
        ---- TIME: the duration in seconds for the attack to run. Once WaiveShell is executed, the attacks cannot 
                   be aborted!

    Example: http://www.vulnerable.com/files/waiveshell.php?type=udp&host=www.example.com&port=80&time=300

LUCKY BOOTER:
    Lucky Shell Booter is a program written in Visual Basic (built on .NET Framework 4.8.1). The user create a
    .txt file. In this file should be each individual uploaded shell. Note: the shell URLs MUST end with 
    "waiveshell.php" for them to be considered valid. If not, they will be thrown out when loaded into the program.

    Once the user loads the contents of the .txt file into Lucky Shell Booter, the user also has the option of 
    checking to ensure the specified shells are online. This operation is multi-threaded and the user can specify
    as many threads as their operating system can manage. The default is five threads. An HTTP-HEAD request is 
    sent to each shell in the list. If Lucky Shell Booter receives a '200 OK' response from the infected server, 
    the shell will remain in the list. If any other response code is received, the shell is counted as "dead" and
    will be removed.

    Upon execution the attack, Lucky Shell Booter will iterate through the list of shells and send an HTTP-GET 
    request containing the attack parameters to each shell. Once this is complete, Lucky Shell Booter will then 
    wait for the duration specified by the user to elapse. After the attack is complete, Lucky Shell Booter will
    then refresh and more attacks can be launched.

---- BUG REPORTS ---
If a bug is incountered, please leave a comment!
