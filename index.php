<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MG Phone — Connexion Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      background: #0a0a0a;
      font-family: 'Jost', sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      background: #111;
      border: 1px solid rgba(106,90,205,0.3);
      border-radius: 20px;
      padding: 48px 40px;
      width: 100%;
      max-width: 420px;
      text-align: center;
    }
    .login-logo { height: 60px; margin-bottom: 24px; }
    .login-title {
      font-family: 'Cormorant Garamond', serif;
      font-size: 1.6rem;
      color: #f5f5f0;
      font-weight: 300;
      margin-bottom: 8px;
    }
    .login-sub { color: #666; font-size: 0.85rem; margin-bottom: 36px; }
    .form-group { text-align: left; margin-bottom: 18px; }
    label { display: block; font-size: 0.72rem; font-weight: 600; color: #6a5acd; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
    input {
      width: 100%; padding: 12px 16px;
      background: #1a1a1a;
      border: 1px solid rgba(255,255,255,0.1);
      border-radius: 8px;
      color: #f5f5f0;
      font-family: 'Jost', sans-serif;
      font-size: 0.95rem;
      transition: border-color 0.2s;
    }
    input:focus { outline: none; border-color: #6a5acd; }
    .btn-login {
      width: 100%;
      margin-top: 8px;
      padding: 14px;
      background: #6a5acd;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: 'Jost', sans-serif;
      cursor: pointer;
      letter-spacing: 1px;
      transition: background 0.2s;
    }
    .btn-login:hover { background: #4b0082; }
    .error-msg {
      display: none;
      background: #2d0000;
      border: 1px solid #ff4444;
      color: #ff8888;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 16px;
    }
    .back-link { display: block; margin-top: 20px; color: #444; font-size: 0.8rem; text-decoration: none; }
    .back-link:hover { color: #888; }
  </style>
</head>
<body>
<div class="login-box">
  <img src="../uploads/logos/logo.png" alt="MG Phone" class="login-logo"/>
  <div class="login-title">Espace Administration</div>
  <div class="login-sub">Connectez-vous pour accéder au dashboard</div>

  <div class="error-msg" id="error-msg">Identifiant ou mot de passe incorrect.</div>

  <div class="form-group">
    <label>Identifiant</label>
    <input type="text" id="username" placeholder="admin" autocomplete="username"/>
  </div>
  <div class="form-group">
    <label>Mot de passe</label>
    <input type="password" id="password" placeholder="••••••••" autocomplete="current-password"/>
  </div>
  <button class="btn-login" id="btn-login" onclick="login()">Se connecter</button>
  <a href="../index.html" class="back-link">← Retour au site</a>
</div>

<script>
  // Vérifier si déjà connecté
  fetch('../api/auth.php?action=check')
    .then(r => r.json())
    .then(d => { if (d.logged_in) window.location.href = 'dashboard.php'; });

  document.addEventListener('keydown', e => { if (e.key === 'Enter') login(); });

  async function login() {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value.trim();
    const btn = document.getElementById('btn-login');
    const err = document.getElementById('error-msg');

    if (!username || !password) { err.style.display = 'block'; err.textContent = 'Veuillez remplir tous les champs.'; return; }

    btn.textContent = 'Connexion…';
    btn.disabled = true;
    err.style.display = 'none';

    try {
      const res = await fetch('../api/auth.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password })
      });
      const data = await res.json();
      if (data.success) {
        window.location.href = 'dashboard.php';
      } else {
        err.style.display = 'block';
        err.textContent = data.error || 'Identifiants incorrects.';
        btn.textContent = 'Se connecter';
        btn.disabled = false;
      }
    } catch(e) {
      err.style.display = 'block';
      err.textContent = 'Erreur réseau. Réessayez.';
      btn.textContent = 'Se connecter';
      btn.disabled = false;
    }
  }
</script>
</body>
</html>
