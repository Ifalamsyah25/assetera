<div class="card card-outline card-warning">
    <div class="card-header">
        <h3 class="card-title">Update Password</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Gunakan password yang panjang dan sulit ditebak agar akun tetap aman.
        </p>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="update_password_current_password">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" class="form-control @if($errors->updatePassword->get('current_password')) is-invalid @endif" autocomplete="current-password">
                @if ($errors->updatePassword->get('current_password'))
                    <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('current_password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="update_password_password">New Password</label>
                <input id="update_password_password" name="password" type="password" class="form-control @if($errors->updatePassword->get('password')) is-invalid @endif" autocomplete="new-password">
                @if ($errors->updatePassword->get('password'))
                    <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('password') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="update_password_password_confirmation">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @if($errors->updatePassword->get('password_confirmation')) is-invalid @endif" autocomplete="new-password">
                @if ($errors->updatePassword->get('password_confirmation'))
                    <span class="invalid-feedback d-block">{{ $errors->updatePassword->first('password_confirmation') }}</span>
                @endif
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-key mr-1"></i> Update Password
                </button>

                @if (session('status') === 'password-updated')
                    <span class="text-success ml-2">Saved.</span>
                @endif
            </div>
        </form>
    </div>
</div>
