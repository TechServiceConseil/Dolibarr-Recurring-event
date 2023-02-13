# Recurring-event
Recurring event

# TSCMOD FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## Features

Look the video on our channel (link to come)
HELP WANTED TO GET CONTACT WORKS

<!--
![Screenshot tscmod](img/screenshot_tscmod.png?raw=true "Tscmod"){imgmd}
-->

Other external modules are available on [Dolistore.com](https://www.dolistore.com).

## Translations

Translations can be completed manually by editing files into directories *langs*.

<!--
This module contains also a sample configuration for Transifex, under the hidden directory [.tx](.tx), so it is possible to manage translation using this service.

For more informations, see the [translator's documentation](https://wiki.dolibarr.org/index.php/Translator_documentation).

There is a [Transifex project](https://transifex.com/projects/p/dolibarr-module-template) for this module.
-->


##Installation

### From the ZIP file and GUI interface

If the module is a ready to deploy zip file, so with a name module_xxx-version.zip (like when downloading it from a market place like [Dolistore](https://www.dolistore.com)),
go into menu ```Home - Setup - Modules - Deploy external module``` and upload the zip file.

Note: If this screen tell you that there is no "custom" directory, check that your setup is correct:

- In your Dolibarr installation directory, edit the ```htdocs/conf/conf.php``` file and check that following lines are not commented:

    ```php
    //$dolibarr_main_url_root_alt ...
    //$dolibarr_main_document_root_alt ...
    ```

- Uncomment them if necessary (delete the leading ```//```) and assign a sensible value according to your Dolibarr installation

    For example :

    - UNIX:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = '/var/www/Dolibarr/htdocs/custom';
        ```

    - Windows:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = 'C:/My Web Sites/Dolibarr/htdocs/custom';
        ```

### From a GIT repository

Clone the repository


### <a name="final_steps"></a>Final steps

From your browser:

  - Log into Dolibarr as a super-administrator
  - Go to "Setup" -> "Modules"
  - You should now be able to find and enable the module

-->

## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.

You need to create extrafieds to get it work. They are bellow.

100; Evénement récurrent; Evénement récurrent; recurrencebool; Boolean (case à cocher unique); Non; Non; Oui; 1; 0; Non	  
105; Unité de la récurrence; Unité de la récurrence; recurrenceunit; Liste de sélection; Non; Non; Oui; 1; 0; Non	  
115; Date de fin de récurrence; Date de fin de récurrence; recurrenceend; Date et heure; Non; Non; Oui; 1; 0; Non	  
120; Evénement source de la récurrence; Evénement source de la récurrence; fk_actioncomm; Chaîne de caractères (1 ligne); 255; Non; Non; Oui; 5; 0; Non	  

                            Édition du champ recurrenceunit
Valeur	
    1,an
    2,mois
    3,semaine
    4,jour
      
      
Enjoy! That functionnality is add in V17 in the core, but that works for v16 and maybe lesser 
