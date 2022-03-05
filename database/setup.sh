#!/bin/bash

mysqld &
sleep 5
mysql --force < /root/database/setup.sql
mysql --force < /root/database/client-user.sql
if "$(cat /root/config.json | jq -r '.TEST')" -eq "1" then
  mysql --force < /root/database/test-data.sql
fi