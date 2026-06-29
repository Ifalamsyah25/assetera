<div class="card card-outline card-danger">
    <div class="card-header">
        <h3 class="card-title">Delete Account</h3>
    </div>
    <div class="card-body">
        <p class="text-muted">
            Setelah akun dihapus, seluruh data yang terkait akan ikut hilang permanen. Masukkan password untuk konfirmasi penghapusan akun.
        </p>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="form-group">
                <label for="delete_password">Password Confirmation</label>
                <input id="delete_password" name="password" type="password" class="form-control @if($errors->userDeletion->get('password')) is-invalid @endif" placeholder="Password">
                @if ($errors->userDeletion->get('password'))
                    <span class="invalid-feedback d-block">{{ $errors->userDeletion->first('password') }}</span>
                @endif
            </div>

            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus akun ini?')">
                <i class="fas fa-trash mr-1"></i> Delete Account
            </button>
        </form>
    </div>
</div>
