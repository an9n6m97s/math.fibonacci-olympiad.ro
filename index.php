<?php

/**
 * This script includes the main layout file for the frontend.
 *
 * The file path to the main layout is constructed using the document root
 * from the server's environment variables.
 *
 * @file /home/netown/public_html/index.php
 * @requires $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/main.php'
 */

// $licenseClientPath = $_SERVER['DOCUMENT_ROOT'] . '/lib/License/LicenseClient.php';
// $licenseServerPath = $_SERVER['DOCUMENT_ROOT'] . '/lib/License/LicenseServer.php';

// if (file_exists($licenseClientPath)) {
//   require_once $licenseClientPath;
// } else {
//   die('
// <!doctype html>
// <html lang="en">
// <head>
//   <meta charset="utf-8" />
//   <meta name="viewport" content="width=device-width, initial-scale=1" />
//   <meta name="robots" content="noindex,nofollow" />
//   <title>EssenByte Solutions · Licensing Unavailable</title>
//   <meta name="theme-color" content="#0b1220" />

//   <style>
//     :root{
//       --bg: #0b1220;
//       --card: #0f172a;
//       --muted: #9fb3c8;
//       --text: #e5eaf2;
//       --primary: #3b82f6;
//       --primary-2: #0ea5e9;
//       --danger: #ef4444;
//       --ring: rgba(59,130,246,.45);
//       --shadow: 0 10px 30px rgba(2,6,23,.35);
//       --radius: 18px;
//     }
//     * { box-sizing: border-box; }
//     html, body { height: 100%; }
//     body{
//       margin:0;
//       font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif;
//       color: var(--text);
//       background: radial-gradient(1200px 600px at 10% -10%, rgba(14,165,233,.18), transparent 60%),
//                   radial-gradient(1000px 700px at 110% 10%, rgba(59,130,246,.18), transparent 60%),
//                   var(--bg);
//       display:flex; align-items:center; justify-content:center;
//       padding: 32px 18px;
//     }
//     .wrap{ max-width: 980px; width:100%; display:grid; gap:18px; }
//     .brand{ display:flex; justify-content:center; gap:12px; align-items:center; }
//     .logo{ width:44px; height:44px; border-radius:12px;
//       background: conic-gradient(from 210deg, var(--bg), var(--card-2));
//       display:grid; place-items:center;
//       box-shadow: 0 8px 28px rgba(59,130,246,.45);
//     }
//     .logo svg{ width:26px; height:26px; fill:#fff; }
//     .brand-title{
//       font-weight:800; font-size:20px; text-transform:uppercase;
//       background:linear-gradient(90deg,#93c5fd,#e0f2fe);
//       -webkit-background-clip:text; background-clip:text; color:transparent;
//     }
//     .card{ background:rgba(15,23,42,.92); border:1px solid rgba(148,163,184,.18);
//       border-radius:var(--radius); box-shadow:var(--shadow); }
//     .card__head{ display:flex; align-items:center; gap:14px; padding:22px 22px 0; }
//     .status{ width:12px; height:12px; border-radius:50%; background:var(--danger);
//       box-shadow:0 0 0 6px rgba(239,68,68,.15); }
//     h1{ margin:0; font-size:22px; font-weight:800; }
//     .card__body{ padding:8px 22px 18px; color:var(--muted); }
//     .card__body p{ margin:.6rem 0; }
//     .list{ margin:12px 0 0; padding:0 0 0 18px; color:#c7d2fe; }
//     .list code{ color:#e0e7ff; background:rgba(99,102,241,.12);
//       padding:2px 6px; border-radius:8px; border:1px solid rgba(99,102,241,.22);
//       font-family:monospace; font-size:.95em; }
//     .actions{ display:flex; flex-wrap:wrap; gap:12px; padding:0 22px 22px; }
//     .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:700;
//       display:inline-flex; align-items:center; gap:10px; cursor:pointer;
//       text-decoration:none; transition:.15s ease; }
//     .btn--primary{ color:#0b1220; background:linear-gradient(90deg,var(--primary),var(--primary-2));
//       box-shadow:0 10px 20px rgba(14,165,233,.35); }
//     .btn--primary:hover{ transform:translateY(-1px); }
//     .btn--ghost{ color:#dbeafe; background:transparent; border:1px solid rgba(148,163,184,.25); }
//     .btn--ghost:hover{ border-color:rgba(148,163,184,.45); transform:translateY(-1px); }
//     .diag{ background:rgba(2,6,23,.45); border-top:1px dashed rgba(148,163,184,.28);
//       padding:16px 22px 22px; display:grid; gap:10px; }
//     .diag h2{ margin:0 0 4px; font-size:14px; font-weight:800; text-transform:uppercase; color:#c7d2fe; }
//     .kv{ display:grid; grid-template-columns:160px 1fr; gap:6px 12px; font-size:14px; }
//     .kv strong{ color:#93c5fd; }
//     .foot{ text-align:center; font-size:12px; color:#8aa3bd; margin-top:6px; }
//     .foot a{ color:#b3e1ff; text-decoration:none; border-bottom:1px dotted rgba(179,225,255,.45); }
//     .foot a:hover{ border-bottom-style:solid; }
//     @media(max-width:560px){ .kv{ grid-template-columns:1fr; } h1{ font-size:20px; } }
//   </style>
// </head>
// <body>
//   <main class="wrap">
//     <div class="brand">
//       <div class="logo">
//         <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 440.83 451.56"><defs><style>.cls-1{fill:url(#linear-gradient-2);}.cls-2{fill:url(#linear-gradient-5);}.cls-3{fill:url(#linear-gradient-3);}.cls-4{fill:url(#linear-gradient);}.cls-5{fill:url(#linear-gradient-4);}</style><linearGradient id="linear-gradient" x1="0" y1="242.93" x2="440.83" y2="242.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#00fefb"/><stop offset=".11" stop-color="#01f4fa"/><stop offset=".29" stop-color="#03dbfa"/><stop offset=".52" stop-color="#08b2fa"/><stop offset=".78" stop-color="#0e7af9"/><stop offset="1" stop-color="#1448f9"/></linearGradient><linearGradient id="linear-gradient-2" x1="142.77" y1="25.57" x2="208.99" y2="25.57" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#00fefb"/><stop offset=".42" stop-color="#08affa"/><stop offset="1" stop-color="#1448f9"/></linearGradient><linearGradient id="linear-gradient-3" y1="-25.57" x2="208.99" y2="-25.57" gradientTransform="translate(0 51.15) scale(1 -1)" xlink:href="#linear-gradient-2"/><linearGradient id="linear-gradient-4" x1="173.91" y1="25.57" x2="240.13" y2="25.57" gradientTransform="translate(466.07) rotate(-180) scale(1 -1)" xlink:href="#linear-gradient-2"/><linearGradient id="linear-gradient-5" x1="173.91" y1="-25.57" x2="240.13" y2="-25.57" gradientTransform="translate(466.07 51.15) rotate(-180)" xlink:href="#linear-gradient-2"/></defs><path class="cls-4" d="m378.33,58.18l-154.01,59.51-.31.12-4.01,1.51L0,34.31v69.69l156,59.08v61.36L0,166.52v67.67l156,59.08v59.65L0,290.4v69.03l212.63,88.82h.01l7.78,3.31,15.48-6.86.21-.09,175.66-73.38c17.1-7.14,28.23-23.85,28.23-42.38v-13.37h.14l.69-214.15v-.3c0-32.27-32.4-54.49-62.5-42.85Zm-89.34,292.79c-2.39.95-4.99-.8-4.99-3.38v-51.76c0-1.51.93-2.86,2.35-3.4l83.69-31.7c3.36-1.27,6.96,1.21,6.96,4.81v49.51c0,.21.07.4.21.56l-88.22,35.36Zm88.01-222.99v58.41c0,2.15-1.34,4.07-3.35,4.81l-84.75,31.48c-2.37.88-4.9-.88-4.9-3.41v-53.58c0-1.51.93-2.87,2.35-3.4l91.07-34.49-.42.18Z"/><path class="cls-1" d="m178.71,51.15l24.74-25.53c2.04-2.1,3.59-4.63,4.55-7.39,3.45-9.96-5.15-19.93-15.51-17.98l-.61.11c-3.92.74-7.54,2.63-10.38,5.44l-34.21,33.77c-3.23,3.19-4.85,7.38-4.86,11.58"/><path class="cls-3" d="m178.71,51.15l24.74,25.53c2.04,2.1,3.59,4.63,4.55,7.39,3.45,9.96-5.15,19.93-15.51,17.98l-.61-.11c-3.92-.74-7.54-2.63-10.38-5.44l-34.21-33.77c-3.23-3.19-4.85-7.38-4.86-11.58"/><path class="cls-5" d="m256.22,51.15l-24.74-25.53c-2.04-2.1-3.59-4.63-4.55-7.39-3.45-9.96,5.15-19.93,15.51-17.98l.61.11c3.92.74,7.54,2.63,10.38,5.44l34.21,33.77c3.23,3.19,4.85,7.38,4.86,11.58"/><path class="cls-2" d="m256.22,51.15l-24.74,25.53c-2.04,2.1-3.59,4.63-4.55,7.39-3.45,9.96,5.15,19.93,15.51,17.98l.61-.11c3.92-.74,7.54-2.63,10.38-5.44l34.21-33.77c3.23-3.19,4.85-7.38,4.86-11.58"/></svg>
//       </div>
//       <div class="brand-title">EssenByte Solutions</div>
//     </div>

