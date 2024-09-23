PW 155 Server Out-of-the-Box Setup, courtesy of Wrechid :

1 : Copy pw155_pwAdmin.tar.gz to server, and extract
		(optional, copy extract_PW_tar.sh to same directory and run to extract)

2 : On Debian 8 or Debian 10 install the required packages by running
		/root/install.sh

3 : Start/Stop server type at prompt
		server start
		server status
		server stop
		server restart
		server ?

NOTES ============================================== BEGIN

serverlist.txt (TAB key delimited) (port range used according to the number of glinkd loaded, each port will need to be opened in firewall/router)

	Server
	Serv_Name	29000:<ip-addy>	1
or
	Server
	Serv_Name	29000-29006:<ip-addy>	1

server start scripts chain start maps using considerable less ram (20GB full maps)
only 2 maps start first time (world and a61)
after first "server start", edit /home/maps

username:password = root:root

Server zoneid = 1

pvp mode set to pve/pvp

commented/disabled all "seckey" fields

debug_command_mode = active

set number of glinkd instances in server control scripts, variable near the top called "gl", currently set at max 7

set zoneid and aid in /var/www/register.php vis text editor near top of file for gold to new accounts

aipolicy.data is Chinese copy translated to English using older aipolicy.data and manually translating the new items

MySQL php admin = http://<ip_addy>/phpmyadmin/
PW Admin = http://<ip_addy>:8080/pwadmin/
registration page = http://<ip_addy>/register.php
User panel = http://<ip_addy>/pwAdmin/setup

NOTES ================================================ END
