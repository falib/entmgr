import os, os.path
import requests

s = requests.Session()

""" 
POST to API 
:param endpoint - string - the endpoint name 
:param payload - object - the data to post 
"""
def postAPI(endpoint, payload):
    baseURL = 'http://54.221.143.11/entmgr/api.php'
    req = s.post(baseURL + endpoint, data=payload)

    return req.json()

"""
Get Data from API
:param endpoint - string - the endpoint name
:filters - string - parameters to the endpoint
"""
def getAPI(endpoint, filters):
    baseURL = 'http://54.221.143.11/entmgr/api.php'
    req = s.get(baseURL+endpoint+filters)

    return req.json()

"""
Update records in API
:param endpoint - string -  the endpoint name
:param payload - object - data to put
"""
def updateAPI(endpoint,filters,payload):
    baseURL = 'http://54.221.143.11/entmgr/api.php'
    req = s.put(baseURL+endpoint+filters,data=payload)

    return req.json()

"""
Disables a systemd service
:param service - string - the name of the service
"""
def disableService(service):
    # stops the service if its running #
    os.system('sudo systemctl stop ' + service)

    cmd = 'sudo systemctl disable ' + service
    
    return os.system(cmd)

"""
Enables a systemd service
:param service - string - the name of the service
"""
def enableService(service):
    cmd = 'sudo systemctl enable ' + service
    
    if os.system(cmd):
        return os.system('sudo systemctl start ' + service)
    else:
        return "Something went wrong"

"""
Disables a network interface
:param interface - string - the name of the interface e.g wifi
"""
def disableInterface(interface):
    cmd = 'connmanctl disable' + interface

    return os.system(cmd)
"""
Enables a network interface
:param interface - string - the name of the interface e.g wifi
"""

def enableInterface(interface):
    cmd = 'connmanctl enable' + interface

    return os.system(cmd)

"""
Gets the mac address from /sys/class/net/
:param interface - string - interface name e.g wlan0
"""
def getMac(interface):
    path = '/sys/class/net/' + interface + '/address'
    cmd = 'cat ' + path

    if os.path.exists(path):
        return os.system(cmd)
    else:
        return False

""" Start the script logic here """

""" Get the userid and password from the end user """
if os.path.exists("/tmp/cred"):
    creds_path = open("/tmp/cred","r")
    get_creds = creds_path.readline();
    creds_str = get_creds.split(",")
    username = creds_str[0]
    password = creds_str[1]
else:
    username = input("Enter the user id: ")
    password = input("Enter the password: ")

""" Try wlan0 first if it does not exist then eth1 """
mac = getMac("wlan0")
if mac is False:
    mac = getMac("eth1")
    if mac is False:
        mac = 0

print(mac)
""" By default run disable commands just in case image has enabled """
cmd_result = disableService("kodi")
print(cmd_result)

""" make an auth call to login """
login = '/login'
device = '/records/device'
filters = '?user_id=' + username
auth = {
        'username': username,
        'password': password
    }

auth_response = postAPI(login, auth)


""" If the user authenticates """
if 'user_id' in auth_response:
    # get the device info #
    device_record = getAPI(device,filters)
 
    for device_response in device_record['records']:
        print(device_response)
        if username == device_response['user_id']:
            # store the credentials in /tmp which is cleared on reboot#
            creds = open("/tmp/cred","w+")
            creds.write(str(username) + "," + password)
            creds.close()
            # Check mac address and If empty then send mac address back to rest api #
            if device_response['mac_address'] is None or 0:
                mac_info = { 'mac_address': mac}
                filters = '/' + str(device_response['id'])
                mac_response = updateAPI(device,filters,mac_info)
                if mac_response:
                    new_response = getAPI(device,filters)
                    print(new_response)
            elif device_response['mac_address'] is not mac:
                print("This userid is associated with another device")
            # Check Quotas and enable/disable services #
            print("Points Quota is currently set to " + str(device_response['points_quota']))
            if device_response['points_quota'] <= 500:
                if disableService("kodi"):
                    print("Kodi Disabled")
            elif 700 <= device_response['points_quota'] <= 900: 
                if enableService("kodi"):
                    print("Kodi enabled")
                if disableService("vlc"):
                    print("VLC disabled")
                if disableInterface("wifi"):
                    print("Wifi disabled")
            elif 900 <= device_response['points_quota'] <= 1000:
                if enableService("kodi"):
                    print("Kodi enabled")
                if enableService("vlc"):
                    print("VLC enabled")
                if enableInterface("wifi"):
                    print("Wifi enabled")
        else:
           print("No user device found")
                

    # Configure sshd #
    ssh_path = "/etc/ssh/sshd_config"
    ssh_allowpw = "\nPasswordAuthenication yes"
    ssh_allowroot = "\nPermitRootLogin yes"
   
   # Only needed for dev testing #
    if os.path.exists(ssh_path):
        sshd_config = open(ssh_path,"a")
        #sshd_config.write("\nPort " + str(username))
        sshd_config.write(ssh_allowpw + ssh_allowroot)
        sshd_config.close()
        # restart ssh service to use new port #
        if os.system("systemctl restart sshd"):
            print("Restarted SSH Service with new config")
    # initiate reverse ssh tunnel script and set cron #
    os.system("./tun " + str(device_response['user_id']) + "&")
    new_cron = open("cronfile","w+")
    new_cron.write("* 12 * * * sudo /usr/bin/python3 api.py\n")
    new_cron.close()
    if os.system("sudo crontab cronfile"):
        print("Cron is set")
        
    else:
        print("No sshd config file found")
else:
    print(auth_response["message"])
