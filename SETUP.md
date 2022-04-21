# Setup

## Default Emails

| User       | Domain             |
| ---------- | ------------------ |
| aplicacion | matriz.autoupb.com |
| admin      | matriz.autoupb.com |
| gerente    | matriz.autoupb.com |
| inventario | matriz.autoupb.com |
| rh         | matriz.autoupb.com |
| ventas     | matriz.autoupb.com |

## Production

### Setup API

1. Install Go > 1.18.
2. Compile:

```shell
go build -buildvcs=false -ldflags="-s -w" -trimpath -mod vendor -o ./api.exe ./cmd/api
```

3. Set environment variables

| Variable name | Description                                             | Example value                           |
| ------------- | ------------------------------------------------------- | --------------------------------------- |
| API-KEY       | API Key to be used by the clients connecting to the API | API_KEY_TO_BE_USED_BY_CLIENTS           |
| DB-URL        | Database URL to connect to                              | root:password@tcp(127.0.0.1)/upb_motors |
| HOST          | Listen address                                          | 127.0.0.1:8080                          |

4. Execute the binary

```shell
./api.exe
```

### Build Web catalog

1. Install Go > 1.18.
2. Compile:

```shell
go build -buildvcs=false -ldflags="-s -w" -trimpath -mod vendor -o ./web.exe ./cmd/web
```

3. Set environment variables

| Variable name | Description                                             | Example value                           |
| ------------- | ------------------------------------------------------- | --------------------------------------- |
| API-KEY       | API Key to be used by the clients connecting to the API | API_KEY_TO_BE_USED_BY_CLIENTS           |
| DB-URL        | Database URL to connect to                              | root:password@tcp(127.0.0.1)/upb_motors |
| HOST          | Listen address                                          | 127.0.0.1:8080                          |

4. Execute the binary

```shell
./web.exe
```

### FTP(S)

Using Windows server.

1. Add roles and features

<img src="docs/images/1.png" alt="1" style="zoom:50%;" />

2. Installation Type `Role-bases`

<img src="docs/images/2.png" alt="2" style="zoom:50%;" />

3. Web server (IIS)

<img src="docs/images/3.png" alt="3" style="zoom:50%;" />

4. Disable all and enable `FTP`

<img src="docs/images/4.png" alt="4" style="zoom:50%;" />

5. `Open Server Manager >Tools > Internet Information Services (IIS) Manager`

<img src="docs/images/5.png" alt="5" style="zoom:50%;" />

6. FTP Firewall support

<img src="docs/images/6.png" alt="6" style="zoom:50%;" />

7. Set configuration and apply

<img src="docs/images/7.png" alt="7" style="zoom:50%;" />

8. Add ports to firewall inbound rules

<img src="docs/images/8.png" alt="8" style="zoom:50%;" />

<img src="docs/images/9.png" alt="9" style="zoom:50%;" />

<img src="docs/images/10.png" alt="10" style="zoom:50%;" />

<img src="docs/images/11.png" alt="11" style="zoom:50%;" />

<img src="docs/images/12.png" alt="12" style="zoom:50%;" />

9. Create SSL certificate.

```powershell
New-SelfSignedCertificate -FriendlyName "selfsigned-upb-motors" -CertStoreLocation cert:\localmachine\my -DnsName www.matriz.autoupb.com
```

![13](docs/images/13.png)

10. Serve folder in FTP

<img src="docs/images/14.png" alt="14" style="zoom:50%;" />

<img src="docs/images/15.png" alt="15" style="zoom:50%;" />

<img src="docs/images/16.png" alt="16" style="zoom:50%;" />

11. Serve folder in FTPS

<img src="docs/images/17.png" alt="17" style="zoom:50%;" />

<img src="docs/images/18.png" alt="18" style="zoom:50%;" />

<img src="docs/images/19.png" alt="19" style="zoom:50%;" />

### HTTP(S)

#### Setup container