//     <section class="card">
//       <header class="card__head">
//         <span class="status"></span>
//         <h1>Licensing Unavailable</h1>
//       </header>

//       <div class="card__body">
//         <p>The required files for licensing this website are missing or cannot be loaded.</p>
//         <ul class="list">
//           <li>Missing or inaccessible: <code>/lib/License/LicenseClient.php</code> and/or <code>/lib/License/LicenseServer.php</code></li>
//         </ul>
//         <p>Please contact <strong>EssenByte Solutions</strong> to resolve this issue as soon as possible.</p>
//       </div>

//       <div class="actions">
//         <a class="btn btn--primary" href="mailto:info@essenbyte.com?subject=Licensing%20Issue&body=Hello%20EssenByte%2C%0A%0AThe%20site%20licensing%20failed.%20The%20LicenseClient.php%20and/or%20LicenseServer.php%20files%20appear%20missing.%0A%0ADomain%3A%20[fill]%0ADate%20%26%20Time%3A%20[fill]%0A%0AThanks.">
//           Contact EssenByte
//         </a>
//         <button class="btn btn--ghost" onclick="location.reload()">Retry</button>
//       </div>

//       <div class="diag">
//         <h2>Diagnostics</h2>
//         <div class="kv">
//           <strong>Host</strong><div id="kv-host">—</div>
//           <strong>Page URL</strong><div id="kv-path">—</div>
//           <strong>Browser</strong><div id="kv-ua">—</div>
//           <strong>Timestamp</strong><div id="kv-ts">—</div>
//         </div>
//       </div>
//     </section>

