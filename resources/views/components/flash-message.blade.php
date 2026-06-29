@if (session()->has('success'))
    <div style="padding: 1rem; background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; margin-bottom: 1.5rem; border-radius: 0.375rem;">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div style="padding: 1rem; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; margin-bottom: 1.5rem; border-radius: 0.375rem;">
        {{ session('error') }}
    </div>
@endif