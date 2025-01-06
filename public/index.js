// Created/Modified files during execution:
// - server.js

const express = require('express');
const session = require('express-session');
const mysql = require('mysql');
const path = require('path');

const app = express();

// Middleware untuk body parser (Express v4.16+)
// Jika masih gunakan body-parser, import dan pakai bodyParser.json() / bodyParser.urlencoded()
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Konfigurasi session
app.use(session({
  secret: 'ini-secret-key-anda',
  resave: false,
  saveUninitialized: false
}));

// Koneksi database (sesuaikan dengan konfigurasi Anda)
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'my_rental_db'
});

db.connect((err) => {
  if (err) {
    console.error('Koneksi DB gagal:', err);
    return;
  }
  console.log('Terhubung ke database MySQL');
});

// Contoh: Set session username (biasanya didapat dari proses login)
app.get('/login', (req, res) => {
  // Misal setelah verifikasi user, kita set session:
  req.session.username = 'john_doe'; // Ubah sesuai user login
  res.send('Anda sudah login sebagai john_doe');
});

// Endpoint untuk menerima data pembayaran (POST)
app.post('/api/pay', (req, res) => {
  // Ambil username dari session
  const username = req.session.username;
  // Ambil data pembayaran dari request body
  const { carType, start, end, total } = req.body;

  // Validasi jika session belum ada (belum login)
  if (!username) {
    return res.status(401).json({ error: 'Silakan login terlebih dahulu.' });
  }

  // Siapkan query untuk insert
  const paymentDate = new Date(); // Waktu sekarang
  const sql = `
    INSERT INTO payments (user_name, car_type, start_date, end_date, total, payment_date)
    VALUES (?, ?, ?, ?, ?, ?)
  `;
  const params = [ username, carType, start, end, parseFloat(total.replace('$','')), paymentDate ]; 
  // parseFloat(total.replace('$','')) → contoh jika total dalam format "\$123.45"

  db.query(sql, params, (err, result) => {
    if (err) {
      console.error('Error saat insert ke DB:', err);
      return res.status(500).json({ error: 'Terjadi kesalahan saat menyimpan data.' });
    }
    console.log('Data pembayaran tersimpan dengan ID:', result.insertId);
    return res.status(200).json({
      message: 'Pembayaran sukses! Data tersimpan ke database.',
      id: result.insertId
    });
  });
});

// Untuk menyajikan file HTML/Frontend (index.html)
app.use('/', express.static(path.join(__dirname, 'public')));

// Jalankan server
const PORT = 3000;
app.listen(PORT, () => {
  console.log(`Server berjalan di http://localhost:${PORT}`);
});