Install docker in the operating system.

1. Update `config.json`:

2. Build the image:

```shell
docker build -t upb-motors:latest /path/to/repository
```

3. Create the container:

```shell
docker create -p 80:80 -p 443:443 --restart unless-stopped --name upb-motors-production upb-motors:latest
```

4. Start the container:

```shell
docker start upb-motors-production
```

### Primary DNS

#### Using Windows Server.

1. Add roles and features

<img src="docs/images/1.png" alt="1" style="zoom:50%;" />

2. Installation Type `Role-bases`

<img src="docs/images/2.png" alt="2" style="zoom:50%;" />

3. DNS server

<img src="docs/images/21.png" alt="21" style="zoom:50%;" />

4. Confirmation and install

<img src="docs/images/22.png" alt="22" style="zoom:50%;" />

4. `Open Server Manager >Tools > DNS`

<img src="docs/images/23.png" alt="23" style="zoom:50%;" />

5. Select server

<img src="docs/images/24.png" alt="24" style="zoom:50%;" />

6. New zone

![25](docs/images/25.png)

7. Primary zone

<img src="docs/images/26.png" alt="26" style="zoom:50%;" />

8. Forward lookup zone

![27](docs/images/27.png)

9. Zone name

<img src="docs/images/28.png" alt="28" style="zoom:50%;" />

10. Zone file

<img src="docs/images/29.png" alt="29" style="zoom:50%;" />

11. No Dynamic updates

<img src="docs/images/30.png" alt="30" style="zoom:50%;" />

12. New Reverse lookup zone

<img src="docs/images/35.png" alt="35" style="zoom:50%;" />

<img src="docs/images/36.png" alt="36" style="zoom:50%;" />

<img src="docs/images/37.png" alt="37" style="zoom: 50%;" />

13. Ensure zone transfer is allowed (`Zone properties -> Zone tranfers`):

<img src="docs/images/45.png" alt="45" style="zoom:50%;" />

##### Services Domains

###### HTTP/HTTPS

1. New Host **A**

<img src="docs/images/31.png" alt="31" style="zoom:50%;" />

2. `www` name

<img src="docs/images/32.png" alt="32" style="zoom:50%;" />

###### FTP/FTPS

1. `ftp` name (A)

<img src="docs/images/33.png" alt="33" style="zoom:50%;" />

###### All mail services

1. `mail` host (A)

<img src="docs/images/39.png" alt="40" style="zoom:50%;" />

2. Mail exchange (MX)

<img src="docs/images/40.png" alt="40" style="zoom:50%;" />

### Secondary DNS

For each company, create a zone pointing to its domain and domain server. (Ensure that zone transfer is allowed in the primary server).

1. Create zone

<img src="docs/images/41.png" alt="41" style="zoom:50%;" />

2. Point to domain of company

<img src="docs/images/42.png" alt="42" style="zoom:50%;" />

<img src="docs/images/43.png" alt="43" style="zoom:50%;" />

<img src="docs/images/44.png" alt="44" style="zoom:50%;" />



### Mail

1. Access the file `sources.list`

```shell
sudo nano sources.list
```

2. comment the second line

![46](docs/images/46.png)

3. update repositories

```shell
sudo apt update
```

4. install postfix

```shell
apt install postfix
```

5. Press accept in postfix configuration

![47](docs/images/47.png)

6. Select website

![48](docs/images/48.png)

7. Add a mail system name

![49](docs/images/49.png)

8. restart postfix

```shell
sudo service postfix restart
```

9. Install dovecot

```shell
sudo apt install dovecot-imapd dovecot-pop3d
```

10. restart dovecot

```shell
sudo service dovecot restart
```

11. install PHP

```shell
sudo apt-get install php
```

12. install squirrelmail

```shell
wget https://sourceforge.net/projects/squirrelmail/files/stable/1.4.22/squirrelmail-webmail-1.4.22.zip
```

