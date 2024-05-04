# StudentHosting
A CLI script to provide students with a simple hosting for educational purposes.

- Prepare a file named `students.csv` which consists of student numbers on each line. No CSV headers! Example:
```
0030701
0030702
0030703

```

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
  10. Setup FTP over secure connection
  0. Exit
```

- Test it using [index.php](index.php)

- Make sure to:
  - Install apache, mysql/maridb, php and php extensions


 
  ## For Apache
  - Enable userdir mod for apache.
  
  ```shell
  sudo a2enmod userdir
  sudo systemctl restart apache2
  ```
  
  - Go to `/etc/apache2/mods-available/phpx.x.conf` and comment these lines:
  ```
    <IfModule mod_userdir.c>                                           
    <Directory /home/*/public_html>                                
        php_admin_flag engine Off                                  
    </Directory>                                                   
    </IfModule>         
  ```

  - Go to `/etc/apache2/mods-enabled/dir.conf` and bring `index.php` to the beginning:
  ```
  <IfModule mod_dir.c>
    DirectoryIndex index.php index.html index.cgi index.pl index.xhtml index.htm
  </IfModule>
  ```



  ## For PHP
  - Go to `/etc/php/x.x/apache2/php.ini` and turn on or off `display_errors`
  ```
  display_errors = On # or Off
  ```



  ## For MySQL
  - Run MySQL/MariaDB secure installation script:
  ```shell
  sudo mysql_secure_installation
  ```



  ## For FTP
  - Go to `/etc/vsftpd.conf` and configure it (!!! use real absolute path instead of $HOME or ~ !!!):
  ```
  ssl_enable=YES
  rsa_cert_file=$HOME/sthosting/vsftpd_cert.pem
  rsa_private_key_file=$HOME/sthosting/vsftpd_private.pem
  pam_service_name=vsftpd
  sudo systemctl restart vsftpd
  ```

  - Go to `/etc/pam.d/vsftpd` and configure it:
  ```
  auth    required    pam_unix.so
  account required    pam_unix.so
  ```

  
  
