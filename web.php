<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('login');
});

Route::get('/ogrenci', function () {
    if (!session()->has('user_id') || session('rol') != 'ogrenci') {
        return redirect('/');
    }

    $ogrenci = DB::table('users')
        ->where('id', session('user_id'))
        ->where('rol', 'ogrenci')
        ->first();

    if (!$ogrenci) {
        return 'Ogrenci bulunamadi';
    }

    $dersler = DB::table('ogrenci_ders')
        ->join('courses', 'ogrenci_ders.ders_id', '=', 'courses.id')
        ->leftJoin('users as ogretmen', 'courses.ogretmen_id', '=', 'ogretmen.id')
        ->where('ogrenci_ders.ogrenci_id', session('user_id'))
        ->select(
            'courses.id as ders_id',
            'courses.ders_adi',
            'courses.ders_kodu',
            'courses.gun',
            'courses.saat',
            'ogretmen.name as ogretmen_adi'
        )
        ->orderBy('courses.ders_adi')
        ->get()
        ->map(function ($ders) {
            $toplamYoklama = DB::table('attendance')
                ->where('ogrenci_id', session('user_id'))
                ->where('ders_id', $ders->ders_id)
                ->count();

            $devamsizlik = DB::table('attendance')
                ->where('ogrenci_id', session('user_id'))
                ->where('ders_id', $ders->ders_id)
                ->where('durum', 'yok')
                ->count();

            $ders->toplam_yoklama = $toplamYoklama;
            $ders->devamsizlik = $devamsizlik;
            $ders->kalan_hak = max(4 - $devamsizlik, 0);
            $ders->devamsizlik_yuzdesi = $toplamYoklama > 0
                ? round(($devamsizlik / $toplamYoklama) * 100, 1)
                : 0;

            return $ders;
        });

    $genelToplamYoklama = $dersler->sum('toplam_yoklama');
    $genelDevamsizlik = $dersler->sum('devamsizlik');
    $genelDevamsizlikYuzdesi = $genelToplamYoklama > 0
        ? round(($genelDevamsizlik / $genelToplamYoklama) * 100, 1)
        : 0;
    $gunuNormalizeEt = function ($metin) {
        $metin = trim((string) $metin);
        $metin = str_replace(
            ['Ç', 'ç', 'Ğ', 'ğ', 'I', 'ı', 'İ', 'i', 'Ö', 'ö', 'Ş', 'ş', 'Ü', 'ü'],
            ['C', 'c', 'G', 'g', 'I', 'i', 'I', 'i', 'O', 'o', 'S', 's', 'U', 'u'],
            $metin
        );

        return strtolower($metin);
    };

    $gunSirasi = ['Pazartesi', 'Sali', 'Carsamba', 'Persembe', 'Cuma'];
    $haftalikProgram = collect($gunSirasi)->mapWithKeys(function ($gun) use ($dersler, $gunuNormalizeEt) {
        return [
            $gun => $dersler
                ->filter(fn ($ders) => $gunuNormalizeEt($ders->gun) === $gunuNormalizeEt($gun))
                ->sortBy('saat')
                ->values(),
        ];
    });

    $istanbulNow = now('Europe/Istanbul');

    $gunMap = [
        1 => 'Pazartesi',
        2 => 'Sali',
        3 => 'Carsamba',
        4 => 'Persembe',
        5 => 'Cuma',
        6 => 'Cumartesi',
        7 => 'Pazar',
    ];
    $bugununGunu = $gunMap[$istanbulNow->dayOfWeekIso] ?? null;
    $bugununDersleri = $dersler
        ->filter(fn ($ders) => $gunuNormalizeEt($ders->gun) === $gunuNormalizeEt($bugununGunu))
        ->sortBy('saat')
        ->values();
    $kritikDers = $dersler->sortBy('kalan_hak')->first();
    $uyariTipi = 'ok';
    $uyariMesaji = 'Devamsizlik durumun su an guvenli gorunuyor.';
    $aktifQrOturumlari = DB::table('attendance_sessions')
        ->join('courses', 'attendance_sessions.ders_id', '=', 'courses.id')
        ->where('attendance_sessions.aktif', 1)
        ->whereIn('attendance_sessions.ders_id', $dersler->pluck('ders_id'))
        ->select(
            'attendance_sessions.token',
            'attendance_sessions.tarih',
            'courses.ders_adi',
            'courses.ders_kodu'
        )
        ->orderBy('courses.ders_adi')
        ->get();

    if ($kritikDers && $kritikDers->kalan_hak === 0) {
        $uyariTipi = 'danger';
        $uyariMesaji = $kritikDers->ders_adi . ' dersi icin devamsizlik hakkin doldu.';
    } elseif ($kritikDers && $kritikDers->kalan_hak === 1) {
        $uyariTipi = 'warn';
        $uyariMesaji = $kritikDers->ders_adi . ' dersi icin yalnizca 1 devamsizlik hakkin kaldi.';
    }

    return view('ogrenci', [
        'ogrenci' => $ogrenci,
        'dersler' => $dersler,
        'genelToplamYoklama' => $genelToplamYoklama,
        'genelDevamsizlik' => $genelDevamsizlik,
        'genelDevamsizlikYuzdesi' => $genelDevamsizlikYuzdesi,
        'haftalikProgram' => $haftalikProgram,
        'bugununDersleri' => $bugununDersleri,
        'bugununGunu' => $bugununGunu,
        'uyariTipi' => $uyariTipi,
        'uyariMesaji' => $uyariMesaji,
        'aktifQrOturumlari' => $aktifQrOturumlari,
    ]);
});

