#!/usr/bin/env python3

# scripts/config_hillstone.py
#
# Import/Export script for Hillstone.
#
# @author Maosen Sun <sunnymaosen@gmail.com,sun285529808@qq.com>
# @author Haibin Qiao <haibin.qiao@gmail.com >
# @copyright Maosen Sun, Haibin Qiao
# @organization EmulatedLab(QQ Group:593920308)
# @link http://www.EmulatedLab.com
# @version 20170628

import getopt, multiprocessing, os, pexpect, re, sys, time

username = 'hillstone'
password = 'hillstone'
secret = ''
conntimeout = 3     # Maximum time for console connection
expctimeout = 5     # Maximum time for each short expect
longtimeout = 30    # Maximum time for each long expect
timeout = 60        # Maximum run time (conntimeout is included)

def node_login(handler):
    # Send an empty line, and wait for the login prompt
    i = -1
#    j = -1
    while i == -1:
        handler.sendline('\r\n')
        try:
            i = handler.expect([
		'login:',
                'password:',
                '#',
		'\(config'], timeout = 5)
        except:
            i = -1

    if i == 0:
        # Need to send username and password
        handler.sendline(username)
        try:
            handler.expect('password:', timeout = expctimeout)
        except:
            print('ERROR: error waiting for "password:" prompt.')
            node_quit(handler)
            return False
        handler.sendline(password)
        try:
            handler.expect('#', timeout = expctimeout)
        except:
            print('ERROR: error waiting for "#" prompt.')    
            node_quit(handler)
            return False
    elif i == 1:
        handler.sendline(password)
        try:
            handler.expect('#', timeout = expctimeout)
        except:
            print('ERROR: error waiting for "#" prompt.')
            node_quit(handler)
            return False
    elif i == 2:
        # Nothing to do
        return True
    elif i == 3:
        # Send End
        handler.sendline('end')
        return True
    else:
        # Unexpected output
        node_quit(handler)
        return False

    return True

def node_quit(handler):
    if handler.isalive() == True:
        handler.sendline('config')
        handler.sendline('no terminal length')
        handler.sendline('end')
        handler.sendline('exit\r\n')
    handler.close()

def config_get(handler):
    # Clearing all "expect" buffer
#    handler.sendline('show configuration running')
    while True:
        try:
            handler.expect('#', timeout = 0.1)
        except:
            break

    # Disable paging
    handler.sendline('config')
    handler.sendline('terminal length 0')
    handler.sendline('end')
    try:
        handler.expect('#', timeout = expctimeout)
    except:
        print('ERROR: error waiting for "#" prompt.')
        node_quit(handler)
        return False

    # Getting the config
    handler.sendline('show configuration running')
    try:
        handler.expect('End', timeout = longtimeout)
    except:
        print('ERROR: error waiting for "#" prompt.')
        node_quit(handler)
        return False
    config = handler.before.decode()

    # Manipulating the config
    config = re.sub('\r', '', config, flags=re.DOTALL)                                      # Unix style
    config = re.sub('.*show configuration running', '', config, flags=re.DOTALL)     # Header
    config = re.sub('^End', '', config, flags=re.DOTALL)     # Footer
    # config = re.sub('^return','', config, flags=re.DOTALL)  # Footer

    return config

def config_put(handler, config):
    # Got to configure mode
    p = 1
    handler.sendline('\r\n')
    handler.sendline('config')
    try:
        handler.expect('#', timeout = expctimeout)
    except:
        print('ERROR: error waiting for "#" prompt.')
        node_quit(handler)
        return False

    # Pushing the config
    for line in config.splitlines():
        if re.match(r'ips\s.*|qos-engine.*', line):
            p = 0
            continue
        elif p == 0:
            if line == "exit":
            # handler.sendline(line)
                p = 1
            continue

        handler.sendline(line)
        try:
            handler.expect('\r\n', timeout = longtimeout)
        except:
            print('ERROR: error waiting for EOL.')
            node_quit(handler)
            return False

    # At the end of configuration be sure we are in non config mode (sending CTRl + Z)
    handler.sendline('\x1A')
    try:
        handler.expect('#', timeout = expctimeout)
    except:
        print('ERROR: error waiting for "#" prompt.')
        node_quit(handler)
        return False

    # Save
    handler.sendline('save')
    handler.send('y')

    # The current configuration will be written to the device. Are you sure? [Y/N]:y

    # (To leave the existing filename unchanged, press the enter key):

    # flash:/startup.cfg exists, overwrite? [Y/N]:y
    try:
        handler.expect('y/\[n\]:', timeout = expctimeout)
        handler.send('y')
    except:
        return True

    try:
        handler.expect('#', timeout = longtimeout)
    except:
        print('ERROR: error waiting for "#" prompt.')
        handler.sendline('\x1A')
        return True
    return True

