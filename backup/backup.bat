@echo off
set fecha=%date:~-4%%date:~3,2%%date:~0,2%
set hora=%time:~0,2%%time:~3,2%%time:~6,2%
set hora=%hora: =0%

"C:\xampp\mysql\bin\mysqldump.exe" -u root fraser > "C:\xampp\htdocs\documentacion\backup\backup_fraser_%fecha%_%hora%.sql"

exit
