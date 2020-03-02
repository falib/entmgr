import os, os.path
import requests
import re

# Define system paths and other configuration related variables #
vlc = "/usr/bin/vlc"
cred_file = "/root/cred";

# init session for rest api #
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

def setVLC(status):
    if os.path.exists(vlc):
        if status is "enable":
            os.system("sudo chmod 755 " + vlc)
        elif status is "disable":
            os.system("sudo chmod 400 " + vlc)
    else:
        print("VLC is not installed at " + vlc)

def get_creds():
    cred = []
    username = input("Enter the user id: ")
    password = input("Enter the password: ")

    cred = [username,password]
    return cred

""" Start the script logic here """

""" Get the userid and password from the end user """
if os.path.exists(cred_file):
    creds_path = open(cred_file,"r")
    get_creds = creds_path.readline();
    creds_str = get_creds.split(",")
    username = creds_str[0]
    password = creds_str[1]
else:
    cred = get_creds()
    username = cred[0]
    password = cred[1]
   # username = input("Enter the user id: ")
   # password = input("Enter the password: ")

""" Try eth0 first if it does not exist then eth1 """
mac = getMac("eth0")
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
            # store the credentials in /root/cred #
            creds = open(cred_file,"w+")
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
                

            # Configure sshd #
            ssh_path = "/etc/ssh/sshd_config"
            ssh_allowpw = "PasswordAuthentication"
            ssh_allowroot = "PermitRootLogin"

            # Only needed for dev testing #
            if os.path.exists(ssh_path):
                sshd_config = open(ssh_path,"r")
                sshd_text = sshd_config.read()
            if re.search(ssh_allowpw + " no", sshd_text):
                print("match")
                sshd_replace = re.sub(ssh_allowpw + " no",ssh_allowpw + " yes" ,sshd_text)
            elif re.search(ssh_allowpw, sshd_text) is None:
                sshd_replace = sshd_text + "\n" + ssh_allowpw + " yes\n"
            if re.search(ssh_allowroot,sshd_text):
                print("match2")
                sshd_replace = re.sub("#" + ssh_allowroot + " prohibit-password",ssh_allowroot + " yes",sshd_text)
            else:
                sshd_replace = sshd_replace + "\n" + ssh_allowroot + " yes\n"
            sshd_config.close()
            sshd_config = open(ssh_path,"w+")
            #sshd_config.write("\nPort " + str(username))
            #sshd_config.write(ssh_allowpw + ssh_allowroot)
            sshd_config.write(sshd_replace)
            sshd_config.close()

            # restart ssh service to use new port #
            if os.system("sudo systemctl restart sshd"):
                print("Restarted SSH Service with new config")
            # initiate reverse ssh tunnel script, reg and start service #
            autossh = "autossh-svc"
            if 1024 < int(device_response['user_id']) < 9000:
                ssh_port = device_response['user_id']
            elif 1024 > int(device_response['user_id']):
                ssh_port ="{:0<5}".format(device_response['user_id'])
            elif 9000 < int(device_response['user_id']):
                ssh_port = str(device_response['user_id'])[1:5]
                print("More than 9000 " + ssh_port)
            service_cmd = "ExecStart=/usr/bin/autossh -M 0 -o 'ServerAliveInterval 30' -o 'ServerAliveCountMax 3' -o 'UserKnownHostsFile=/dev/null' -o 'StrictHostKeyChecking=no' -TN -R " + str(ssh_port) + ":localhost:22 -i /home/ubuntu/LightsailDefaultKey-us-east-1.pem bitnami@54.221.143.11"
            autossh_conf = open(autossh,"r")
            autossh_text = autossh_conf.read()
            if re.search('ExecStart.*',autossh_text):
                print("Found ExecStart")
                autossh_text = re.sub('ExecStart.*',service_cmd,autossh_text)
            autossh_conf.close()
            autossh_conf = open(autossh,"w+")
            autossh_conf.write(autossh_text)
            autossh_conf.close()
            # If autossh already exists then stop the service first #
            if os.path.exists("/etc/systemd/system/autossh-svc.service"):
                os.system("sudo systemctl stop autossh-svc")
            os.system("sudo cp autossh-svc /etc/systemd/system/autossh-svc.service")
            os.system("sudo systemctl daemon-reload")
            os.system("sudo systemctl enable autossh-svc")
            if os.system("sudo systemctl start autossh-svc"):
                print("Auto SSH started")

            # setup cron job for this script #
            new_cron = open("cronfile","w+")
            new_cron.write("* 12 * * * sudo /usr/bin/python3 api.py\n")
            new_cron.close()
            if os.system("sudo crontab cronfile") is 0:
                print("Cron is set") 
            else:
                print("No cron config file found")
        else:
            print("User not found, Please try again")
            count += 1
            if count < 3:
                cred = get_creds()
                username = cred[0]
                password = cred[1]
            else:
                print("You have exceed the number of allowed login attempts, the box will now reboot")
                os.system("sudo init 6")
else:
    print(auth_response["message"])
