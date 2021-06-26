# Erstellung von Templates

## Hauptfunktionen
siehe Code Dokumentation.

## Laden von Templates

### Automatisches Language Datei Laden; Datei benennung
1. *class*.language.template.json für alle Templates der Klasse
1. *class*.language.template.*names des Templates*.json für das jeweilige Template der Klasse

### Beispiel

Container::get('Template')
->loadTemplate($__CLASS__, 'information')
->assign($key,$value)
->assignArray([],'information')
->parse('information')
->get('information');