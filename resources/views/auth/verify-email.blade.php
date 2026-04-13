<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-semibold uppercase tracking-[0.28em] text-blue-600">Verify Email</p>
        <h2 class="mt-3 text-3xl font-semibold text-slate-900">Verifikasi Email</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">
            Sebelum melanjutkan, klik link verifikasi yang sudah dikirim ke email kamu.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            Link verifikasi baru sudah dikirim ke email kamu.
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-600 underline hover:text-slate-900">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
