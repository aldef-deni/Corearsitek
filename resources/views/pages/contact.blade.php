@extends('layouts.frontend')

@section('title', ($contents['contact_title'] ?? __('situs.kontak')) . ' — ' . ($contents['site_name'] ?? 'CoreArsitek'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($contents['contact_intro'] ?? ''), 155) ?: 'Hubungi CoreArsitek dan ajukan pembuatan desain hunian Anda.')

@php
    $wa = 'https://wa.me/' . preg_replace('/\D/', '', $contents['whatsapp_number'] ?? '');
    $berhasil = session('submission_success');
@endphp

@section('content')

@include('partials.banner', [
    'slides' => $banners,
    'variant' => 'page',
    'fallbackImage' => $contents['hero_image'] ?? '',
    'fallbackTitle' => $contents['contact_title'] ?? __('situs.judul_hubungi'),
    'fallbackSubtitle' => $contents['hero_subtitle'] ?? '',
    'siteName' => $contents['site_name'] ?? 'CoreArsitek',
])

{{-- ================= DETAIL KONTAK ================= --}}
<section class="contact-detail">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">{{ __('situs.kontak') }}</span>
            <h2 class="section-title">{{ $contents['contact_title'] ?? __('situs.judul_hubungi') }}</h2>
        </div>

        @if (!empty($contents['contact_intro']))
            <p class="contact-lead reveal">{{ $contents['contact_intro'] }}</p>
        @endif

        <div class="cd-grid" data-reveal-group="70">
            @if (!empty($contents['contact_address']))
                <div class="cd-card reveal" data-tilt="4">
                    <span class="cd-icon"><i class="fa-solid fa-location-dot"></i></span>
                    <h3>{{ __('situs.alamat_studio') }}</h3>
                    <p>{{ $contents['contact_address'] }}</p>
                    @if (!empty($contents['contact_maps_url']))
                        <a href="{{ $contents['contact_maps_url'] }}" target="_blank" rel="noopener" class="cd-link">
                            {{ __('situs.lihat_peta') }} <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif
                </div>
            @endif

            @if (!empty($contents['contact_phone']))
                <div class="cd-card reveal" data-tilt="4">
                    <span class="cd-icon"><i class="fa-solid fa-phone"></i></span>
                    <h3>{{ __('situs.telepon_wa') }}</h3>
                    <p>{{ $contents['contact_phone'] }}</p>
                    <a href="{{ $wa }}" target="_blank" rel="noopener" class="cd-link">
                        {{ __('situs.chat_wa') }} <i class="fa-brands fa-whatsapp"></i>
                    </a>
                </div>
            @endif

            @if (!empty($contents['contact_email']))
                <div class="cd-card reveal" data-tilt="4">
                    <span class="cd-icon"><i class="fa-solid fa-envelope"></i></span>
                    <h3>{{ __('situs.email') }}</h3>
                    <p>{{ $contents['contact_email'] }}</p>
                    <a href="mailto:{{ $contents['contact_email'] }}" class="cd-link">
                        {{ __('situs.kirim_email') }} <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endif

            @if (!empty($contents['contact_hours']))
                <div class="cd-card reveal" data-tilt="4">
                    <span class="cd-icon"><i class="fa-regular fa-clock"></i></span>
                    <h3>{{ __('situs.jam_operasional') }}</h3>
                    <p>{{ $contents['contact_hours'] }}</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ================= FORM PENGAJUAN ================= --}}
