@extends('admin.components.app')

@section('title', 'Оформление — Админ-панель')

@section('content')

    <div class="container admin-page">

        {{-- HEADER --}}
        <div class="admin-header">

            <div>

                <x-admin.breadcrumb :items="[
                    ['label' => 'Админ-панель', 'url' => route('admin.index')],
                    ['label' => 'Оформление'],
                ]" />

                <h1 class="admin-title">
                    Оформление форума
                </h1>

                <p class="admin-subtitle">
                    Тема применяется ко всем пользователям, которые не выбрали свою
                </p>

            </div>

        </div>


        @livewire('admin.appearance')

    </div>

@endsection