//     <p class="foot">© <span id="year"></span> EssenByte Solutions. All rights reserved. | <a href="https://essenbyte.com" target="_blank">essenbyte.com</a></p>
//   </main>

//   <script>
//     (function(){
//       document.getElementById("kv-host").textContent = location.hostname;
//       document.getElementById("kv-path").textContent = location.href;
//       document.getElementById("kv-ua").textContent = navigator.userAgent;
//       document.getElementById("kv-ts").textContent = new Date().toLocaleString();
//       document.getElementById("year").textContent = new Date().getFullYear();
//     })();
//   </script>
// </body>
// </html>
// ');
// }

// if (file_exists($licenseServerPath)) {
//   require_once $licenseServerPath;
// } else {
//   die('
// <!doctype html>
// <html lang="en">
// <head>
//   <meta charset="utf-8" />
//   <meta name="viewport" content="width=device-width, initial-scale=1" />
//   <meta name="robots" content="noindex,nofollow" />
//   <title>EssenByte Solutions · Licensing Unavailable</title>
//   <meta name="theme-color" content="#0b1220" />

//   <style>
//     :root{
//       --bg: #0b1220;
//       --card: #0f172a;
//       --muted: #9fb3c8;
//       --text: #e5eaf2;
//       --primary: #3b82f6;
//       --primary-2: #0ea5e9;
//       --danger: #ef4444;
//       --ring: rgba(59,130,246,.45);
//       --shadow: 0 10px 30px rgba(2,6,23,.35);
//       --radius: 18px;
//     }
//     * { box-sizing: border-box; }
//     html, body { height: 100%; }
//     body{
//       margin:0;
//       font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, sans-serif;
//       color: var(--text);
//       background: radial-gradient(1200px 600px at 10% -10%, rgba(14,165,233,.18), transparent 60%),
//                   radial-gradient(1000px 700px at 110% 10%, rgba(59,130,246,.18), transparent 60%),
//                   var(--bg);
//       display:flex; align-items:center; justify-content:center;
//       padding: 32px 18px;
//     }
//     .wrap{ max-width: 980px; width:100%; display:grid; gap:18px; }
//     .brand{ display:flex; justify-content:center; gap:12px; align-items:center; }
//     .logo{ width:44px; height:44px; border-radius:12px;
//       background: conic-gradient(from 210deg, var(--bg), var(--card-2));
//       display:grid; place-items:center;
//       box-shadow: 0 8px 28px rgba(59,130,246,.45);
//     }
//     .logo svg{ width:26px; height:26px; fill:#fff; }
//     .brand-title{
//       font-weight:800; font-size:20px; text-transform:uppercase;
//       background:linear-gradient(90deg,#93c5fd,#e0f2fe);
//       -webkit-background-clip:text; background-clip:text; color:transparent;
//     }
//     .card{ background:rgba(15,23,42,.92); border:1px solid rgba(148,163,184,.18);
//       border-radius:var(--radius); box-shadow:var(--shadow); }
//     .card__head{ display:flex; align-items:center; gap:14px; padding:22px 22px 0; }
//     .status{ width:12px; height:12px; border-radius:50%; background:var(--danger);
//       box-shadow:0 0 0 6px rgba(239,68,68,.15); }
//     h1{ margin:0; font-size:22px; font-weight:800; }
//     .card__body{ padding:8px 22px 18px; color:var(--muted); }
//     .card__body p{ margin:.6rem 0; }
//     .list{ margin:12px 0 0; padding:0 0 0 18px; color:#c7d2fe; }
//     .list code{ color:#e0e7ff; background:rgba(99,102,241,.12);
//       padding:2px 6px; border-radius:8px; border:1px solid rgba(99,102,241,.22);
//       font-family:monospace; font-size:.95em; }
//     .actions{ display:flex; flex-wrap:wrap; gap:12px; padding:0 22px 22px; }
//     .btn{ border:0; border-radius:12px; padding:12px 16px; font-weight:700;
//       display:inline-flex; align-items:center; gap:10px; cursor:pointer;
//       text-decoration:none; transition:.15s ease; }
//     .btn--primary{ color:#0b1220; background:linear-gradient(90deg,var(--primary),var(--primary-2));
//       box-shadow:0 10px 20px rgba(14,165,233,.35); }
//     .btn--primary:hover{ transform:translateY(-1px); }
//     .btn--ghost{ color:#dbeafe; background:transparent; border:1px solid rgba(148,163,184,.25); }
//     .btn--ghost:hover{ border-color:rgba(148,163,184,.45); transform:translateY(-1px); }
//     .diag{ background:rgba(2,6,23,.45); border-top:1px dashed rgba(148,163,184,.28);
//       padding:16px 22px 22px; display:grid; gap:10px; }
//     .diag h2{ margin:0 0 4px; font-size:14px; font-weight:800; text-transform:uppercase; color:#c7d2fe; }
//     .kv{ display:grid; grid-template-columns:160px 1fr; gap:6px 12px; font-size:14px; }
//     .kv strong{ color:#93c5fd; }
//     .foot{ text-align:center; font-size:12px; color:#8aa3bd; margin-top:6px; }
//     .foot a{ color:#b3e1ff; text-decoration:none; border-bottom:1px dotted rgba(179,225,255,.45); }
//     .foot a:hover{ border-bottom-style:solid; }
//     @media(max-width:560px){ .kv{ grid-template-columns:1fr; } h1{ font-size:20px; } }
//   </style>
// </head>
// <body>
//   <main class="wrap">
//     <div class="brand">
//       <div class="logo">
//         <svg id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 440.83 451.56"><defs><style>.cls-1{fill:url(#linear-gradient-2);}.cls-2{fill:url(#linear-gradient-5);}.cls-3{fill:url(#linear-gradient-3);}.cls-4{fill:url(#linear-gradient);}.cls-5{fill:url(#linear-gradient-4);}</style><linearGradient id="linear-gradient" x1="0" y1="242.93" x2="440.83" y2="242.93" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#00fefb"/><stop offset=".11" stop-color="#01f4fa"/><stop offset=".29" stop-color="#03dbfa"/><stop offset=".52" stop-color="#08b2fa"/><stop offset=".78" stop-color="#0e7af9"/><stop offset="1" stop-color="#1448f9"/></linearGradient><linearGradient id="linear-gradient-2" x1="142.77" y1="25.57" x2="208.99" y2="25.57" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#00fefb"/><stop offset=".42" stop-color="#08affa"/><stop offset="1" stop-color="#1448f9"/></linearGradient><linearGradient id="linear-gradient-3" y1="-25.57" x2="208.99" y2="-25.57" gradientTransform="translate(0 51.15) scale(1 -1)" xlink:href="#linear-gradient-2"/><linearGradient id="linear-gradient-4" x1="173.91" y1="25.57" x2="240.13" y2="25.57" gradientTransform="translate(466.07) rotate(-180) scale(1 -1)" xlink:href="#linear-gradient-2"/><linearGradient id="linear-gradient-5" x1="173.91" y1="-25.57" x2="240.13" y2="-25.57" gradientTransform="translate(466.07 51.15) rotate(-180)" xlink:href="#linear-gradient-2"/></defs><path class="cls-4" d="m378.33,58.18l-154.01,59.51-.31.12-4.01,1.51L0,34.31v69.69l156,59.08v61.36L0,166.52v67.67l156,59.08v59.65L0,290.4v69.03l212.63,88.82h.01l7.78,3.31,15.48-6.86.21-.09,175.66-73.38c17.1-7.14,28.23-23.85,28.23-42.38v-13.37h.14l.69-214.15v-.3c0-32.27-32.4-54.49-62.5-42.85Zm-89.34,292.79c-2.39.95-4.99-.8-4.99-3.38v-51.76c0-1.51.93-2.86,2.35-3.4l83.69-31.7c3.36-1.27,6.96,1.21,6.96,4.81v49.51c0,.21.07.4.21.56l-88.22,35.36Zm88.01-222.99v58.41c0,2.15-1.34,4.07-3.35,4.81l-84.75,31.48c-2.37.88-4.9-.88-4.9-3.41v-53.58c0-1.51.93-2.87,2.35-3.4l91.07-34.49-.42.18Z"/><path class="cls-1" d="m178.71,51.15l24.74-25.53c2.04-2.1,3.59-4.63,4.55-7.39,3.45-9.96-5.15-19.93-15.51-17.98l-.61.11c-3.92.74-7.54,2.63-10.38,5.44l-34.21,33.77c-3.23,3.19-4.85,7.38-4.86,11.58"/><path class="cls-3" d="m178.71,51.15l24.74,25.53c2.04,2.1,3.59,4.63,4.55,7.39,3.45,9.96-5.15,19.93-15.51,17.98l-.61-.11c-3.92-.74-7.54-2.63-10.38-5.44l-34.21-33.77c-3.23-3.19-4.85-7.38-4.86-11.58"/><path class="cls-5" d="m256.22,51.15l-24.74-25.53c-2.04-2.1-3.59-4.63-4.55-7.39-3.45-9.96,5.15-19.93,15.51-17.98l.61.11c3.92.74,7.54,2.63,10.38,5.44l34.21,33.77c3.23,3.19,4.85,7.38,4.86,11.58"/><path class="cls-2" d="m256.22,51.15l-24.74,25.53c-2.04,2.1-3.59,4.63-4.55,7.39-3.45,9.96,5.15,19.93,15.51,17.98l.61-.11c3.92-.74,7.54-2.63,10.38-5.44l34.21-33.77c3.23-3.19,4.85-7.38,4.86-11.58"/></svg>
//       </div>
//       <div class="brand-title">EssenByte Solutions</div>
//     </div>

