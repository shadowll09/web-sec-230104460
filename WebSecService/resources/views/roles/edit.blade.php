@extends('layouts.master')
@section('title', 'Edit Role')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col">
            <h1>Edit Role: {{ $role->name }}</h1>
        </div>
        <div class="col-auto">
            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Roles
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('roles.update', $role) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                           value="{{ old('name', $role->name) }}" {{ in_array($role->name, ['Admin', 'Employee', 'Customer']) ? 'readonly' : 'required' }}>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if(in_array($role->name, ['Admin', 'Employee', 'Customer']))
                        <div class="form-text text-muted">System roles cannot be renamed.</div>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Permissions</label>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @foreach($permissions->chunk(ceil($permissions->count() / 3)) as $chunk)
                                    <div class="col-md-4">
                                        @foreach($chunk as $permission)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" 
                                                       value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="selectAll">Select All</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAll">Deselect All</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle select all button
        document.getElementById('selectAll').addEventListener('click', function() {
            document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
                checkbox.checked = true;
            });
        });

        // Handle deselect all button
        document.getElementById('deselectAll').addEventListener('click', function() {
            document.querySelectorAll('input[name="permissions[]"]').forEach(function(checkbox) {
                checkbox.checked = false;
            });
        });
    });
</script>
@endpush
@endsection
