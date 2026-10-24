<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>เข้าสู่ระบบ | SOS อพาร์ตเมนต์</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            margin: 0;
            color: #2563eb;
            font-size: 32px;
        }

        .logo p {
            margin-top: 8px;
            color: #64748b;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: #334155;
            font-weight: 600;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font-size: 15px;
            outline: none;
        }

        input:focus {
            border-color: #2563eb;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 9px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">
            <h1>SOS</h1>
            <p>ระบบจัดการอพาร์ตเมนต์</p>
        </div>

        <form method="POST" action="/login">

            @csrf

            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="กรอกชื่อผู้ใช้"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="กรอกรหัสผ่าน"
                    required
                >
            </div>

            <button type="submit">
                เข้าสู่ระบบ
            </button>

        </form>

    </div>

</div>

</body>
</html>