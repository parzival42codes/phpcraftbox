
# phpCraftBox

Das Content Management Framework phpCraftBox stellt ein innovatives, universelles, eigenständiges, anpassbares System für Webapplikationen dar.

## Was kann es ?

Die Anwendungsgebiete sind vielfältig:
- CRM
- Content Management
- Shop / Warenwirtschaft
- Ticketverwaltung
- Arbeitszeiterfassung
- Intranet Applikationen
- Backends
- etc.

## Das Besondere ?

Durch die Grundstrukturen sind schnelle Programmierungen und Fehlerbehebungen möglich:
- Das Debuggingsystem, Profilingsystem und die stabile Fehlerverarbeitung, welche für den Entwickler wertvolle Informationen liefern.
- Hilfefunktionen bei der Erstellung von Modulen / Dokumentationen, um einen schnellen Zugriff darauf zu gewährleisten.

## Hilfreiche Eigenschaften

### Routing

Die phpCraftBox besitzt ein einfach zu benutzendes Routingsystem, welches von jedem Modul genutzt werden kann.
Es bedient sich nicht den direkten Parametern in der URL, sondern benutzt die von den Modulen vordefinierten Pfade.

Dabei sind einfache und Pfade mit Regulären Ausdrücken möglich.
Es können mehrere Möglichkeiten genutzt werden, z.B.:
- **Normales Routing** wird von Applikationen genutzt. (Z.B.)
- **Ajax** gewährt einen angepassten Ajax Zugriff.
- **Free** erlaubt die freie Verfügung des gesendeten Inhalts. (Z.B. bei der Ausgabe von CSS und Javascript Dateien)

### cGui

Das cGui greift auf die .console.php Klassen der Module zu.
In diesen Consolen Klassen befinden sich Closures, welche später in einem bestimmten Intervall nacheinander abgearbeitet werden.

### Programmiertechniken

Genutzt werden u.A.:
- DRY
- SRP
- OOP
- KISS

## Installation

Ganz einfach:
http://localhost/cms/cgui.php
(URL kann ja nach Einsatz abweichen)
Die cGUI Einstellungen sind derzeit auf Installation voreingestellt.

## Benötigt wird:

- min. PHP 8
- MySql

# Autoren

* **Stefan Schlombs** - *Initial work* - [Stefan Schlombs](https://github.com/StefanSchlombs1980)

# Lizenz

AGPL 3
