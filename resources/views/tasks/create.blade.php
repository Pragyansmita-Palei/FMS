@extends('layouts.app')

@section('content')

<form action="{{ route('tasks.store') }}" method="POST">
@csrf

<div class="mb-2">
<label>Task Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-2">
<label>Description</label>
<textarea name="description" class="form-control"></textarea>
</div>

<div class="mb-2">
<label>Project</label>
<select name="project_id" class="form-control">
<option value="">Select Project</option>
@foreach($projects as $project)
<option value="{{ $project->id }}">
{{ $project->project_name }}
</option>
@endforeach
</select>
</div>

<div class="mb-2">
<label>Tailor</label>
<select name="tailor_id" class="form-control">
<option value="">Select Tailor</option>
@foreach($tailors as $tailor)
<option value="{{ $tailor->id }}">
{{ $tailor->name }}
</option>
@endforeach
</select>
</div>

<div class="mb-2">
<label>Sales Associate</label>
<select name="sales_associate_id" class="form-control">
<option value="">Select Sales Associate</option>
@foreach($salesAssociates as $sales)
<option value="{{ $sales->id }}">
{{ $sales->name }}
</option>
@endforeach
</select>
</div>

<div class="mb-2">
<label>Due Date</label>
<input type="date" name="due_date" class="form-control">
</div>

<div class="mb-2">
<label>Priority</label>
<select name="priority" class="form-control">
<option value="Low">Low</option>
<option value="Medium">Medium</option>
<option value="High">High</option>
</select>
</div>

<div class="mb-2">
<label>Status</label>
<select name="status" class="form-control">
<option value="To Do">To Do</option>
<option value="In Progress">In Progress</option>
<option value="Completed">Completed</option>
</select>
</div>

<button type="submit" class="btn btn-success">
 Save Task
</button>

</form>

@endsection
