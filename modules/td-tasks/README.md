Versjon 0.1 - Startfase

Formål:
Oppgave (Task) modulen er laget for å tillate brukere å opprette og administrere oppgaver. Den støtter tildeling av oppgaver til brukere, tilknytning til tidsfrister, kommentarer, statusoppdateringer og sub-oppgaver. Det skal også være mulig å knytte oppgaver til saker fra tickets-modulen. Modulen vil inkludere både globale og brukerspesifikke innstillinger for synkronisering med Nextcloud.

Oppgave Modulens Funksjoner
Opprettelse av oppgaver – Brukere kan lage oppgaver med navn, beskrivelser, tidsfrister, og tildeling til andre brukere.
Oppgavevegger (Task Walls) – Brukere kan gruppere oppgaver under vegger, for eksempel for prosjekter eller kategorier.
Oppgaveavhengigheter – Det er mulig å opprette avhengigheter mellom oppgaver, for eksempel at noen sub-oppgaver må være fullført før hovedoppgaven.
Statuser og tilpassede statuser – Systemet kommer med forhåndsdefinerte statuser, men admin kan også opprette egne statuser.
Synkronisering med Nextcloud – Oppgaver kan synkroniseres med Nextclouds kalender og/eller oppgaveapp.
Regelmotor – En regelmotor for varsling ved spesifikke hendelser, som når oppgaver går over fristen.

modules/
    TdTasks/
        Database/
            migrations/
                2024_xx_xx_add_menu_for_task_module.php
        Http/
            Controllers/
                TaskController.php
                TaskWallController.php
                TaskConfigController.php
        Models/
            Task.php
            TaskWall.php
        Providers/
            TaskServiceProvider.php
        resources/
            views/
                admin/
                    tasks/
                        config.blade.php        // Admin-side for konfigurasjon av Task-modulen
                tasks/
                    create.blade.php           // Opprettelse av nye oppgaver
                    index.blade.php            // Visning av alle oppgaver
                    walls.blade.php            // Visning av alle oppgave-vegger (walls)
        routes/
            web.php                             // Ruter for Task-modulen
        module.json                             // Modul-konfigurasjon
        composer.json                           // Composer-avhengigheter
        README.md                               // Dokumentasjon for Task-modulen


Beskrivelse av Hovedkomponentene:

1. Database (migrations & seeders)
Migrations:
Disse migrasjonsfilene definerer strukturen på databasetabellene som trengs for å lagre informasjon om oppgaver, vegger, avhengigheter, og statuser. Tabellene inkluderer blant annet task_walls, tasks, task_dependencies, og task_statuses.

Seeders:
Seederen vil fylle databasen med standard oppgavestatuser som "Ikke startet", "Pågående", og "Fullført".

2. Controllers
TaskController:
Håndterer operasjoner knyttet til individuelle oppgaver, som opprettelse, redigering og synkronisering med Nextcloud.

TaskWallController:
Ansvarlig for administrasjon av oppgavevegger, der oppgaver kan grupperes.

TaskSettingsController:
Håndterer innstillinger for modulen, både globale og brukerspesifikke innstillinger, for eksempel valg av synkroniseringstype og kalendertype.

3. Models
Task:
Representerer en individuell oppgave i systemet. Denne modellen definerer feltene som er relevante for en oppgave, som tittel, frist, status, og bruker.

TaskWall:
Representerer en vegg av oppgaver (project eller kategori) som inneholder flere oppgaver.

TaskStatus:
Representerer statusen til en oppgave, som "Ikke startet", "Pågående", etc.

TaskDependency:
Representerer en avhengighet mellom oppgaver, som at en underoppgave må være fullført før hovedoppgaven.

4. Routes
Ruter blir definert i web.php. Disse rutene definerer URL-er for oppgaver, oppgavevegger og synkroniseringsoperasjoner, som å opprette, vise, redigere og slette oppgaver, samt synkronisere dem med Nextcloud.

5. Views
tasks/index.blade.php:
Viser en liste over oppgaver for brukeren.

tasks/create.blade.php:
Inneholder skjemaet for å opprette nye oppgaver.

tasks/show.blade.php:
Viser detaljer for en individuell oppgave.

walls/index.blade.php:
Viser en oversikt over oppgavevegger.

walls/show.blade.php:
Viser detaljert informasjon om en spesifikk oppgavevegg og de tilhørende oppgavene.

6. Providers
TaskServiceProvider:
Registrerer ruter, migrasjoner, views, og modultjenester for Task-modulen. Dette er det første entry-punktet for å laste oppgavenes funksjonalitet inn i TaskHub-plattformen.

7. Composer & Module Files
composer.json:
Definerer modulen, dens avhengigheter og meta-informasjon. Dette er nødvendig for å gjøre modulen selvstendig.

module.json:
Definerer modulens metadata for TaskHub, inkludert navn, versjon, og beskrivelse.

8. Tests
TaskTest:
Tester funksjonene i Task-modulen, som opprettelse, oppdatering og synkronisering av oppgaver.

Innstillinger og Synkronisering
Globale innstillinger:
Globale innstillinger håndteres av admin og inkluderer ting som om oppgaver automatisk skal synkroniseres med Nextcloud eller hvilken kalender som skal brukes. Disse innstillingene håndteres via TaskSettingsController og lagres i databasen.

Brukerspesifikke innstillinger:
Hver bruker kan ha egne preferanser, som hvilken Nextcloud-konto og kalender som skal brukes. Disse innstillingene kan overstyre de globale innstillingene, hvis brukeren ønsker det.

Nextcloud Synkronisering
Oppgaver kan synkroniseres til Nextcloud enten som kalenderhendelser eller oppgaver. Synkronisering er implementert ved å bruke Nextclouds WebDAV API, som tillater kommunikasjon mellom TaskHub og Nextcloud.

Synkroniseringen kan enten kjøres ved å bruke manuelle triggere (brukere klikker "sync"), eller ved automatiserte prosesser som kjører med en cronjob.

Oppsummering
Task-modulen gir brukere mulighet til å administrere oppgaver og prosjekter (oppgavevegger) på en effektiv måte. Den støtter fleksibel opprettelse og administrasjon av oppgaver med tidsfrister, statuser, avhengigheter, og synkronisering med Nextcloud. Sammen med regelmotoren og tilpassede innstillinger, gir modulen mulighet for høy grad av tilpasning og automatisering i TaskHub.