13. unzip the file

```shell
unzip squirrelmail-webmail-1.4.22.zip
```

14. Select file location

```shell
sudo mv squirrelmail-webmail-1.4.22 /var/www/html/
```

15. Change Directory Owner

```shell
sudo chown -R www-data:www-data /var/www/html/squirrelmail-webmail-1.4.22/
sudo chmod 755 -R /var/www/html/squirrelmail-webmail-1.4.22/
sudo mv /var/www/html/squirrelmail-webmail-1.4.22/ /var/www/html/squirrelmail
```

16. configure squirrelmail

```shell
sudo perl /var/www/html/squirrelmail/config/conf.pl
```

17. Select option 2 of "server settings"

![50](docs/images/50.png)

18. Select option 1 of "domain"

![51](docs/images/51.png)

19. Write the domain

![52](docs/images/52.png)

20. Select option 4 of "general options"

![53](docs/images/53.png)

21. Modify options 1,2 and 11

![54](docs/images/54.png)

22. Enter the link `localhost/src/login.php`

![55](docs/images/55.png)

### VoIP ASTERISK

1. update repositories

```shell
sudo apt update
```

2. install asterisk

```shell
sudo apt install asterisk
```
3. install core spanish

```shell
sudo apt install asterisk-prompt-es asterisk-core-sounds-es asterisk-core-sounds-es-gsm asterisk-core-sounds-es-wav asterisk-core-sounds-es-g722
```
3. check install core spanish

```shell
sudo dpkg -l asterisk*
```
4. configuration cell.conf

```
;
; Asterisk Channel Event Logging (CEL)
;

; Channel Event Logging is a mechanism to provide fine-grained event information
; that can be used to generate billing information. Such event information can
; be recorded to various backend modules.
;

[general]

; CEL Activation
;
; Use the 'enable' keyword to turn CEL on or off.
;
; Accepted values: yes and no
; Default value:   no

;enable=yes

; Application Tracking
;
; Use the 'apps' keyword to specify the list of applications for which you want
; to receive CEL events.  This is a comma separated list of Asterisk dialplan
; applications, such as Dial, Queue, and Park.
;
; Accepted values: A comma separated list of Asterisk dialplan applications
; Default value:   none
;
; Note: You may also use 'all' which will result in CEL events being reported
;       for all Asterisk applications.  This may affect Asterisk's performance
;       significantly.

apps=dial,park

; Event Tracking
;
; Use the 'events' keyword to specify the list of events which you want to be
; raised when they occur.  This is a comma separated list of the values in the
; table below.
;
; Accepted values: A comma separated list of one or more of the following:
;  ALL              -- Generate entries on all events
;  CHAN_START       -- The time a channel was created
;  CHAN_END         -- The time a channel was terminated
;  ANSWER           -- The time a channel was answered (ie, phone taken off-hook)
;  HANGUP           -- The time at which a hangup occurred
;  BRIDGE_ENTER       -- The time a channel was connected into a conference room
;  BRIDGE_EXIT        -- The time a channel was removed from a conference room
;  APP_START        -- The time a tracked application was started
;  APP_END          -- the time a tracked application ended
;  PARK_START       -- The time a call was parked
;  PARK_END         -- Unpark event
;  BLINDTRANSFER    -- When a blind transfer is initiated
;  ATTENDEDTRANSFER -- When an attended transfer is initiated
;  PICKUP           -- This channel picked up the specified channel
;  FORWARD          -- This channel is being forwarded somewhere else
;  LINKEDID_END     -- The last channel with the given linkedid is retired
;  USER_DEFINED     -- Triggered from the dialplan, and has a name given by the
;                      user
;  LOCAL_OPTIMIZE   -- A local channel pair is optimizing away.
;
; Default value: none
;                (Track no events)

events=APP_START,CHAN_START,CHAN_END,ANSWER,HANGUP,BRIDGE_ENTER,BRIDGE_EXIT

; Date Format
;
; Use the 'dateformat' keyword to specify the date format used when CEL events
; are raised.
;
; Accepted values: A strftime format string (see man strftime)
;
; Example: "%F %T"
;  -> This gives the date and time in the format "2009-06-23 17:02:35"
;
; If this option is not specified, the default format is "<seconds>.<microseconds>"
; since epoch.  The microseconds field will always be 6 digits in length, meaning it
; may have leading zeros.
;
;dateformat = %F %T

;
; Asterisk Manager Interface (AMI) CEL Backend
;
[manager]

; AMI Backend Activation
;
; Use the 'enable' keyword to turn CEL logging to the Asterisk Manager Interface
; on or off.
;
; Accepted values: yes and no
; Default value:   no
;enabled=yes

; Use 'show_user_defined' to put "USER_DEFINED" in the EventName header,
; instead of (by default) just putting the user defined event name there.
; When enabled the UserDefType header is added for user defined events to
; provide the user defined event name.
;
;show_user_defined=yes

;
; RADIUS CEL Backend
;
[radius]
;
; Log date/time in GMT
;usegmtime=yes
;
; Set this to the location of the radiusclient-ng configuration file
; The default is /etc/radiusclient-ng/radiusclient.conf
;radiuscfg => /usr/local/etc/radiusclient-ng/radiusclient.conf
;
radiuscfg => /etc/radcli/radiusclient.conf
```

