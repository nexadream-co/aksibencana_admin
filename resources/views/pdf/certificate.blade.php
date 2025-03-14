<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Excellence</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            text-align: center;
            padding: 50px;
            background: #f5f5f5;
        }
        .certificate-container {
            border: 12px solid #2c3e50;
            padding: 60px;
            max-width: 1000px;
            margin: auto;
            background: #ffffff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .certificate-title {
            font-size: 60px;
            color: #2c3e50;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .certificate-subtitle {
            font-size: 30px;
            color: #34495e;
            margin-bottom: 20px;
            font-style: italic;
        }
        .certificate-body {
            font-size: 26px;
            margin: 50px 0;
            color: #2c3e50;
            line-height: 1.8;
        }
        .certificate-name {
            font-size: 50px;
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
            font-size: 22px;
        }
        .signature {
            text-align: left;
        }
        .date {
            text-align: right;
        }
        .seal {
            width: 130px;
            height: 130px;
            border: 10px solid #2c3e50;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #2c3e50;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-title">Certificate of Excellence</div>
        <div class="certificate-subtitle">In Recognition of Exceptional Achievement</div>

        <div class="certificate-body">
            This is to certify that
            <div class="certificate-name">{{ $user->name }}</div>
            has demonstrated exemplary performance and dedication,
            achieving outstanding success in the respective field.
        </div>

        <div class="certificate-footer">
            <div class="signature">
                _________________________<br>
                Authorized Signatory
            </div>

            <div class="seal">Excellence</div>

            <div class="date">
                Date: {{ now()->format('d-m-Y') }}
            </div>
        </div>
    </div>
</body>
</html>