<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Profile Information</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Update informasi akun dan alamat email yang digunakan untuk login.
        </p>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @error('name')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
                @error('email')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                @enderror
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning">
                    <p class="mb-2">Email kamu belum diverifikasi.</p>
                    <button form="send-verification" class="btn btn-sm btn-warning" type="submit">
                        Kirim ulang verifikasi email
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="mt-2 text-success">
                            Link verifikasi baru sudah dikirim.
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Save
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success ml-2">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>