Route::get('/ogretmen', function () {
    if (!session()->has('user_id') || session('rol') != 'ogretmen') {
        return redirect('/');
    }

    $ogretmen = DB::table('users')
        ->where('id', session('user_id'))
        ->where('rol', 'ogretmen')
        ->first();

    if (!$ogretmen) {
        return 'Ogretmen bulunamadi';
    }

    $dersler = DB::table('courses')
        ->where('ogretmen_id', session('user_id'))
        ->orderBy('ders_adi')
        ->get();

    $gunuNormalizeEt = function ($metin) {
        $metin = trim((string) $metin);
        $metin = str_replace(
            ['Ã‡', 'Ã§', 'Ä', 'ÄŸ', 'I', 'Ä±', 'Ä°', 'i', 'Ã–', 'Ã¶', 'Å', 'ÅŸ', 'Ãœ', 'Ã¼'],
            ['C', 'c', 'G', 'g', 'I', 'i', 'I', 'i', 'O', 'o', 'S', 's', 'U', 'u'],
            $metin
        );

        return strtolower($metin);
    };

    $seciliDersId = request('ders_id');
    $istanbulNow = now('Europe/Istanbul');
    $seciliTarih = request('tarih', $istanbulNow->format('Y-m-d'));
    $seciliDers = null;
    $ogrenciler = collect();
    $aktifQrOturumu = null;
    $seciliDersToplamDersSayisi = 0;
    $seciliDersGelenSayisi = 0;
    $seciliDersGelmeyenSayisi = 0;
    $seciliDersKalanSayisi = 0;

    if ($seciliDersId) {
        $seciliDers = DB::table('courses')
            ->where('id', $seciliDersId)
            ->where('ogretmen_id', session('user_id'))
            ->first();

        if ($seciliDers) {
            $seciliDersToplamDersSayisi = DB::table('attendance')
                ->where('ders_id', $seciliDersId)
                ->distinct('tarih')
                ->count('tarih');

            $aktifQrOturumu = DB::table('attendance_sessions')
                ->where('ders_id', $seciliDersId)
                ->where('ogretmen_id', session('user_id'))
                ->where('tarih', $seciliTarih)
                ->where('aktif', 1)
                ->latest('id')
                ->first();

            $ogrenciler = DB::table('ogrenci_ders')
                ->join('users', 'ogrenci_ders.ogrenci_id', '=', 'users.id')
                ->leftJoin('attendance', function ($join) use ($seciliDersId, $seciliTarih) {
                    $join->on('attendance.ogrenci_id', '=', 'users.id')
                        ->where('attendance.ders_id', '=', $seciliDersId)
                        ->whereDate('attendance.tarih', '=', $seciliTarih);
                })
                ->where('ogrenci_ders.ders_id', $seciliDersId)
                ->select(
                    'users.id',
                    'users.name',
                    'users.email',
                    'attendance.durum as mevcut_durum'
                )
                ->orderBy('users.name')
                ->get()
                ->map(function ($ogrenci) use ($seciliDersId) {
                    $ogrenci->devamsizlik_sayisi = DB::table('attendance')
                        ->where('ogrenci_id', $ogrenci->id)
                        ->where('ders_id', $seciliDersId)
                        ->where('durum', 'yok')
                        ->count();

                    $ogrenci->kaldi = $ogrenci->devamsizlik_sayisi >= 5;
                    $ogrenci->bugunku_durum = $ogrenci->mevcut_durum ?? 'bekliyor';

                    return $ogrenci;
                });

            $seciliDersGelenSayisi = $ogrenciler->where('bugunku_durum', 'var')->count();
            $seciliDersGelmeyenSayisi = $ogrenciler->where('bugunku_durum', 'yok')->count();
            $seciliDersKalanSayisi = $ogrenciler->where('kaldi', true)->count();
        }
    }

    $dersOzetleri = $dersler->map(function ($ders) {
        $ders->ogrenci_sayisi = DB::table('ogrenci_ders')
            ->where('ders_id', $ders->id)
            ->count();

        $ders->yoklama_sayisi = DB::table('attendance')
            ->where('ders_id', $ders->id)
            ->distinct('tarih')
            ->count('tarih');

        $ders->devamsiz_ogrenci_sayisi = DB::table('attendance')
            ->where('ders_id', $ders->id)
            ->where('durum', 'yok')
            ->distinct('ogrenci_id')
            ->count('ogrenci_id');

        $ders->toplam_ders_sayisi = $ders->yoklama_sayisi;
        $ders->kalan_ogrenci_sayisi = DB::table('attendance')
            ->select('ogrenci_id')
            ->where('ders_id', $ders->id)
            ->where('durum', 'yok')
            ->groupBy('ogrenci_id')
            ->havingRaw('COUNT(*) >= 5')
            ->get()
            ->count();

        return $ders;
    });

    $gunMap = [
        1 => 'Pazartesi',
        2 => 'Sali',
        3 => 'Carsamba',
        4 => 'Persembe',
        5 => 'Cuma',
        6 => 'Cumartesi',
        7 => 'Pazar',
    ];
    $bugununGunu = $gunMap[$istanbulNow->dayOfWeekIso] ?? null;
    $bugununDersleri = $dersOzetleri
        ->filter(fn ($ders) => $gunuNormalizeEt($ders->gun) === $gunuNormalizeEt($bugununGunu))
        ->sortBy('saat')
        ->values();
    $aktifOturumlar = DB::table('attendance_sessions')
        ->join('courses', 'attendance_sessions.ders_id', '=', 'courses.id')
        ->where('attendance_sessions.ogretmen_id', session('user_id'))
        ->where('attendance_sessions.aktif', 1)
        ->select(
            'attendance_sessions.id',
            'attendance_sessions.token',
            'attendance_sessions.tarih',
            'courses.ders_adi',
            'courses.ders_kodu'
        )
        ->latest('attendance_sessions.id')
        ->get();
    $toplamOgrenci = $dersOzetleri->sum('ogrenci_sayisi');
    $toplamYoklamaGunu = $dersOzetleri->sum('yoklama_sayisi');
    $toplamRiskliOgrenci = $dersOzetleri->sum('devamsiz_ogrenci_sayisi');

    return view('ogretmen', [
        'ogretmen' => $ogretmen,
        'dersler' => $dersOzetleri,
        'seciliDers' => $seciliDers,
        'seciliTarih' => $seciliTarih,
        'ogrenciler' => $ogrenciler,
        'aktifQrOturumu' => $aktifQrOturumu,
        'aktifOturumlar' => $aktifOturumlar,
        'bugununDersleri' => $bugununDersleri,
        'bugununGunu' => $bugununGunu,
        'toplamOgrenci' => $toplamOgrenci,
        'toplamYoklamaGunu' => $toplamYoklamaGunu,
        'toplamRiskliOgrenci' => $toplamRiskliOgrenci,
        'seciliDersToplamDersSayisi' => $seciliDersToplamDersSayisi,
        'seciliDersGelenSayisi' => $seciliDersGelenSayisi,
        'seciliDersGelmeyenSayisi' => $seciliDersGelmeyenSayisi,
        'seciliDersKalanSayisi' => $seciliDersKalanSayisi,
    ]);
});

