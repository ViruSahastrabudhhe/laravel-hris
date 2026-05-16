@extends('layouts.admin')

@php use App\Models\User; @endphp

@section('page-content')
    <div class="welcome-banner">
        <div class="banner-left">
            <div class="banner-icon">
                <svg width="22" height="22" fill="none" stroke="#d9bb00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
            </div>
            <div>
                <h2>Audit Trail</h2>
                <p>User Actions</p>
            </div>
        </div>
        <div class="banner-right" hidden>
            <div class="recruit-search-wrap">
                <svg width="13" height="13" fill="none" stroke="#9999bb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" id="archive-search" placeholder="Search archive..." class="recruit-search" oninput="filterArchive()">
            </div>
        </div>
    </div>

    <div class="table-section">
        <div class="table-header">
            <div>
                <p class="table-title">Audit Trail</p>
                <p class="table-sub">Keep Track of Changes Made to Records</p>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="payroll-table" id="audits-table">
                <thead>
                    <tr>
                        <th>Audit ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Values</th>
                        <th>URL</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                        @php
                            $user = User::find($audit->user_id);
                        @endphp
                        <tr>
                            <td><span>{{ $audit->id }}</span></td>
                            <td><span>{{ $user->name}}</span></td>
                            <td><span>{{ $audit->event }} {{ $audit->auditable_type }}</span></td>
                            <td>
                                <div>
                                    <span>Old values: </span>
                                    @foreach($audit->old_values as $index => $old)
                                        <span>{{ is_array($old) ? print($old) : $old }}</span>
                                    @endforeach
                                </div>
                                <div>
                                    <span>New values: </span>
                                    @foreach($audit->new_values as $index => $new)
                                        <span>{{ $index }} {{ $new }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td><span>{{ $audit->url }}</span></td>
                            <td><span>{{ $audit->ip_address }}</span></td>
                            <td><span>{{ $audit->user_agent }}</span></td>
                            <td><span>{{ $audit->created_at }}</span></td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
