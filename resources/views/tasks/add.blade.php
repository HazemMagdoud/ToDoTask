@extends('base')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>
                    <div class="card-body">
                        <form id="myForm" action="{{ route('tasks.add-or-update',request()->route('id')) }}" method="POST">
                            @csrf
                            <div>
                                <label for="title">Titre:</label>
                                <input class="form-control" type="text" name="title" id="title" required value="{{ $task ? $task->title : '' }}">
                            </div>

                            <div>
                                <label for="description">Description:</label>
                                <textarea class="form-control" name="description" id="description" >{{ $task->description ?? '' }}</textarea>
                            </div>
                            <div>
                                <label for="due_date">Date d'échéance:</label>
                                <input class="form-control" type="date" name="due_date" id="due_date" value="{{ isset($task->due_date) && $task->due_date instanceof \Carbon\Carbon ? $task->due_date->format('Y-m-d') : '' }}">
                            </div>

                            @if(request()->route('id') == 0)

                            <button class = "form-control" type="submit" style="margin-top: 20px">Créer Tâche</button>
                            @else
                                <button class = "form-control" type="submit" style="margin-top: 20px">Modifier Tâche</button>
                            @endif

                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

