<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN LOGIN | CTMS PRO V3</title>
    <style>
        /* --- General Styles --- */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #020617; /* Dark Background matching Admin */
            color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* --- Login Card --- */
        .login-card {
            background-color: #0f172a; /* Slightly lighter dark for card */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 350px;
            text-align: center;
            border: 1px solid #1e293b;
        }

        .login-card h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #3b82f6; /* Blue Accent */
            letter-spacing: 1px;
        }

        /* --- Form Elements --- */
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #94a3b8;
        }

        input {
            width: 100%;
            padding: 12px;
            background-color: #1e293b;
            border: 1px solid #334155;
            border-radius: 6px;
            color: white;
            font-size: 15px;
            box-sizing: border-box; /* Ensures padding doesn't affect width */
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #3b82f6;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background-color: #2563eb;
        }

        button:disabled {
            background-color: #475569;
            cursor: not-allowed;
        }

        /* --- Error Message --- */
        .error-msg {
            color: #ef4444;
            font-size: 14px;
            margin-top: 15px;
            display: none; /* Hidden by default */
            background: rgba(239, 68, 68, 0.1);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>ADMIN LOGIN</h2>
        
        <form onsubmit="doLogin(event, this)">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" id="loginBtn">LOGIN</button>
            
            <div class="error-msg" id="errorMsg">
                <i class="fa-solid fa-circle-exclamation"></i> Invalid Credentials!
            </div>
        </form>
    </div>

    <script>
    function doLogin(event, form) {
        event.preventDefault();

        const btn = document.getElementById('loginBtn');
        const err = document.getElementById('errorMsg');
        const originalText = btn.innerText;

        

        const formData = new FormData(form);

        // SEND REQUEST TO API
        fetch('api/loginvalidate.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = 'admin.php';
            } else {
                err.innerHTML = data.message || "Invalid Username or Password!";
                err.style.display = "block";
                
                btn.disabled = false;
                btn.innerText = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            err.innerText = "Connection Failed! Check Server.";
            err.style.display = "block";
            btn.disabled = false;
            btn.innerText = originalText;
        });
    }
    </script>

</body>
</html>