<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Excellence</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            text-align: center;
            background: #f5f5f5;
            border: 12px solid #2c3e50;
        }
        .certificate-container {
            /* border: 12px solid #2c3e50; */
            padding: 60px;
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .certificate-title {
            font-size: 40px;
            color: #2c3e50;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-subtitle {
            font-size: 20px;
            color: #34495e;
            margin-bottom: 20px;
            font-style: italic;
        }
        .certificate-body {
            font-size: 18px;
            margin: 50px 0;
            color: #2c3e50;
            line-height: 1.8;
        }
        .certificate-name {
            font-size: 40px;
            font-weight: bold;
            margin: 40px 0;
            color: #2c3e50;
            text-decoration: underline;
        }
        .certificate-footer {
            margin-top: 100px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .signature, .date {
            font-size: 16px;
        }
        .signature {
            text-align: left;
        }
        .date {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-title">Sertifikat Penghargaan</div>
        <div class="certificate-subtitle">{{@$disaster->title}}</div>

        <div class="certificate-body">
            Diberikan kepada:
            <div class="certificate-name">{{ $user->name }}</div>
            Atas kontribusi luar biasa sebagai relawan dalam upaya pemulihan pasca-bencana di Indonesia.
        </div>

        <div class="certificate-footer">
            <div class="signature">
                _________________________<br>
                Aksi Bencana
            </div>

            <div class="date">
                Tanggal: {{ now()->format('d F Y') }}
            </div>
        </div>
    </div>
</body>
</html>