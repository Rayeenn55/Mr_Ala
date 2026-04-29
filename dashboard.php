<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MG Phone — Dashboard Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600;700&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <style>
    :root {
      --purple: #6a5acd;
      --purple-dark: #4b0082;
      --pink: #ff69b4;
      --gold: #c9a96e;
      --bg: #0a0a0a;
      --card: #111111;
      --card2: #1a1a1a;
      --border: rgba(255,255,255,0.08);
      --text: #f5f5f0;
      --gray: #888;
      --success: #2ecc71;
      --warning: #f39c12;
      --danger: #e74c3c;
    }
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Jost',sans-serif; background:var(--bg); color:var(--text); min-height:100vh; }
    .sidebar { position:fixed; left:0; top:0; bottom:0; width:240px; background:var(--card); border-right:1px solid var(--border); display:flex; flex-direction:column; z-index:100; }
    .sidebar-logo { padding:24px 20px; border-bottom:1px solid var(--border); }
    .sidebar-logo img { height:40px; }
    .sidebar-logo .admin-label { font-size:0.65rem; color:var(--gray); text-transform:uppercase; letter-spacing:2px; margin-top:6px; }
    .sidebar-nav { flex:1; padding:16px 0; }
    .nav-item { display:flex; align-items:center; gap:12px; padding:12px 20px; color:var(--gray); cursor:pointer; font-size:0.88rem; transition:all 0.2s; border-left:3px solid transparent; }
    .nav-item:hover { background:rgba(106,90,205,0.1); color:var(--text); }
    .nav-item.active { background:rgba(106,90,205,0.15); color:var(--purple); border-left-color:var(--purple); }
    .nav-item .icon { font-size:1.1rem; width:20px; }
    .nav-badge { background:var(--pink); color:#fff; border-radius:10px; padding:2px 7px; font-size:0.65rem; font-weight:700; margin-left:auto; display:none; }
    .sidebar-footer { padding:16px 20px; border-top:1px solid var(--border); }
    .btn-logout { width:100%; padding:10px; background:rgba(231,76,60,0.15); color:#e74c3c; border:1px solid rgba(231,76,60,0.3); border-radius:8px; cursor:pointer; font-family:inherit; font-size:0.85rem; }
    .btn-logout:hover { background:rgba(231,76,60,0.25); }
    .main { margin-left:240px; padding:32px; min-height:100vh; }
    .page { display:none; }
    .page.active { display:block; }
    .page-title { font-family:'Cormorant Garamond',serif; font-size:2rem; font-weight:300; margin-bottom:8px; }
    .page-sub { color:var(--gray); font-size:0.85rem; margin-bottom:32px; }
    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:32px; }
    .stat-card { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:20px; }
    .stat-card .s-label { font-size:0.7rem; color:var(--gray); text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; }
    .stat-card .s-num { font-family:'Cormorant Garamond',serif; font-size:2.2rem; font-weight:700; }
    .stat-card .s-sub { font-size:0.75rem; color:var(--gray); margin-top:4px; }
    .s-purple { color:var(--purple); } .s-pink { color:var(--pink); } .s-gold { color:var(--gold); } .s-green { color:var(--success); }
    .alerts-box { background:rgba(243,156,18,0.08); border:1px solid rgba(243,156,18,0.3); border-radius:12px; padding:16px 20px; margin-bottom:24px; display:none; }
    .alerts-box .alert-title { color:var(--warning); font-weight:600; font-size:0.85rem; margin-bottom:10px; }
    .alert-item { font-size:0.82rem; color:#ddd; padding:4px 0; border-bottom:1px solid rgba(255,255,255,0.05); }
    .alert-item:last-child { border-bottom:none; }
    .panel { background:var(--card); border:1px solid var(--border); border-radius:12px; padding:24px; margin-bottom:24px; }
    .panel-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border); }
    .panel-title { font-size:1rem; font-weight:600; color:var(--text); }
    table { width:100%; border-collapse:collapse; font-size:0.85rem; }
    th { background:rgba(106,90,205,0.1); color:var(--purple); font-weight:600; text-align:left; padding:10px 14px; font-size:0.75rem; text-transform:uppercase; letter-spacing:1px; }
    td { padding:12px 14px; border-bottom:1px solid var(--border); color:#ccc; }
    tr:last-child td { border-bottom:none; }
    tr:hover td { background:rgba(255,255,255,0.02); }
    .statut { display:inline-block; padding:3px 12px; border-radius:20px; font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; }
    .statut-en_attente { background:rgba(243,156,18,0.15); color:#f39c12; }
    .statut-confirmee  { background:rgba(106,90,205,0.15); color:#9b8ee8; }
    .statut-livree     { background:rgba(46,204,113,0.15); color:#2ecc71; }
    .statut-annulee    { background:rgba(231,76,60,0.15); color:#e74c3c; }
    .btn { padding:7px 14px; border:none; border-radius:6px; cursor:pointer; font-size:0.78rem; font-weight:600; font-family:inherit; transition:all 0.2s; }
    .btn-primary { background:var(--purple); color:#fff; }
    .btn-primary:hover { background:var(--purple-dark); }
    .btn-warning { background:rgba(243,156,18,0.15); color:#f39c12; border:1px solid rgba(243,156,18,0.3); }
    .btn-danger  { background:rgba(231,76,60,0.15); color:#e74c3c; border:1px solid rgba(231,76,60,0.3); }
    .btn-sm { padding:5px 10px; font-size:0.72rem; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .form-full { grid-column:1/-1; }
    .f-label { display:block; font-size:0.7rem; font-weight:600; color:var(--purple); text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
    .f-input, .f-select { width:100%; padding:10px 12px; background:var(--card2); border:1px solid var(--border); border-radius:8px; color:var(--text); font-family:inherit; font-size:0.88rem; }
    .f-input:focus, .f-select:focus { outline:none; border-color:var(--purple); }
    .f-select option { background:#1a1a1a; }
    #toast { position:fixed; top:24px; right:24px; z-index:9999; background:var(--card); border:1px solid var(--purple); border-radius:12px; padding:16px 20px; max-width:320px; display:none; }
    #toast .t-title { font-weight:600; color:var(--purple); margin-bottom:4px; font-size:0.88rem; }
    #toast .t-body { font-size:0.8rem; color:var(--gray); }
    .stock-ok { color:var(--success); } .stock-alerte { color:var(--warning); } .stock-rupture { color:var(--danger); }
    .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:500; align-items:center; justify-content:center; }
    .modal-overlay.open { display:flex; }
    .modal-box { background:var(--card); border:1px solid var(--border); border-radius:16px; padding:32px; width:90%; max-width:560px; max-height:80vh; overflow-y:auto; }
    .modal-title { font-family:'Cormorant Garamond',serif; font-size:1.5rem; margin-bottom:20px; color:var(--text); }
    .modal-close { float:right; background:none; border:none; color:var(--gray); font-size:1.4rem; cursor:pointer; }
  </style>
</head>
<body>

<div id="toast">
  <div class="t-title" id="toast-title">🔔 Nouvelle commande !</div>
  <div class="t-body" id="toast-body"></div>
</div>

<aside class="sidebar">
  <div class="sidebar-logo">
    <img src="../uploads/logos/logo.png" alt="MG Phone"/>
    <div class="admin-label">Administration</div>
  </div>
  <nav class="sidebar-nav">
    <div class="nav-item active" onclick="showPage('dashboard')" id="nav-dashboard"><span class="icon">📊</span> <span>Dashboard</span></div>
    <div class="nav-item" onclick="showPage('commandes')" id="nav-commandes"><span class="icon">🛍️</span> <span>Commandes</span><span class="nav-badge" id="badge-commandes">0</span></div>
    <div class="nav-item" onclick="showPage('produits')" id="nav-produits"><span class="icon">📱</span> <span>Produits</span></div>
    <div class="nav-item" onclick="showPage('accessoires')" id="nav-accessoires"><span class="icon">🎧</span> <span>Accessoires</span></div>
    <div class="nav-item" onclick="showPage('ajouter')" id="nav-ajouter"><span class="icon">➕</span> <span>Ajouter produit</span></div>
  </nav>
  <div class="sidebar-footer">
    <button class="btn-logout" onclick="logout()">🚪 <span>Déconnexion</span></button>
  </div>
</aside>

<main class="main">

  <!-- DASHBOARD -->
  <div class="page active" id="page-dashboard">
    <div class="page-title">Tableau de bord</div>
    <div class="page-sub" id="date-now"></div>
    <div class="stats-grid">
      <div class="stat-card"><div class="s-label">Commandes totales</div><div class="s-num s-purple" id="stat-total">—</div><div class="s-sub">depuis le début</div></div>
      <div class="stat-card"><div class="s-label">Nouvelles commandes</div><div class="s-num s-pink" id="stat-nouvelles">—</div><div class="s-sub">non traitées</div></div>
      <div class="stat-card"><div class="s-label">Produits actifs</div><div class="s-num s-gold" id="stat-produits">—</div><div class="s-sub">en catalogue</div></div>
      <div class="stat-card"><div class="s-label">Alertes stock</div><div class="s-num s-green" id="stat-alertes">—</div><div class="s-sub">à réapprovisionner</div></div>
    </div>
    <div class="alerts-box" id="alerts-box"><div class="alert-title">⚠️ Alertes de stock</div><div id="alerts-list"></div></div>
    <div class="panel">
      <div class="panel-header"><div class="panel-title">🛍️ Dernières commandes</div><button class="btn btn-primary btn-sm" onclick="showPage('commandes')">Voir tout →</button></div>
      <table><thead><tr><th>#</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th><th>Action</th></tr></thead>
      <tbody id="dash-commandes"><tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Chargement…</td></tr></tbody></table>
    </div>
  </div>

  <!-- COMMANDES -->
  <div class="page" id="page-commandes">
    <div class="page-title">Commandes</div>
    <div class="page-sub">Gérez et suivez toutes les commandes clients</div>
    <div class="panel">
      <table><thead><tr><th>#</th><th>Client</th><th>Téléphone</th><th>Total</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody id="all-commandes"><tr><td colspan="7" style="text-align:center;color:var(--gray);padding:20px;">Chargement…</td></tr></tbody></table>
    </div>
  </div>

  <!-- PRODUITS -->
  <div class="page" id="page-produits">
    <div class="page-title">Catalogue produits</div>
    <div class="page-sub">Gérez votre catalogue et les stocks</div>
    <div class="panel">
      <table><thead><tr><th>Produit</th><th>Prix</th><th>Type</th><th>Stock</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody id="all-produits"><tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Chargement…</td></tr></tbody></table>
    </div>
  </div>

  <!-- ACCESSOIRES -->
  <div class="page" id="page-accessoires">
    <div class="page-title">Accessoires</div>
    <div class="page-sub">Gérez vos accessoires (coques, chargeurs, écouteurs…)</div>
    <div class="panel">
      <div class="panel-header">
        <div class="panel-title">🎧 Liste des accessoires</div>
        <button class="btn btn-primary btn-sm" onclick="showPage('ajouter'); document.getElementById('f-type').value='accessoire'">➕ Ajouter un accessoire</button>
      </div>
      <table><thead><tr><th>Photo</th><th>Produit</th><th>Prix</th><th>Stock</th><th>Statut</th><th>Actions</th></tr></thead>
      <tbody id="all-accessoires"><tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Chargement…</td></tr></tbody></table>
    </div>
  </div>

  <!-- AJOUTER -->
  <div class="page" id="page-ajouter">
    <div class="page-title">Ajouter un produit</div>
    <div class="page-sub">Ajoutez un nouveau produit ou accessoire au catalogue</div>
    <div class="panel" style="max-width:680px;">
      <div id="form-alert" style="display:none;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:0.85rem;"></div>
      <div class="form-grid">
        <div><label class="f-label">Marque *</label><input id="f-marque" class="f-input" placeholder="Apple, Samsung…"/></div>
        <div><label class="f-label">Nom du modèle *</label><input id="f-nom" class="f-input" placeholder="iPhone 16 Pro"/></div>
        <div class="form-full"><label class="f-label">Spécifications</label><input id="f-specs" class="f-input" placeholder="256 Go · Noir · 5G"/></div>
        <div><label class="f-label">Prix (DT) *</label><input id="f-prix" type="number" class="f-input" placeholder="2490"/></div>
        <div><label class="f-label">Ancien prix</label><input id="f-prix-ancien" type="number" class="f-input" placeholder="3200"/></div>
        <div><label class="f-label">Type *</label>
          <select id="f-type" class="f-select">
            <option value="neuf">Neuf</option>
            <option value="occasion">Occasion</option>
            <option value="accessoire">Accessoire</option>
          </select>
        </div>
        <div><label class="f-label">Stock *</label><input id="f-stock" type="number" class="f-input" placeholder="10" value="10"/></div>
        <div><label class="f-label">Badge</label><input id="f-badge" class="f-input" placeholder="Nouveau, Promo…"/></div>
        <div><label class="f-label">Emoji</label><input id="f-emoji" class="f-input" placeholder="📱" maxlength="5" value="📱"/></div>
        <div><label class="f-label">État (occasion)</label><input id="f-etat" class="f-input" placeholder="Très bon état"/></div>
        <div><label class="f-label">Batterie % (occasion)</label><input id="f-batterie" type="number" class="f-input" placeholder="92"/></div>
        <div><label class="f-label">Garantie</label><input id="f-garantie" class="f-input" placeholder="3 mois"/></div>
        <div class="form-full">
          <label class="f-label">Image du produit</label>
          <div id="f-upload-zone" onclick="document.getElementById('f-image-input').click()"
            style="border:2px dashed rgba(106,90,205,0.4);border-radius:10px;padding:24px;text-align:center;cursor:pointer;background:rgba(106,90,205,0.04);"
            onmouseover="this.style.borderColor='#6a5acd'" onmouseout="this.style.borderColor='rgba(106,90,205,0.4)'">
            <div id="f-upload-preview" style="display:none;margin-bottom:12px;"><img id="f-preview-img" style="max-height:140px;border-radius:8px;object-fit:contain;"/></div>
            <div id="f-upload-placeholder">
              <div style="font-size:2rem;margin-bottom:8px;">📷</div>
              <div style="color:var(--gray);font-size:0.85rem;">Cliquez pour choisir une image</div>
              <div style="color:var(--gray);font-size:0.75rem;margin-top:4px;">JPG, PNG, WebP — max 5MB</div>
            </div>
          </div>
          <input type="file" id="f-image-input" accept="image/*" style="display:none" onchange="previewImage('f')"/>
          <input type="hidden" id="f-image-url"/>
        </div>
      </div>
      <button class="btn btn-primary" style="margin-top:20px;padding:12px 32px;" onclick="ajouterProduit()">➕ Ajouter le produit</button>
    </div>
  </div>

</main>

<!-- Modal commande -->
<div class="modal-overlay" id="modal-commande">
  <div class="modal-box">
    <button class="modal-close" onclick="fermerModal()">✕</button>
    <div class="modal-title">Détail de la commande</div>
    <div id="modal-content">Chargement…</div>
  </div>
</div>

<!-- Modal produit -->
<div class="modal-overlay" id="modal-produit">
  <div class="modal-box">
    <button class="modal-close" onclick="fermerModalProduit()">✕</button>
    <div class="modal-title">Modifier le produit</div>
    <div class="form-grid">
      <input type="hidden" id="edit-id"/>
      <div><label class="f-label">Marque</label><input id="edit-marque" class="f-input"/></div>
      <div><label class="f-label">Nom</label><input id="edit-nom" class="f-input"/></div>
      <div class="form-full"><label class="f-label">Spécifications</label><input id="edit-specs" class="f-input"/></div>
      <div><label class="f-label">Prix (DT)</label><input id="edit-prix" type="number" class="f-input"/></div>
      <div><label class="f-label">Ancien prix</label><input id="edit-prix-ancien" type="number" class="f-input"/></div>
      <div><label class="f-label">Stock</label><input id="edit-stock" type="number" class="f-input"/></div>
      <div><label class="f-label">Type</label>
        <select id="edit-type" class="f-select">
          <option value="neuf">Neuf</option>
          <option value="occasion">Occasion</option>
          <option value="accessoire">Accessoire</option>
        </select>
      </div>
      <div><label class="f-label">Badge</label><input id="edit-badge" class="f-input"/></div>
      <div><label class="f-label">Emoji</label><input id="edit-emoji" class="f-input"/></div>
      <div><label class="f-label">État (occasion)</label><input id="edit-etat" class="f-input"/></div>
      <div><label class="f-label">Batterie %</label><input id="edit-batterie" type="number" class="f-input"/></div>
      <div><label class="f-label">Garantie</label><input id="edit-garantie" class="f-input"/></div>
      <div class="form-full">
        <label class="f-label">Image du produit</label>
        <div id="edit-upload-zone" onclick="document.getElementById('edit-image-input').click()"
          style="border:2px dashed rgba(106,90,205,0.4);border-radius:10px;padding:20px;text-align:center;cursor:pointer;background:rgba(106,90,205,0.04);"
          onmouseover="this.style.borderColor='#6a5acd'" onmouseout="this.style.borderColor='rgba(106,90,205,0.4)'">
          <div id="edit-upload-preview" style="display:none;margin-bottom:8px;"><img id="edit-preview-img" style="max-height:120px;border-radius:8px;object-fit:contain;"/></div>
          <div id="edit-upload-placeholder">
            <div style="font-size:1.5rem;margin-bottom:6px;">📷</div>
            <div style="color:var(--gray);font-size:0.82rem;">Cliquez pour changer l'image</div>
          </div>
        </div>
        <input type="file" id="edit-image-input" accept="image/*" style="display:none" onchange="previewImage('edit')"/>
        <input type="hidden" id="edit-image-url"/>
      </div>
    </div>
    <button class="btn btn-primary" style="margin-top:20px;padding:12px 32px;" onclick="sauvegarderProduit()">💾 Sauvegarder</button>
  </div>
</div>

<script>
  // Verification session
  fetch('../api/auth.php?action=check').then(r => r.json()).then(d => { if (!d.logged_in) window.location.href = 'index.php'; });

  // Date
  document.getElementById('date-now').textContent = new Intl.DateTimeFormat('fr-FR', {weekday:'long', year:'numeric', month:'long', day:'numeric'}).format(new Date());

  // Navigation
  function showPage(name) {
    document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
    document.getElementById('page-' + name).classList.add('active');
    document.getElementById('nav-' + name).classList.add('active');
    if (name === 'commandes')   chargerCommandes();
    if (name === 'produits')    chargerProduits();
    if (name === 'accessoires') chargerAccessoires();
  }

  // Logout
  async function logout() {
    await fetch('../api/auth.php?action=logout');
    window.location.href = 'index.php';
  }

  // Toast
  function showToast(title, body) {
    document.getElementById('toast-title').textContent = title;
    document.getElementById('toast-body').textContent  = body;
    const toast = document.getElementById('toast');
    toast.style.display = 'block';
    setTimeout(function() { toast.style.display = 'none'; }, 5000);
  }

  // Notifications
  let lastCommandeCount = 0;
  async function checkNotifications() {
    try {
      const res  = await fetch('../api/notifications.php');
      const data = await res.json();
      const badge = document.getElementById('badge-commandes');
      badge.textContent = data.nouvelles_commandes;
      badge.style.display = data.nouvelles_commandes > 0 ? 'inline' : 'none';
      const statNouvelles = document.getElementById('stat-nouvelles');
      if (statNouvelles) statNouvelles.textContent = data.nouvelles_commandes;
      if (data.nouvelles_commandes > lastCommandeCount && lastCommandeCount > 0) {
        showToast('Nouvelle commande !', 'Une nouvelle commande vient d\'etre passee.');
      }
      lastCommandeCount = data.nouvelles_commandes;
      const alertesBox   = document.getElementById('alerts-box');
      const alertesList  = document.getElementById('alerts-list');
      const statAlertes  = document.getElementById('stat-alertes');
      const totalAlertes = data.alertes_stock.length + data.ruptures.length;
      if (statAlertes) statAlertes.textContent = totalAlertes;
      if (totalAlertes > 0) {
        alertesBox.style.display = 'block';
        alertesList.innerHTML = [
          ...data.ruptures.map(function(p) { return '<div class="alert-item">Rupture : <strong>' + p.marque + ' ' + p.nom + '</strong> (0 en stock)</div>'; }),
          ...data.alertes_stock.map(function(p) { return '<div class="alert-item">Alerte : <strong>' + p.marque + ' ' + p.nom + '</strong> — ' + p.stock + ' unites restantes</div>'; })
        ].join('');
      } else {
        alertesBox.style.display = 'none';
      }
    } catch(e) {}
  }

  // Dashboard
  async function chargerDashboard() {
    try {
      const [commandes, produits] = await Promise.all([
        fetch('../api/commandes.php').then(r => r.json()),
        fetch('../api/produits.php').then(r => r.json())
      ]);
      document.getElementById('stat-total').textContent    = commandes.length;
      document.getElementById('stat-produits').textContent = produits.length;
      const tbody  = document.getElementById('dash-commandes');
      const recent = commandes.slice(0, 6);
      if (!recent.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Aucune commande</td></tr>';
        return;
      }
      tbody.innerHTML = recent.map(function(c) {
        return '<tr>' +
          '<td>#' + c.id + '</td>' +
          '<td>' + c.prenom + ' ' + c.nom + (!c.vu ? ' <span style="background:var(--pink);color:#fff;border-radius:4px;padding:1px 6px;font-size:0.65rem;">Nouveau</span>' : '') + '</td>' +
          '<td style="color:var(--gold);font-weight:600;">' + parseFloat(c.total).toLocaleString('fr-TN') + ' DT</td>' +
          '<td><span class="statut statut-' + c.statut + '">' + statutLabel(c.statut) + '</span></td>' +
          '<td style="color:var(--gray);font-size:0.8rem;">' + new Date(c.created_at).toLocaleDateString('fr-FR') + '</td>' +
          '<td><button class="btn btn-primary btn-sm" onclick="voirCommande(' + c.id + ')">Voir</button></td>' +
        '</tr>';
      }).join('');
    } catch(e) {}
  }

  // Commandes
  async function chargerCommandes() {
    try {
      const commandes = await fetch('../api/commandes.php').then(r => r.json());
      const tbody = document.getElementById('all-commandes');
      if (!commandes.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:var(--gray);padding:20px;">Aucune commande</td></tr>';
        return;
      }
      tbody.innerHTML = commandes.map(function(c) {
        const opts = ['en_attente','confirmee','livree','annulee'].map(function(s) {
          return '<option value="' + s + '"' + (c.statut === s ? ' selected' : '') + '>' + statutLabel(s) + '</option>';
        }).join('');
        return '<tr>' +
          '<td>#' + c.id + '</td>' +
          '<td>' + c.prenom + ' ' + c.nom + (!c.vu ? ' <span style="background:var(--pink);color:#fff;border-radius:4px;padding:1px 6px;font-size:0.65rem;">Nouveau</span>' : '') + '</td>' +
          '<td>' + c.telephone + '</td>' +
          '<td style="color:var(--gold);font-weight:600;">' + parseFloat(c.total).toLocaleString('fr-TN') + ' DT</td>' +
          '<td><select onchange="changerStatut(' + c.id + ', this.value)" style="background:var(--card2);border:1px solid var(--border);color:var(--text);padding:5px 8px;border-radius:6px;font-family:inherit;font-size:0.78rem;">' + opts + '</select></td>' +
          '<td style="color:var(--gray);font-size:0.8rem;">' + new Date(c.created_at).toLocaleDateString('fr-FR') + '</td>' +
          '<td><button class="btn btn-primary btn-sm" onclick="voirCommande(' + c.id + ')">Detail</button></td>' +
        '</tr>';
      }).join('');
    } catch(e) {}
  }

  async function voirCommande(id) {
    document.getElementById('modal-content').innerHTML = '<div style="text-align:center;padding:20px;color:var(--gray);">Chargement…</div>';
    document.getElementById('modal-commande').classList.add('open');
    try {
      const c = await fetch('../api/commandes.php?id=' + id).then(r => r.json());
      await fetch('../api/commandes.php', {method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id:id, vu:1})});
      const items = (c.items || []).map(function(i) {
        return '<div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);font-size:0.85rem;">' +
          '<span>' + i.nom_produit + '</span>' +
          '<span style="color:var(--gray);">x' + i.quantite + '</span>' +
          '<span style="color:var(--gold);">' + (i.prix_unit * i.quantite).toLocaleString('fr-TN') + ' DT</span>' +
        '</div>';
      }).join('');
      const btns = ['en_attente','confirmee','livree','annulee'].map(function(s) {
        return '<button class="btn ' + (c.statut === s ? 'btn-primary' : 'btn-warning') + ' btn-sm" onclick="changerStatut(' + c.id + ',\'' + s + '\');fermerModal();">' + statutLabel(s) + '</button>';
      }).join('');
      document.getElementById('modal-content').innerHTML =
        '<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">' +
          '<div><div style="color:var(--gray);font-size:0.72rem;text-transform:uppercase;margin-bottom:4px;">Client</div><div>' + c.prenom + ' ' + c.nom + '</div></div>' +
          '<div><div style="color:var(--gray);font-size:0.72rem;text-transform:uppercase;margin-bottom:4px;">Telephone</div><div>' + c.telephone + '</div></div>' +
          '<div style="grid-column:1/-1"><div style="color:var(--gray);font-size:0.72rem;text-transform:uppercase;margin-bottom:4px;">Adresse</div><div>' + c.adresse + '</div></div>' +
        '</div>' +
        '<div style="margin-bottom:16px;padding-bottom:16px;border-bottom:1px solid var(--border);">' +
          '<div style="font-weight:600;margin-bottom:10px;">Produits commandes</div>' + items +
        '</div>' +
        '<div style="display:flex;justify-content:space-between;align-items:center;">' +
          '<span style="font-weight:600;">Total</span>' +
          '<span style="font-family:\'Cormorant Garamond\',serif;font-size:1.5rem;color:var(--gold);">' + parseFloat(c.total).toLocaleString('fr-TN') + ' DT</span>' +
        '</div>' +
        '<div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap;">' + btns + '</div>';
      checkNotifications();
      chargerDashboard();
    } catch(e) {}
  }

  function fermerModal() { document.getElementById('modal-commande').classList.remove('open'); }

  async function changerStatut(id, statut) {
    await fetch('../api/commandes.php', {method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id:id, statut:statut})});
    chargerCommandes();
    chargerDashboard();
  }

  // Image upload
  async function previewImage(prefix) {
    const input = document.getElementById(prefix + '-image-input');
    const file  = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById(prefix + '-preview-img').src = e.target.result;
      document.getElementById(prefix + '-upload-preview').style.display = 'block';
      document.getElementById(prefix + '-upload-placeholder').style.display = 'none';
    };
    reader.readAsDataURL(file);
    const formData = new FormData();
    formData.append('image', file);
    try {
      const res    = await fetch('../api/upload.php', {method:'POST', body:formData});
      const result = await res.json();
      if (result.success) {
        document.getElementById(prefix + '-image-url').value = result.url;
        showToast('Image uploadee', 'Image sauvegardee avec succes.');
      } else {
        alert('Erreur upload : ' + result.error);
      }
    } catch(e) { alert('Erreur reseau.'); }
  }

  // Produits
  async function chargerProduits() {
    try {
      const produits = await fetch('../api/produits.php').then(r => r.json());
      const filtered = produits.filter(function(p) { return p.type !== 'accessoire'; });
      const tbody = document.getElementById('all-produits');
      if (!filtered.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Aucun produit</td></tr>';
        return;
      }
      tbody.innerHTML = filtered.map(function(p) {
        const s = parseInt(p.stock);
        const stockClass = s === 0 ? 'stock-rupture' : s <= 3 ? 'stock-alerte' : 'stock-ok';
        const stockLabel = s === 0 ? 'Rupture' : s <= 3 ? s + ' unites' : s + ' en stock';
        const imgHtml = p.image
          ? '<img src="../' + p.image + '" style="width:36px;height:36px;object-fit:cover;border-radius:6px;margin-right:8px;vertical-align:middle;"/>'
          : '<span style="margin-right:8px;">' + p.emoji + '</span>';
        return '<tr>' +
          '<td>' + imgHtml + '<strong>' + p.marque + '</strong> ' + p.nom + '</td>' +
          '<td style="color:var(--gold);">' + parseFloat(p.prix).toLocaleString('fr-TN') + ' DT</td>' +
          '<td><span class="statut ' + (p.type === 'neuf' ? 'statut-confirmee' : 'statut-en_attente') + '">' + p.type + '</span></td>' +
          '<td class="' + stockClass + '">' + stockLabel + '</td>' +
          '<td><span class="statut ' + (p.actif ? 'statut-livree' : 'statut-annulee') + '">' + (p.actif ? 'Actif' : 'Inactif') + '</span></td>' +
          '<td style="display:flex;gap:6px;">' +
            '<button class="btn btn-warning btn-sm" onclick="ouvrirEditProduit(' + p.id + ')">Modifier</button>' +
            '<button class="btn btn-danger btn-sm" onclick="supprimerProduit(' + p.id + ')">Supprimer</button>' +
          '</td></tr>';
      }).join('');
    } catch(e) {}
  }

  // Accessoires
  async function chargerAccessoires() {
    try {
      const produits    = await fetch('../api/produits.php').then(r => r.json());
      const accessoires = produits.filter(function(p) { return p.type === 'accessoire'; });
      const tbody = document.getElementById('all-accessoires');
      if (!accessoires.length) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--gray);padding:20px;">Aucun accessoire</td></tr>';
        return;
      }
      tbody.innerHTML = accessoires.map(function(p) {
        const s = parseInt(p.stock);
        const stockClass = s === 0 ? 'stock-rupture' : s <= 3 ? 'stock-alerte' : 'stock-ok';
        const stockLabel = s === 0 ? 'Rupture' : s + ' en stock';
        const imgHtml = p.image
          ? '<img src="../' + p.image + '" style="width:44px;height:44px;object-fit:cover;border-radius:8px;"/>'
          : '<span style="font-size:1.8rem;">' + p.emoji + '</span>';
        return '<tr>' +
          '<td style="text-align:center;">' + imgHtml + '</td>' +
          '<td><strong>' + p.marque + '</strong> ' + p.nom + '<br/><small style="color:var(--gray);">' + (p.specs || '') + '</small></td>' +
          '<td style="color:var(--gold);">' + parseFloat(p.prix).toLocaleString('fr-TN') + ' DT</td>' +
          '<td class="' + stockClass + '">' + stockLabel + '</td>' +
          '<td><span class="statut ' + (p.actif ? 'statut-livree' : 'statut-annulee') + '">' + (p.actif ? 'Actif' : 'Inactif') + '</span></td>' +
          '<td style="display:flex;gap:6px;">' +
            '<button class="btn btn-warning btn-sm" onclick="ouvrirEditProduit(' + p.id + ')">Modifier</button>' +
            '<button class="btn btn-danger btn-sm" onclick="supprimerProduit(' + p.id + ')">Supprimer</button>' +
          '</td></tr>';
      }).join('');
    } catch(e) {}
  }

  let produitsCache = [];
  async function ouvrirEditProduit(id) {
    if (!produitsCache.length) produitsCache = await fetch('../api/produits.php').then(r => r.json());
    const p = produitsCache.find(function(x) { return x.id == id; });
    if (!p) return;
    document.getElementById('edit-id').value          = p.id;
    document.getElementById('edit-marque').value      = p.marque;
    document.getElementById('edit-nom').value         = p.nom;
    document.getElementById('edit-specs').value       = p.specs || '';
    document.getElementById('edit-prix').value        = p.prix;
    document.getElementById('edit-prix-ancien').value = p.prix_ancien || '';
    document.getElementById('edit-stock').value       = p.stock;
    document.getElementById('edit-type').value        = p.type || 'neuf';
    document.getElementById('edit-badge').value       = p.badge || '';
    document.getElementById('edit-emoji').value       = p.emoji || '';
    document.getElementById('edit-etat').value        = p.etat || '';
    document.getElementById('edit-batterie').value    = p.batterie || '';
    document.getElementById('edit-garantie').value    = p.garantie || '';
    document.getElementById('edit-image-url').value   = p.image || '';
    if (p.image) {
      document.getElementById('edit-preview-img').src                    = '../' + p.image;
      document.getElementById('edit-upload-preview').style.display       = 'block';
      document.getElementById('edit-upload-placeholder').style.display   = 'none';
    } else {
      document.getElementById('edit-upload-preview').style.display       = 'none';
      document.getElementById('edit-upload-placeholder').style.display   = 'block';
    }
    document.getElementById('modal-produit').classList.add('open');
  }

  async function sauvegarderProduit() {
    const data = {
      id:          document.getElementById('edit-id').value,
      marque:      document.getElementById('edit-marque').value,
      nom:         document.getElementById('edit-nom').value,
      specs:       document.getElementById('edit-specs').value,
      prix:        document.getElementById('edit-prix').value,
      prix_ancien: document.getElementById('edit-prix-ancien').value || null,
      stock:       document.getElementById('edit-stock').value,
      type:        document.getElementById('edit-type').value,
      badge:       document.getElementById('edit-badge').value || null,
      emoji:       document.getElementById('edit-emoji').value || '',
      etat:        document.getElementById('edit-etat').value || null,
      batterie:    document.getElementById('edit-batterie').value || null,
      garantie:    document.getElementById('edit-garantie').value || null,
      image:       document.getElementById('edit-image-url').value || null
    };
    try {
      const res    = await fetch('../api/produits.php', {method:'PUT', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)});
      const result = await res.json();
      if (result.success) {
        showToast('Produit modifie', data.marque + ' ' + data.nom + ' mis a jour.');
      } else {
        alert('Erreur lors de la sauvegarde.');
      }
    } catch(e) {
      alert('Erreur reseau.');
    }
    produitsCache = [];
    fermerModalProduit();
    chargerProduits();
    checkNotifications();
  }

  function fermerModalProduit() { document.getElementById('modal-produit').classList.remove('open'); }

  async function supprimerProduit(id) {
    if (!confirm('Desactiver ce produit ?')) return;
    await fetch('../api/produits.php', {method:'DELETE', headers:{'Content-Type':'application/json'}, body:JSON.stringify({id:id})});
    produitsCache = [];
    chargerProduits();
  }

  async function ajouterProduit() {
    const data = {
      marque:      document.getElementById('f-marque').value.trim(),
      nom:         document.getElementById('f-nom').value.trim(),
      specs:       document.getElementById('f-specs').value.trim(),
      prix:        document.getElementById('f-prix').value,
      prix_ancien: document.getElementById('f-prix-ancien').value || null,
      type:        document.getElementById('f-type').value,
      stock:       document.getElementById('f-stock').value || 0,
      badge:       document.getElementById('f-badge').value.trim() || null,
      emoji:       document.getElementById('f-emoji').value.trim() || '',
      etat:        document.getElementById('f-etat').value.trim() || null,
      batterie:    document.getElementById('f-batterie').value || null,
      garantie:    document.getElementById('f-garantie').value.trim() || null,
      image:       document.getElementById('f-image-url').value || null
    };
    const alertEl = document.getElementById('form-alert');
    if (!data.marque || !data.nom || !data.prix) {
      alertEl.style.cssText = 'display:block;background:rgba(231,76,60,0.1);border:1px solid rgba(231,76,60,0.3);color:#e74c3c;padding:12px;border-radius:8px;margin-bottom:20px;font-size:0.85rem;';
      alertEl.textContent = 'Marque, nom et prix sont obligatoires.';
      return;
    }
    try {
      const res    = await fetch('../api/produits.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(data)});
      const result = await res.json();
      if (result.success) {
        alertEl.style.cssText = 'display:block;background:rgba(46,204,113,0.1);border:1px solid rgba(46,204,113,0.3);color:#2ecc71;padding:12px;border-radius:8px;margin-bottom:20px;font-size:0.85rem;';
        alertEl.textContent = 'Produit ajoute avec succes !';
        ['f-marque','f-nom','f-specs','f-prix','f-prix-ancien','f-badge','f-etat','f-batterie','f-garantie'].forEach(function(id) { document.getElementById(id).value = ''; });
        document.getElementById('f-stock').value = '10';
        document.getElementById('f-emoji').value = '';
        document.getElementById('f-image-url').value = '';
        document.getElementById('f-upload-preview').style.display = 'none';
        document.getElementById('f-upload-placeholder').style.display = 'block';
        showToast('Produit ajoute', data.marque + ' ' + data.nom + ' ajoute au catalogue.');
        produitsCache = [];
      } else {
        alertEl.style.cssText = 'display:block;background:rgba(231,76,60,0.1);border:1px solid rgba(231,76,60,0.3);color:#e74c3c;padding:12px;border-radius:8px;margin-bottom:20px;font-size:0.85rem;';
        alertEl.textContent = 'Erreur : ' + (result.error || 'Reessayez.');
      }
    } catch(e) {
      alertEl.textContent = 'Erreur serveur.';
    }
  }

  function statutLabel(s) {
    if (s === 'en_attente') return 'En attente';
    if (s === 'confirmee')  return 'Confirmee';
    if (s === 'livree')     return 'Livree';
    if (s === 'annulee')    return 'Annulee';
    return s;
  }

  chargerDashboard();
  checkNotifications();
  setInterval(checkNotifications, 15000);
</script>
</body>
</html>