Route::get('/admin', function () {
    $rolSayilari = DB::table('users')
        ->select('rol', DB::raw('COUNT(*) as sayi'))
        ->groupBy('rol')
        ->pluck('sayi', 'rol');

    $istatistikler = [
        'toplam_kullanici' => DB::table('users')->count(),
        'toplam_ogrenci' => $rolSayilari['ogrenci'] ?? 0,
        'toplam_ogretmen' => $rolSayilari['ogretmen'] ?? 0,
        'toplam_admin' => $rolSayilari['admin'] ?? 0,
        'toplam_ders' => DB::table('courses')->count(),
        'toplam_atama' => DB::table('ogrenci_ders')->count(),
        'aktif_qr' => DB::table('attendance_sessions')->where('aktif', 1)->count(),
        'ogretmensiz_ders' => DB::table('courses')->whereNull('ogretmen_id')->count(),
        'derssiz_ogrenci' => DB::table('users')
            ->where('rol', 'ogrenci')
            ->whereNotIn('id', function ($query) {
                $query->select('ogrenci_id')->from('ogrenci_ders');
            })
            ->count(),
        'derssiz_ogretmen' => DB::table('users')
            ->where('rol', 'ogretmen')
            ->whereNotIn('id', function ($query) {
                $query->select('ogretmen_id')
                    ->from('courses')
                    ->whereNotNull('ogretmen_id');
            })
            ->count(),
    ];

    $sonEklenenDersler = DB::table('courses')
        ->orderByDesc('id')
        ->limit(5)
        ->get();

    return view('admin', [
        'istatistikler' => $istatistikler,
        'sonEklenenDersler' => $sonEklenenDersler,
    ]);
});