<section id="form" class="ajukan">
    <div class="container">
        <div class="section-head reveal">
            <span class="eyebrow">{{ __('situs.eyebrow_ajukan') }}</span>
            <h2 class="section-title">{{ __('situs.judul_ajukan') }}</h2>
        </div>

        <div class="ajukan-shell reveal">

            @if ($berhasil)
                <div class="form-note form-note-ok">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <strong>{{ __('situs.sukses_judul') }}</strong>
                        <span>{{ __('situs.sukses_teks') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="form-note form-note-bad">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong>{{ __('situs.gagal_judul') }}</strong>
                        <span>{{ $errors->first() }}</span>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="ajukan-form">
                @csrf

                {{-- Umpan penjaring robot: disembunyikan dari manusia dan pembaca layar. --}}
                <div class="hp-field" aria-hidden="true">
                    <label for="website">Jangan diisi</label>
                    <input type="text" name="website" id="website" tabindex="-1" autocomplete="off">
                </div>

                <fieldset class="fs">
                    <legend>{{ __('situs.data_anda') }}</legend>

                    <div class="fgrid">
                        <div class="fitem">
                            <label for="name">{{ __('situs.nama_lengkap') }} <span>*</span></label>
                            <input type="text" name="name" id="name" required maxlength="120"
                                   value="{{ old('name') }}" placeholder="{{ __('situs.ph_nama') }}"
                                   class="{{ $errors->has('name') ? 'is-bad' : '' }}">
                            @error('name') <small class="ferr">{{ $message }}</small> @enderror
                        </div>

                        <div class="fitem">
                            <label for="phone">{{ __('situs.nomor_wa') }} <span>*</span></label>
                            <input type="tel" name="phone" id="phone" required maxlength="30"
                                   value="{{ old('phone') }}" placeholder="{{ __('situs.ph_wa') }}"
                                   class="{{ $errors->has('phone') ? 'is-bad' : '' }}">
                            @error('phone') <small class="ferr">{{ $message }}</small> @enderror
                        </div>

                        <div class="fitem fitem-wide">
                            <label for="email">{{ __('situs.email') }}</label>
                            <input type="email" name="email" id="email" maxlength="150"
                                   value="{{ old('email') }}" placeholder="{{ __('situs.ph_email') }}"
                                   class="{{ $errors->has('email') ? 'is-bad' : '' }}">
                            @error('email') <small class="ferr">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset class="fs">
                    <legend>{{ __('situs.rincian_proyek') }}</legend>

                    <div class="fgrid">
                        <div class="fitem">
                            <label for="service_type">{{ __('situs.jenis_layanan') }}</label>
                            <select name="service_type" id="service_type">
                                <option value="">{{ __('situs.pilih_layanan') }}</option>
                                @foreach ($services as $layanan)
                                    {{-- Nilai yang tersimpan tetap judul Indonesia supaya data
                                         pengajuan seragam apa pun bahasa pengunjungnya. --}}
                                    <option value="{{ $layanan->title }}" @selected(old('service_type') === $layanan->title)>
                                        {{ $layanan->t('title') }}
                                    </option>
                                @endforeach
                                <option value="Lainnya" @selected(old('service_type') === 'Lainnya')>{{ __('situs.lainnya') }}</option>
                            </select>
                        </div>

                        <div class="fitem">
                            <label for="location">{{ __('situs.lokasi_proyek') }}</label>
                            <input type="text" name="location" id="location" maxlength="150"
                                   value="{{ old('location') }}" placeholder="{{ __('situs.ph_lokasi') }}">
                        </div>

                        <div class="fitem fitem-sm">
                            <label for="land_area">{{ __('situs.luas_tanah') }} (m&sup2;)</label>
                            <input type="text" name="land_area" id="land_area" maxlength="20"
                                   value="{{ old('land_area') }}" placeholder="150" inputmode="numeric">
                        </div>

                        <div class="fitem fitem-sm">
                            <label for="building_area">{{ __('situs.luas_bangunan') }} (m&sup2;)</label>
                            <input type="text" name="building_area" id="building_area" maxlength="20"
                                   value="{{ old('building_area') }}" placeholder="220" inputmode="numeric">
                        </div>

                        <div class="fitem fitem-sm">
                            <label for="floors">{{ __('situs.jumlah_lantai') }}</label>
                            <input type="text" name="floors" id="floors" maxlength="20"
                                   value="{{ old('floors') }}" placeholder="2" inputmode="numeric">
                        </div>

                        <div class="fitem">
                            <label for="budget">{{ __('situs.perkiraan_anggaran') }}</label>
                            <select name="budget" id="budget">
                                <option value="">{{ __('situs.pilih_rentang') }}</option>
                                @foreach (\App\Models\Submission::BUDGETS as $rentang)
                                    <option value="{{ $rentang }}" @selected(old('budget') === $rentang)>{{ __('situs.anggaran.' . $rentang) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fitem">
                            <label for="style">{{ __('situs.gaya_diinginkan') }}</label>
                            <input type="text" name="style" id="style" maxlength="120"
                                   value="{{ old('style') }}" placeholder="{{ __('situs.ph_gaya') }}">
                        </div>

                        <div class="fitem fitem-full">
                            <label for="message">{{ __('situs.kebutuhan_desain') }} <span>*</span></label>
                            <textarea name="message" id="message" rows="6" required maxlength="4000"
                                      placeholder="{{ __('situs.ph_kebutuhan') }}"
                                      class="{{ $errors->has('message') ? 'is-bad' : '' }}">{{ old('message') }}</textarea>
                            @error('message') <small class="ferr">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="ajukan-kirim">
                    <p class="ajukan-catatan">
                        <i class="fa-solid fa-lock"></i>
                        {{ __('situs.catatan_privasi') }}
                    </p>
                    <button type="submit" class="btn btn-red btn-flash magnetic" data-magnetic="0.1">
                        <i class="fa-solid fa-paper-plane"></i> {{ __('situs.kirim_pengajuan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
