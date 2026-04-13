<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanicilar</title>
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
            box-shadow: 0 8px 24px rgba(18, 59, 158, 0.18);
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
            box-shadow: 0 18px 40px rgba(37, 99, 235, 0.18);
        }
        .hero h1 {
            margin: 0 0 10px;
            font-size: 34px;
        }

        .hero p {
            margin: 0;
            opacity: 0.95;
            line-height: 1.6;
        }
        .layout {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 20px;
        }
        .panel {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 20px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
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

        .field input,
        .field select {
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
            vertical-align: top;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 13px;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: bold;
        }

        .role-admin {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .role-ogretmen {
            background: #dcfce7;
            color: #166534;
        }

        .role-ogrenci {
            background: #fef3c7;
            color: #92400e;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 18px;
        }

        .muted {
            color: #64748b;
        }

        @media (max-width: 980px) {
            .layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">Kullanicilar</div>
    <div class="container">
        <div class="hero">
            <h1>Kullanici Yonetimi</h1>
            <p>Ogrenci, ogretmen ve admin hesaplarini bu ekrandan kolayca yonetebilirsin.</p>
        </div>
        <div class="layout">
            <div class="panel">
                <h2>Yeni Kullanici Ekle</h2>
                <form method="POST" action="/kullanici-ekle">
                    @csrf
                    <div class="field">
                        <label>Ad Soyad</label>
                        <input type="text" name="ad" placeholder="Ad soyad" required>
                    </div>
                    <div class="field">
                        <label>E-posta</label>
                        <input type="email" name="email" placeholder="ornek@mail.com" required>
                    </div>
                    <div class="field">
                        <label>Sifre</label>
                        <input type="text" name="sifre" placeholder="Sifre" required>
                    </div>
                    <div class="field">
                        <label>Rol</label>
                        <select name="rol">
                            <option value="ogrenci">Ogrenci</option>
                            <option value="ogretmen">Ogretmen</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="btn">Kullanici Ekle</button>
                </form>
                <div class="actions">
                    <a href="/admin" class="btn btn-light">Admin Panele Don</a>
                </div>
            </div>
            <div class="panel">
                <h2>Kullanici Listesi</h2>
                <p class="muted">Toplam {{ $kullanicilar->count() }} kullanici listeleniyor.</p>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Islem</th>
                    </tr>
                    @foreach($kullanicilar as $k)
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td>{{ $k->name }}</td>
                        <td>{{ $k->email }}</td>
                        <td>
                            <span class="role-badge role-{{ $k->rol }}">{{ ucfirst($k->rol) }}</span>
                        </td>
                        <td>
                            <a class="btn btn-danger" href="/kullanici-sil/{{ $k->id }}" onclick="return confirm('Silmek istedigine emin misin?')">Sil</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</body>
</html>