Route::post('/login', function (Request $request) {
    $kullanici = DB::table('users')
        ->where('email', $request->kullanici)
        ->where('password', $request->sifre)
        ->first();

    if ($kullanici) {
        session([
            'user_id' => $kullanici->id,
            'rol' => $kullanici->rol,
            'name' => $kullanici->name,
        ]);

        if ($kullanici->rol == 'admin') {
            return redirect('/admin');
        } elseif ($kullanici->rol == 'ogretmen') {
            return redirect('/ogretmen');
        } else {
            return redirect('/ogrenci');
        }
    }

    return 'Hatali giris';
});

Route::get('/logout', function () {
    session()->flush();
    return redirect('/');
});

Route::get('/kullanicilar', function () {
    $kullanicilar = DB::table('users')->get();
    return view('kullanicilar', ['kullanicilar' => $kullanicilar]);
});

Route::post('/kullanici-ekle', function (Request $request) {
    DB::table('users')->insert([
        'name' => $request->ad,
        'email' => $request->email,
        'password' => $request->sifre,
        'rol' => $request->rol,
    ]);

    return redirect('/kullanicilar');
});

Route::get('/kullanici-sil/{id}', function ($id) {
    DB::table('users')->where('id', $id)->delete();
    return redirect('/kullanicilar');
});

Route::get('/dersler', function () {
    $dersler = DB::table('courses')->get();
    return view('dersler', ['dersler' => $dersler]);
});

Route::post('/ders-ekle', function (Request $request) {
    DB::table('courses')->insert([
        'ders_adi' => $request->ders_adi,
        'ders_kodu' => $request->ders_kodu,
        'gun' => $request->gun,
        'saat' => $request->saat,
    ]);

    return redirect('/dersler');
});

Route::get('/ders-sil/{id}', function ($id) {
    DB::table('courses')->where('id', $id)->delete();
    return redirect('/dersler');
});

