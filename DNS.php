
<?php
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


/* Net_DNS_Resolver object definition {{{ */
/**
 * A DNS Resolver library
 *
 * Resolver library.  Builds a DNS query packet, sends  the packet to the
 * server and parses the reponse.
 *
 * @package Net_DNS
 */
class Net_DNS_Resolver
{
    /* class variable definitions {{{ */
    /**
     * An array of all nameservers to query
     *
     * An array of all nameservers to query
     *
     * @var array   $nameservers
     * @access public
     */
    var $nameservers;
    /**
     * The UDP port to use for the query (default = 53)
     *
     * The UDP port to use for the query (default = 53)
     *
     * @var integer $port
     * @access public
     */
    var $port;
    /**
     * The domain in which the resolver client host resides.
     *
     * The domain in which the resolver client host resides.
     *
     * @var string $domain
     * @access public
     */
    var $domain;
    /**
     * The searchlist to apply to unqualified hosts
     *
     * An array of strings containg domains to apply to unqualified hosts
     * passed to the resolver.
     *
     * @var array $searchlist
     * @access public
     */
    var $searchlist;
    /**
     * The number of seconds between retransmission of unaswered queries
     *
     * The number of seconds between retransmission of unaswered queries
     *
     * @var integer $retrans
     * @access public
     */
    var $retrans;
    /**
     * The number of times unanswered requests should be retried
     *
     * The number of times unanswered requests should be retried
     *
     * @var integer $retry
     * @access public
     */
    var $retry;
    /**
     * Whether or not to use TCP (Virtual Circuits) instead of UDP
     *
     * If set to 0, UDP will be used unless TCP is required.  TCP is
     * required for questions or responses greater than 512 bytes.
     *
     * @var boolean $usevc
     * @access public
     */
    var $usevc;
    /**
     * Unknown
     */
    var $stayopen;
    /**
     * Ignore TC (truncated) bit
     *
     * If the server responds with the TC bit set on a response, and $igntc
     * is set to 0, the resolver will automatically retransmit the request
     * using virtual circuits (TCP).
     *
     * @access public
     * @var boolean $igntc
     */
    var $igntc;
    /**
     * Recursion Desired
     *
     * Sets the value of the RD (recursion desired) bit in the header. If
     * the RD bit is set to 0, the server will not perform recursion on the
     * request.
     *
     * @var boolean $recurse
     * @access public
     */
    var $recurse;
    /**
     * Unknown
     */
    var $defnames;
    /**
     * Unknown
     */
    var $dnsrch;
    /**
     * Contains the value of the last error returned by the resolver.
     *
     * Contains the value of the last error returned by the resolver.
     *
     * @var string $errorstring
     * @access public
     */
    var $errorstring;
    /**
     * The origin of the packet.
     *
     * This contains a string containing the IP address of the name server
     * from which the answer was given.
     *
     * @var string $answerfrom
     * @access public
     */
    var $answerfrom;
    /**
     * The size of the answer packet.
     *
     * This contains a integer containing the size of the DNS packet the
     * server responded with.
     *
     * @var string $answersize
     * @access public
     */
    var $answersize;
    /**
     * The number of seconds after which a TCP connetion should timeout
     *
     * @var integer $tcp_timeout
     * @access public
     */
    var $tcp_timeout;
    /**
     * The location of the system resolv.conf file.
     *
     * The location of the system resolv.conf file.
     * 
     * @var string $resolv_conf
     */
    var $resolv_conf = '/etc/resolv.conf';
    /**
     * The name of the user defined resolv.conf
     *
     * The resolver will attempt to look in both the current directory as
     * well as the user's home directory for a user defined resolver
     * configuration file
     *
     * @var string $dotfile
     * @see Net_DNS_Resolver::$confpath
     */
    var $dotfile = '.resolv.conf';
    /**
     * A array of directories to search for the user's resolver config
     *
     * A array of directories to search for the user's resolver config
     *
     * @var string $confpath
     * @see Net_DNS_Resolver::$dotfile
     */
    var $confpath;
    /**
     * debugging flag
     *
     * If set to TRUE (non-zero), debugging code will be displayed as the
     * resolver makes the request.
     *
     * @var boolean $debug;
     * @access public
     */
    var $debug;
    /**
     * use the (currently) experimental PHP socket library
     *
     * If set to TRUE (non-zero), the Resolver will attempt to use the
     * much more effecient PHP sockets extension (if available).
     *
     * @var boolean $useEnhancedSockets;
     * @access public
     */
    var $useEnhancedSockets = 1;
    /**
     * An array of sockets connected to a name servers
     *
     * @var array $sockets
     * @access private
     */
    var $sockets;
    /**
     * axfr tcp socket
     *
     * Used to store a PHP socket resource for a connection to a server
     *
     * @var resource $_axfr_sock;
     * @access private
     */
    var $_axfr_sock;
    /**
     * axfr resource record lsit
     *
     * Used to store a resource record list from a zone transfer
     *
     * @var resource $_axfr_rr;
     * @access private
     */
    var $_axfr_rr;
    /**
     * axfr soa count
     *
     * Used to store the number of soa records received from a zone transfer
     *
     * @var resource $_axfr_soa_count;
     * @access private
     */
    var $_axfr_soa_count;


    /* }}} */
    /* class constructor - Net_DNS_Resolver() {{{ */
    /**
     * Initializes the Resolver Object
     */
    function Net_DNS_Resolver()
    {
        $default = array(
                'nameservers' => array(),
                'port'    => '53',
                'domain'  => '',
                'searchlist'  => array(),
                'retrans' => 5,
                'retry'   => 4,
                'usevc'   => 0,
                'stayopen'  => 0,
                'igntc'   => 0,
                'recurse' => 1,
                'defnames'  => 1,
                'dnsrch'  => 1,
                'debug'   => 0,
                'errorstring' => 'unknown error or no error',
                'answerfrom'    => '',
                'answersize'    => 0,
                'tcp_timeout'   => 1200
                );
        foreach ($default as $k => $v) {
            $this->{$k} = $v;
        }
        //$this->confpath[0] ='C:\php\includes';
		echo "Env == ". getenv('HOME');
        $this->confpath[1] = '.';
        $this->res_init();
    }

    /* }}} */
    /* Net_DNS_Resolver::res_init() {{{ */
    /**
     * Initalizes the resolver library
     *
     * res_init() searches for resolver library configuration files  and
     * initializes the various properties of the resolver object.
     *
     * @see Net_DNS_Resolver::$resolv_conf, Net_DNS_Resolver::$dotfile,
     *      Net_DNS_Resolver::$confpath, Net_DNS_Resolver::$searchlist,
     *      Net_DNS_Resolver::$domain, Net_DNS_Resolver::$nameservers
     * @access public
     */
    function res_init()
    {
        $err = error_reporting(1);
        if (file_exists($this->resolv_conf) && is_readable($this->resolv_conf)) {
            $this->read_config($this->resolv_conf);
        }

        foreach ($this->confpath as $dir) {
            $file = "$dir/" . $this->dotfile;
            if (file_exists($file) && is_readable($file)) {
                $this->read_config($file);
            }
        }

        $this->read_env();

        if (!strlen($this->domain) && strlen($this->searchlist)) {
            $this->default{'domain'} = $this->default{'searchlist'}[0];
        } else if (! strlen($this->searchlist) && strlen($this->domain)) {
            $this->searchlist = array($this->domain);
        }
        error_reporting($err);
    }

    /* }}} */
    /* Net_DNS_Resolver::read_config {{{ */
    /**
     * Reads and parses a resolver configuration file
     *
     * @param string $file The name of the file to open and parse
     */
    function read_config($file)
    {
        if (! ($f = fopen($file, 'r'))) {
            $this->error = "can't open $file";
        }

        while (! feof($f)) {
            $line = chop(fgets($f, 10240));
            $line = ereg_replace('(.*)[;#].*', '\\1', $line);
            if (ereg("^[ \t]*$", $line, $regs)) {
                continue;
            }
            ereg("^[ \t]*([^ \t]+)[ \t]+([^ \t]+)", $line, $regs);
            $option = $regs[1];
            $value = $regs[2];

            switch ($option) {
                case 'domain':
                    $this->domain = $regs[2];
                    break;
                case 'search':
                    $this->searchlist[count($this->searchlist)] = $regs[2];
                    break;
                case 'nameserver':
                    foreach (split(' ', $regs[2]) as $ns)
                        $this->nameservers[count($this->nameservers)] = $ns;
                    break;
            }
        }
        fclose($f);
    }

