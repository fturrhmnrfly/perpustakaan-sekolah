@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="space-y-6">
    <div class="profile-highlight">
        <h1 class="page-title !text-3xl md:!text-4xl">Pengaturan Profil</h1>
        <p class="mt-2 max-w-3xl text-sm md:text-base text-slate-700">
            Kelola identitas akun, keamanan password, dan kontrol akun pribadi dari satu halaman yang lebih rapi.
        </p>
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="status-badge status-active">Akun Aktif</span>
            <span class="status-badge status-returned">Keamanan Dasar</span>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="section-card w-full lg:col-span-2">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="space-y-6">
            <div class="section-card w-full">
                @include('profile.partials.update-password-form')
            </div>

            <div class="section-card w-full border-rose-200 bg-rose-50/80">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
