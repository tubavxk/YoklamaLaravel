<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ogrenci Paneli</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #1f2937;
        }

        .navbar {
            background: #0f766e;
            color: white;
            padding: 18px 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px 40px;
        }

        .hero {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
            margin-bottom: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        }

        .card h3,
        .table-card h3 {
            margin-top: 0;
            color: #0f766e;
        }

        .stat {
            font-size: 34px;
            font-weight: bold;
            margin: 8px 0;
        }

        .muted {
            color: #6b7280;
        }

        .table-card {
            background: white;
            padding: 22px;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
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
            background: #f0fdfa;
            color: #115e59;
        }

        .empty {
            padding: 20px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            color: #475569;
        }

        .btn {
            display: inline-block;
            margin-top: 16px;
            padding: 10px 16px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .btn-danger {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="navbar">Ogrenci Paneli</div>

    <div class="container">
        <div class="hero">
            <h1>Hos geldin, {{ $ogrenci->name }}</h1>
            <p>Bu ekranda sana atanan dersleri ve ders bilgilerini görebilirsin.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3>Profil Bilgisi</h3>
                <p><strong>Ad Soyad:</strong> {{ $ogrenci->name }}</p>
                <p><strong>E-posta:</strong> {{ $ogrenci->email }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($ogrenci->rol) }}</p>
            </div>

            <div class="card">
                <h3>Ders Sayisi</h3>
                <div class="stat">{{ $dersler->count() }}</div>
                <p class="muted">Uzerine tanimli toplam ders sayisi</p>
            </div>
        </div>

        <div class="table-card">
            <h3>Atanan Derslerim</h3>

            @if($dersler->count() > 0)
                <table>
                    <tr>
                        <th>Ders</th>
                        <th>Kod</th>
                        <th>Gun</th>
                        <th>Saat</th>
                        <th>Ogretmen</th>
                    </tr>

                    @foreach($dersler as $ders)
                        <tr>
                            <td>{{ $ders->ders_adi }}</td>
                            <td>{{ $ders->ders_kodu }}</td>
                            <td>{{ $ders->gun }}</td>
                            <td>{{ $ders->saat }}</td>
                            <td>{{ $ders->ogretmen_adi ?? 'Henuz ogretmen atanmadi' }}</td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="empty">
                    Sana henuz bir ders atanmamis. Admin panelinden ders atamasi yapildiginda burada gorunecek.
                </div>
            @endif

            <a href="/logout" class="btn btn-danger">Cikis Yap</a>
        </div>
    </div>
</body>
</html>