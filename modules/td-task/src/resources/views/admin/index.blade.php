@extends('layouts.app')

@section('pageHeader')
    <h1>Task Admin Dashboard</h1>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header  text-bg-primary">
                        <h2 class="card-title">Task-Modul Dokumentasjon</h2>
                    </div>
                    <div class="card-body bg-body-tertiary">

                        <div class="row mt-3">

                            <!-- ------------------------------------------------- -->
                            <!-- 1. Introduksjon -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">1. Introduksjon</h2>
                                    </div>
                                    <div class="card-body">
                                        <p>Task-modulen er designet for å håndtere oppgaver, vegger (walls), grupper, status og oppgave-relaterte funksjoner som opprettelse, oppdatering, sletting og administrasjon. Modulen er modulær, skalerbar og integrerbar med andre moduler i Task Hub-systemet.</p>

                                        <h5>Hovedfunksjoner:</h5>
                                        <ul class="list-group">
                                            <li class="list-group-item">Opprette og administrere oppgaver</li>
                                            <li class="list-group-item">Administrere vegger (walls) som inneholder oppgaver</li>
                                            <li class="list-group-item">Assigne oppgaver til brukere</li>
                                            <li class="list-group-item">Administrere oppgavestatus (Not Started, In Progress, Completed, Blocked)</li>
                                            <li class="list-group-item">API-er for å samhandle med modulen fra andre systemer</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 2.1 Krav -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">2.1 Krav</h2>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">PHP 8.0+</li>
                                            <li class="list-group-item">Laravel 9.x+</li>
                                            <li class="list-group-item">MySQL eller annen kompatibel database</li>
                                            <li class="list-group-item">Task Hub-modularkitektur</li>
                                            <li class="list-group-item">Autentisering (Laravel’s Auth-system)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 2.2 Installasjonssteg -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">2.2 Installasjonssteg</h2>
                                    </div>
                                    <div class="card-body">

                                            <div class="row mt-3 border-bottom">
                                                <p><b>1: </b> Klon modulen fra GitHub (eller legg den til via Composer)</p>

                                                <pre class="text-bg-light border p-2 mt-3 mb-2"><code>git clone https://github.com/your-repo/td-task.git modules/td-task</code></pre>
                                            </div>
                                            <div class="row mt-3 border-bottom">
                                                <p><b>2: </b> Kjør migrasjoner for databasen Modulen har sine egne migrasjonsfiler for å opprette nødvendige tabeller som oppgaver, oppgavestatus, vegger osv. Kjør følgende kommando for å kjøre migrasjonene:</p>
                                                <pre class="text-bg-light border p-2 mt-3 mb-2"><code>php artisan migrate</code></pre>
                                            </div>
                                            <div class="row mt-3 border-bottom">
                                                <p><b>3: </b> Publiser konfigurasjoner (hvis aktuelt) Hvis modulen inneholder publiserbare konfigurasjonsfiler:</p>
                                                
                                                <pre class="text-bg-light border p-2 mt-3 mb-2"><code>php artisan vendor:publish --provider="tronderdata\TdTask\Providers\TdTaskServiceProvider"</code></pre>
                                            </div>
                                            <div class="row mt-3 border-bottom">
                                                <p><b>4: </b> Seed database Hvis det er noen standarddata som skal legges til (som oppgavestatus), kan du kjøre seeding-kommandoen:</p>
                                                
                                                <pre class="text-bg-light border p-2 mt-3 mb-2"><code>php artisan db:seed --class=TaskStatusSeeder</code></pre>
                                            </div>
                                            <div class="row mt-3 border-bottom">
                                                <p><b>5: </b> Kjør eventuelle nødvendige npm-kommandoer for frontend-ressurser (hvis aktuelt):</p>
                                                
                                                <pre class="text-bg-light border p-2 mt-3 mb-2"><code>npm install && npm run dev</code></pre>
                                            </div>

                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.1 Navigering -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.1 Navigering</h2>
                                    </div>
                                    <div class="card-body">
                                        <p>Etter å ha installert Task-modulen vil du se en egen "Tasks"-seksjon i hovedmenyen. Her vil du ha tilgang til å opprette og administrere oppgaver, se vegger, statusoversikt, samt mulighet til å assigne oppgaver til brukere.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.2 Opprette en Vegg (Wall) -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.2 Opprette en Vegg (Wall)</h2>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Gå til Tasks -> Walls -> Create Wall.</li>
                                            <li class="list-group-item">Fyll inn et navn og en beskrivelse for veggen.</li>
                                            <li class="list-group-item">Hvis du har administrativ tilgang, vil du også kunne lagre veggen som en template, som kan brukes for fremtidige oppgaver.</li>
                                          </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.3 Opprette en Oppgave -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.3 Opprette en Oppgave</h2>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Gå til en vegg (Wall) og klikk Add Task.</li>
                                            <li class="list-group-item">Fyll ut nødvendige felter som tittel, beskrivelse, due date, og assign en bruker til oppgaven.</li>
                                            <li class="list-group-item">Du kan også velge status for oppgaven, som indikerer dens fremdrift.</li>
                                          </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.4 Redigere en Oppgave -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.4 Redigere en Oppgave</h2>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Klikk på en oppgave i oversikten og trykk på Edit Task-knappen.</li>
                                            <li class="list-group-item">Gjør nødvendige endringer, og lagre oppdateringene.</li>
                                          </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.5 Brukertilgang -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.5 Brukertilgang</h2>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">Superadmin og brukere med task.admin-tillatelse kan opprette, redigere og slette både vegger og oppgaver.</li>
                                            <li class="list-group-item">Vanlige brukere kan bare endre og administrere oppgaver som de har blitt assignet til.</li>
                                          </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- ------------------------------------------------- -->
                            <!-- 3.6 Tillatelser -->
                            <!-- ------------------------------------------------- -->
                            <div class="col-md-6 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title">3.6 Tillatelser</h2>
                                    </div>
                                    <div class="card-body">
                                        <p>Tillatelser kan konfigureres via standard Laravel-tillatelsessystemet. Følgende roller og tillatelser finnes:</p>
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item">superadmin: Full tilgang til alle moduler, inkludert Task-modulen.</li>
                                            <li class="list-group-item">task.admin: Full tilgang til opprettelse, redigering og sletting av oppgaver og vegger.</li>
                                            <li class="list-group-item">task.view: Tillatelse til å vise oppgaver.</li>
                                            <li class="list-group-item">task.create: Tillatelse til å opprette oppgaver.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