Route::get('/ders-atama', function () {
    $dersler = DB::table('courses')->get();
    $ogretmenler = DB::table('users')->where('rol', 'ogretmen')->get();
    $ogrenciler = DB::table('users')->where('rol', 'ogrenci')->get();

    $atananOgretmenler = DB::table('courses')
        ->leftJoin('users', 'courses.ogretmen_id', '=', 'users.id')
        ->select('courses.id as ders_id', 'courses.ders_adi', 'users.name as ogretmen_adi')
        ->whereNotNull('courses.ogretmen_id')
        ->get();

    $atananOgrenciler = DB::table('ogrenci_ders')
        ->join('users', 'ogrenci_ders.ogrenci_id', '=', 'users.id')
        ->join('courses', 'ogrenci_ders.ders_id', '=', 'courses.id')
        ->select('ogrenci_ders.id', 'users.name as ogrenci_adi', 'courses.ders_adi')
        ->get();

    $atanmamisOgretmenler = DB::table('users')
        ->where('rol', 'ogretmen')
        ->whereNotIn('id', function ($query) {
            $query->select('ogretmen_id')
                ->from('courses')
                ->whereNotNull('ogretmen_id');
        })
        ->get();

    $atanmamisOgrenciler = DB::table('users')
        ->where('rol', 'ogrenci')
        ->whereNotIn('id', function ($query) {
            $query->select('ogrenci_id')
                ->from('ogrenci_ders');
        })
        ->get();

    return view('ders_atama', compact(
        'dersler',
        'ogretmenler',
        'ogrenciler',
        'atananOgretmenler',
        'atananOgrenciler',
        'atanmamisOgretmenler',
        'atanmamisOgrenciler'
    ));
});

Route::post('/ogretmen-ata', function (Request $request) {
    DB::table('courses')
        ->where('id', $request->ders_id)
        ->update([
            'ogretmen_id' => $request->ogretmen_id,
        ]);

    return redirect('/ders-atama');
});

Route::post('/ogrenciye-ders-ata', function (Request $request) {
    $varMi = DB::table('ogrenci_ders')
        ->where('ogrenci_id', $request->ogrenci_id)
        ->where('ders_id', $request->ders_id)
        ->first();

    if (!$varMi) {
        DB::table('ogrenci_ders')->insert([
            'ogrenci_id' => $request->ogrenci_id,
            'ders_id' => $request->ders_id,
        ]);
    }

    return redirect('/ders-atama');
});

Route::post('/qr-oturumu-baslat', function (Request $request) {
    if (!session()->has('user_id') || session('rol') != 'ogretmen') {
        return redirect('/');
    }

    $ders = DB::table('courses')
        ->where('id', $request->ders_id)
        ->where('ogretmen_id', session('user_id'))
        ->first();

    if (!$ders) {
        return redirect('/ogretmen')->with('error', 'Ders bulunamadi.');
    }

    DB::table('attendance_sessions')
        ->where('ders_id', $request->ders_id)
        ->where('ogretmen_id', session('user_id'))
        ->where('aktif', 1)
        ->update([
            'aktif' => 0,
            'kapanis_zamani' => now('Europe/Istanbul'),
            'updated_at' => now('Europe/Istanbul'),
        ]);

    DB::table('attendance_sessions')->insert([
        'ogretmen_id' => session('user_id'),
        'ders_id' => $request->ders_id,
        'token' => (string) Str::uuid(),
        'tarih' => $request->tarih,
        'aktif' => 1,
        'created_at' => now('Europe/Istanbul'),
        'updated_at' => now('Europe/Istanbul'),
    ]);

    return redirect('/ogretmen?ders_id=' . $request->ders_id . '&tarih=' . $request->tarih)
        ->with('success', 'QR yoklama oturumu baslatildi.');
});

Route::post('/qr-oturumu-kapat', function (Request $request) {
    if (!session()->has('user_id') || session('rol') != 'ogretmen') {
        return redirect('/');
    }

    $oturum = DB::table('attendance_sessions')
        ->where('id', $request->oturum_id)
        ->where('ogretmen_id', session('user_id'))
        ->where('aktif', 1)
        ->first();

    if (!$oturum) {
        return redirect('/ogretmen')->with('error', 'Aktif QR oturumu bulunamadi.');
    }

    DB::table('attendance_sessions')
        ->where('id', $oturum->id)
        ->update([
            'aktif' => 0,
            'kapanis_zamani' => now('Europe/Istanbul'),
            'updated_at' => now('Europe/Istanbul'),
        ]);

    $ogrenciIdleri = DB::table('ogrenci_ders')
        ->where('ders_id', $oturum->ders_id)
        ->pluck('ogrenci_id');

    foreach ($ogrenciIdleri as $ogrenciId) {
        $kayitVarMi = DB::table('attendance')
            ->where('ogrenci_id', $ogrenciId)
            ->where('ders_id', $oturum->ders_id)
            ->whereDate('tarih', $oturum->tarih)
            ->exists();

        if (!$kayitVarMi) {
            DB::table('attendance')->insert([
                'ogrenci_id' => $ogrenciId,
                'ders_id' => $oturum->ders_id,
                'tarih' => $oturum->tarih . ' 00:00:00',
                'durum' => 'yok',
            ]);
        }
    }

    return redirect('/ogretmen?ders_id=' . $oturum->ders_id . '&tarih=' . $oturum->tarih)
        ->with('success', 'QR yoklama kapatildi. Okutmayan ogrenciler yok yazildi.');
});

