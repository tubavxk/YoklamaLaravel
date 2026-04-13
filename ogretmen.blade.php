<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ogretmen Paneli</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f8fafc;
            color: #1f2937;
        }

        .navbar {
            background: #1d4ed8;
            color: white;
            padding: 18px 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px 40px;
        }

        .hero {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.18);
            margin-bottom: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .card,
        .panel {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .card h3,
        .panel h3 {
            margin-top: 0;
            color: #1d4ed8;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 16px;
            margin-top: 12px;
        }

        .course-card {
            border: 1px solid #dbeafe;
            background: #f8fbff;
            border-radius: 12px;
            padding: 16px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        form.inline-filter {
            display: grid;
            grid-template-columns: 1.3fr 1fr auto;
            gap: 12px;
            align-items: end;
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #334155;
        }

        select,
        input[type="date"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            background: #1d4ed8;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #0f766e;
        }

        .btn-danger {
            background: #dc2626;
        }

        .empty {
            padding: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="navbar">Ogretmen Paneli</div>

    <div class="container">
        <div class="hero">
            <h1>Hos geldin, {{ $ogretmen->name }}</h1>
            <p>Derslerini goruntuleyebilir, tarih secerek ogrenciler icin yoklama girebilir ve daha once girilen kayitlari ayni gun icin guncelleyebilirsin.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Profil Bilgisi</h3>
                <p><strong>Ad Soyad:</strong> {{ $ogretmen->name }}</p>
                <p><strong>E-posta:</strong> {{ $ogretmen->email }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($ogretmen->rol) }}</p>
            </div>

            <div class="card">
                <h3>Ders Sayisi</h3>
                <p style="font-size: 34px; font-weight: bold; margin: 8px 0;">{{ $dersler->count() }}</p>
                <p>Uzerine atanmis toplam ders</p>
            </div>
        </div>

        <div class="panel" style="margin-bottom: 24px;">
            <h3>Derslerim</h3>

            @if($dersler->count() > 0)
                <div class="course-grid">
                    @foreach($dersler as $ders)
                        <div class="course-card">
                            <div class="badge">{{ $ders->ders_kodu }}</div>
                            <h4 style="margin: 0 0 10px;">{{ $ders->ders_adi }}</h4>
                            <p><strong>Gun:</strong> {{ $ders->gun }}</p>
                            <p><strong>Saat:</strong> {{ $ders->saat }}</p>
                            <p><strong>Ogrenci:</strong> {{ $ders->ogrenci_sayisi }}</p>
                            <p><strong>Yoklama Gun Sayisi:</strong> {{ $ders->yoklama_sayisi }}</p>
                            <p><strong>Devamsiz Ogrenci:</strong> {{ $ders->devamsiz_ogrenci_sayisi }}</p>
                            <a href="/ogretmen?ders_id={{ $ders->id }}&tarih={{ $seciliTarih }}" class="btn" style="margin-top: 10px;">Bu Dersi Sec</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty">Sana henuz ders atanmamis. Admin panelinden ders atandiginda burada gorunecek.</div>
            @endif
        </div>

        <div class="panel">
            <h3>Yoklama Gir</h3>

            <form method="GET" action="/ogretmen" class="inline-filter">
                <div>
                    <label>Ders Sec</label>
                    <select name="ders_id" required>
                        <option value="">Ders sec</option>
                        @foreach($dersler as $ders)
                            <option value="{{ $ders->id }}" {{ $seciliDers && $seciliDers->id == $ders->id ? 'selected' : '' }}>
                                {{ $ders->ders_adi }} ({{ $ders->ders_kodu }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Tarih</label>
                    <input type="date" name="tarih" value="{{ $seciliTarih }}" required>
                </div>

                <div>
                    <button type="submit" class="btn">Listele</button>
                </div>
            </form>

            @if($seciliDers)
                <p><strong>Secili Ders:</strong> {{ $seciliDers->ders_adi }} - {{ $seciliDers->gun }} / {{ $seciliDers->saat }}</p>

                @if($ogrenciler->count() > 0)
                    <form method="POST" action="/yoklama-kaydet">
                        @csrf
                        <input type="hidden" name="ders_id" value="{{ $seciliDers->id }}">
                        <input type="hidden" name="tarih" value="{{ $seciliTarih }}">

                        <table>
                            <tr>
                                <th>Ogrenci</th>
                                <th>E-posta</th>
                                <th>Durum</th>
                            </tr>

                            @foreach($ogrenciler as $ogrenci)
                                <tr>
                                    <td>{{ $ogrenci->name }}</td>
                                    <td>{{ $ogrenci->email }}</td>
                                    <td>
                                        <select name="durumlar[{{ $ogrenci->id }}]">
                                            <option value="var" {{ ($ogrenci->mevcut_durum ?? 'var') == 'var' ? 'selected' : '' }}>Var</option>
                                            <option value="yok" {{ ($ogrenci->mevcut_durum ?? '') == 'yok' ? 'selected' : '' }}>Yok</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </table>

                        <div class="actions">
                            <button type="submit" class="btn btn-secondary">Yoklamayi Kaydet</button>
                            <a href="/logout" class="btn btn-danger">Cikis Yap</a>
                        </div>
                    </form>
                @else
                    <div class="empty">Bu derse atanmis ogrenci bulunmuyor.</div>
                    <div class="actions">
                        <a href="/logout" class="btn btn-danger">Cikis Yap</a>
                    </div>
                @endif
            @else
                <div class="empty">Yoklama girmek icin once bir ders ve tarih sec.</div>
                <div class="actions">
                    <a href="/logout" class="btn btn-danger">Cikis Yap</a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>