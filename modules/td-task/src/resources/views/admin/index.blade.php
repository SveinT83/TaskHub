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
                        
                        <!-- ------------------------------------------------- -->
                        <!-- Card -->
                        <!-- ------------------------------------------------- -->
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title"></h2>
                            </div>
                            <div class="card-body">

                            </div>
                        </div>

                        <div class="row mt-3">

                            <!-- ------------------------------------------------- -->
                            <!-- 1. Introduksjon -->
                            <!-- ------------------------------------------------- -->
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



                        <h4 class="row mt-3">1. Introduksjon</h4>


                        <div class="row mt-2">
                            
                            <p class="col">Task-modulen er designet for å håndtere oppgaver, vegger (walls), grupper, status og oppgave-relaterte funksjoner som opprettelse, oppdatering, sletting og administrasjon. Modulen er modulær, skalerbar og integrerbar med andre moduler i Task Hub-systemet.</p>

                            <div class="col">
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



                        <h4 class="row mt-3">2. Installasjon</h4>

                        <div class="row mt-2">2.1 Krav</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
