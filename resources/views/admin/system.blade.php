@extends('admin.components.app')

@section('title', 'Система — Админ-панель')

@section('content')

    <div class="container admin-page">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>
                <x-admin.breadcrumb :items="[
                    ['label' => 'Админ-панель', 'url' => route('admin.index')],
                    ['label' => 'Система'],
                ]" />

                <h1 class="admin-title">
                    Состояние системы
                </h1>

                <p class="admin-subtitle">
                    CPU, память, диск и GPU в реальном времени
                </p>
            </div>

        </div>


        @livewire('admin.system-metrics')

    </div>

@endsection
