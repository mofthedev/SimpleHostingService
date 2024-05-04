# StudentHosting
A CLI script to provide students with a simple hosting for educational purposes.

- Run [sthosting.py](sthosting.py) with sudo
```shell
sudo python3 sthosting.py
```

- Use the options #3, #4, #5, #6 respectively.
```
Options:
  1. Enable userdir module for Apache
  2. Install MariaDB
  3. Process student information from students.csv
  4. Create users.txt for newusers program (from student_infos.csv)
  5. Run newusers program using users.txt
  6. Create databases and database users for students
  7. Reset system (delete databases, remove users)
  8. Install PHP
  9. Show databases and users
  0. Exit
```

- Test it using [index.php](index.php)

- Make sure to:
  - Install apache, mysql/maridb, php and php extensions
  - Enable userdir mod for apache.
  - Go to `/etc/apache2/mods-available/phpx.x.conf` and comment these lines:
  
  ```
    <IfModule mod_userdir.c>                                           
    <Directory /home/*/public_html>                                
        php_admin_flag engine Off                                  
    </Directory>                                                   
    </IfModule>         
  ```
  
  - Go to `/etc/php/x.x/apache2/php.ini` and turn on or off `display_errors`
  
  ```
  display_errors = On # or Off
  ```
  
  - Run MySQL/MariaDB secure installation script:
 
  ```shell
  sudo mysql_secure_installation
  ```

  - Go to `/etc/apache2/mods-enabled/dir.conf` and bring `index.php` to the beginning:
  ```
  <IfModule mod_dir.c>
    DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
  </IfModule>
  ```

  
