{{--
    Kolom pendamping bahasa Inggris.

    Parameter:
      $nama   Nama input, mis. "title_en"
      $label  Label kolom Indonesia yang didampinginya
      $nilai  Isi saat ini
      $tipe   'text' (bawaan) atau 'textarea'
      $rows   Tinggi textarea
      $kelas  Kelas tambahan untuk .field
--}}
<div class="field {{ $kelas ?? '' }}">
    <label>{{ $label }} <span class="tag-en">EN</span></label>

    @if (($tipe ?? 'text') === 'textarea')
        <textarea name="{{ $nama }}" rows="{{ $rows ?? 3 }}"
                  placeholder="Kosongkan untuk memakai teks Indonesia">{{ $nilai }}</textarea>
    @else
        <input type="text" name="{{ $nama }}" value="{{ $nilai }}"
               placeholder="Kosongkan untuk memakai teks Indonesia">
    @endif
</div>