5. configuration cdr.conf

```
;
; Asterisk Call Detail Record engine configuration
;
; CDR is Call Detail Record, which provides logging services via a variety of
; pluggable backend modules.  Detailed call information can be recorded to
; databases, files, etc.  Useful for billing, fraud prevention, compliance with
; Sarbanes-Oxley aka The Enron Act, QOS evaluations, and more.
;

[general]

; Define whether or not to use CDR logging.  Setting this to "no" will override
; any loading of backend CDR modules.  Default is "yes".
;enable=yes

; Define whether or not to log unanswered calls that don't involve an outgoing
; party. Setting this to "yes" will make calls to extensions that don't answer
; and don't set a B side channel (such as by using the Dial application)
; receive CDR log entries. If this option is set to "no", then those log
; entries will not be created. Unanswered Calls which get offered to an
; outgoing line will always receive log entries regardless of this option, and
; that is the intended behaviour.
;unanswered = no

; Define whether or not to log congested calls. Setting this to "yes" will
; report each call that fails to complete due to congestion conditions. Default
; is "no".
;congestion = no

; Normally, CDR's are not closed out until after all extensions are finished
; executing.  By enabling this option, the CDR will be ended before executing
; the "h" extension and hangup handlers so that CDR values such as "end" and
; "billsec" may be retrieved inside of of this extension.
; The default value is "no".
;endbeforehexten=no

; Normally, the 'billsec' field logged to the backends (text files or databases)
; is simply the end time (hangup time) minus the answer time in seconds. Internally,
; asterisk stores the time in terms of microseconds and seconds. By setting
; initiatedseconds to 'yes', you can force asterisk to report any seconds
; that were initiated (a sort of round up method). Technically, this is
; when the microsecond part of the end time is greater than the microsecond
; part of the answer time, then the billsec time is incremented one second.
; The default value is "no".
;initiatedseconds=no

; Define the CDR batch mode, where instead of posting the CDR at the end of
; every call, the data will be stored in a buffer to help alleviate load on the
; asterisk server.  Default is "no".
;
; WARNING WARNING WARNING
; Use of batch mode may result in data loss after unsafe asterisk termination
; ie. software crash, power failure, kill -9, etc.
; WARNING WARNING WARNING
;
;batch=no

; Define the maximum number of CDRs to accumulate in the buffer before posting
; them to the backend engines.  'batch' must be set to 'yes'.  Default is 100.
;size=100

; Define the maximum time to accumulate CDRs in the buffer before posting them
; to the backend engines.  If this time limit is reached, then it will post the
; records, regardless of the value defined for 'size'.  'batch' must be set to
; 'yes'.  Note that time is in seconds.  Default is 300 (5 minutes).
;time=300

; The CDR engine uses the internal asterisk scheduler to determine when to post
; records.  Posting can either occur inside the scheduler thread, or a new
; thread can be spawned for the submission of every batch.  For small batches,
; it might be acceptable to just use the scheduler thread, so set this to "yes".
; For large batches, say anything over size=10, a new thread is recommended, so
; set this to "no".  Default is "no".
;scheduleronly=no

; When shutting down asterisk, you can block until the CDRs are submitted.  If
; you don't, then data will likely be lost.  You can always check the size of
; the CDR batch buffer with the CLI "cdr status" command.  To enable blocking on
; submission of CDR data during asterisk shutdown, set this to "yes".  Default
; is "yes".
;safeshutdown=yes

;
;
; CHOOSING A CDR "BACKEND"  (what kind of output to generate)
;
; To choose a backend, you have to make sure either the right category is
; defined in this file, or that the appropriate config file exists, and has the
; proper definitions in it. If there are any problems, usually, the entry will
; silently ignored, and you get no output.
;
; Also, please note that you can generate CDR records in as many formats as you
; wish. If you configure 5 different CDR formats, then each event will be logged
; in 5 different places! In the example config files, all formats are commented
; out except for the cdr-csv format.
;
; Here are all the possible back ends:
;
;   csv, custom, manager, odbc, pgsql, radius, sqlite, tds
;    (also, mysql is available via the asterisk-addons, due to licensing
;     requirements)
;   (please note, also, that other backends can be created, by creating
;    a new backend module in the source cdr/ directory!)
;
; Some of the modules required to provide these backends will not build or install
; unless some dependency requirements are met. Examples of this are pgsql, odbc,
; etc. If you are not getting output as you would expect, the first thing to do
; is to run the command "make menuselect", and check what modules are available,
; by looking in the "2. Call Detail Recording" option in the main menu. If your
; backend is marked with XXX, you know that the "configure" command could not find
; the required libraries for that option.
;
; To get CDRs to be logged to the plain-jane /var/log/asterisk/cdr-csv/Master.csv
; file, define the [csv] category in this file. No database necessary. The example
; config files are set up to provide this kind of output by default.
;
; To get custom csv CDR records, make sure the cdr_custom.conf file
; is present, and contains the proper [mappings] section. The advantage to
; using this backend, is that you can define which fields to output, and in
; what order. By default, the example configs are set up to mimic the cdr-csv
; output. If you don't make any changes to the mappings, you are basically generating
; the same thing as cdr-csv, but expending more CPU cycles to do so!
;
; To get manager events generated, make sure the cdr_manager.conf file exists,
; and the [general] section is defined, with the single variable 'enabled = yes'.
;
; For odbc, make sure all the proper libs are installed, that "make menuselect"
; shows that the modules are available, and the cdr_odbc.conf file exists, and
; has a [global] section with the proper variables defined.
;
; For pgsql, make sure all the proper libs are installed, that "make menuselect"
; shows that the modules are available, and the cdr_pgsql.conf file exists, and
; has a [global] section with the proper variables defined.
;
; For logging to radius databases, make sure all the proper libs are installed, that
; "make menuselect" shows that the modules are available, and the [radius]
; category is defined in this file, and in that section, make sure the 'radiuscfg'
; variable is properly pointing to an existing radiusclient.conf file.
;
; For logging to sqlite databases, make sure the 'cdr.db' file exists in the log directory,
; which is usually /var/log/asterisk. Of course, the proper libraries should be available
; during the 'configure' operation.
;
; For tds logging, make sure the proper libraries are available during the 'configure'
; phase, and that cdr_tds.conf exists and is properly set up with a [global] category.
;
; Also, remember, that if you wish to log CDR info to a database, you will have to define
; a specific table in that databse to make things work! See the doc directory for more details
; on how to create this table in each database.
;

[csv]
usegmtime=yes    ; log date/time in GMT.  Default is "no"
loguniqueid=yes  ; log uniqueid.  Default is "no"
loguserfield=yes ; log user field.  Default is "no"
accountlogs=yes  ; create separate log file for each account code. Default is "yes"
;newcdrcolumns=yes ; Enable logging of post-1.8 CDR columns (peeraccount, linkedid, sequence).
                   ; Default is "no".

[radius]
;usegmtime=yes    ; log date/time in GMT
;loguniqueid=yes  ; log uniqueid
;loguserfield=yes ; log user field
; Set this to the location of the radiusclient-ng configuration file
; The default is /etc/radiusclient-ng/radiusclient.conf
;radiuscfg => /usr/local/etc/radiusclient-ng/radiusclient.conf
radiuscfg => /etc/radcli/radiusclient.congf
```

