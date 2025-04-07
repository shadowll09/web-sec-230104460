@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">User Profile</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-striped">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        @if($user->hasRole('Customer'))
                        <tr>
                            <th>Credits</th>
                            <td>
                                <span class="badge bg-success fs-6">${{ number_format($user->credits, 2) }}</span>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Roles</th>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary">{{ $role->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>Permissions</th>
                            <td>
                                @foreach($permissions as $permission)
                                    <span class="badge bg-success">{{ $permission->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </div>

                @if($user->hasRole('Customer'))
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">Account Balance</h4>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="display-4">${{ number_format($user->credits, 2) }}</h2>
                            <p class="lead">Available Credits</p>

                            @if(auth()->user()->hasAnyRole(['Admin', 'Employee']) && auth()->id() != $user->id)
                                <a href="{{ route('add_credits_form', $user) }}" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Add Credits
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="d-flex justify-content-end mt-3">
                @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                    <a class="btn btn-primary me-2" href="{{ route('edit_password', $user->id) }}">Change Password</a>
                @endif

                @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                    <a href="{{ route('users_edit', $user->id) }}" class="btn btn-success">Edit Profile</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
