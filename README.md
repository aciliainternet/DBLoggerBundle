# DBLoggerBundle

Acilia DBLogger Bundle for Symfony4

* Require bundle
```bash
composer install aciliainternet/dblogger-bundle
```

* Create a file named dblogger.yaml on the /config/packages folder and place following code:
```
monolog:
    channels: ['db']
    handlers:
        db:
            channels: ['db']
            type: service
            id: acilia.dblogger
```
* Paste the following on the services.yaml of the config folder:

```
# dblogger archive command
Acilia\Bundle\DBLoggerBundle\Command\ArchiveCommand:
    tags: ['console.command'] 
```