//     <section class="card">
//       <header class="card__head">
//         <span class="status"></span>
//         <h1>Licensing Unavailable</h1>
//       </header>

//       <div class="card__body">
//         <p>The required files for licensing this website are missing or cannot be loaded.</p>
//         <ul class="list">
//           <li>Missing or inaccessible: <code>/lib/License/LicenseClient.php</code> and/or <code>/lib/License/LicenseServer.php</code></li>
//         </ul>
//         <p>Please contact <strong>EssenByte Solutions</strong> to resolve this issue as soon as possible.</p>
//       </div>

//       <div class="actions">
//         <a class="btn btn--primary" href="mailto:info@essenbyte.com?subject=Licensing%20Issue&body=Hello%20EssenByte%2C%0A%0AThe%20site%20licensing%20failed.%20The%20LicenseClient.php%20and/or%20LicenseServer.php%20files%20appear%20missing.%0A%0ADomain%3A%20[fill]%0ADate%20%26%20Time%3A%20[fill]%0A%0AThanks.">
//           Contact EssenByte
//         </a>
//         <button class="btn btn--ghost" onclick="location.reload()">Retry</button>
//       </div>

//       <div class="diag">
//         <h2>Diagnostics</h2>
//         <div class="kv">
//           <strong>Host</strong><div id="kv-host">—</div>
//           <strong>Page URL</strong><div id="kv-path">—</div>
//           <strong>Browser</strong><div id="kv-ua">—</div>
//           <strong>Timestamp</strong><div id="kv-ts">—</div>
//         </div>
//       </div>
//     </section>

//     <p class="foot">© <span id="year"></span> EssenByte Solutions. All rights reserved. | <a href="https://essenbyte.com" target="_blank">essenbyte.com</a></p>
//   </main>

//   <script>
//     (function(){
//       document.getElementById("kv-host").textContent = location.hostname;
//       document.getElementById("kv-path").textContent = location.href;
//       document.getElementById("kv-ua").textContent = navigator.userAgent;
//       document.getElementById("kv-ts").textContent = new Date().toLocaleString();
//       document.getElementById("year").textContent = new Date().getFullYear();
//     })();
//   </script>
// </body>
// </html>
// ');
// }

// $licenseServer = new LicenseServer();


require_once $_SERVER['DOCUMENT_ROOT'] . '/frontend/layout/main.php';
