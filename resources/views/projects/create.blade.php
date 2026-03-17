@extends('layouts.app')
@section('title', 'Projects | FurnishPro')

@section('content')

@php
    $step = request('step', 0);
    $hasProject = session()->has('project_id');

    $step1 = $step1_completed ?? false;
    $step2 = $step2_completed ?? false;
    $step3 = $step3_completed ?? false;
@endphp


<div class="container-fluid">
    {{-- Project Overview Heading --}}
        <h4 class="fw-bold mb-1">Project Overview</h4>
    {{-- Removed card border-0 shadow-sm rounded-4 --}}

        {{-- ================= HEADER (PROJECT NAME + ID) ================= --}}
@if($hasProject)
<div class="py-3 header-full-width">
    <div class="px-4 d-flex align-items-center flex-wrap gap-3">
        {{-- Breadcrumb / Navigation Links --}}
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
            <span class="text-muted small">/</span>
            <a href="{{ route('projects.index') }}" class="text-decoration-none text-muted small">
                All Projects
            </a>
        </div>

        {{-- Project Name and ID --}}
      <div class="d-flex align-items-center gap-2">
    <span class="fw-semibold fs-6 text-dark">
        {{ $project->project_name }}
    </span>
    {{-- <span class="text-muted small details">
        #{{ $project->project_code }}
    </span> --}}
</div>
    </div>
</div>
@endif



        {{-- ===================== TABS ===================== --}}
        @if($hasProject)

            <ul class="nav custom-tabs px-4">

                {{-- OVERVIEW --}}
                @can('view project overview')
                <li class="nav-item">
                    <a href="{{ route('projects.create', ['step' => 0]) }}"
                       class="nav-link {{ $step == 0 ? 'active' : '' }}">
                        Overview
                    </a>
                </li>
                @endcan


                {{-- CUSTOMER DETAILS --}}
                @can('view project details')
                <li class="nav-item">
                    <a href="{{ route('projects.create', ['step' => 1]) }}"
                       class="nav-link {{ $step == 1 ? 'active' : '' }}">
                        Customer & Project Details
                    </a>
                </li>
                @endcan


                {{-- MEASUREMENT --}}
                @can('manage measurement')
                <li class="nav-item">
                    <a href="{{ route('projects.create', ['step' => 2]) }}"
                       class="nav-link {{ $step == 2 ? 'active' : '' }} {{ !$step1 ? 'disabled' : '' }}">
                        Measurement
                    </a>
                </li>
                @endcan


                {{-- MATERIAL --}}
                @can('manage materials')
                <li class="nav-item">
                    <a href="{{ route('projects.create', ['step' => 3]) }}"
                       class="nav-link {{ $step == 3 ? 'active' : '' }} {{ !$step2 ? 'disabled' : '' }}">
                        Material Selection
                    </a>
                </li>
                @endcan


                {{-- QUOTATION --}}
                @can('manage quotation')
                <li class="nav-item">
                    <a href="{{ route('projects.create', ['step' => 4]) }}"
                       class="nav-link {{ $step == 4 ? 'active' : '' }} {{ !$step3 ? 'disabled' : '' }}">
                        Quotation
                    </a>
                </li>
                @endcan


                {{-- PAYMENTS --}}
              {{-- PAYMENTS --}}
{{-- @can('manage payments')
    @if($project->status === 'confirmed')
        <li class="nav-item">
            <a href="{{ route('projects.create', ['step' => 5]) }}"
               class="nav-link {{ $step == 5 ? 'active' : '' }}">
                Payments
            </a>
        </li>
    @endif
@endcan --}}


            </ul>

        @endif


        {{-- ===================== TAB CONTENT ===================== --}}
        <div class="p-4">

            @if($step == 0)
                @include('projects.step')
            @elseif($step == 1)
                @include('projects.step1')
            @elseif($step == 2 && $step1)
                @include('projects.step2')
            @elseif($step == 3 && $step2)
                @include('projects.step3')
            @elseif($step == 4 && $step3)
                @include('projects.step4')
            @elseif($step == 5)
                @include('projects.received-payments')
            @endif

        </div>

    {{-- Removed closing card div --}}
</div>


@endsection
