<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            background:
                radial-gradient(circle at top left, rgba(29, 78, 216, 0.12), transparent 24%),
                linear-gradient(180deg, #f9fbff 0%, #eef3fb 100%);
            color: #1f2937;
        }

        .navbar {
            background: #123b9e;
            color: white;
            padding: 18px 30px;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.3px;
            box-shadow: 0 10px 24px rgba(18, 59, 158, 0.18);
        }

        .container {
            max-width: 1220px;
            margin: 28px auto;
            padding: 0 20px 40px;
        }

        .hero {
            background: linear-gradient(135deg, #123b9e, #2563eb 55%, #60a5fa 100%);
            color: white;
            padding: 28px;
            border-radius: 24px;
            margin-bottom: 22px;
            box-shadow: 0 20px 42px rgba(37, 99, 235, 0.2);
        }

        .hero h1 {
            margin: 0 0 10px;
            font-size: 34px;
        }

        .hero p {
            margin: 0;
            max-width: 720px;
            line-height: 1.6;
            opacity: 0.96;
        }

        .hero-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .hero-pill {
            padding: 12px 14px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .hero-pill-label {
            font-size: 12px;
            opacity: 0.82;
            margin-bottom: 4px;
        }

        .hero-pill-value {
            font-size: 20px;
            font-weight: bold;
        }

        .card,
        .panel,
        .action-card,
        .mini-status {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.9);
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        }

        .card,
        .panel,
        .action-card {
            padding: 20px;
        }

        .card h3,
        .panel h3,
        .action-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #123b9e;
        }

        .stat {
            font-size: 34px;
            font-weight: bold;
            margin: 6px 0 8px;
        }

        .circle-stat {
            min-height: 208px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 28px;
            position: relative;
        }

        .circle-stat .number-bubble {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .shape-blue {
            background: linear-gradient(180deg, #ffffff 0%, #eff6ff 100%);
        }

        .shape-blue .number-bubble {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .shape-sand {
            background: linear-gradient(180deg, #fffdf7 0%, #fff7ed 100%);
            border-radius: 22px 50px 22px 22px;
        }

        .shape-sand .number-bubble {
            background: #fed7aa;
            color: #c2410c;
        }

        .shape-alert {
            background: linear-gradient(180deg, #fff1f2 0%, #ffffff 100%);
            border-radius: 50px 22px 22px 22px;
        }

        .shape-alert .number-bubble {
            background: #fecdd3;
            color: #be123c;
        }

        .muted {
            color: #64748b;
        }

        .summary-table {
            margin-bottom: 22px;
        }

        .summary-table-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 12px;
        }

        .summary-item {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #dbeafe;
            border-radius: 16px;
            padding: 16px;
        }

        .summary-title {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 30px;
            font-weight: bold;
            color: #123b9e;
            margin-bottom: 6px;
        }

        .summary-note {
            color: #64748b;
            font-size: 13px;
            line-height: 1.45;
        }

        .overview-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 18px;
            margin-bottom: 22px;
        }

        .mini-status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .mini-status {
            padding: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .mini-status-title {
            font-size: 13px;
            color: #475569;
        }

        .mini-status strong {
            display: block;
            margin-top: 6px;
            font-size: 24px;
        }

        .warning {
            color: #b91c1c;
        }

        .success {
            color: #047857;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 13px;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 16px;
        }

        .action-card {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .action-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 28px rgba(37, 99, 235, 0.12);
        }

        .action-card p {
            color: #4b5563;
            line-height: 1.6;
            min-height: 70px;
        }

        .action-card.circle-stat {
            min-height: 218px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .shape-blue {
            background: linear-gradient(180deg, #ffffff 0%, #eff6ff 100%);
        }

        .shape-blue .number-bubble {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .shape-sand {
            background: linear-gradient(180deg, #fffdf7 0%, #fff7ed 100%);
            border-radius: 22px 50px 22px 22px;
        }

        .shape-sand .number-bubble {
            background: #fed7aa;
            color: #c2410c;
        }

        .shape-alert {
            background: linear-gradient(180deg, #fff1f2 0%, #ffffff 100%);
            border-radius: 50px 22px 22px 22px;
        }

        .shape-alert .number-bubble {
            background: #fecdd3;
            color: #be123c;
        }

        .shape-teal {
            background: linear-gradient(180deg, #f0fdfa 0%, #ffffff 100%);
            border-radius: 22px 22px 50px 22px;
        }

        .shape-teal .number-bubble {
            background: #99f6e4;
            color: #0f766e;
        }

        .number-bubble {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: bold;
            margin-top: 10px;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #123b9e;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn {
            display: inline-block;
            margin-top: 12px;
            padding: 10px 16px;
            background: #123b9e;
            color: white;
            text-decoration: none;
            border-radius: 10px;
        }

        .btn-danger {
            background: #dc2626;
        }

        .empty {
            padding: 18px;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            color: #475569;
        }

        @media (max-width: 980px) {
            .overview-grid {
                grid-template-columns: 1fr;
            }

            .mini-status-grid {
                grid-template-columns: 1fr 1fr;
            }

            .summary-table-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .summary-table-grid {
                grid-template-columns: 1fr;
            }

            .mini-status-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">Admin Paneli</div>

    <div class="container">
        <div class="hero">
            <h1>Hos geldin Admin</h1>
            <p>Sistemin genel durumunu tek bakista gorebilir, kullanici ve ders yonetimini hizli kartlar uzerinden yapabilirsin.</p>
            <div class="hero-strip">
                <div class="hero-pill">
                    <div class="hero-pill-label">Toplam Kullanici</div>
                    <div class="hero-pill-value">{{ $istatistikler['toplam_kullanici'] }}</div>
                </div>
                <div class="hero-pill">
                    <div class="hero-pill-label">Toplam Ders</div>
                    <div class="hero-pill-value">{{ $istatistikler['toplam_ders'] }}</div>
                </div>
                <div class="hero-pill">
                    <div class="hero-pill-label">Toplam Atama</div>
                    <div class="hero-pill-value">{{ $istatistikler['toplam_atama'] }}</div>
                </div>
                <div class="hero-pill">
                    <div class="hero-pill-label">Aktif QR</div>
                    <div class="hero-pill-value">{{ $istatistikler['aktif_qr'] }}</div>
                </div>
            </div>
        </div>

        <div class="action-grid">
            <div class="action-card circle-stat shape-blue" style="grid-row: span 2;">
                <div>
                    <div class="badge">Yonetim</div>
                    <h3>Kullanicilar</h3>
                    <p>Ogrenci, ogretmen ve admin kullanicilarini yonet ve yeni hesaplar ekle.</p>
                    <a href="/kullanicilar" class="btn">Kullanicilari Gor</a>
                </div>
                <div class="number-bubble">U</div>
            </div>

            <div class="action-card circle-stat shape-sand">
                <div>
                    <div class="badge">Dersler</div>
                    <h3>Ders Yonetimi</h3>
                    <p>Dersleri olustur, program bilgilerini duzenle ve ders havuzunu kontrol et.</p>
                    <a href="/dersler" class="btn">Dersleri Gor</a>
                </div>
                <div class="number-bubble">D</div>
            </div>

            <div class="action-card circle-stat shape-alert">
                <div>
                    <div class="badge">Atama</div>
                    <h3>Ders Atama</h3>
                    <p>Ogretmen ve ogrencileri derslerle eslestir, eksik atamalari tamamla.</p>
                    <a href="/ders-atama" class="btn">Atama Yap</a>
                </div>
                <div class="number-bubble">A</div>
            </div>

            <div class="action-card circle-stat shape-teal">
                <div>
                    <div class="badge">Oturum</div>
                    <h3>Cikis</h3>
                    <p>Admin oturumunu guvenli sekilde sonlandir.</p>
                    <a href="/logout" class="btn btn-danger">Cikis Yap</a>
                </div>
                <div class="number-bubble">O</div>
            </div>
        </div>

        <div class="panel summary-table" style="margin-top: 22px;">
            <h3>Kullanici ve Ders Ozeti</h3>
            <div class="summary-table-grid">
                <div class="summary-item">
                    <div class="summary-title">Ogrenciler</div>
                    <div class="summary-value">{{ $istatistikler['toplam_ogrenci'] }}</div>
                    <div class="summary-note">Sistemde aktif gorunen toplam ogrenci sayisi</div>
                </div>

                <div class="summary-item">
                    <div class="summary-title">Ogretmenler</div>
                    <div class="summary-value">{{ $istatistikler['toplam_ogretmen'] }}</div>
                    <div class="summary-note">Ders yoneten ogretmenler</div>
                </div>

                <div class="summary-item">
                    <div class="summary-title">Adminler</div>
                    <div class="summary-value">{{ $istatistikler['toplam_admin'] }}</div>
                    <div class="summary-note">Panel yetkili kullanicilar</div>
                </div>

                <div class="summary-item">
                    <div class="summary-title">Ogretmensiz Ders</div>
                    <div class="summary-value">{{ $istatistikler['ogretmensiz_ders'] }}</div>
                    <div class="summary-note">Atama bekleyen dersler</div>
                </div>
            </div>
        </div>

        <div class="overview-grid" style="margin-top: 22px;">
            <div class="panel">
                <h3>Hizli Durum</h3>
                <div class="mini-status-grid">
                    <div class="mini-status">
                        <div class="mini-status-title">Derssiz Ogrenci</div>
                        <strong class="{{ $istatistikler['derssiz_ogrenci'] > 0 ? 'warning' : 'success' }}">{{ $istatistikler['derssiz_ogrenci'] }}</strong>
                    </div>
                    <div class="mini-status">
                        <div class="mini-status-title">Derssiz Ogretmen</div>
                        <strong class="{{ $istatistikler['derssiz_ogretmen'] > 0 ? 'warning' : 'success' }}">{{ $istatistikler['derssiz_ogretmen'] }}</strong>
                    </div>
                    <div class="mini-status">
                        <div class="mini-status-title">Aktif QR Oturumu</div>
                        <strong class="{{ $istatistikler['aktif_qr'] > 0 ? 'warning' : 'success' }}">{{ $istatistikler['aktif_qr'] }}</strong>
                    </div>
                    <div class="mini-status">
                        <div class="mini-status-title">Toplam Atama</div>
                        <strong>{{ $istatistikler['toplam_atama'] }}</strong>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h3>Son Eklenen Dersler</h3>
                @if($sonEklenenDersler->count() > 0)
                    <div class="table-wrap">
                        <table>
                            <tr>
                                <th>Ders</th>
                                <th>Kod</th>
                                <th>Program</th>
                            </tr>
                            @foreach($sonEklenenDersler as $ders)
                                <tr>
                                    <td>{{ $ders->ders_adi }}</td>
                                    <td>{{ $ders->ders_kodu }}</td>
                                    <td>{{ $ders->gun }} / {{ $ders->saat }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @else
                    <div class="empty">Henuz ders eklenmemis.</div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
