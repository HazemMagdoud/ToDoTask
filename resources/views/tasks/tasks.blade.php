@extends('base')

@section('content')
    <div class="container">
        <!-- Sidebar -->

        <!-- Main Content -->
        <main class="main-content">
            <header>
                <h1>Todo List</h1>
                <div class="header-controls" style="text-align: center">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button class="form-control" id="header-menu-toggle" class="menu-icon" style="width: 10%">☰</button>

                        <form action="{{ route('search') }}"  id="search-form" method="GET" style="display: flex; align-items: center;">
                            <input class="form-control" type="text" name="query" placeholder="Search" class="search-bar" style="margin-right: 10px;">
                            <button class="form-control" type="submit">Rechercher</button>

                        </form>
                        <a style="width: 10%" href="{{ route('tasks.index') }}" class="form-control reset-icon" title="Réinitialiser">
                            <i class="fas fa-times-circle"></i> <!-- Icône de fermeture -->
                        </a>
                    </div>
                    <!-- Conteneur pour les boutons d'exportation -->
                    <div class="export-buttons">
                        <form action="{{ route('export.csv') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 24px; padding: 10px;" title="Exporter en CSV">
                                <i class="fas fa-file-csv" style="color: green;"></i>
                            </button>
                        </form>
                        <form action="{{ route('export.pdf') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 24px; padding: 10px;" title="Exporter en PDF">
                                <i class="fas fa-file-pdf" style="color: red;"></i>
                            </button>
                        </form>

                        <form action="{{ route('export.excel') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; cursor: pointer; font-size: 24px; padding: 10px;" title="Exporter en Excel">
                                <i class="fas fa-file-excel" style="color: #28a745;"></i>
                            </button>
                        </form>

                    </div>
                </div>
            </header>

        @if (session('success'))
                <div id="success-alert" class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div id="error-alert" class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Task Cards -->
            <section class="task-cards">
                @foreach($tasks as $task)
                    <div class="card" style="background-color: {{ $task->color }};">
                        <div style="padding: 20px">
                            <!-- Bouton de suppression -->
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="position: absolute; top: 10px; right: 10px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: red;" title="Supprimer la tâche">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            <form action="{{ route('tasks.show', $task->id) }}" method="GET" style="position: absolute; top: 10px; right: 50px;">
                                @csrf
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: blue;" title="Modifier la tâche">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            @if(!$task->completed)
                            <!-- Bouton pour marquer comme terminé -->
                            <form action="{{ route('tasks.complete', $task->id) }}" method="POST" style="position: absolute; top: 10px; right: 90px;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: none; border: none; cursor: pointer; color: green;" title="Marquer comme terminé">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </form>
                            @endif

                        </div>
                        <h2>{{ $task->title }}</h2>
                        <ul>
                            <li>{{ $task->description }}</li>
                            <li>Date échéance : {{$task->due_date}}</li>
                            @if($task->completed)
                                <span style="color: green;">(Complétée)</span>
                            @else
                                <span style="color: red;">(Non complétée)</span>
                            @endif
                        </ul>
                    </div>
                @endforeach

                <div class="card add-card">
                    <a href="{{ route('tasks.show', 0) }}">+</a>
                </div>
            </section>
        </main>
    </div>
    @section('scripts')
        <script>
            // Ferme l'alerte de succès après 5 secondes
            setTimeout(function() {
                const successAlert = document.getElementById('success-alert');
                if (successAlert) {
                    successAlert.style.display = 'none';
                }
            }, 3000);

            // Ferme l'alerte d'erreur après 5 secondes
            setTimeout(function() {
                const errorAlert = document.getElementById('error-alert');
                if (errorAlert) {
                    errorAlert.style.display = 'none';
                }
            }, 3000);
        </script>
    @endsection
@endsection
