@extends('layout')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight">
            {{ __('Pengaturan Profil') }}
        </h2>
        <p class="text-sm text-gray-600">Kelola informasi akun dan keamanan Anda.</p>
    </div>

    <div class="p-6 bg-white shadow sm:rounded-lg border border-gray-100">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="p-6 bg-white shadow sm:rounded-lg border border-gray-100">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="p-6 bg-white shadow sm:rounded-lg border border-gray-100">
        <div class="max-w-xl">
            @include('profile.partials.session-activity')
        </div>
    </div>

    <div class="p-6 bg-red-50 shadow sm:rounded-lg border border-red-200">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection