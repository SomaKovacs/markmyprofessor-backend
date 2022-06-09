# Adatbázis
1. Telepíts xampp-ot: https://www.apachefriends.org/xampp-files/8.1.4/xampp-windows-x64-8.1.4-1-VS16-installer.exe
2. xampp-ban indítsd el: Apache, MySQL
3. Böngészőben menj phpmyadminba: http://localhost/phpmyadmin
4. Hozz létre egy adatbázist 'laravel' néven

# DEV környezet
1. Cloneozd le valahova
2. .env.example fájlt másold le .env néven
3. .env-et nyisd meg és DB_*** adatokat töltsd ki (ha a fenti DB módszert használod, akkor elméletileg jók lesznek a default értékek)
4. Telepíts composer-t: https://getcomposer.org/Composer-Setup.exe
5. Nyiss egy cmd-t és navigálj a repo mappába, majd telepítsd a packageket: composer install
6. Ezután ugyan itt cmd-ben: git checkout dev
7. Ezután: php artisan migrate
8. Majd dev szervert elindítani: php artisan serve