Route::get('/qr-yoklama/{token}', function ($token) {
    if (!session()->has('user_id') || session('rol') != 'ogrenci') {
        return redirect('/');
    }

    $oturum = DB::table('attendance_sessions')
        ->where('token', $token)
        ->where('aktif', 1)
        ->first();

    if (!$oturum) {
        return redirect('/ogrenci')->with('error', 'Bu QR oturumu aktif degil.');
    }

    $ogrenciDersteVarMi = DB::table('ogrenci_ders')
        ->where('ogrenci_id', session('user_id'))
        ->where('ders_id', $oturum->ders_id)
        ->exists();

    if (!$ogrenciDersteVarMi) {
        return redirect('/ogrenci')->with('error', 'Bu derse kayitli degilsin.');
    }

    $mevcutKayit = DB::table('attendance')
        ->where('ogrenci_id', session('user_id'))
        ->where('ders_id', $oturum->ders_id)
        ->whereDate('tarih', $oturum->tarih)
        ->first();

    if ($mevcutKayit) {
        DB::table('attendance')
            ->where('id', $mevcutKayit->id)
            ->update([
                'durum' => 'var',
                'tarih' => $oturum->tarih . ' 00:00:00',
            ]);
    } else {
        DB::table('attendance')->insert([
            'ogrenci_id' => session('user_id'),
            'ders_id' => $oturum->ders_id,
            'tarih' => $oturum->tarih . ' 00:00:00',
            'durum' => 'var',
        ]);
    }

    return redirect('/ogrenci')->with('success', 'QR okutuldu, yoklaman var olarak kaydedildi.');
});

Route::post('/yoklama-kaydet', function (Request $request) {
    if (!session()->has('user_id') || session('rol') != 'ogretmen') {
        return redirect('/');
    }

    $ders = DB::table('courses')
        ->where('id', $request->ders_id)
        ->where('ogretmen_id', session('user_id'))
        ->first();

    if (!$ders) {
        return redirect('/ogretmen');
    }

    $durumlar = $request->input('durumlar', []);

    foreach ($durumlar as $ogrenciId => $durum) {
        if (!in_array($durum, ['var', 'yok'])) {
            continue;
        }

        $ogrenciDersteVarMi = DB::table('ogrenci_ders')
            ->where('ogrenci_id', $ogrenciId)
            ->where('ders_id', $request->ders_id)
            ->exists();

        if (!$ogrenciDersteVarMi) {
            continue;
        }

        $mevcutKayit = DB::table('attendance')
            ->where('ogrenci_id', $ogrenciId)
            ->where('ders_id', $request->ders_id)
            ->whereDate('tarih', $request->tarih)
            ->first();

        if ($mevcutKayit) {
            DB::table('attendance')
                ->where('id', $mevcutKayit->id)
                ->update([
                    'durum' => $durum,
                    'tarih' => $request->tarih . ' 00:00:00',
                ]);
        } else {
            DB::table('attendance')->insert([
                'ogrenci_id' => $ogrenciId,
                'ders_id' => $request->ders_id,
                'tarih' => $request->tarih . ' 00:00:00',
                'durum' => $durum,
            ]);
        }
    }

    return redirect('/ogretmen?ders_id=' . $request->ders_id . '&tarih=' . $request->tarih);
});

Route::get('/ogrenci-dersten-cikar/{id}', function ($id) {
    DB::table('ogrenci_ders')->where('id', $id)->delete();
    return redirect('/ders-atama');
});

Route::get('/ogretmen-dersten-cikar/{ders_id}', function ($ders_id) {
    DB::table('courses')
        ->where('id', $ders_id)
        ->update(['ogretmen_id' => null]);

    return redirect('/ders-atama');
});
