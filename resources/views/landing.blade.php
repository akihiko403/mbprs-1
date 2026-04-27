@php
    $landingPage = true;
    $title = 'Welcome';
    $systemSettings = \App\Models\SystemSetting::current();
    $systemLogoUrl = $systemSettings->system_logo_path
        ? asset('storage/' . $systemSettings->system_logo_path).'?v='.$systemSettings->updated_at?->timestamp
        : null;
@endphp

@extends('layouts.app')

@section('content')
<div class="landing-shell">
    <header class="landing-nav">
        <div class="landing-brand">
            <div class="landing-brand-badge" aria-hidden="true">
                @if ($systemLogoUrl)
                    <img src="{{ $systemLogoUrl }}" alt="">
                @else
                    <span style="font-size:1.25rem; font-weight:700;">MB</span>
                @endif
            </div>
            <div class="landing-brand-copy">
                <strong>{{ $systemSettings->system_name }}</strong>
                <span>{{ $systemSettings->system_subheader ?? 'Municipality of Lebak' }}</span>
            </div>
        </div>
        <div class="landing-nav-actions">
            <a class="landing-nav-link" href="#features">Explore features</a>
            <a class="landing-nav-link primary" href="{{ route('login') }}">Log in</a>
        </div>
    </header>

    <section class="landing-hero">
        <div class="landing-copy">
            <span class="landing-eyebrow">Municipal digital records platform</span>
            <h1>Make permit work easier before the first click.</h1>
            <p>{{ $systemSettings->system_description }}</p>
            <div class="landing-actions">
                <a class="btn" href="{{ route('login') }}">Proceed to login</a>
                <a class="btn secondary" href="#process">See how it works</a>
            </div>
        </div>

        <aside class="landing-panel">
            <div class="landing-panel-grid">
                <div>
                    <div class="landing-panel-kicker">Operations snapshot</div>
                    <h2 class="landing-panel-title">One workspace for intake, approvals, and records access.</h2>
                    <p class="landing-panel-copy">Designed for LGU teams that need a clear handoff from encoding to approval to reporting without digging through paper trails or scattered files.</p>
                </div>
                <div class="landing-stat-grid">
                    <div class="landing-stat">
                        <strong>Centralized</strong>
                        <span>Permit files, supporting documents, and activity logs stay in one repository.</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Traceable</strong>
                        <span>User actions and approval updates remain visible for accountability.</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Role-based</strong>
                        <span>Access stays aligned with each office function and assigned responsibility.</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Report-ready</strong>
                        <span>Summary views and exports help prepare records for review and printing.</span>
                    </div>
                </div>
            </div>
        </aside>
    </section>

    <div class="landing-sections">
        <section class="landing-band" id="features">
            <div class="landing-band-header">
                <div>
                    <h2>What the system supports</h2>
                    <p>The homepage introduces the workflow clearly before sign-in while staying aligned with the modules already inside the application.</p>
                </div>
            </div>
            <div class="landing-feature-grid">
                <article class="landing-feature">
                    <div class="landing-feature-mark">01</div>
                    <h3>Permit repository</h3>
                    <p>Store building permit records with structured details, uploaded files, and consistent tracking from submission onward.</p>
                </article>
                <article class="landing-feature">
                    <div class="landing-feature-mark">02</div>
                    <h3>Approval monitoring</h3>
                    <p>Review application progress, update statuses, and keep staff aligned on what needs action next.</p>
                </article>
                <article class="landing-feature">
                    <div class="landing-feature-mark">03</div>
                    <h3>Audit and reports</h3>
                    <p>Generate operational outputs and keep a visible trail of logins and record changes for office accountability.</p>
                </article>
            </div>
        </section>

        <section class="landing-band" id="process">
            <div class="landing-band-header">
                <div>
                    <h2>How the workflow moves</h2>
                    <p>Visitors can see the core process at a glance before they sign in, which makes the app feel more welcoming and less abrupt.</p>
                </div>
            </div>
            <div class="landing-flow">
                <article class="landing-flow-step">
                    <h3>Encode and organize</h3>
                    <p>Capture permit details, classify records, and attach the documents needed for review.</p>
                </article>
                <article class="landing-flow-step">
                    <h3>Review and approve</h3>
                    <p>Assigned personnel assess permit status updates and move applications through the approval path.</p>
                </article>
                <article class="landing-flow-step">
                    <h3>Report and retrieve</h3>
                    <p>Teams can search, print, export, and revisit records without rebuilding the trail manually.</p>
                </article>
            </div>
        </section>
    </div>

    <div class="landing-footer">
        Public homepage for {{ $systemSettings->system_name }}. Authorized personnel can continue through the secure login page.
    </div>
</div>
@endsection