6. configuration sip.conf

```
[general]
context=public                  ; Default context for incoming calls. Defaults to 'default'
allowoverlap=no                 ; Disable overlap dialing support. (Default is yes)
udpbindaddr=0.0.0.0             ; IP address to bind UDP listen socket to (0.0.0.0 binds to all)
tcpenable=no                    ; Enable server for incoming TCP connections (default is no)
tcpbindaddr=0.0.0.0             ; IP address for TCP server to bind to (0.0.0.0 binds to all interfaces)
transport=udp                   ; Set the default transports.  The order determines the primary default transport.
srvlookup=yes                   ; Enable DNS SRV lookups on outbound calls

register => upbmotors_asterisk:password@192.168.1.31/partes_asterisk

qualify=yes
language=es
disallow=all
allow=alaw,ulaw

[authentication]
[basic-options](!)                ; a template
        dtmfmode=rfc2833
        context=from-office
        type=friend
[natted-phone](!,basic-options)   ; another template inheriting basic-options
        directmedia=no
        host=dynamic
[public-phone](!,basic-options)   ; another template inheriting basic-options
        directmedia=yes
[my-codecs](!)                    ; a template for my preferred codecs
        disallow=all
        allow=ilbc
        allow=g729
        allow=gsm
        allow=g723
        allow=ulaw
[ulaw-phone](!)                   ; and another one for ulaw-only
        disallow=all
        allow=ulaw

[usuario](!)
type=friend
host=dynamic
context=upbmotors

Extension 101
[ext101](usuario)
username=delfin
secret=s1234
;port=5061

Extension 102
[ext102](usuario)
username=tortuga
secret=s1234
port=5061

[partes_asterisk]
type=friend
secret=password
context=partes_in
host=dynamic
qualify=yes
dtmfmode=rfc2833
disallow=all
allow=ulaw
```

7. configuration extensions.conf

```
[partes_local]
exten => 501,1,Dial(SIP/partes_asterisk/501,120,Tt)
exten => 502,1,Dial(SIP/partes_asterisk/502,120,Tt)

[local]
exten => 101,1,Dial(SIP/ext101)
exten => 102,1,Dial(SIP/ext102)

[upbmotors]
include => local
include => partes_local

[partes_in]
include => local
```

8. install jami
9. add user SIP jami

## Development

### `php.ini`

```ini
extension = /path/to/php_openssl
extension = /path/to/php_pdo_mysql
file_uploads = On
upload_max_filesize = 100M
post_max_size = 100M
```

## Environment variables for php process

```ini
DB_HOST = HOST:PORT
DB_USERNAME = USERNAME
DB_PASSWORD = PASSWORD
DB_DATABASE = DATABASE
EMAIL_HOST = HOST
EMAIL_PORT = PORT
EMAIL_USERNAME = USERNAME
EMAIL_PASSWORD = PASSWORD
```