def usage():
    print('Usage: %s <standard options>' %(sys.argv[0]));
    print('Standard Options:');
    print('-a <s>    *Action can be:')
    print('           - get: get the startup-configuration and push it to a file')
    print('           - put: put the file as startup-configuration')
    print('-f <s>    *File');
    print('-p <n>    *Console port');
    print('-t <n>     Timeout (default = %i)' %(timeout));
    print('* Mandatory option')

def now():
    # Return current UNIX time in milliseconds
    return int(round(time.time() * 1000))

def main(action, fiename, port):
    try:
        # Connect to the device
        tmp = conntimeout
        while (tmp > 0):
            handler = pexpect.spawn('telnet 127.0.0.1 %i' %(port))
            time.sleep(0.1)
            tmp = tmp - 0.1
            if handler.isalive() == True:
                break

        if (handler.isalive() != True):
            print('ERROR: cannot connect to port "%i".' %(port))
            node_quit(handler)
            sys.exit(1)

        if action == 'get':
            # Login to the device and get a privileged prompt
            rc = node_login(handler)
            if rc != True:
                print('ERROR: failed to login.')
                node_quit(handler)
                sys.exit(1)

            config = config_get(handler)
            if config in [False, None]:
                print('ERROR: failed to retrieve config.')
                node_quit(handler)
                sys.exit(1)

            try:
                fd = open(filename, 'a')
                fd.write(config)
                fd.close()
            except:
                print('ERROR: cannot write config to file.')
                node_quit(handler)
                sys.exit(1)
        elif action == 'put':
            # Login to the device and get a privileged prompt
            rc = node_login(handler)
            if rc != True:
                print('ERROR: failed to login.')
                node_quit(handler)
                sys.exit(1)

            try:
                fd = open(filename, 'r')
                config = fd.read()
                fd.close()
            except:
                print('ERROR: cannot read config from file.')
                node_quit(handler)
                sys.exit(1)

            rc = config_put(handler, config)
            if rc != True:
                print('ERROR: failed to push config.')
                node_quit(handler)
                sys.exit(1)

            # Remove lock file
            lock = '%s/.lock' %(os.path.dirname(filename))

            if os.path.exists(lock):
                os.remove(lock)

            # Mark as configured
            configured = '%s/.configured' %(os.path.dirname(filename))
            if not os.path.exists(configured):
                open(configured, 'a').close()

        node_quit(handler)
        sys.exit(0)

    except Exception as e:
        print('ERROR: got an exception')
        print(type(e))  # the exception instance
        print(e.args)   # arguments stored in .args
        print(e)        # __str__ allows args to be printed directly,
        node_quit(handler)
        return False

if __name__ == "__main__":
    action = None
    filename = None
    port = None

    # Getting parameters from command line
    try:
        opts, args = getopt.getopt(sys.argv[1:], 'a:p:t:f:', ['action=', 'port=', 'timeout=', 'file='])
    except getopt.GetoptError as e:
        usage()
        sys.exit(3)

    for o, a in opts:
        if o in ('-a', '--action'):
            action = a
        elif o in ('-f', '--file'):
            filename = a
        elif o in ('-p', '--port'):
            try:
                port = int(a)
            except:
                port = -1
        elif o in ('-t', '--timeout'):
            try:
                timeout = int(a)
            except:
                timeout = -1
        else:
            print('ERROR: invalid parameter.')

    # Checking mandatory parameters
    if action == None or port == None or filename == None:
        usage()
        print('ERROR: missing mandatory parameters.')
        sys.exit(1)
    if action not in ['get', 'put']:
        usage()
        print('ERROR: invalid action.')
        sys.exit(1)
    if timeout < 0:
        usage()
        print('ERROR: timeout must be 0 or higher.')
        sys.exit(1)
    if port < 0:
        usage()
        print('ERROR: port must be 32768 or higher.')
        sys.exit(1)
    if action == 'get' and os.path.exists(filename):
        usage()
        print('ERROR: destination file already exists.')
        sys.exit(1)
    if action == 'put' and not os.path.exists(filename):
        usage()
        print('ERROR: source file does not already exist.')
        sys.exit(1)
    if action == 'put':
        try:
            fd = open(filename, 'r')
            config = fd.read()
            fd.close()
        except:
            usage()
            print('ERROR: cannot read from file.')
            sys.exit(1)

    # Backgrounding the script
    end_before = now() + timeout * 1000
    p = multiprocessing.Process(target=main, name="Main", args=(action, filename, port))
    p.start()

    while (p.is_alive() and now() < end_before):
        # Waiting for the child process to end
        time.sleep(1)

    if p.is_alive():
        # Timeout occurred
        print('ERROR: timeout occurred.')
        p.terminate()
        sys.exit(127)

    if p.exitcode != 0:
        sys.exit(127)

    sys.exit(0)

