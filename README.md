## Voraussetzungen
Das Projekt benötigt einige externe Dependencies. Um diese zu installieren verwenden wir den PHP-Dependencymanager 
"Composer". Installieren Sie nun zuerst [Composer](https://getcomposer.org/doc/00-intro.md).

Um danach die eigentlichen Dependencies zu installieren rufen Sie folgendes Kommando auf:

`php composer.phar install`

## Applikationsstart
Die Applikation besteht aus einem Web-Server der die HTML/CSS/JS-Ressourcen bereitstellt
sowie aus einem WebSockets-Server. Starten Sie beide wiefolgt (Linux/MacOS oder Windows):

1. `./startWebServer.sh` oder `./startWebServer.bat`
2. `./startWSServer.sh` oder `./startWSServer.bat`
3. Rufen Sie dann im Browser `http://localhost:8000` auf.

## Tests ausführen
Um die PHP Unit Tests auszuführen, benutzen Sie folgendes Kommando:

`vendor/bin/phpunit`