    /* }}} */
    /* Net_DNS_Resolver::read_env() {{{ */
    /**
     * Examines the environment for resolver config information
     */
    function read_env()
    {
        if (getenv('RES_NAMESERVERS')) {
            $this->nameservers = split(' ', getenv('RES_NAMESERVERS'));
			//echo"res_name = ".getenv('RES_NAMESERVERS');
        }

        if (getenv('RES_SEARCHLIST')) {
            $this->searchlist = split(' ', getenv('RES_SEARCHLIST'));
        }

        if (getenv('LOCALDOMAIN')) {
            $this->domain = getenv('LOCALDOMAIN');
        }

        if (getenv('RES_OPTIONS')) {
            $env = split(' ', getenv('RES_OPTIONS'));
            foreach ($env as $opt) {
                list($name, $val) = split(':', $opt);
                if ($val == '') {
                    $val = 1;
                }
                $this->{$name} = $val;
            }
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::string() {{{ */
    /**
     * Builds a string containing the current state of the resolver
     *
     * Builds formatted string containing the state of the resolver library suited
     * for display.
     *
     * @access public
     */
    function string()
    {
        $state = ";; Net_DNS_Resolver state:\n";
        $state .= ';;  domain       = ' . $this->domain . "\n";
        $state .= ';;  searchlist   = ' . implode(' ', $this->searchlist) . "\n";
        $state .= ';;  nameservers  = ' . implode(' ', $this->nameservers) . "\n"; 
        $state .= ';;  port         = ' . $this->port . "\n";
        $state .= ';;  tcp_timeout  = ';
        $state .= ($this->tcp_timeout ? $this->tcp_timeout : 'indefinite') . "\n";
        $state .= ';;  retrans  = ' . $this->retrans . '  ';
        $state .= 'retry    = ' . $this->retry . "\n";
        $state .= ';;  usevc    = ' . $this->usevc . '  ';
        $state .= 'stayopen = ' . $this->stayopen . '    ';
        $state .= 'igntc = ' . $this->igntc . "\n";
        $state .= ';;  defnames = ' . $this->defnames . '  ';
        $state .= 'dnsrch   = ' . $this->dnsrch . "\n";
        $state .= ';;  recurse  = ' . $this->recurse . '  ';
        $state .= 'debug    = ' . $this->debug . "\n";
        return($state);
		//print_f ($state);
    }

    /* }}} */
    /* Net_DNS_Resolver::nextid() {{{ */
    /**
     * Returns the next request Id to be used for the DNS packet header
     */
    function nextid()
    {
        global $_Net_DNS_packet_id;

        return($_Net_DNS_packet_id++);
    }
    /* }}} */
    /* not completed - Net_DNS_Resolver::nameservers() {{{ */
    /**
     * Unknown - not ported yet
     */
    function nameservers($nsa)
    {
        $defres = new Net_DNS_Resolver();

        if (is_array($ns)) {
            foreach ($nsa as $ns) {
                if (ereg('^[0-9]+(\.[0-9]+){0,3}$', $ns, $regs)) {
                    $newns[count($newns)] = $ns;
                } else {
                    /* 
                     * This still needs to be ported
                     *
                     if ($ns !~ /\./) {
                     if (defined $defres->searchlist) {
                     @names = map { $ns . "." . $_ }
                     $defres->searchlist;
                     }
                     elsif (defined $defres->domain) {
                     @names = ($ns . "." . $defres->domain);
                     }
                     }
                     else {
                     @names = ($ns);
                     }

                     my $packet = $defres->search($ns);
                     $this->errorstring($defres->errorstring);
                     if (defined($packet)) {
                     push @a, cname_addr([@names], $packet);
                     }
                 */
                }
            }
            $this->nameservers = $nsa;
        }
        return($this->nameservers);
		//printf($this->nameservers);
    }

    /* }}} */
    /* not completed - Net_DNS_Resolver::cname_addr() {{{ */
    /**
     * Unknown - not ported yet
     */
    function cname_addr()
    {
    }
    /* }}} */
    /* Net_DNS_Resolver::search() {{{ */
    /**
     * Searches nameservers for an answer
     *
     * Goes through the search list and attempts to resolve name based on
     * the information in the search list.
     *
     * @param string $name The name (LHS) of a resource record to query.
     * @param string $type The type of record to query.
     * @param string $class The class of record to query.
     * @return mixed    an object of type Net_DNS_Packet on success,
     *                  or FALSE on failure.
     * @see Net_DNS::typesbyname(), Net_DNS::classesbyname()
     * @access public
     */
    function search($name, $type = 'A', $class = 'IN')
    {
        /*
         * If the name looks like an IP address then do an appropriate
         * PTR query.
         */
        if (preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $name, $regs)) {
            $name = "$regs[4].$regs[3].$regs[2].$regs[1].in-addr.arpa";
            $type = 'PTR';
        }

        /*
         * If the name contains at least one dot then try it as is first.
         */
        if (strchr($name, '.')) {
            if ($this->debug) {
                echo ";; search($name, $type, $class)\n";
				//echo ";; searching for ".$name;
            }
            $ans = $this->query($name, $type, $class);
            if ((is_object($ans)) && $ans->header->ancount > 0) {
                return($ans);
            }
        }

        /*
         * If the name doesn't end in a dot then apply the search list.
         */
        $domain = '';
        if ((! preg_match('/\.$/', $name)) && $this->dnsrch) {
            foreach ($this->searchlist as $domain) {
                $newname = "$name.$domain";
                if ($this->debug) {
                    echo ";; search($newname, $type, $class)\n";
                }
                $ans = $this->query($newname, $type, $class);
                if ((is_object($ans)) && $ans->header->ancount > 0) {
                    return($ans);
                }
            }
        }

        /*
         * Finally, if the name has no dots then try it as is.
         */
        if (! strlen(strchr($name, '.'))) {
            if ($this->debug) {
                echo ";; search($name, $type, $class)\n";
            }
            $ans = $this->query("$name.", $type, $class);
            if (($ans = $this->query($name, $type, $class)) &&
                    $ans->header->ancount > 0) {
                return($ans);
            }
        }

        /*
         * No answer was found.
         */
        return(0);
    }

    /* }}} */
    /* Net_DNS_Resolver::query() {{{ */
    /**
     * Queries nameservers for an answer
     *
     * Queries the nameservers listed in the resolver configuration for  an
     * answer to a question packet.
     *
     * @param string $name The name (LHS) of a resource record to query.
     * @param string $type The type of record to query.
     * @param string $class The class of record to query.
     * @return mixed    an object of type Net_DNS_Packet on success,
     *                  or FALSE on failure.
     * @see Net_DNS::typesbyname(), Net_DNS::classesbyname()
     * @access public
     */
    function query($name, $type = 'A', $class = 'IN')
    {
        /*
         * If the name doesn't contain any dots then append the default domain.
         */
        if ((strchr($name, '.') < 0) && $this->defnames) {
            $name .= '.' . $this->domain;
        }

        /*
         * If the name looks like an IP address then do an appropriate
         * PTR query.
         */
        if (preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $name, $regs)) {
            $name = "$regs[4].$regs[3].$regs[2].$regs[1].in-addr.arpa";
            $type = 'PTR';
        }

        if ($this->debug) {
            echo ";; query($name, $type, $class)\n";
        }
        $packet = new Net_DNS_Packet($this->debug);
        $packet->buildQuestion($name, $type, $class);
		
        $packet->header->rd = $this->recurse;
		
        $ans = $this->send($packet);
		
        if (is_object($ans) && $ans->header->ancount > 0) {
            return($ans);
        }
        return(0);
    }

    /* }}} */
    /* Net_DNS_Resolver::send($packetORname, $qtype = '', $qclass = '') {{{ */
    /**
     * Sends a packet to a nameserver
     *
     * Determines the appropriate communication method (UDP or TCP) and
     * send a DNS packet to a nameserver.  Use of the this function
     * directly  is discouraged. $packetORname should always be a properly
     * formatted binary DNS packet.  However, it is possible to send  a
     * query here and bypass Net_DNS_Resolver::query()
     *
     * @param string $packetORname      A binary DNS packet stream or a
     *                                  hostname to query
     * @param string $qtype     This should not be used
     * @param string $qclass    This should not be used
     * @return object Net_DNS_Packet    An answer packet object
     */
    function send($packetORname, $qtype = '', $qclass = '')
    {
        $packet = $this->make_query_packet($packetORname, $qtype, $qclass);
        $packet_data = $packet->data();

        if ($this->usevc != 0 || strlen($packet_data > 512)) {
            $ans = $this->send_tcp($packet, $packet_data);
        } else {
            $ans = $this->send_udp($packet, $packet_data);

            if ($ans && $ans->header->tc && $this->igntc != 0) {
                if ($this->debug) {
                    echo ";;\n;; packet truncated: retrying using TCP\n";
                }
                $ans = $this->send_tcp($packet, $packet_data);
            }
        }
        return($ans);
    }

    /* }}} */
    /* Net_DNS_Resolver::printhex($packet_data) {{{ */
    /**
     * Sends a packet via TCP to the list of name servers.
     */
    function printhex($data)
    {
        $data = '  ' . $data;
        $start = 0;
        while ($start < strlen($data)) {
            printf(';; %03d: ', $start);
            for ($ctr = $start; $ctr < $start+16; $ctr++) {
                if ($ctr < strlen($data))
                    printf('%02x ', ord($data[$ctr]));
                else
                    echo '   ';
            }
            echo '   ';
            for ($ctr = $start; $ctr < $start+16; $ctr++) {
                if (ord($data[$ctr]) < 32 || ord($data[$ctr]) > 127) {
                    echo '.';
                } else {
                    echo $data[$ctr];
                }
            }
            echo "\n";
            $start += 16;
        }
    }
    /* }}} */
    /* Net_DNS_Resolver::send_tcp($packet, $packet_data) {{{ */
    /**
     * Sends a packet via TCP to the list of name servers.
     *
     * @param string $packet    A packet object to send to the NS list
     * @param string $packet_data   The data in the packet as returned by
     *                              the Net_DNS_Packet::data() method
     * @return object Net_DNS_Packet Returns an answer packet object
     * @see Net_DNS_Resolver::send_udp(), Net_DNS_Resolver::send()
     */
    function send_tcp($packet, $packet_data)
    {
        if (! count($this->nameservers)) {
            $this->errorstring = 'no nameservers';
            if ($this->debug) {
                echo ";; ERROR: send_tcp: no nameservers\n";
            }
            return(NULL);
        }
        $timeout = $this->tcp_timeout;

        foreach ($this->nameservers as $ns) {
            $dstport = $this->port;
            if ($this->debug) {
                echo ";; send_tcp($ns:$dstport)\n";
            }
            $sock_key = "$ns:$dstport";
            if (isset($this->sockets[$sock_key]) && is_resource($this->sockets[$sock_key])) {
                $sock = &$this->sockets[$sock_key];
            } else {
                if (! ($sock = @fsockopen($ns, $dstport, $errno,
                                $errstr, $timeout))) {
                    $this->errorstring = 'connection failed';
                    if ($this->debug) {
                        echo ";; ERROR: send_tcp: connection failed: $errstr\n";
                    }
                    continue;
                }
                $this->sockets[$sock_key] = $sock;
                unset($sock);
                $sock = &$this->sockets[$sock_key];
            }
            $lenmsg = pack('n', strlen($packet_data));
            if ($this->debug) {
                echo ';; sending ' . strlen($packet_data) . " bytes\n";
            }

            if (($sent = fwrite($sock, $lenmsg)) == -1) {
                $this->errorstring = 'length send failed';
                if ($this->debug) {
                    echo ";; ERROR: send_tcp: length send failed\n";
                }
                continue;
            }

            if (($sent = fwrite($sock, $packet_data)) == -1) {
                $this->errorstring = 'packet send failed';
                if ($this->debug) {
                    echo ";; ERROR: send_tcp: packet data send failed\n";
                }
            }

            socket_set_timeout($sock, $timeout);
            $buf = fread($sock, 2);
            $e = socket_get_status($sock);
            /* If $buf is empty, we want to supress errors
               long enough to reach the continue; down the line */
            $len = @unpack('nint', $buf);
            $len = @$len['int'];
            if (!$len) {
                continue;
            }
            $buf = fread($sock, $len);
            $actual = strlen($buf);
            $this->answerfrom = $ns;
            $this->answersize = $len;
            if ($this->debug) {
                echo ";; received $actual bytes\n";
            }
            if ($actual != $len) {
                $this->errorstring = "expected $len bytes, received $buf";
                if ($this->debug) {
                    echo ';; send_tcp: ' . $this->errorstring;
                }
                continue;
            }

            $ans = new Net_DNS_Packet($this->debug);
            if (is_null($ans->parse($buf))) {
                continue;
            }
            $this->errorstring = $ans->header->rcode;
            $ans->answerfrom = $this->answerfrom;
            $ans->answersize = $this->answersize;
            return($ans);
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::send_udp_no_sock_lib($packet, $packet_data) {{{ */
    /**
     * Sends a packet via UDP to the list of name servers.
     *
     * This function sends a packet to a nameserver.  It is called by
     * send_udp if the sockets PHP extension is not compiled into PHP.
     *
     * @param string $packet    A packet object to send to the NS list
     * @param string $packet_data   The data in the packet as returned by
     *                              the Net_DNS_Packet::data() method
     * @return object Net_DNS_Packet Returns an answer packet object
     * @see Net_DNS_Resolver::send_tcp(), Net_DNS_Resolver::send(),
     *      Net_DNS_Resolver::send_udp(), Net_DNS_Resolver::send_udp_with_sock_lib()
     */
    function send_udp_no_sock_lib($packet, $packet_data)
    {
        $retrans = $this->retrans;
        $timeout = $retrans;

        /*
         * PHP doesn't have excellent socket support as of this writing.
         * This needs to be rewritten when PHP POSIX socket support is
         * complete.
         * Obviously, this code is MUCH different than the PERL implementation
         */

        $w = error_reporting(0);
        $ctr = 0;
        // Create a socket handle for each nameserver
        foreach ($this->nameservers as $nameserver) {
            if ($sock[$ctr++] = fsockopen("udp://$nameserver", $this->port)) {
                $peerhost[$ctr-1] = $nameserver;
                $peerport[$ctr-1] = $this->port;
                socket_set_blocking($sock, FALSE);
            } else {
                $ctr--;
            }
        }
        error_reporting($w);

        if ($ctr == 0) {
            $this->errorstring = 'no nameservers';
            return(NULL);
        }

        for ($i = 0; $i < $this->retry; $i++, $retrans *= 2,
                $timeout = (int) ($retrans / (count($ns)+1))) {
            if ($timeout < 1) {
                $timeout = 1;
            }

            foreach ($sock as $k => $s) {
                if ($this->debug) {
                    echo ';; send_udp(' . $peerhost[$k] . ':' . $peerport[$k] . '): sending ' . strlen($packet_data) . " bytes\n";
                }

                if (! fwrite($s, $packet_data)) {
                    if ($this->debug) {
                        echo ";; send error\n";
                    }
                }

                /*
                 *  Here's where it get's really nasty.  We don't have a select()
                 *  function here, so we have to poll for a response... UGH!
                 */

                $timetoTO  = time() + (double)microtime() + $timeout;

                /*
                 * let's sleep for a few hundred microseconds to let the
                 * data come in from the network...
                 */
                usleep(500);
                $buf = '';
                while (! strlen($buf) && $timetoTO > (time() +
                            (double)microtime())) {
                    socket_set_blocking($s, FALSE);
                    if ($buf = fread($s, 512)) {
                        $this->answerfrom = $peerhost[$k];
                        $this->answersize = strlen($buf);
                        if ($this->debug) {
                            echo ';; answer from ' . $peerhost[$k] . ':' .
                                $peerport[$k] .  ': ' . strlen($buf) . " bytes\n";
                        }
                        $ans = new Net_DNS_Packet($this->debug);
                        if ($ans->parse($buf)) {
                            if ($ans->header->qr != '1') {
                                continue;
                            }
                            if ($ans->header->id != $packet->header->id) {
                                continue;
                            }
                            $this->errorstring = $ans->header->rcode;
                            $ans->answerfrom = $this->answerfrom;
                            $ans->answersize = $this->answersize;
                            return($ans);
                        }
                    }
                    // Sleep another 1/100th of a second... this sucks...
                    usleep(1000);
                }

                $this->errorstring = 'query timed out';
                return(NULL);
            }
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::send_udp_with_sock_lib($packet, $packet_data) {{{ */
    /**
     * Sends a packet via UDP to the list of name servers.
     *
     * This function sends a packet to a nameserver.  It is called by
     * send_udp if the sockets PHP extension is compiled into PHP.
     *
     * @param string $packet    A packet object to send to the NS list
     * @param string $packet_data   The data in the packet as returned by
     *                              the Net_DNS_Packet::data() method
     * @return object Net_DNS_Packet Returns an answer packet object
     * @see Net_DNS_Resolver::send_tcp(), Net_DNS_Resolver::send(),
     *      Net_DNS_Resolver::send_udp(), Net_DNS_Resolver::send_udp_no_sock_lib()
     */
    function send_udp_with_sock_lib($packet, $packet_data)
    {
        $retrans = $this->retrans;
        $timeout = $retrans;

        //$w = error_reporting(0);
        $ctr = 0;
        // Create a socket handle for each nameserver
        foreach ($this->nameservers as $nameserver) {
            if ((($sock[$ctr++] = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) >= 0) &&
                    socket_connect($sock[$ctr-1], $nameserver, $this->port) >= 0) {
                $peerhost[$ctr-1] = $nameserver;
                $peerport[$ctr-1] = $this->port;
                socket_set_nonblock($sock[$ctr-1]);
            } else {
                $ctr--;
            }
        }
        //error_reporting($w);

        if ($ctr == 0) {
            $this->errorstring = 'no nameservers';
            return(NULL);
        }

        for ($i = 0; $i < $this->retry; $i++, $retrans *= 2,
                $timeout = (int) ($retrans / (count($ns)+1))) {
            if ($timeout < 1) {
                $timeout = 1;
            }

            foreach ($sock as $k => $s) {
                if ($this->debug) {
                    echo ';; send_udp(' . $peerhost[$k] . ':' . $peerport[$k] . '): sending ' . strlen($packet_data) . " bytes\n";
                }

                if (! socket_write($s, $packet_data)) {
                    if ($this->debug) {
                        echo ";; send error\n";
                    }
                }

                $set = array($s);
                if ($this->debug) {
                    echo ";; timeout set to $timeout seconds\n";
                }
                $changed = socket_select($set, $w = null, $e = null, $timeout);
                if ($changed) {
                    $buf = socket_read($set[0], 512);
                    $this->answerfrom = $peerhost[$k];
                    $this->answersize = strlen($buf);
                    if ($this->debug) {
                        echo ';; answer from ' . $peerhost[$k] . ':' .
                            $peerport[$k] .  ': ' . strlen($buf) . " bytes\n";
                    }
                    $ans = new Net_DNS_Packet($this->debug);
                    if ($ans->parse($buf)) {
                        if ($ans->header->qr != '1') {
                            continue;
                        }
                        if ($ans->header->id != $packet->header->id) {


                            continue;
                        }
                        $this->errorstring = $ans->header->rcode;
                        $ans->answerfrom = $this->answerfrom;
                        $ans->answersize = $this->answersize;
                        return($ans);
                    }
                }

                $this->errorstring = 'query timed out';
                return(NULL);
            }
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::send_udp($packet, $packet_data) {{{ */
    /**
     * Sends a packet via UDP to the list of name servers.
     *
     * This function sends a packet to a nameserver.  send_udp calls
     * either Net_DNS_Resolver::send_udp_no_sock_lib() or
     * Net_DNS_Resolver::send_udp_with_sock_lib() depending on whether or
     * not the sockets extension is compiled into PHP.  Note that using the
     * sockets extension is MUCH more effecient.
     *
     * @param object Net_DNS_Packet $packet A packet object to send to the NS list
     * @param string $packet_data   The data in the packet as returned by
     *                              the Net_DNS_Packet::data() method
     * @return object Net_DNS_Packet Returns an answer packet object
     * @see Net_DNS_Resolver::send_tcp(), Net_DNS_Resolver::send(),
     *      Net_DNS_Resolver::send_udp(), Net_DNS_Resolver::send_udp_no_sock_lib()
     */
    function send_udp($packet, $packet_data)
    {
        if (extension_loaded('sockets') && $this->useEnhancedSockets) {
            if ($this->debug) {
                echo "\n;; using extended PHP sockets\n";
            }
            return($this->send_udp_with_sock_lib($packet, $packet_data));
        } else {
            if ($this->debug) {
                echo "\n;; using simple sockets\n";
            }
            return($this->send_udp_no_sock_lib($packet, $packet_data));
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::make_query_packet($packetORname, $type = '', $class = '') {{{ */
    /**
     * Unknown
     */
    function make_query_packet($packetORname, $type = '', $class = '')
    {
        if (is_object($packetORname) && get_class($packetORname) == 'net_dns_packet') {
            $packet = $packetORname;
        } else {
            $name = $packetORname;
            if ($type == '') {
                $type = 'A';
            }
            if ($class == '') {
                $class = 'IN';
            }

            /*
             * If the name looks like an IP address then do an appropriate
             * PTR query.
             */
            if (preg_match('/^(\d+)\.(\d+)\.(\d+)\.(\d+)$/', $name, $regs)) {
                $name = "$regs[4].$regs[3].$regs[2].$regs[1].in-addr.arpa";
                $type = 'PTR';
            }

            if ($this->debug) {
                echo ";; query($name, $type, $class)\n";
            }
            $packet = new Net_DNS_Packet($this->debug);
            $packet->buildQuestion($name, $type, $class);
        }

        $packet->header->rd = $this->recurse;

        return($packet);
    }

    /* }}} */
    /* Net_DNS_Resolver::axfr_old($dname, $class = 'IN') {{{ */
    /**
     * Performs an AXFR query (zone transfer) (OLD BUGGY STYLE)
     *
     * This is deprecated and should not be used!
     *
     * @param string $dname The domain (zone) to transfer
     * @param string $class The class in which to look for the zone.
     * @return object Net_DNS_Packet
     * @access public
     */
    function axfr_old($dname, $class = 'IN')
    {
        return($this->axfr($dname, $class, TRUE));
    }
    /* }}} */
    /* Net_DNS_Resolver::axfr($dname, $class = 'IN', $old = FALSE) {{{ */
    /**
     * Performs an AXFR query (zone transfer)
     *
     * Requests a zone transfer from the nameservers. Note that zone
     * transfers will ALWAYS use TCP regardless of the setting of the
     * Net_DNS_Resolver::$usevc flag.  If $old is set to TRUE, Net_DNS requires
     * a nameserver that supports the many-answers style transfer format.  Large
     * zone transfers will not function properly.  Setting $old to TRUE is _NOT_
     * recommended and should only be used for backwards compatibility.
     *
     * @param string $dname The domain (zone) to transfer
     * @param string $class The class in which to look for the zone.
     * @param boolean $old Requires 'old' style many-answer format to function.  Used for backwards compatibility only.
     * @return object Net_DNS_Packet
     * @access public
     */
    function axfr($dname, $class = 'IN', $old = FALSE)
    {
        if ($old) {
            if ($this->debug) {
                echo ";; axfr_start($dname, $class)\n";
            }
            if (! count($this->nameservers)) {
                $this->errorstring = 'no nameservers';
                if ($this->debug) {
                    echo ";; ERROR: no nameservers\n";
                }
                return(NULL);
            }
            $packet = $this->make_query_packet($dname, 'AXFR', $class);
            $packet_data = $packet->data();
            $ans = $this->send_tcp($packet, $packet_data);
            return($ans);
        } else {
            if ($this->axfr_start($dname, $class) === NULL) {
                return(NULL);
            }
            $ret = array();
            while (($ans = $this->axfr_next()) !== NULL) {
                if ($ans === NULL) {
                    return(NULL);
                }
                array_push($ret, $ans);
            }
            return($ret);
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::axfr_start($dname, $class = 'IN') {{{ */
    /**
     * Sends a packet via TCP to the list of name servers.
     *
     * @param string $packet    A packet object to send to the NS list
     * @param string $packet_data   The data in the packet as returned by
     *                              the Net_DNS_Packet::data() method
     * @return object Net_DNS_Packet Returns an answer packet object
     * @see Net_DNS_Resolver::send_tcp()
     */
    function axfr_start($dname, $class = 'IN')
    {
        if ($this->debug) {
            echo ";; axfr_start($dname, $class)\n";
        }

        if (! count($this->nameservers)) {
            $this->errorstring = "no nameservers";
            if ($this->debug) {
                echo ";; ERROR: axfr_start: no nameservers\n";
            }
            return(NULL);
        }
        $packet = $this->make_query_packet($dname, "AXFR", $class);
        $packet_data = $packet->data();

        $timeout = $this->tcp_timeout;

        foreach ($this->nameservers as $ns) {
            $dstport = $this->port;
            if ($this->debug) {
                echo ";; axfr_start($ns:$dstport)\n";
            }
            $sock_key = "$ns:$dstport";
            if (is_resource($this->sockets[$sock_key])) {
                $sock = &$this->sockets[$sock_key];
            } else {
                if (! ($sock = fsockopen($ns, $dstport, $errno,
                                $errstr, $timeout))) {
                    $this->errorstring = "connection failed";
                    if ($this->debug) {
                        echo ";; ERROR: axfr_start: connection failed: $errstr\n";
                    }
                    continue;
                }
                $this->sockets[$sock_key] = $sock;
                unset($sock);
                $sock = &$this->sockets[$sock_key];
            }
            $lenmsg = pack("n", strlen($packet_data));
            if ($this->debug) {
                echo ";; sending " . strlen($packet_data) . " bytes\n";
            }

            if (($sent = fwrite($sock, $lenmsg)) == -1) {
                $this->errorstring = "length send failed";
                if ($this->debug) {
                    echo ";; ERROR: axfr_start: length send failed\n";
                }
                continue;
            }

            if (($sent = fwrite($sock, $packet_data)) == -1) {
                $this->errorstring = "packet send failed";
                if ($this->debug) {
                    echo ";; ERROR: axfr_start: packet data send failed\n";
                }
            }

            socket_set_timeout($sock, $timeout);

            $this->_axfr_sock = $sock;
            $this->_axfr_rr = array();
            $this->_axfr_soa_count = 0;
            return($sock);
        }
    }

    /* }}} */
    /* Net_DNS_Resolver::axfr_next() {{{ */
    /**
     * Requests the next RR from a existing transfer started with axfr_start
     *
     * @return object Net_DNS_RR Returns a Net_DNS_RR object of the next RR
     *                           from a zone transfer.
     * @see Net_DNS_Resolver::send_tcp()
     */
    function axfr_next()
    {
        if (! count($this->_axfr_rr)) {
            if (! isset($this->_axfr_sock) || ! is_resource($this->_axfr_sock)) {
                $this->errorstring = 'no zone transfer in progress';
                return(NULL);
            }
            $timeout = $this->tcp_timeout;
            $buf = $this->read_tcp($this->_axfr_sock, 2, $this->debug);
            if (! strlen($buf)) {
                $this->errorstring = 'truncated zone transfer';
                return(NULL);
            }
            $len = unpack('n1len', $buf);
            $len = $len['len'];
            if (! $len) {
                $this->errorstring = 'truncated zone transfer';
                return(NULL);
            }
            $buf = $this->read_tcp($this->_axfr_sock, $len, $this->debug);
            if ($this->debug) {
                echo ';; received ' . strlen($buf) . "bytes\n";
            }
            if (strlen($buf) != $len) {
                $this->errorstring = 'expected ' . $len . ' bytes, received ' . strlen($buf);
                if ($this->debug) {
                    echo ';; ' . $err . "\n";
                }
                return(NULL);
            }
            $ans = new Net_DNS_Packet($this->debug);
            if (! $ans->parse($buf)) {
                if (! $this->errorstring) {
                    $this->errorstring = 'unknown error during packet parsing';
                }
                return(NULL);
            }
            if ($ans->header->ancount < 1) {
                $this->errorstring = 'truncated zone transfer';
                return(NULL);
            }
            if ($ans->header->rcode != 'NOERROR') {
                $this->errorstring = 'errorcode ' . $ans->header->rcode . ' returned';
                return(NULL);
            }
            foreach ($ans->answer as $rr) {
                if ($rr->type == 'SOA') {
                    if (++$this->_axfr_soa_count < 2) {
                        array_push($this->_axfr_rr, $rr);
                    }
                } else {
                    array_push($this->_axfr_rr, $rr);
                }
            }
            if ($this->_axfr_soa_count >= 2) {
                unset($this->_axfr_sock);
            }
        }
        $rr = array_shift($this->_axfr_rr);
        return($rr);
    }

    /* }}} */
    /* Net_DNS_Resolver::read_tcp() {{{ */
    /**
     * Unknown - not ported yet
     */
    function read_tcp($sock, $nbytes, $debug = 0)
    {
        $buf = '';
        while (strlen($buf) < $nbytes) {
            $nread = $nbytes - strlen($buf);
            $read_buf = '';
            if ($debug) {
                echo ";; read_tcp: expecting $nread bytes\n";
            }
            $read_buf = fread($sock, $nread);
            if (! strlen($read_buf)) {
                if ($debug) {
                    echo ";; ERROR: read_tcp: fread failed\n";
                }
                break;
            }
            if ($debug) {
                echo ';; read_tcp: received ' . strlen($read_buf) . " bytes\n";
            }
            if (!strlen($read_buf)) {
                break;
            }

            $buf .= $read_buf;
        }
        return($buf);
    }
    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * expandtab on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */

class Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    /* }}} */

    /*
     * I finally did it... i pass an array to the function
     * instead of a parameter list... UGH... i hate perl...
     */
    /* class constructor - Net_DNS_RR($rrdata) {{{ */
    function Net_DNS_RR($rrdata)
    {
        if (is_string($rrdata)) {
            $this = $this->new_from_string($rrdata);
        } else if (count($rrdata) == 7) {
            list ($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset) = $rrdata;
            $this = $this->new_from_data($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset);
        } else {
            $this = $this->new_from_array($rrdata);
        }
    }

    /* }}} */
    /* Net_DNS_RR::new_from_data($name, $ttl, $rrtype, $rrclass, $rdlength, $data, $offset) {{{ */
    function new_from_data($name, $rrtype, $rrclass, $ttl, $rdlength, $data, $offset)
    {
        $this->name = $name;
        $this->type = $rrtype;
        $this->class = $rrclass;
        $this->ttl = $ttl;
        $this->rdlength = $rdlength;
        $this->rdata = substr($data, $offset, $rdlength);
        if (class_exists('Net_DNS_RR_' . $rrtype)) {
            $scn = 'Net_DNS_RR_' . $rrtype;
            $subclass = new $scn($this, $data, $offset);
            return($subclass);
        } else {
            return($this);
        }
    }

    /* }}} */
    /* Net_DNS_RR::new_from_string($rrstring, $update_type = '') {{{ */
    function new_from_string($rrstring, $update_type = '')
    {
        $ttl = 0;
        $parts = preg_split('/[\s]+/', $rrstring);
        while ($s = array_shift($parts)) {
            if (!isset($name)) {
                $name = ereg_replace('\.+$', '', $s);
            } else if (preg_match('/^\d+$/', $s)) {
                $ttl = $s;
            } else if (!isset($rrclass) && ! is_null(Net_DNS::classesbyname(strtoupper($s)))) {
                $rrclass = strtoupper($s);
                $rdata = join(' ', $parts);
            } else if (! is_null(Net_DNS::typesbyname(strtoupper($s)))) {
                $rrtype = strtoupper($s);
                $rdata = join(' ', $parts);
                break;
            } else {
                break;
            }
        }

        /*
         *  Do we need to do this?
         */
        $rdata = trim(chop($rdata));

        if (! strlen($rrtype) && strlen($rrclass) && $rrclass == 'ANY') {
            $rrtype = $rrclass;
            $rrclass = 'IN';
        } else if (! isset($rrclass)) {
            $rrclass = 'IN';
        }

        if (! strlen($rrtype)) {
            $rrtype = 'ANY';
        }

        if (strlen($update_type)) {
            $update_type = strtolower($update_type);
            if ($update_type == 'yxrrset') {
                $ttl = 0;
                if (! strlen($rdata)) {
                    $rrclass = 'ANY';
                }
            } else if ($update_type == 'nxrrset') {
                $ttl = 0;
                $rrclass = 'NONE';
                $rdata = '';
            } else if ($update_type == 'yxdomain') {
                $ttl = 0;
                $rrclass = 'ANY';
                $rrtype = 'ANY';
                $rdata = '';
            } else if ($update_type == 'nxdomain') {
                $ttl = 0;
                $rrclass = 'NONE';
                $rrtype = 'ANY';
                $rdata = '';
            } else if (preg_match('/^(rr_)?add$/', $update_type)) {
                $update_type = 'add';
                if (! $ttl) {
                    $ttl = 86400;
                }
            } else if (preg_match('/^(rr_)?del(ete)?$/', $update_type)) {
                $update_type = 'del';
                $ttl = 0;
                $rrclass = $rdata ? 'NONE' : 'ANY';
            }
        }

        if (strlen($rrtype)) {
            $this->name = $name;
            $this->type = $rrtype;
            $this->class = $rrclass;
            $this->ttl = $ttl;
            $this->rdlength = 0;
            $this->rdata = '';

            if (class_exists('Net_DNS_RR_' . $rrtype)) {
                $scn = 'Net_DNS_RR_' . $rrtype;
                $rc = new $scn($this, $rdata);
                return($rc);
            } else {
                return($this);
            }
        } else {
            return(NULL);
        }
    }

    /* }}} */
    /* Net_DNS_RR::new_from_array($rrarray) {{{ */
    function new_from_array($rrarray)
    {
        foreach ($rrarray as $k => $v) {
            $this->{strtolower($k)} = $v;
        }

        if (! strlen($this->name)) {
            return(NULL);
        }
        if (! strlen($this->type)){
            return(NULL);
        }
        if (! $this->ttl) {
            $this->ttl = 0;
        }
        if (! strlen($this->class)) {
            $this->class = 'IN';
        }
        if (strlen($this->rdata)) {
            $this->rdlength = strlen($rdata);
        }
        if (class_exists('Net_DNS_RR_' . $rrtype)) {
            $scn = 'Net_DNS_RR_' . $rrtype;
            $rc = new $scn($this, $rdata);
            return($rc);
        } else
            return($this);
    }

    /* }}} */
    /* Net_DNS_RR::display() {{{ */
    function display()
    {
        echo $this->string() . "\n";
    }

    /* }}} */
    /* Net_DNS_RR::string() {{{ */
    function string()
    {
        return($this->name . ".\t" . (strlen($this->name) < 16 ? "\t" : '') .
                $this->ttl  . "\t"  .

                $this->class. "\t"  .
                $this->type . "\t"  .
                $this->rdatastr());

    }

    /* }}} */
    /* Net_DNS_RR::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->rdlength) {
            return('; rdlength = ' . $this->rdlength);
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR::rdata() {{{ */
    function rdata(&$packetORrdata, $offset = '')
    {
        if ($offset) {
            return($this->rr_rdata($packetORrdata, $offset));
        } else if (strlen($this->rdata)) {
            return($this->rdata);
        } else {
            return(NULL);
        }
    }

    /* }}} */
    /* Net_DNS_RR::rr_rdata($packet, $offset) {{{ */
    function rr_rdata(&$packet, $offset)
    {
        return((strlen($this->rdata) ? $this->rdata : ''));
    }
    /* }}} */
    /* Net_DNS_RR::data() {{{ */
    function data(&$packet, $offset)
    {
        $data = $packet->dn_comp($this->name, $offset);
        $data .= pack('n', Net_DNS::typesbyname(strtoupper($this->type)));
        $data .= pack('n', Net_DNS::classesbyname(strtoupper($this->class)));
        $data .= pack('N', $this->ttl);

        $offset += strlen($data) + 2;  // The 2 extra bytes are for rdlength

        $rdata = $this->rdata($packet, $offset);
        $data .= pack('n', strlen($rdata));
        $data .= $rdata;

        return($data);
    }
    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_SRV definition {{{ */
/**
 * A representation of a resource record of type <b>SRV</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_SRV extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $preference;
	var $weight;
	var $port;
    var $target;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_SRV(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $a = unpack("@$offset/npreference/nweight/nport", $data);
                $offset += 6;
                list($target, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->preference = $a['preference'];
                $this->weight = $a['weight'];
                $this->port = $a['port'];
                $this->target = $target;
            }
        } else {
            ereg("([0-9]+)[ \t]+([0-9]+)[ \t]+([0-9]+)[ \t]+(.+)[ \t]*$", $data, $regs);
            $this->preference = $regs[1];
            $this->weight = $regs[2];
            $this->port = $regs[3];
            $this->target = ereg_replace('(.*)\.$', '\\1', $regs[4]);
        }
    }

    /* }}} */
    /* Net_DNS_RR_SRV::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->port) {
            return(intval($this->preference) . ' ' . intval($this->weight) . ' ' . intval($this->port) . ' ' . $this->target . '.');
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_SRV::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->preference) {
            $rdata = pack('nnn', $this->preference, $this->weight, $this->port);
            $rdata .= $packet->dn_comp($this->target, $offset + strlen($rdata));
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_PTR definition {{{ */
/**
 * A representation of a resource record of type <b>PTR</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_PTR extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $ptrdname;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_PTR(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;


        if ($offset) {
            if ($this->rdlength > 0) {
                list($ptrdname, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->ptrdname = $ptrdname;
            }
        } else {
            $this->ptrdname = ereg_replace("[ \t]+(.+)[ \t]*$", '\\1', $data);
        }
    }

    /* }}} */
    /* Net_DNS_RR_PTR::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->ptrdname)) {
            return($this->ptrdname . '.');
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_PTR::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (strlen($this->ptrdname)) {
            return($packet->dn_comp($this->ptrdname, $offset));
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/*  Net_DNS_Header object definition {{{ */
/**
 * Object representation of the HEADER section of a DNS packet
 *
 * The Net_DNS::Header class contains the values of a DNS  packet.  It parses
 * the header of a DNS packet or can  generate the binary data
 * representation of the packet.  The format of the header is described in
 * RFC1035.
 *
 * @package Net_DNS
 */
class Net_DNS_Header
{
    /* class variable definitions {{{ */
    /**
     * The packet's request id
     *
     * The request id of the packet represented as  a 16 bit integer.
     */
    var $id;
    /**
     * The QR bit in a DNS packet header
     *
     * The QR bit as described in RFC1035.  QR is set to 0 for queries, and
     * 1 for repsones.
     */
    var $qr;
    /**
     * The OPCODE name of this packet.
     *
     * The string value (name) of the opcode for the DNS packet.
     */
    var $opcode;
    /**
     * The AA (authoritative answer) bit in a DNS packet header
     *
     * The AA bit as described in RFC1035.  AA is set to  1 if the answer
     * is authoritative.  It has no meaning if QR is set to 0.
     */
    var $aa;
    /**
     * The TC (truncated) bit in a DNS packet header
     *
     * This flag is set to 1 if the response was truncated.  This flag has
     * no meaning in a query packet.
     */
    var $tc;
    /**
     * The RD (recursion desired) bit in a DNS packet header
     *
     * This bit should be set to 1 in a query if recursion  is desired by
     * the DNS server.
     */
    var $rd;
    /**
     * The RA (recursion available) bit in a DNS packet header
     *
     * This bit is set to 1 by the DNS server if the server is willing to
     * perform recursion.
     */
    var $ra;
    /**
     * The RCODE name for this packet.
     *
     * The string value (name) of the rcode for the DNS packet.
     */
    var $rcode;
    /**
     * Number of questions contained within the packet
     *
     * 16bit integer representing the number of questions in the question
     * section of the DNS packet.
     *
     * @var integer $qdcount
     * @see     Net_DNS_Question class
     */
    var $qdcount;
    /**
     * Number of answer RRs contained within the packet
     *
     * 16bit integer representing the number of answer resource records
     * contained in the answer section of the DNS packet.
     *
     * @var integer $ancount
     * @see     Net_DNS_RR class
     */
    var $ancount;
    /**
     * Number of authority RRs within the packet
     *
     * 16bit integer representing the number of authority (NS) resource
     * records  contained in the authority section of the DNS packet.
     *
     * @var integer $nscount
     * @see     Net_DNS_RR class
     */
    var $nscount;
    /**
     * Number of additional RRs within the packet
     *
     * 16bit integer representing the number of additional resource records
     * contained in the additional section of the DNS packet.
     *
     * @var integer $arcount
     * @see     Net_DNS_RR class
     */
    var $arcount;

    /* }}} */
    /* class constructor - Net_DNS_Header($data = "") {{{ */
    /**
     * Initializes the default values for the Header object.
     * 
     * Builds a header object from either default values, or from a DNS
     * packet passed into the constructor as $data
     *
     * @param string $data  A DNS packet of which the header will be parsed.
     * @return  object  Net_DNS_Header
     * @access public
     */
    function Net_DNS_Header($data = '')
    {
        if ($data != '') {
            /*
             * The header MUST be at least 12 bytes.
             * Passing the full datagram to this constructor
             * will examine only the header section of the DNS packet
             */
            if (strlen($data) < 12)
                return(0);

            $a = unpack('nid/C2flags/n4counts', $data);
            $this->id      = $a['id'];
            $this->qr      = ($a['flags1'] >> 7) & 0x1;
            $this->opcode  = ($a['flags1'] >> 3) & 0xf;
            $this->aa      = ($a['flags1'] >> 2) & 0x1;
            $this->tc      = ($a['flags1'] >> 1) & 0x1;
            $this->rd      = $a['flags1'] & 0x1;
            $this->ra      = ($a['flags2'] >> 7) & 0x1;
            $this->rcode   = $a['flags2'] & 0xf;
            $this->qdcount = $a['counts1'];
            $this->ancount = $a['counts2'];
            $this->nscount = $a['counts3'];
            $this->arcount = $a['counts4'];
        }
        else {
            $this->id      = Net_DNS_Resolver::nextid();
            $this->qr      = 0;
            $this->opcode  = 0;
            $this->aa      = 0;
            $this->tc      = 0;
            $this->rd      = 1;
            $this->ra      = 0;
            $this->rcode   = 0;
            $this->qdcount = 1;
            $this->ancount = 0;
            $this->nscount = 0;
            $this->arcount = 0;
        }

        if (Net_DNS::opcodesbyval($this->opcode)) {
            $this->opcode = Net_DNS::opcodesbyval($this->opcode);
        }
        if (Net_DNS::rcodesbyval($this->rcode)) {
            $this->rcode = Net_DNS::rcodesbyval($this->rcode);
        }
    }

    /* }}} */
    /* Net_DNS_Header::display() {{{ */
    /**
     * Displays the properties of the header.
     *
     * Displays the properties of the header.
     *
     * @access public
     */
    function display()
    {
        echo $this->string();
    }

    /* }}} */
    /* Net_DNS_Header::string() {{{ */
    /**
     * Returns a formatted string containing the properties of the header.
     *
     * @return string   a formatted string containing the properties of the header.
     * @access public
     */
    function string()
    {
        $retval = ';; id = ' . $this->id . "\n";
        if ($this->opcode == 'UPDATE') {
            $retval .= ';; qr = ' . $this->qr . '    ' .
                'opcode = ' . $this->opcode . '    '   .
                'rcode = ' . $this->rcode . "\n";
            $retval .= ';; zocount = ' . $this->qdcount . '  ' .  
                'prcount = ' . $this->ancount . '  '           .
                'upcount = ' . $this->nscount . '  '           .
                'adcount = ' . $this->arcount . "\n";
        } else {
            $retval .= ';; qr = ' . $this->qr . '    ' .
                'opcode = ' . $this->opcode . '    '   .
                'aa = ' . $this->aa . '    '           .
                'tc = ' . $this->tc . '    '           .
                'rd = ' . $this->rd . "\n";

            $retval .= ';; ra = ' . $this->ra . '    ' .
                'rcode  = ' . $this->rcode . "\n";

            $retval .= ';; qdcount = ' . $this->qdcount . '  ' .
                'ancount = ' . $this->ancount . '  '    .
                'nscount = ' . $this->nscount . '  '    .
                'arcount = ' . $this->arcount . "\n";
        }
        return($retval);
    }

    /* }}} */
    /* Net_DNS_Header::data() {{{ */
    /**
     * Returns the binary data containing the properties of the header
     *
     * Packs the properties of the Header object into a binary string
     * suitable for using as the Header section of a DNS packet.
     *
     * @return string   binary representation of the header object
     * @access public
     */
    function data()
    {
        $opcode = Net_DNS::opcodesbyname($this->opcode);
        $rcode  = Net_DNS::rcodesbyname($this->rcode);

        $byte2 = ($this->qr << 7)
            | ($opcode << 3)
            | ($this->aa << 2)
            | ($this->tc << 1)
            | ($this->rd);

        $byte3 = ($this->ra << 7) | $rcode;

        return pack('nC2n4', $this->id,
                $byte2,
                $byte3,
                $this->qdcount,
                $this->ancount,
                $this->nscount,
                $this->arcount);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * expandtab on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_NAPTR definition {{{ */
/**
 * A representation of a resource record of type <b>NAPTR</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_NAPTR extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
	var $order;
	var $preference;
	var $flags;
	var $services;
	var $regex;
	var $replacement;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_NAPTR(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $a = unpack("@$offset/norder/npreference", $data);
                $offset += 4;
                list($flags, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                list($services, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                list($regex, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                list($replacement, $offset) = Net_DNS_Packet::dn_expand($data, $offset);

                $this->order = $a['order'];
                $this->preference = $a['preference'];
                $this->flags = $flags;
                $this->services = $services;
                $this->regex = $regex;
                $this->replacement = $replacement;
            }
        } else {
            $data = str_replace('\\\\', chr(1) . chr(1), $data); /* disguise escaped backslash */
            $data = str_replace('\\"', chr(2) . chr(2), $data); /* disguise \" */
            ereg('([0-9]+)[ \t]+([0-9]+)[ \t]+("[^"]*"|[^ \t]*)[ \t]+("[^"]*"|[^ \t]*)[ \t]+("[^"]*"|[^ \t]*)[ \t]+(.*?)[ \t]*$', $data, $regs);
            $this->preference = $regs[1];
            $this->weight = $regs[2];
            foreach($regs as $idx => $value) {
                $value = str_replace(chr(2) . chr(2), '\\"', $value);
                $value = str_replace(chr(1) . chr(1), '\\\\', $value);
                $regs[$idx] = stripslashes($value);
            }
            $this->flags = $regs[3];
            $this->services = $regs[4];
            $this->regex = $regs[5];
            $this->replacement = $regs[6];
        }
    }

    /* }}} */
    /* Net_DNS_RR_NAPTR::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->port) {
            return(intval($this->order) . ' ' . intval($this->preference) . ' "' . addslashes($this->flags) . '" "' . 
                   addslashes($this->services) . '" "' . addslashes($this->regex) . '" "' . addslashes($this->replacement) . '"');
        } else return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_NAPTR::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->preference) {
            $rdata  = pack('nn', $this->order, $this->preference);
            $rdata .= pack('C', strlen($this->flags))    . $this->flags;
            $rdata .= pack('C', strlen($this->services)) . $this->services;
            $rdata .= pack('C', strlen($this->regex))    . $this->regex;
            $rdata .= $packet->dn_comp($this->replacement, $offset + strlen($rdata));
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_TXT definition {{{ */
/**
 * A representation of a resource record of type <b>TXT</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_TXT extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
	var $text;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_TXT(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($text, $offset) = Net_DNS_Packet::label_extract($data, $offset);

                $this->text = $text;
            }
        } else {
            $data = str_replace('\\\\', chr(1) . chr(1), $data); /* disguise escaped backslash */
            $data = str_replace('\\"', chr(2) . chr(2), $data); /* disguise \" */

            ereg('("[^"]*"|[^ \t]*)[ \t]*$', $data, $regs);
            $regs[1] = str_replace(chr(2) . chr(2), '\\"', $regs[1]);
            $regs[1] = str_replace(chr(1) . chr(1), '\\\\', $regs[1]);
            $regs[1] = stripslashes($regs[1]);

            $this->text = $regs[1];
        }
    }

    /* }}} */
    /* Net_DNS_RR_TXT::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->text) {
            return('"' . addslashes($this->text) . '"');
        } else return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_TXT::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->text) {
            $rdata  = pack('C', strlen($this->text)) . $this->text;
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_Question object definition {{{ */
/**
 * Builds or parses the QUESTION section of a DNS packet
 *
 * Builds or parses the QUESTION section of a DNS packet
 *
 * @package Net_DNS
 */
class Net_DNS_Question
{
    /* class variable definitions {{{ */
    var $qname = NULL;
    var $qtype = NULL;
    var $qclass = NULL;

    /* }}} */
    /* class constructor Net_DNS_Question($qname, $qtype, $qclass) {{{ */
    function Net_DNS_Question($qname, $qtype, $qclass)
    {
        if (   is_null(Net_DNS::typesbyname($qtype))
                &&  !is_null(Net_DNS::classesbyname($qtype))
                && is_null(Net_DNS::classesbyname($qclass))
                &&  !is_null(Net_DNS::typesbyname($qclass))) {

            $t = $qtype;
            $qtype = $qclass;
            $qclass = $t;
        }

        $this->qname = $qname;
        $this->qtype = $qtype;
        $this->qclass = $qclass;
    }

    /* }}} */
    /* Net_DNS_Question::display() {{{*/
    function display()
    {
        echo $this->string() . "\n";
    }

    /*}}}*/
    /* Net_DNS_Question::string() {{{*/
    function string()
    {
        return($this->qname . ".\t" . $this->qclass . "\t" . $this->qtype);
    }

    /*}}}*/
    /* Net_DNS_Question::data(&$packet, $offset) {{{*/
    function data($packet, $offset)
    {
        $data = $packet->dn_comp($this->qname, $offset);
        $data .= pack('n', Net_DNS::typesbyname(strtoupper($this->qtype)));
        $data .= pack('n', Net_DNS::classesbyname(strtoupper($this->qclass)));
        return($data);
    }

    /*}}}*/
}
/* }}} */
/* VIM settings{{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_MX definition {{{ */
/**
 * A representation of a resource record of type <b>MX</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_MX extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $preference;
    var $exchange;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_MX(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                $a = unpack("@$offset/npreference", $data);
                $offset += 2;
                list($exchange, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->preference = $a['preference'];
                $this->exchange = $exchange;
            }
        } else {
            ereg("([0-9]+)[ \t]+(.+)[ \t]*$", $data, $regs);
            $this->preference = $regs[1];
            $this->exchange = ereg_replace('(.*)\.$', '\\1', $regs[2]);
        }
    }

    /* }}} */
    /* Net_DNS_RR_MX::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->preference) {
            return($this->preference . ' ' . $this->exchange . '.');
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_MX::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->preference) {
            $rdata = pack('n', $this->preference);
            $rdata .= $packet->dn_comp($this->exchange, $offset + strlen($rdata));
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */
/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Include files {{{ */

/* }}} */
/* Net_DNS_RR object definition {{{ */
/**
 * Resource Record object definition
 *
 * Builds or parses resource record sections of the DNS  packet including
 * the answer, authority, and additional  sections of the packet.
 *
 * @package Net_DNS
 */


/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_SOA definition {{{ */
/**
 * A representation of a resource record of type <b>SOA</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_SOA extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $mname;
    var $rname;
    var $serial;
    var $refresh;
    var $retry;
    var $expire;
    var $minimum;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_SOA(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($mname, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                list($rname, $offset) = Net_DNS_Packet::dn_expand($data, $offset);

                $a = unpack("@$offset/N5soavals", $data);
                $this->mname = $mname;
                $this->rname = $rname;
                $this->serial = $a['soavals1'];
                $this->refresh = $a['soavals2'];
                $this->retry = $a['soavals3'];
                $this->expire = $a['soavals4'];
                $this->minimum = $a['soavals5'];
            }
        } else {
            if (ereg("([^ \t]+)[ \t]+([^ \t]+)[ \t]+([0-9]+)[^ \t]+([0-9]+)[^ \t]+([0-9]+)[^ \t]+([0-9]+)[^ \t]*$", $string, $regs))
            {
                $this->mname = ereg_replace('(.*)\.$', '\\1', $regs[1]);
                $this->rname = ereg_replace('(.*)\.$', '\\1', $regs[2]);
                $this->serial = $regs[3];
                $this->refresh = $regs[4];
                $this->retry = $regs[5];
                $this->expire = $regs[6];
                $this->minimum = $regs[7];
            }
        }
    }

    /* }}} */
    /* Net_DNS_RR_SOA::rdatastr($pretty = 0) {{{ */
    function rdatastr($pretty = 0)
    {
        if (strlen($this->mname)) {
            if ($pretty) {
                $rdatastr  = $this->mname . '. ' . $this->rname . ". (\n";
                $rdatastr .= "\t\t\t\t\t" . $this->serial . "\t; Serial\n";
                $rdatastr .= "\t\t\t\t\t" . $this->refresh . "\t; Refresh\n";
                $rdatastr .= "\t\t\t\t\t" . $this->retry . "\t; Retry\n";
                $rdatastr .= "\t\t\t\t\t" . $this->expire . "\t; Expire\n";
                $rdatastr .= "\t\t\t\t\t" . $this->minimum . " )\t; Minimum TTL";
            } else {
                $rdatastr  = $this->mname . '. ' . $this->rname . '. ' .
                    $this->serial . ' ' .  $this->refresh . ' ' .  $this->retry . ' ' .
                    $this->expire . ' ' .  $this->minimum;
            }
            return($rdatastr);
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_SOA::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (strlen($this->mname)) {
            $rdata = $packet->dn_comp($this->mname, $offset);
            $rdata .= $packet->dn_comp($this->rname, $offset + strlen($rdata));
            $rdata .= pack('N5', $this->serial,
                    $this->refresh,
                    $this->retry,
                    $this->expire,
                    $this->minimum);
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */


/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_CNAME definition {{{ */
/**
 * A representation of a resource record of type <b>CNAME</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_CNAME extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $cname;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_CNAME(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($cname, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->cname = $cname;
            }
        } else {
            $this->cname = ereg_replace("[ \t]+(.+)[\. \t]*$", '\\1', $data);
        }
    }

    /* }}} */
    /* Net_DNS_RR_CNAME::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->cname)) {
            return($this->cname . '.');
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_CNAME::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (strlen($this->cname)) {
            return($packet->dn_comp($this->cname, $offset));
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

define('NET_DNS_DEFAULT_ALGORITHM', 'hmac-md5.sig-alg.reg.int');
define('NET_DNS_DEFAULT_FUDGE', 300);

/* Net_DNS_RR_TSIG definition {{{ */
/**
 * A representation of a resource record of type <b>TSIG</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_TSIG extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $time_signed;
    var $fudge;
    var $mac_size;
    var $mac;
    var $original_id;
    var $error;
    var $other_len;
    var $other_data;
    var $key;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_TSIG(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($alg, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->algorithm = $alg;

                $d = unpack("\@$offset/nth/Ntl/nfudge/nmac_size", $data);
                $time_high = $d['th'];
                $time_low = $d['tl'];
                $this->time_signed = $time_low;
                $this->fudge = $d['fudge'];
                $this->mac_size = $d['mac_size'];
                $offset += 10;

                $this->mac = substr($data, $offset, $this->mac_size);
                $offset += $this->mac_size;

                $d = unpack("@$offset/noid/nerror/nolen", $data);
                $this->original_id = $d['oid'];
                $this->error = $d['error'];
                $this->other_len = $d['olen'];
                $offset += 6;

                $odata = substr($data, $offset, $this->other_len);
                $d = unpack('nodata_high/Nodata_low', $odata);
                $this->other_data = $d['odata_low'];
            }
        } else {
            if (strlen($data) && preg_match('/^(.*)$/', $data, $regs)) {
                $this->key = $regs[1];
            }

            $this->algorithm   = NET_DNS_DEFAULT_ALGORITHM;
            $this->time_signed = time();

            $this->fudge       = NET_DNS_DEFAULT_FUDGE;
            $this->mac_size    = 0;
            $this->mac         = '';
            $this->original_id = 0;
            $this->error       = 0;
            $this->other_len   = 0;
            $this->other_data  = '';

            // RFC 2845 Section 2.3
            $this->class = 'ANY';
        }
    }

    /* }}} */
    /* Net_DNS_RR_TSIG::rdatastr() {{{ */
    function rdatastr()
    {
        $error = $this->error;
        if (! $error) {
            $error = 'UNDEFINED';
        }

        if (strlen($this->algorithm)) {
            $rdatastr = $this->algorithm . '. ' . $this->time_signed . ' ' .
                $this->fudge . ' ';
            if ($this->mac_size && strlen($this->mac)) {
                $rdatastr .= ' ' . $this->mac_size . ' ' . base64_encode($this->mac);
            } else {
                $rdatastr .= ' 0 ';
            }
            $rdatastr .= ' ' . $this->original_id . ' ' . $error;
            if ($this->other_len && strlen($this->other_data)) {
                $rdatastr .= ' ' . $this->other_data;
            } else {
                $rdatastr .= ' 0 ';
            }
        } else {
            $rdatastr = '; no data';
        }

        return($rdatastr);
    }

    /* }}} */
    /* Net_DNS_RR_TSIG::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        $rdata = '';
        $sigdata = '';

        if (strlen($this->key)) {
            $key = $this->key;
            $key = ereg_replace(' ', '', $key);
            $key = base64_decode($key);

            $newpacket = $packet;
            $newoffset = $offset;
            array_pop($newpacket->additional);
            $newpacket->header->arcount--;
            $newpacket->compnames = array();

            /*
             * Add the request MAC if present (used to validate responses).
             */
            if (isset($this->request_mac)) {
                $sigdata .= pack('H*', $this->request_mac);
            }
            $sigdata .= $newpacket->data();

            /*
             * Don't compress the record (key) name.
             */
            $tmppacket = new Net_DNS_Packet;
            $sigdata .= $tmppacket->dn_comp(strtolower($this->name), 0);

            $sigdata .= pack('n', Net_DNS::classesbyname(strtoupper($this->class)));
            $sigdata .= pack('N', $this->ttl);

            /*
             * Don't compress the algorithm name.
             */
            $tmppacket->compnames = array();
            $sigdata .= $tmppacket->dn_comp(strtolower($this->algorithm), 0);

            $sigdata .= pack('nN', 0, $this->time_signed);
            $sigdata .= pack('n', $this->fudge);
            $sigdata .= pack('nn', $this->error, $this->other_len);

            if (strlen($this->other_data)) {
                $sigdata .= pack('nN', 0, $this->other_data);
            }

            $this->mac = mhash(MHASH_MD5, $sigdata, $key);
            $this->mac_size = strlen($this->mac);

            /*
             * Don't compress the algorithm name.
             */
            unset($tmppacket);
            $tmppacket = new Net_DNS_Packet;
            $rdata .= $tmppacket->dn_comp(strtolower($this->algorithm), 0);

            $rdata .= pack('nN', 0, $this->time_signed);
            $rdata .= pack('nn', $this->fudge, $this->mac_size);
            $rdata .= $this->mac;

            $rdata .= pack('nnn',$packet->header->id,
                    $this->error,
                    $this->other_len);

            if ($this->other_data) {
                $rdata .= pack('nN', 0, $this->other_data);
            }
        }
        return($rdata);
    }
    /* }}} */
    /* Net_DNS_RR_TSIG::error() {{{ */
    function error()
    {
        if ($this->error != 0) {
            $rcode = Net_DNS::rcodesbyval($error);
        }
        return $rcode;
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * expandtab on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_NS definition {{{ */
/**
 * A representation of a resource record of type <b>NS</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_NS extends Net_DNS_RR
{
    /* class variable defintiions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $nsdname;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_NS(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;


        if ($offset) {
            if ($this->rdlength > 0) {
                list($nsdname, $offset) = Net_DNS_Packet::dn_expand($data, $offset);
                $this->nsdname = $nsdname;
            }
        } else {
            $this->nsdname = ereg_replace("[ \t]+(.+)[ \t]*$", '\\1', $data);
        }
    }

    /* }}} */
    /* Net_DNS_RR_NS::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->nsdname)) {
            return($this->nsdname . '.');
        }
        return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_NS::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if (strlen($this->nsdname)) {
            return($packet->dn_comp($this->nsdname, $offset));
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_NS definition {{{ */
/**
 * A representation of a resource record of type <b>NS</b>
 *
 * @package Net_DNS
 */

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_Packet object definition {{{ */
/**
 * A object represation of a DNS packet (RFC1035)
 *
 * This object is used to manage a DNS packet.  It contains methods for
 * DNS packet compression as defined in RFC1035, as well as parsing  a DNS
 * packet response from a DNS server, or building a DNS packet from  the
 * instance variables contained in the class.
 *
 * @package Net_DNS
 */
class Net_DNS_Packet
{
    /* class variable definitions {{{ */
    /**
     * debugging flag
     *
     * If set to TRUE (non-zero), debugging code will be displayed as the
     * packet is parsed.
     *
     * @var boolean $debug
     * @access  public
     */
    var $debug;
    /**
     * A packet Header object.
     *
     * An object of type Net_DNS_Header which contains the header
     * information  of the packet.
     *
     * @var object Net_DNS_Header $header
     * @access  public
     */
    var $header;
    /**
     * A hash of compressed labels
     *
     * A list of all labels which have been compressed in the DNS packet
     * and  the location offset of the label within the packet.
     *
     * @var array   $compnames
     */
    var $compnames;
    /**
     * The origin of the packet, if the packet is a server response.
     *
     * This contains a string containing the IP address of the name server
     * from which the answer was given.
     *
     * @var string  $answerfrom
     * @access  public
     */
    var $answerfrom;
    /**
     * The size of the answer packet, if the packet is a server response.
     *
     * This contains a integer containing the size of the DNS packet the
     * server responded with if this packet was received by a DNS server
     * using the query() method.
     *
     * @var string  $answersize
     * @access  public
     */
    var $answersize;
    /**
     * An array of Net_DNS_Question objects
     *
     * Contains all of the questions within the packet.  Each question is
     * stored as an object of type Net_DNS_Question.
     *
     * @var array   $question
     * @access  public
     */
    var $question;
    /**
     * An array of Net_DNS_RR ANSWER objects
     *
     * Contains all of the answer RRs within the packet.  Each answer is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $answer
     * @access  public
     */
    var $answer;
    /**
     * An array of Net_DNS_RR AUTHORITY objects
     *
     * Contains all of the authority RRs within the packet.  Each authority is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $authority
     * @access  public
     */
    var $authority;
    /**
     * An array of Net_DNS_RR ADDITIONAL objects
     *
     * Contains all of the additional RRs within the packet.  Each additional is
     * stored as an object of type Net_DNS_RR.
     *
     * @var array   $additional
     * @access  public
     */
    var $additional;

    /* }}} */
    /* class constructor - Net_DNS_Packet($debug = FALSE) {{{ */
    /*
     * unfortunately (or fortunately), we can't follow the same
     * silly method for determining if name is a hostname or a packet
     * stream in PHP, since there is no ref() function.  So we're going
     * to define a new method called parse to deal with this
     * circumstance and another method called buildQuestion to build a question.
     * I like it better that way anyway.
     */
    /**
     * Initalizes a Net_DNS_Packet object
     *
     * @param boolean $debug Turns debugging on or off
     */
    function Net_DNS_Packet($debug = FALSE)
    {
        $this->debug = $debug;
        $this->compnames = array();
    }

    /* }}} */
    /* Net_DNS_Packet::buildQuestion($name, $type = "A", $class = "IN") {{{ */
    /**
     * Adds a DNS question to the DNS packet
     *
     * @param   string $name    The name of the record to query
     * @param   string $type    The type of record to query
     * @param   string $class   The class of record to query
     * @see Net_DNS::typesbyname(), Net_DNS::classesbyname()
     */
    function buildQuestion($name, $type = 'A', $class = 'IN')
    {
        $this->header = new Net_DNS_Header();
        $this->header->qdcount = 1;
        $this->question[0] = new Net_DNS_Question($name, $type, $class);
        $this->answer = NULL;
        $this->authority = NULL;
        $this->additional = NULL;
        if ($this->debug) {
            $this->display();
        }
    }

    /* }}} */
    /* Net_DNS_Packet::parse($data) {{{ */
    /**
     * Parses a DNS packet returned by a DNS server
     *
     * Parses a complete DNS packet and builds an object hierarchy
     * containing all of the parts of the packet:
     * <ul>
     *   <li>HEADER   
     *   <li>QUESTION   
     *   <li>ANSWER || PREREQUISITE   
     *   <li>ADDITIONAL || UPDATE   
     *   <li>AUTHORITY
     * </ul>
     * 
     * @param string $data  A binary string containing a DNS packet
     * @return boolean TRUE on success, NULL on parser error
     */
    function parse($data)
    {
        if ($this->debug) {
            echo ';; HEADER SECTION' . "\n";
        }

        $this->header = new Net_DNS_Header($data);

        if ($this->debug) {
            $this->header->display();
        }

        /*
         *  Print and parse the QUESTION section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($this->header->opcode  == 'UPDATE') ? 'ZONE' : 'QUESTION';

            echo ";; $section SECTION (" . $this->header->qdcount . ' record' .
                ($this->header->qdcount == 1 ? '' : 's') . ")\n";
        }

        $offset = 12;

        $this->question = array();
        for ($ctr = 0; $ctr < $this->header->qdcount; $ctr++) {
            list($qobj, $offset) = $this->parse_question($data, $offset);
            if (is_null($qobj)) {
                return(NULL);
            }

            $this->question[count($this->question)] = $qobj;
            if ($this->debug) {
                echo ";;\n;";
                $qobj->display();
            }
        }

        /*
         *  Print and parse the PREREQUISITE or ANSWER  section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($this->header->opcode == 'UPDATE') ? 'PREREQUISITE' :'ANSWER';
            echo ";; $section SECTION (" .
                $this->header->ancount . ' record' .
                (($this->header->ancount == 1) ? '' : 's') .
                ")\n";
        }

        $this->answer = array();
        for ($ctr = 0; $ctr < $this->header->ancount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                return(NULL);
            }
            array_push($this->answer, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        /*
         *  Print and parse the UPDATE or AUTHORITY section of the packet
         */
        if ($this->debug) {
            echo "\n";
            $section = ($this->header->opcode == 'UPDATE') ? 'UPDATE' : 'AUTHORITY';
            echo ";; $section SECTION (" .
                $this->header->nscount . ' record' .
                (($this->header->nscount == 1) ? '' : 's') .
                ")\n";
        }

        $this->authority = array();
        for ($ctr = 0; $ctr < $this->header->nscount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                return(NULL);
            }
            array_push($this->authority, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        /*
         *  Print and parse the ADDITIONAL section of the packet
         */
        if ($this->debug) {
            echo "\n";
            echo ';; ADDITIONAL SECTION (' .
                $this->header->arcount . ' record' .
                (($this->header->arcount == 1) ? '' : 's') .
                ")\n";
        }

        $this->additional = array();
        for ($ctr = 0; $ctr < $this->header->arcount; $ctr++) {
            list($rrobj, $offset) = $this->parse_rr($data, $offset);

            if (is_null($rrobj)) {
                return(NULL);
            }
            array_push($this->additional, $rrobj);
            if ($this->debug) {
                $rrobj->display();
            }
        }

        return(TRUE);
    }

    /* }}} */
    /* Net_DNS_Packet::data() {{{*/
    /**
     * Build a packet from a Packet object hierarchy
     *
     * Builds a valid DNS packet suitable for sending to a DNS server or
     * resolver client containing all of the data in the packet hierarchy.
     *
     * @return string A binary string containing a DNS Packet
     */
    function data()
    {
        $data = $this->header->data();

        for ($ctr = 0; $ctr < $this->header->qdcount; $ctr++) {
            $data .= $this->question[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $this->header->ancount; $ctr++) {
            $data .= $this->answer[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $this->header->nscount; $ctr++) {
            $data .= $this->authority[$ctr]->data($this, strlen($data));
        }

        for ($ctr = 0; $ctr < $this->header->arcount; $ctr++) {
            $data .= $this->additional[$ctr]->data($this, strlen($data));
        }

        return($data);
    }

    /*}}}*/
    /* Net_DNS_Packet::dn_comp($name, $offset) {{{*/
    /**
     * DNS packet compression method
     *
     * Returns a domain name compressed for a particular packet object, to
     * be stored beginning at the given offset within the packet data.  The
     * name will be added to a running list of compressed domain names for
     * future use.
     * 
     * @param string    $name       The name of the label to compress
     * @param integer   $offset     The location offset in the packet to where
     *                              the label will be stored.
     * @return string   $compname   A binary string containing the compressed
     *                              label.
     * @see Net_DNS_Packet::dn_expand()
     */
    function dn_comp($name, $offset)
    {
        $names = explode('.', $name);
        $compname = '';
        while (count($names)) {
            $dname = join('.', $names);
            if (isset($this->compnames[$dname])) {
                $compname .= pack('n', 0xc000 | $this->compnames[$dname]);
                break;
            }

            $this->compnames[$dname] = $offset;
            $first = array_shift($names);
            $length = strlen($first);
            $compname .= pack('Ca*', $length, $first);
            $offset += $length + 1;
        }
        if (! count($names)) {
            $compname .= pack('C', 0);
        }
        return($compname);
    }

    /*}}}*/
    /* Net_DNS_Packet::dn_expand($packet, $offset) {{{ */
    /**
     * DNS packet decompression method
     *
     * Expands the domain name stored at a particular location in a DNS
     * packet.  The first argument is a variable containing  the packet
     * data.  The second argument is the offset within the  packet where
     * the (possibly) compressed domain name is stored.
     * 
     * @param   string  $packet The packet data
     * @param   integer $offset The location offset in the packet of the
     *                          label to decompress.
     * @return  array   Returns a list of type array($name, $offset) where
     *                  $name is the name of the label which was decompressed
     *                  and $offset is the offset of the next field in the
     *                  packet.  Returns array(NULL, NULL) on error
     */
    function dn_expand($packet, $offset)
    {
        $packetlen = strlen($packet);
        $int16sz = 2;
        $name = '';
        while (1) {
            if ($packetlen < ($offset + 1)) {
                return(array(NULL, NULL));
            }

            $a = unpack("@$offset/Cchar", $packet);
            $len = $a['char'];

            if ($len == 0) {
                $offset++;
                break;
            } else if (($len & 0xc0) == 0xc0) {
                if ($packetlen < ($offset + $int16sz)) {
                    return(array(NULL, NULL));
                }
                $ptr = unpack("@$offset/ni", $packet);
                $ptr = $ptr['i'];
                $ptr = $ptr & 0x3fff;
                $name2 = Net_DNS_Packet::dn_expand($packet, $ptr);

                if (is_null($name2[0])) {
                    return(array(NULL, NULL));
                }
                $name .= $name2[0];
                $offset += $int16sz;
                break;
            } else {
                $offset++;

                if ($packetlen < ($offset + $len)) {
                    return(array(NULL, NULL));
                }

                $elem = substr($packet, $offset, $len);
                $name .= $elem . '.';
                $offset += $len;
            }
        }
        $name = ereg_replace('\.$', '', $name);
        return(array($name, $offset));
    }

    /*}}}*/
    /* Net_DNS_Packet::label_extract($packet, $offset) {{{ */
    /**
     * DNS packet decompression method
     *
     * Extracts the label stored at a particular location in a DNS
     * packet.  The first argument is a variable containing  the packet
     * data.  The second argument is the offset within the  packet where
     * the (possibly) compressed domain name is stored.
     * 
     * @param   string  $packet The packet data
     * @param   integer $offset The location offset in the packet of the
     *                          label to extract.
     * @return  array   Returns a list of type array($name, $offset) where
     *                  $name is the name of the label which was decompressed
     *                  and $offset is the offset of the next field in the
     *                  packet.  Returns array(NULL, NULL) on error
     */
    function label_extract($packet, $offset)
    {
        $packetlen = strlen($packet);
        $name = '';
        if ($packetlen < ($offset + 1)) {
            return(array(NULL, NULL));
        }

        $a = unpack("@$offset/Cchar", $packet);
        $len = $a['char'];
		$offset++;

        if ($len + $offset > $packetlen) {
            $name = substr($packet, $offset);
            $offset = $packetlen;
        } else {
            $name = substr($packet, $offset, $len);
            $offset += $len;
        }
        return(array($name, $offset));
    }

    /*}}}*/
    /* Net_DNS_Packet::parse_question($data, $offset) {{{ */
    /**
     * Parses the question section of a packet
     *
     * Examines a DNS packet at the specified offset and parses the data
     * of the QUESTION section.
     *
     * @param   string  $data   The packet data returned from the server
     * @param   integer $offset The location offset of the start of the
     *                          question section.
     * @return  array   An array of type array($q, $offset) where $q
     *                  is a Net_DNS_Question object and $offset is the
     *                  location of the next section of the packet which
     *                  needs to be parsed.
     */
    function parse_question($data, $offset)
    {
        list($qname, $offset) = $this->dn_expand($data, $offset);
        if (is_null($qname)) {
            return(array(NULL, NULL));
        }

        if (strlen($data) < ($offset + 2 * 2)) {
            return(array(NULL, NULL));
        }

        $q = unpack("@$offset/n2int", $data);
        $qtype = $q['int1'];
        $qclass = $q['int2'];
        $offset += 2 * 2;

        $qtype = Net_DNS::typesbyval($qtype);
        $qclass = Net_DNS::classesbyval($qclass);

        $q = new Net_DNS_Question($qname, $qtype, $qclass);
        return(array($q, $offset));
    }

    /*}}}*/
    /* Net_DNS_Packet::parse_rr($data, $offset) {{{ */
    /**
     * Parses a resource record section of a packet
     *
     * Examines a DNS packet at the specified offset and parses the data
     * of a section which contains RRs (ANSWER, AUTHORITY, ADDITIONAL).
     *
     * @param string    $data   The packet data returned from the server
     * @param integer   $offset The location offset of the start of the resource
     *                          record section.
     * @return  array   An array of type array($rr, $offset) where $rr
     *                  is a Net_DNS_RR object and $offset is the
     *                  location of the next section of the packet which
     *                  needs to be parsed.
     */
    function parse_rr($data, $offset)
    {
        list($name, $offset) = $this->dn_expand($data, $offset);
        if (! strlen($name)) {
            return(array(NULL, NULL));
        }

        if (strlen($data) < ($offset + 10)) {
            return(array(NULL, NULL));
        }

        $a = unpack("@$offset/n2tc/Nttl/nrdlength", $data);
        $type = $a['tc1'];
        $class = $a['tc2'];
        $ttl = $a['ttl'];
        $rdlength = $a['rdlength'];

        $type = Net_DNS::typesbyval($type);
        $class = Net_DNS::classesbyval($class);

        $offset += 10;
        if (strlen($data) < ($offset + $rdlength)) {
            return(array(NULL, NULL));
        }

        $rrobj = new Net_DNS_RR(array($name,
                    $type,
                    $class,
                    $ttl,
                    $rdlength,
                    $data,
                    $offset));

        if (is_null($rrobj)) {
            return(array(NULL, NULL));
        }

        $offset += $rdlength;

        return(array($rrobj, $offset));
    }

    /* }}} */
    /* Net_DNS_Packet::display() {{{ */
    /**
     * Prints out the packet in a human readable formatted string
     */
    function display()
    {
        echo $this->string();
    }

    /*}}}*/
    /* Net_DNS_Packet::string() {{{ */
    /**
     * Builds a human readable formatted string representing a packet
     */ 
    function string()
    {
        $retval = '';
        if ($this->answerfrom) {
            $retval .= ';; Answer received from ' . $this->answerfrom . '(' .
                $this->answersize . " bytes)\n;;\n";
        }

        $retval .= ";; HEADER SECTION\n";
        $retval .= $this->header->string();
        $retval .= "\n";

        $section = ($this->header->opcode == 'UPDATE') ? 'ZONE' : 'QUESTION';
        $retval .= ";; $section SECTION (" . $this->header->qdcount     .
            ' record' . ($this->header->qdcount == 1 ? '' : 's') .
            ")\n";

        foreach ($this->question as $qr) {
            $retval .= ';; ' . $qr->string() . "\n";
        }

        $section = ($this->header->opcode == 'UPDATE') ? 'PREREQUISITE' : 'ANSWER';
        $retval .= "\n;; $section SECTION (" . $this->header->ancount     .
            ' record' . ($this->header->ancount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->answer)) {
            foreach ($this->answer as $ans) {
                $retval .= ';; ' . $ans->string() . "\n";
            }
        }

        $section = ($this->header->opcode == 'UPDATE') ? 'UPDATE' : 'AUTHORITY';
        $retval .= "\n;; $section SECTION (" . $this->header->nscount     .
            ' record' . ($this->header->nscount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->authority)) {
            foreach ($this->authority as $auth) {
                $retval .= ';; ' . $auth->string() . "\n";
            }
        }

        $retval .= "\n;; ADDITIONAL SECTION (" . $this->header->arcount     .
            ' record' . ($this->header->arcount == 1 ? '' : 's') .
            ")\n";

        if (is_array($this->additional)) {
            foreach ($this->additional as $addl) {
                $retval .= ';; ' . $addl->string() . "\n";
            }
        }

        $retval .= "\n\n";
        return($retval);
    }

    /*}}}*/
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */


/* Net_DNS_RR_AAAA object definition {{{ */
/**
 * A representation of a resource record of type <b>AAAA</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_AAAA extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
    var $address;

    /* }}} */
    /* class constructor - Net_DNS_RR_AAAA(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_AAAA(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
			$this->address = Net_DNS_RR_AAAA::ipv6_decompress(substr($this->rdata, 0, $this->rdlength));
        } else {
            if (strlen($data)) {
                if (count($adata = explode(':', $data, 8)) >= 3) {
                    foreach($adata as $addr)
                        if (!preg_match('/^[0-9A-F]{0,4}$/i', $addr)) return;
                    $this->address = trim($data);
                }
            }
        } 
    }

    /* }}} */
    /* Net_DNS_RR_AAAA::rdatastr() {{{ */
    function rdatastr()
    {
        if (strlen($this->address)) {
            return($this->address);
        }
        return('; no data');
    }
    /* }}} */
    /* Net_DNS_RR_AAAA::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
		return Net_DNS_RR_AAAA::ipv6_compress($this->address);
    }

    /* }}} */
    /* Net_DNS_RR_AAAA::ipv6_compress($addr) {{{ */
    function ipv6_compress($addr)
    {
        $numparts = count(explode(':', $addr));
        if ($numparts < 3 || $numparts > 8 ||
            !preg_match('/^([0-9A-F]{0,4}:){0,7}(:[0-9A-F]{0,4}){0,7}$/i', $addr)) {
            /* Non-sensical IPv6 address */
            return pack('n8', 0, 0, 0, 0, 0, 0, 0, 0);
        }
        if (strpos($addr, '::') !== false) {
            /* First we have to normalize the address, turn :: into :0:0:0:0: */
            $filler = str_repeat(':0', 9 - $numparts) . ':';
            if (substr($addr, 0, 2) == '::') {
                $filler = "0$filler";
            }
            if (substr($addr, -2, 2) == '::') {
                $filler .= '0';
            }
            $addr = str_replace('::', $filler, $addr);
        }
        $aparts = explode(':', $addr);
        return pack('n8', hexdec($aparts[0]), hexdec($aparts[1]), hexdec($aparts[2]), hexdec($aparts[3]),
                          hexdec($aparts[4]), hexdec($aparts[5]), hexdec($aparts[6]), hexdec($aparts[7]));
    }
    /* }}} */

    /* Net_DNS_RR_AAAA::ipv6_decompress($pack) {{{ */
    function ipv6_decompress($pack)
    {
        if (strlen($pack) != 16) {
            /* Must be 8 shorts long */
            return '::';
        }
        $a = unpack('n8b', $pack);
        foreach($a as $idx => $value)
            $a[$idx] = dechex($value);
        $addr = implode(':', $a);
        /* Shorthand the first :0:0:0: set into a :: */
        /* TODO: Make this is a single replacement pattern */
        if (substr($addr, -4, 4) == ':0:0') {
            return preg_replace('/((:0){2,})$/', '::', $addr);
        } else {
            return preg_replace('/(:?(0:){2,})/', '::', $addr);
        }
    }
    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4

/*
 *  License Information:
 *
 *    Net_DNS:  A resolver library for PHP
 *    Copyright (c) 2002-2003 Eric Kilfoil eric@ypass.net
 *
 *    This library is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU Lesser General Public
 *    License as published by the Free Software Foundation; either
 *    version 2.1 of the License, or (at your option) any later version.
 *
 *    This library is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *    Lesser General Public License for more details.
 *
 *    You should have received a copy of the GNU Lesser General Public
 *    License along with this library; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

/* Net_DNS_RR_HINFO definition {{{ */
/**
 * A representation of a resource record of type <b>HINFO</b>
 *
 * @package Net_DNS
 */
class Net_DNS_RR_HINFO extends Net_DNS_RR
{
    /* class variable definitions {{{ */
    var $name;
    var $type;
    var $class;
    var $ttl;
    var $rdlength;
    var $rdata;
	var $cpu;
    var $os;

    /* }}} */
    /* class constructor - RR(&$rro, $data, $offset = '') {{{ */
    function Net_DNS_RR_HINFO(&$rro, $data, $offset = '')
    {
        $this->name = $rro->name;
        $this->type = $rro->type;
        $this->class = $rro->class;
        $this->ttl = $rro->ttl;
        $this->rdlength = $rro->rdlength;
        $this->rdata = $rro->rdata;

        if ($offset) {
            if ($this->rdlength > 0) {
                list($cpu, $offset) = Net_DNS_Packet::label_extract($data, $offset);
                list($os,  $offset) = Net_DNS_Packet::label_extract($data, $offset);

                $this->cpu = $cpu;
                $this->os  = $os;
            }
        } else {
            $data = str_replace('\\\\', chr(1) . chr(1), $data); /* disguise escaped backslash */
            $data = str_replace('\\"', chr(2) . chr(2), $data); /* disguise \" */

            ereg('("[^"]*"|[^ \t]*)[ \t]+("[^"]*"|[^ \t]*)[ \t]*$', $data, $regs);
            foreach($regs as $idx => $value) {
                $value = str_replace(chr(2) . chr(2), '\\"', $value);
                $value = str_replace(chr(1) . chr(1), '\\\\', $value);
                $regs[$idx] = stripslashes($value);
            }

            $this->cpu = $regs[1];
			$this->os = $regs[2];
        }
    }

    /* }}} */
    /* Net_DNS_RR_HINFO::rdatastr() {{{ */
    function rdatastr()
    {
        if ($this->text) {
            return('"' . addslashes($this->cpu) . '" "' . addslashes($this->os) . '"');
        } else return('; no data');
    }

    /* }}} */
    /* Net_DNS_RR_HINFO::rr_rdata($packet, $offset) {{{ */
    function rr_rdata($packet, $offset)
    {
        if ($this->text) {
            $rdata  = pack('C', strlen($this->cpu)) . $this->cpu;
            $rdata .= pack('C', strlen($this->os))  . $this->os;
            return($rdata);
        }
        return(NULL);
    }

    /* }}} */
}
/* }}} */
/* VIM settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */


/* Include information {{{ */

   

/* }}} */
/* GLOBAL VARIABLE definitions {{{ */

// Used by the Net_DNS_Resolver object to generate an ID
mt_srand((double) microtime() * 10000);
$_Net_DNS_packet_id = (int)mt_rand(0, 65535);

/* }}} */
/* Net_DNS object definition (incomplete) {{{ */
/**
 * Initializes a resolver object
 *
 * Net_DNS allows you to query a nameserver for DNS  lookups.  It bypasses the
 * system resolver library  entirely, which allows you to query any nameserver,
 * set your own values for retries, timeouts, recursion,  etc.
 *
 * @author Eric Kilfoil <eric@ypass.net>
 * @package Net_DNS
 * @version 0.01alpha
 */
 
class Net_DNS
{
    /* class variable definitions {{{ */
    /**
     * A default resolver object created on instantiation
     *
     * @var object Net_DNS_Resolver
     */
    var $resolver;
    var $VERSION = '1.00b2'; // This should probably be a define :(
    var $PACKETSZ = 512;
    var $HFIXEDSZ = 12;
    var $QFIXEDSZ = 4;
    var $RRFIXEDSZ = 10;
    var $INT32SZ = 4;
    var $INT16SZ = 2;
    /* }}} */
    /* class constructor - Net_DNS() {{{ */
    /**
     * Initializes a resolver object
     *
     * @see Net_DNS_Resolver
     */
    function Net_DNS()
    {
        $this->resolver = new Net_DNS_Resolver();
    }
    /* }}} */
    /* Net_DNS::opcodesbyname() {{{ */
    /**
     * Translates opcode names to integers
     *
     * Translates the name of a DNS OPCODE into it's assigned  number
     * listed in RFC1035, RFC1996, or RFC2136. Valid  OPCODES are:
     * <ul>
     *   <li>QUERY   
     *   <li>IQUERY   
     *   <li>STATUS   
     *   <li>NS_NOTIFY_OP   
     *   <li>UPDATE
     * <ul>
     * 
     * @param   string  $opcode A DNS Packet OPCODE name
     * @return  integer The integer value of an OPCODE
     * @see     Net_DNS::opcodesbyval()
     */
    function opcodesbyname($opcode)
    {
        $op = array(
                'QUERY'        => 0,   // RFC 1035
                'IQUERY'       => 1,   // RFC 1035
                'STATUS'       => 2,   // RFC 1035
                'NS_NOTIFY_OP' => 4,   // RFC 1996
                'UPDATE'       => 5,   // RFC 2136
                );
        if (! strlen($op[$opcode])) {
            $op[$opcode] = NULL;
        }
        return($op[$opcode]);
    }

    /* }}} */
    /* Net_DNS::opcodesbyval() {{{*/
    /**
     * Translates opcode integers into names
     *
     * Translates the integer value of an opcode into it's name
     * 
     * @param   integer $opcodeval  A DNS packet opcode integer
     * @return  string  The name of the OPCODE
     * @see     Net_DNS::opcodesbyname()
     */
    function opcodesbyval($opcodeval)
    {
        $opval = array(
                0 => 'QUERY',
                1 => 'IQUERY',
                2 => 'STATUS',
                4 => 'NS_NOTIFY_OP',
                5 => 'UPDATE',
                );
        if (! strlen($opval[$opcodeval])) {
            $opval[$opcodeval] = NULL;
        }
        return($opval[$opcodeval]);
    }

    /*}}}*/
    /* Net_DNS::rcodesbyname() {{{*/
    /**
     * Translates rcode names to integers
     *
     * Translates the name of a DNS RCODE (result code) into it's assigned number.
     * <ul>
     *   <li>NOERROR   
     *   <li>FORMERR   
     *   <li>SERVFAIL   
     *   <li>NXDOMAIN   
     *   <li>NOTIMP   
     *   <li>REFUSED   
     *   <li>YXDOMAIN   
     *   <li>YXRRSET   
     *   <li>NXRRSET   
     *   <li>NOTAUTH   
     *   <li>NOTZONE
     * <ul>
     * 
     * @param   string  $rcode  A DNS Packet RCODE name
     * @return  integer The integer value of an RCODE
     * @see     Net_DNS::rcodesbyval()
     */
    function rcodesbyname($rcode)
    {
        $rc = array(
                'NOERROR'   => 0,   // RFC 1035
                'FORMERR'   => 1,   // RFC 1035
                'SERVFAIL'  => 2,   // RFC 1035
                'NXDOMAIN'  => 3,   // RFC 1035
                'NOTIMP'    => 4,   // RFC 1035
                'REFUSED'   => 5,   // RFC 1035
                'YXDOMAIN'  => 6,   // RFC 2136
                'YXRRSET'   => 7,   // RFC 2136
                'NXRRSET'   => 8,   // RFC 2136
                'NOTAUTH'   => 9,   // RFC 2136
                'NOTZONE'   => 10,    // RFC 2136
                );
        if (! strlen($rc[$rcode])) {
            $rc[$rcode] = NULL;
        }
        return($rc[$rcode]);
    }

    /*}}}*/
    /* Net_DNS::rcodesbyval() {{{*/
    /**
     * Translates rcode integers into names
     *
     * Translates the integer value of an rcode into it's name
     * 
     * @param   integer $rcodeval   A DNS packet rcode integer
     * @return  string  The name of the RCODE
     * @see     Net_DNS::rcodesbyname()
     */
    function rcodesbyval($rcodeval)
    {
        $rc = array(
                0 => 'NOERROR',
                1 => 'FORMERR',
                2 => 'SERVFAIL',
                3 => 'NXDOMAIN',
                4 => 'NOTIMP',
                5 => 'REFUSED',
                6 => 'YXDOMAIN',
                7 => 'YXRRSET',
                8 => 'NXRRSET',
                9 => 'NOTAUTH',
                10 => 'NOTZONE',
                );
        if (! strlen($rc[$rcodeval])) {
            $rc[$rcodeval] = NULL;
        }
        return($rc[$rcodeval]);
    }

    /*}}}*/
    /* Net_DNS::typesbyname() {{{*/
    /**
     * Translates RR type names into integers
     *
     * Translates a Resource Record from it's name to it's  integer value.
     * Valid resource record types are:
     *
     * <ul>
     *   <li>A   
     *   <li>NS   
     *   <li>MD   
     *   <li>MF   
     *   <li>CNAME   
     *   <li>SOA   
     *   <li>MB   
     *   <li>MG   
     *   <li>MR   
     *   <li>NULL   
     *   <li>WKS   
     *   <li>PTR   
     *   <li>HINFO   
     *   <li>MINFO   
     *   <li>MX   
     *   <li>TXT   
     *   <li>RP   
     *   <li>AFSDB   
     *   <li>X25   
     *   <li>ISDN   
     *   <li>RT   
     *   <li>NSAP   
     *   <li>NSAP_PTR   
     *   <li>SIG   
     *   <li>KEY   
     *   <li>PX   
     *   <li>GPOS   
     *   <li>AAAA   
     *   <li>LOC   
     *   <li>NXT   
     *   <li>EID   
     *   <li>NIMLOC   
     *   <li>SRV   
     *   <li>ATMA   
     *   <li>NAPTR   
     *   <li>TSIG   
     *   <li>UINFO   
     *   <li>UID   
     *   <li>GID   
     *   <li>UNSPEC   
     *   <li>IXFR   
     *   <li>AXFR   
     *   <li>MAILB   
     *   <li>MAILA   
     *   <li>ANY
     * <ul>
     * 
     * @param   string  $rrtype A DNS packet RR type name   
     * @return  integer The integer value of an RR type
     * @see     Net_DNS::typesbyval()
     */
    function typesbyname($rrtype)
    {
        $rc = array(
                'A'             => 1,
                'NS'            => 2,
                'MD'            => 3,
                'MF'            => 4,
                'CNAME'         => 5,
                'SOA'           => 6,
                'MB'            => 7,
                'MG'            => 8,
                'MR'            => 9,
                'NULL'          => 10,
                'WKS'           => 11,
                'PTR'           => 12,
                'HINFO'         => 13,
                'MINFO'         => 14,
                'MX'            => 15,
                'TXT'           => 16,
                'RP'            => 17,
                'AFSDB'         => 18,
                'X25'           => 19,
                'ISDN'          => 20,
                'RT'            => 21,
                'NSAP'          => 22,
                'NSAP_PTR'      => 23,
                'SIG'           => 24,
                'KEY'           => 25,
                'PX'            => 26,
                'GPOS'          => 27,
                'AAAA'          => 28,
                'LOC'           => 29,
                'NXT'           => 30,
                'EID'           => 31,
                'NIMLOC'        => 32,
                'SRV'           => 33,
                'ATMA'          => 34,
                'NAPTR'         => 35,
                'UINFO'         => 100,
                'UID'           => 101,
                'GID'           => 102,
                'UNSPEC'        => 103,
                'TSIG'          => 250,
                'IXFR'          => 251,
                'AXFR'          => 252,
                'MAILB'         => 253,
                'MAILA'         => 254,
                'ANY'           => 255,
                );
                if (! strlen($rc[$rrtype])) {
                    $rc[$rrtype] = NULL;
                }
                return($rc[$rrtype]);
    }

    /*}}}*/
    /* Net_DNS::typesbyval() {{{*/
    /**
     * Translates RR type integers into names
     *
     * Translates the integer value of an RR type into it's name
     * 
     * @param   integer $rrtypeval  A DNS packet RR type integer
     * @return  string  The name of the RR type
     * @see     Net_DNS::typesbyname()
     */
    function typesbyval($rrtypeval)
    {
        $rc = array(
                1 => 'A',
                2 => 'NS',
                3 => 'MD',
                4 => 'MF',
                5 => 'CNAME',
                6 => 'SOA',
                7 => 'MB',
                8 => 'MG',
                9 => 'MR',
                10 => 'NULL',
                11 => 'WKS',
                12 => 'PTR',
                13 => 'HINFO',
                14 => 'MINFO',
                15 => 'MX',
                16 => 'TXT',
                17 => 'RP',
                18 => 'AFSDB',
                19 => 'X25',
                20 => 'ISDN',
                21 => 'RT',
                22 => 'NSAP',
                23 => 'NSAP_PTR',
                24 => 'SIG',
                25 => 'KEY',
                26 => 'PX',
                27 => 'GPOS',
                28 => 'AAAA',
                29 => 'LOC',
                30 => 'NXT',
                31 => 'EID',
                32 => 'NIMLOC',
                33 => 'SRV',
                34 => 'ATMA',
                35 => 'NAPTR',
                100 => 'UINFO',
                101 => 'UID',
                102 => 'GID',
                103 => 'UNSPEC',
                250 => 'TSIG',
                251 => 'IXFR',
                252 => 'AXFR',
                253 => 'MAILB',
                254 => 'MAILA',
                255 => 'ANY',
                );
                if (! strlen($rc[$rrtypeval])) {
                    $rc[$rrtypeval] = NULL;
                }
                return($rc[$rrtypeval]);
    }

    /*}}}*/
    /* Net_DNS::classesbyname() {{{*/
    /**
     * translates a DNS class from it's name to it's  integer value. Valid
     * class names are:
     * <ul>
     *   <li>IN   
     *   <li>CH   
     *   <li>HS   
     *   <li>NONE   
     *   <li>ANY
     * </ul>
     * 
     * @param   string  $class  A DNS packet class type
     * @return  integer The integer value of an class type
     * @see     Net_DNS::classesbyval()
     */
    function classesbyname($class)
    {
        $rc = array(
                'IN'            => 1,
                'CH'            => 3,
                'HS'            => 4,
                'NONE'          => 254,
                'ANY'           => 255
                );
        if (! isset($rc[$class])) {
            $rc[$class] = NULL;
        }
        return($rc[$class]);
    }

    /*}}}*/
    /* Net_DNS::classesbyval() {{{*/
    /**
     * Translates RR class integers into names
     *
     * Translates the integer value of an RR class into it's name
     * 
     * @param   integer $classval   A DNS packet RR class integer
     * @return  string  The name of the RR class
     * @see     Net_DNS::classesbyname()
     */
    function classesbyval($classval)
    {
        $rc = array(
                1 => 'IN',
                3 => 'CH',
                4 => 'HS',
                254 => 'NONE',
                255 => 'ANY'
                );
        if (! strlen($rc[$classval])) {
            $rc[$classval] = NULL;
        }
        return($rc[$classval]);
    }

    /*}}}*/
    /* not completed - Net_DNS::mx() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::yxrrset() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::nxrrset() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::yxdomain() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::nxdomain() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::rr_add() {{{*/
    /*}}}*/
    /* not completed - Net_DNS::rr_del() {{{*/
    /*}}}*/
}
/* }}} */
/* VIM Settings {{{
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * soft-stop-width: 4
 * c indent on
 * End:
 * vim600: sw=4 ts=4 sts=4 cindent fdm=marker et
 * vim<600: sw=4 ts=4
 * }}} */



?>