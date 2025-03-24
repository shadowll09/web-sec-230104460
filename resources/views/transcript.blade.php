@extends('layouts.master')
@section('title', 'Student Transcript')
@section('content')
  <div class="card m-4">
    <div class="card-header">Student Transcript</div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Course</th>
            <th>Grade</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($transcript as $course => $grade)
            <tr>
              <td>{{ $course }}</td>
              <td>{{ $grade }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
