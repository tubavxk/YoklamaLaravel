<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ders Atama</title>
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
            max-width: 1220px;
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

        .forms-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 22px;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 22px;
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

        .warning-row {
            background: #fff7ed;
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
            .forms-grid,
            .tables-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">Ders Atama</div>
    <div class="container">
        <div class="hero">
            <h1>Ders Atama Merkezi</h1>
            <p>Ogretmen ve ogrencileri derslerle eslestir, mevcut atamalari takip et ve eksikleri tamamla.</p>
        </div>
        <div class="forms-grid">
            <div class="panel">
                <h2>Ogretmene Ders Ata</h2>
                <form method="POST" action="/ogretmen-ata">
                    @csrf
                    <div class="field">
                        <label>Ogretmen Sec</label>
                        <select name="ogretmen_id" required>
                            <option value="">Ogretmen sec</option>
                            @foreach($ogretmenler as $o)
                                <option value="{{ $o->id }}">{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Ders Sec</label>
                        <select name="ders_id" required>
                            <option value="">Ders sec</option>
                            @foreach($dersler as $d)
                                <option value="{{ $d->id }}">{{ $d->ders_adi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn">Atamayi Kaydet</button>
                </form>
            </div>
            <div class="panel">
                <h2>Ogrenciye Ders Ata</h2>
                <form method="POST" action="/ogrenciye-ders-ata">
                    @csrf
                    <div class="field">
                        <label>Ogrenci Sec</label>
                        <select name="ogrenci_id" required>
                            <option value="">Ogrenci sec</option>
                            @foreach($ogrenciler as $o)
                                <option value="{{ $o->id }}">{{ $o->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label>Ders Sec</label>
                        <select name="ders_id" required>
                            <option value="">Ders sec</option>
                            @foreach($dersler as $d)
                                <option value="{{ $d->id }}">{{ $d->ders_adi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn">Atamayi Kaydet</button>
                </form>
            </div>
        </div>
        <div class="tables-grid">
            <div class="panel">
                <h2>Atanan Ogretmenler</h2>
                <table>
                    <tr><th>Ders</th><th>Ogretmen</th><th>Islem</th></tr>
                    @forelse($atananOgretmenler as $a)
                    <tr>
                        <td>{{ $a->ders_adi }}</td>
                        <td>{{ $a->ogretmen_adi }}</td>
                        <td><a class="btn btn-danger" href="/ogretmen-dersten-cikar/{{ $a->ders_id }}" onclick="return confirm('Ogretmeni bu dersten cikarmak istedigine emin misin?')">Cikar</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="muted">Henuz ogretmen atamasi yok.</td></tr>
                    @endforelse
                </table>
            </div>
            <div class="panel">
                <h2>Atanan Ogrenciler</h2>
                <table>
                    <tr><th>Ogrenci</th><th>Ders</th><th>Islem</th></tr>
                    @forelse($atananOgrenciler as $a)
                    <tr>
                        <td>{{ $a->ogrenci_adi }}</td>
                        <td>{{ $a->ders_adi }}</td>
                        <td><a class="btn btn-danger" href="/ogrenci-dersten-cikar/{{ $a->id }}" onclick="return confirm('Ogrenciyi bu dersten cikarmak istedigine emin misin?')">Cikar</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="muted">Henuz ogrenci atamasi yok.</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
        <div class="panel">
            <h2>Uyari Listesi</h2>
            <table>
                <tr><th>Tur</th><th>Ad</th><th>Durum</th></tr>
                @forelse($atanmamisOgretmenler as $o)
                <tr class="warning-row"><td>Ogretmen</td><td>{{ $o->name }}</td><td>Ders atanmamis, atayin.</td></tr>
                @empty
                @endforelse
                @forelse($atanmamisOgrenciler as $o)
                <tr class="warning-row"><td>Ogrenci</td><td>{{ $o->name }}</td><td>Ders atanmamis, atayin.</td></tr>
                @empty
                @endforelse
                @if($atanmamisOgretmenler->count() == 0 && $atanmamisOgrenciler->count() == 0)
                <tr><td colspan="3" class="muted">Atanmamis ogretmen veya ogrenci yok.</td></tr>
                @endif
            </table>
            <div class="actions">
                <a href="/admin" class="btn btn-light">Admin Panele Don</a>
            </div>
        </div>
    </div>
</body>
</html>
