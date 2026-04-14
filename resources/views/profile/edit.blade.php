@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <div class="profile-header">
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun dan pengaturan profil Anda</p>
    </div>
@stop

@section('content')
    @php
        $user = auth()->user();
        $divisionLabel = match ($user->role ?? '') {
            'admin' => 'Operation',
            'staff' => 'Logistik',
            'pimpinan' => 'Manajemen',
            default => '-',
        };
        $phonePlaceholder = '+62 812 3456 7890';
        $jobTitle = match ($user->role ?? '') {
            'admin' => 'Operation Admin Specialist',
            'staff' => 'Asset Distribution Staff',
            'pimpinan' => 'Head of Asset Management',
            default => 'Team Member',
        };
    @endphp

    <div class="profile-page">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="row">
                <div class="col-lg-4">
                    <div class="profile-side-card">
                        <div class="avatar-badge"><i class="fas fa-pen"></i></div>
                        <h3>{{ $user->name }}</h3>
                        <div class="user-flags">
                            <span class="badge-role">{{ ucfirst($user->role ?? 'user') }}</span>
                            <span class="badge-active">Active</span>
                        </div>
                        <p>{{ $user->email }}</p>

                        <div class="meta-table">
                            <div><span>User ID</span><strong>{{ strtoupper($user->username ?? 'MBG'.str_pad((string) $user->id, 10, '0', STR_PAD_LEFT)) }}</strong></div>
                            <div><span>Division</span><strong>{{ $divisionLabel }}</strong></div>
                            <div><span>Joined Date</span><strong>{{ optional($user->created_at)->format('d M Y') }}</strong></div>
                        </div>

                        <div class="managed-assets-card">
                            <small>Total Assets Managed</small>
                            <h4>{{ \App\Models\Asset::count() }}</h4>
                            <button type="button" class="btn-view-history">View History <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="profile-main-card">
                        <section class="group-section">
                            <h3><i class="fas fa-user"></i>Informasi Pribadi</h3>
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input id="name" name="name" type="text" class="form-control input-pill @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email">Email Address</label>
                                    <input id="email" name="email" type="email" class="form-control input-pill @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                                    @error('email')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone_preview">Phone Number</label>
                                    <input id="phone_preview" type="text" class="form-control input-pill" value="{{ $phonePlaceholder }}">
                                </div>
                            </div>

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="verification-box">
                                    Email belum diverifikasi.
                                    <button form="send-verification" class="btn btn-link p-0 align-baseline" type="submit">Kirim ulang verifikasi</button>
                                    @if (session('status') === 'verification-link-sent')
                                        <span class="text-success ml-1">Link verifikasi baru sudah dikirim.</span>
                                    @endif
                                </div>
                            @endif
                        </section>

                        <section class="group-section mb-0">
                            <h3><i class="fas fa-briefcase"></i>Informasi Pekerjaan</h3>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="role_preview">Role</label>
                                    <input id="role_preview" type="text" class="form-control input-pill" value="{{ ucfirst($user->role ?? '-') }}" readonly>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="division_preview">Division</label>
                                    <input id="division_preview" type="text" class="form-control input-pill" value="{{ $divisionLabel }}" readonly>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="job_title_preview">Job Title</label>
                                <input id="job_title_preview" type="text" class="form-control input-pill" value="{{ $jobTitle }}" readonly>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <a href="{{ route('profile.edit') }}" class="btn btn-reset">Reset</a>
                <button type="submit" class="btn btn-save"><i class="fas fa-lock mr-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        .profile-header h1 { margin: 0; font-size: 44px; color: #222f46; font-weight: 700; }
        .profile-header p { margin: 4px 0 0; color: #7f8ca5; }
        .profile-page { padding-bottom: 20px; }
        .profile-side-card { background: #fff; border-radius: 16px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); padding: 18px; text-align: center; }
        .avatar-badge { width: 40px; height: 40px; margin: 0 auto 10px; border-radius: 999px; display: grid; place-items: center; background: #dbe7ff; color: #4a6ec8; }
        .profile-side-card h3 { margin: 0; font-size: 34px; color: #273753; font-weight: 700; }
        .user-flags { margin: 8px 0 6px; display: flex; justify-content: center; gap: 6px; }
        .badge-role, .badge-active { font-size: 11px; font-weight: 700; border-radius: 999px; padding: 3px 9px; }
        .badge-role { background: #e6ecff; color: #4f6cbc; }
        .badge-active { background: #dff5e8; color: #2c9f64; }
        .profile-side-card p { color: #7d8ba6; margin: 0 0 12px; }
        .meta-table { border: 1px solid #edf1f7; border-radius: 10px; padding: 8px 10px; text-align: left; }
        .meta-table div { display: flex; justify-content: space-between; padding: 7px 0; border-bottom: 1px solid #f1f4fa; }
        .meta-table div:last-child { border-bottom: 0; }
        .meta-table span { color: #8a97ad; font-size: 12px; }
        .meta-table strong { color: #33415f; font-size: 12px; }
        .managed-assets-card { margin-top: 12px; background: linear-gradient(135deg, #6d8fc6, #5579b8); border-radius: 14px; color: #fff; text-align: left; padding: 12px; }
        .managed-assets-card small { opacity: .85; font-size: 11px; display: block; }
        .managed-assets-card h4 { margin: 3px 0 8px; font-size: 40px; font-weight: 700; }
        .btn-view-history { background: rgba(255, 255, 255, 0.2); border: 0; color: #fff; border-radius: 8px; font-size: 12px; padding: 6px 10px; }
        .profile-main-card { background: #fff; border-radius: 16px; box-shadow: 0 8px 20px rgba(37, 59, 102, 0.08); padding: 18px; }
        .group-section { margin-bottom: 14px; }
        .group-section h3 { margin: 0 0 12px; font-size: 30px; color: #253656; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .group-section h3 i { width: 26px; height: 26px; border-radius: 8px; display: grid; place-items: center; background: #e9effa; color: #5271ac; font-size: 12px; }
        .group-section label { color: #415170; font-weight: 700; font-size: 12px; }
        .input-pill { border-radius: 999px; border: 1px solid #e2e8f3; background: #f3f6fb; height: 44px; color: #2f3f5f; }
        .verification-box { margin-top: 8px; background: #fff8e8; border: 1px solid #ffe3a4; color: #9e7419; border-radius: 10px; padding: 8px 10px; font-size: 13px; }
        .profile-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; }
        .btn-reset, .btn-save { border-radius: 999px; min-width: 130px; font-weight: 700; padding: 9px 16px; }
        .btn-reset { background: #fff; border: 1px solid #d7deea; color: #5f6f8b; text-align: center; }
        .btn-save { background: #4f74b7; color: #fff; border: 0; }
        .btn-save:hover { color: #fff; }
    </style>
@stop
