@extends('admin.layouts.app')

@section('title', 'Profil')

@section('content')
    <h1 class="page-title">
        Profil Administrator
        <small>Informasi akun Anda dan penggantian password.</small>
    </h1>

    <div class="profile-grid">
        {{-- ---------- Kartu ringkas ---------- --}}
        <div class="card profile-summary">
            <div class="profile-avatar">
                @if ($user->avatar)
                    <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}">
                @else
                    <span class="profile-initials">{{ \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('') }}</span>
                @endif
            </div>

            <h2 class="profile-name">{{ $user->name }}</h2>
            <p class="profile-position">{{ $user->position ?: 'Administrator' }}</p>

            <ul class="profile-meta">
                <li><i class="fa-regular fa-envelope"></i> {{ $user->email }}</li>
                @if ($user->phone)
                    <li><i class="fa-solid fa-phone"></i> {{ $user->phone }}</li>
                @endif
                <li><i class="fa-solid fa-shield-halved"></i> {{ $user->is_admin ? 'Administrator' : 'Pengguna biasa' }}</li>
                <li><i class="fa-regular fa-clock"></i> Bergabung {{ $user->created_at?->translatedFormat('d F Y') }}</li>
            </ul>

            @if ($user->bio)
                <p class="profile-bio">{{ $user->bio }}</p>
            @endif

            @if ($user->avatar)
                <form method="POST" action="{{ route('admin.profile.avatar.destroy') }}" onsubmit="return confirm('Hapus foto profil?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-block"><i class="fa-solid fa-trash"></i> Hapus Foto Profil</button>
                </form>
            @endif
        </div>

        {{-- ---------- Form ---------- --}}
        <div>
            <div class="card">
                <h2>Informasi Akun</h2>
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="avatar">Foto Profil</label>
                        <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png">
                        <small class="hint">{{ \App\Support\UploadHelper::hint() }} Gambar persegi memberi hasil terbaik.</small>
                    </div>

                    <div class="form-inline">
                        <div class="field">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>

                    <div class="form-inline">
                        <div class="field">
                            <label for="phone">Telepon</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" placeholder="+62 812-3456-7890">
                        </div>
                        <div class="field">
                            <label for="position">Jabatan</label>
                            <input type="text" name="position" id="position" value="{{ old('position', $user->position) }}" placeholder="Principal Architect">
                        </div>
                    </div>

                    <div class="field">
                        <label for="bio">Bio Singkat</label>
                        <textarea name="bio" id="bio" rows="4" placeholder="Ceritakan sedikit tentang Anda.">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-red"><i class="fa-solid fa-floppy-disk"></i> SIMPAN PROFIL</button>
                </form>
            </div>

            <div class="card">
                <h2>Ubah Password</h2>
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required autocomplete="current-password">
                    </div>

                    <div class="form-inline">
                        <div class="field">
                            <label for="password">Password Baru</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password">
                        </div>
                        <div class="field">
                            <label for="password_confirmation">Ulangi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <small class="hint form-note">Minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol.</small>

                    <button type="submit" class="btn btn-outline-red"><i class="fa-solid fa-key"></i> UBAH PASSWORD</button>
                </form>
            </div>
        </div>
    </div>
@endsection
