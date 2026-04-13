<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dersler</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background: linear-gradient(180deg, #f8fbff 0%, #eef4ff 100%);
            color: #1f2937;
        }

        .navbar {
            background: #123b9e;
            color: white;
            padding: 18px 30px;
            font-size: 24px;
            font-weight: bold;
            box-shadow: 0 8px 24px rgba(18,59,158,0.18);
        }

        .container {
            max-width: 1180px;
            margin: 28px auto;
            padding: 0 20px 40px;
        }

        .hero {
            background: linear-gradient(135deg, #123b9e, #2563eb);
            color: white;
            padding: 28px;
            border-radius: 22px;
            margin-bottom: 22px;
            box-shadow: 0 18px 40px rgba(37,99,235,0.18);
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: 34px;
        }

        .hero p {
            margin: 0;
            line-height: 1.6;
        }

        .layout {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 20px;
        }

        .panel {
            background: rgba(255,255,255,0.95);
            border: 1px solid rgba(226,232,240,0.9);
            border-radius: 20px;
            box-shadow: 0 10px 24px rgba(15,23,42,0.06);
            padding: 22px;
        }

        .panel h2 {
            margin-top: 0;
            color: #123b9e;
        }

        .field {
            margin-bottom: 14px;
        }

        .field label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        .field input {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
        }

        .btn {
            display: inline-block;
            border: none;
            padding: 12px 16px;
            border-radius: 12px;
            background: #123b9e;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn-light {
            background: #e2e8f0;
            color: #1f2937;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            text-align: left;
            padding: 14px 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 13px;
        }

        .muted {
            color: #64748b;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">Dersler</div>
    <div class="container">
        <div class="hero">
            <h1>Ders Yonetimi</h1>
            <p>Dersleri ekle, program bilgilerini duzenle ve mevcut ders listesini yonet.</p>
        </div>
        <div class="layout">
            <div class="panel">
                <h2>Yeni Ders Ekle</h2>
                <form method="POST" action="/ders-ekle">
                    @csrf
                    <div class="field"><label>Ders Adi</label><input type="text" name="ders_adi" placeholder="Matematik" required></div>
                    <div class="field"><label>Ders Kodu</label><input type="text" name="ders_kodu" placeholder="MAT101" required></div>
                    <div class="field"><label>Gun</label><input type="text" name="gun" placeholder="Pazartesi" required></div>
                    <div class="field"><label>Saat</label><input type="text" name="saat" placeholder="09:00" required></div>
                    <button type="submit" class="btn">Ders Ekle</button>
                </form>
                <div class="actions">
                    <a href="/admin" class="btn btn-light">Admin Panele Don</a>
                </div>
            </div>
            <div class="panel">
                <h2>Ders Listesi</h2>
                <p class="muted">Toplam {{ $dersler->count() }} ders listeleniyor.</p>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Ders Adi</th>
                        <th>Kod</th>
                        <th>Gun</th>
                        <th>Saat</th>
                        <th>Islem</th>
                    </tr>
                    @foreach($dersler as $d)
                    <tr>
                        <td>{{ $d->id }}</td>
                        <td>{{ $d->ders_adi }}</td>
                        <td>{{ $d->ders_kodu }}</td>
                        <td>{{ $d->gun }}</td>
                        <td>{{ $d->saat }}</td>
                        <td><a class="btn btn-danger" href="/ders-sil/{{ $d->id }}" onclick="return confirm('Bu dersi silmek istedigine emin misin?')">Sil</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</body>
</html>
