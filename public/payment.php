<?php
// ======== Koneksi Database ========
$host = 'localhost';
$user = 'root';          // Ganti sesuai username MySQL Anda
$pass = '';              // Ganti sesuai password MySQL Anda
$dbname = 'cardwi';     // Ganti sesuai nama database Anda

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// ======== Variabel Inisialisasi ========
$carSelectVal     = '';
$startDateVal     = '';
$endDateVal       = '';
$totalVal         = '';
$paymentSuccess   = false;
$errorMessage     = '';
$carImages = [
    '300' => 'https://placehold.co/600x400/FF0000/FFFFFF?text=Mobil+A',
    '250' => 'https://placehold.co/600x400/00FF00/FFFFFF?text=Mobil+B',
    '200' => 'https://placehold.co/600x400/0000FF/FFFFFF?text=Mobil+C',
];

// ======== Tangani Form Submission ========
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $carSelectVal = $_POST['carSelect'] ?? '';
    $startDateVal = $_POST['startDate'] ?? '';
    $endDateVal   = $_POST['endDate'] ?? '';

    // Cek tombol yang ditekan
    if (isset($_POST['calc'])) {
        // Tombol "Hitung" ditekan => Hitung total biaya
        // Validasi input
        if ($carSelectVal === '' || $startDateVal === '' || $endDateVal === '') {
            $errorMessage = 'Mohon isi semua kolom.';
        } else {
            // Hitung selisih hari
            $datetime1 = new DateTime($startDateVal);
            $datetime2 = new DateTime($endDateVal);
            $diff = $datetime2->diff($datetime1)->days;

            if ($datetime2 < $datetime1) {
                $errorMessage = 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.';
            } else {
                // Hitung total biaya
                $basePrice = (int)$carSelectVal;
                if ($diff <= 3) {
                    $totalVal = $basePrice;
                } else {
                    $extraDays = $diff - 3;
                    $extraCostPerDay = 0.25 * $basePrice;
                    $totalVal = $basePrice + ($extraDays * $extraCostPerDay);
                }
            }
        }
    } elseif (isset($_POST['pay'])) {
        // Tombol "Bayar" ditekan => Simpan data ke database
        // Validasi input dan perhitungan total
        if ($carSelectVal === '' || $startDateVal === '' || $endDateVal === '') {
            $errorMessage = 'Mohon isi semua kolom.';
        } else {
            $datetime1 = new DateTime($startDateVal);
            $datetime2 = new DateTime($endDateVal);
            $diff = $datetime2->diff($datetime1)->days;

            if ($datetime2 < $datetime1) {
                $errorMessage = 'Tanggal selesai harus setelah atau sama dengan tanggal mulai.';
            } else {
                $basePrice = (int)$carSelectVal;
                if ($diff <= 3) {
                    $totalVal = $basePrice;
                } else {
                    $extraDays = $diff - 3;
                    $extraCostPerDay = 0.25 * $basePrice;
                    $totalVal = $basePrice + ($extraDays * $extraCostPerDay);
                }

                // Insert data ke database
                $carType = mysqli_real_escape_string($conn, $carSelectVal);
                $startDate = mysqli_real_escape_string($conn, $startDateVal);
                $endDate = mysqli_real_escape_string($conn, $endDateVal);
                $totalPrice = mysqli_real_escape_string($conn, $totalVal);

                $sql = "INSERT INTO rental (carType, start_date, end_date, total_price)
                        VALUES ('$carType', '$startDate', '$endDate', '$totalPrice')";
                
                if (mysqli_query($conn, $sql)) {
                    $paymentSuccess = true;
                    // Reset variabel setelah sukses
                    $carSelectVal = '';
                    $startDateVal = '';
                    $endDateVal   = '';
                    $totalVal     = '';
                } else {
                    $errorMessage = 'Gagal menyimpan data: ' . mysqli_error($conn);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Rental Mobil</title>
    <!-- Tailwind CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">
    <div class="max-w-xl mx-auto my-8 p-6 bg-white shadow-md rounded-md">
        <h1 class="text-2xl font-bold text-center mb-6">Form Pembayaran Rental Mobil</h1>

        <!-- Pesan Sukses -->
        <?php if ($paymentSuccess): ?>
            <div class="mb-4 p-4 bg-green-200 rounded">
                <p class="font-semibold">Pembayaran sukses! Data tersimpan di database.</p>
            </div>
        <?php endif; ?>

        <!-- Pesan Error -->
        <?php if ($errorMessage): ?>
            <div class="mb-4 p-4 bg-red-200 rounded">
                <p class="font-semibold text-red-700"><?php echo $errorMessage; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Pilih Jenis Mobil -->
            <label for="carSelect" class="block font-semibold mb-1">Pilih Mobil:</label>
            <select 
                id="carSelect" 
                name="carSelect"
                class="w-full mb-4 px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="" disabled <?php if($carSelectVal === '') {echo 'selected';}?>>Pilih jenis mobil</option>
                <option value="300" <?php if($carSelectVal === '300') {echo 'selected';}?>>Mobil A (\$300 /3 Hari)</option>
                <option value="250" <?php if($carSelectVal === '250') {echo 'selected';}?>>Mobil B (\$250 /3 Hari)</option>
                <option value="200" <?php if($carSelectVal === '200') {echo 'selected';}?>>Mobil C (\$200 /3 Hari)</option>
            </select>

            <!-- Gambar Mobil -->
            <div id="carImageContainer" class="mb-4 hidden">
                <img 
                    id="carImage" 
                    src="" 
                    alt="Gambar Mobil" 
                    class="w-full h-48 object-cover rounded shadow"
                />
            </div>

            <!-- Tanggal Mulai & Tanggal Selesai -->
            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                <div class="flex-1">
                    <label for="startDate" class="block font-semibold mb-1">Tanggal Mulai:</label>
                    <input 
                        type="date" 
                        id="startDate" 
                        name="startDate"
                        value="<?php echo htmlspecialchars($startDateVal); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                <div class="flex-1">
                    <label for="endDate" class="block font-semibold mb-1">Tanggal Selesai:</label>
                    <input 
                        type="date" 
                        id="endDate" 
                        name="endDate"
                        value="<?php echo htmlspecialchars($endDateVal); ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
            </div>

            <!-- Tampilkan Total Biaya -->
            <?php if ($totalVal !== ''): ?>
                <div class="mb-4 p-4 bg-blue-100 rounded">
                    <p class="font-bold">Total Biaya:</p>
                    <p class="text-lg font-semibold">$<?php echo number_format($totalVal, 2); ?></p>
                </div>
            <?php endif; ?>

            <!-- Tombol Hitung -->
            <button 
                type="submit" 
                name="calc"
                class="w-full bg-blue-600 text-white font-semibold px-4 py-2 rounded hover:bg-blue-700 transition-colors"
            >
                Hitung Total Harga
            </button>

            <!-- Tombol Bayar (Muncul Jika Total Sudah Dihitung Dan Tidak Berhasil Bayar) -->
            <?php if ($totalVal !== '' && !$paymentSuccess): ?>
                <button 
                    type="submit" 
                    name="pay"
                    class="w-full bg-green-600 text-white font-semibold px-4 py-2 rounded hover:bg-green-700 transition-colors mt-2"
                >
                    Bayar Sekarang
                </button>
            <?php endif; ?>
        </form>
    </div>

    <!-- JavaScript untuk Menampilkan Gambar Mobil Secara Dinamis -->
    <script>
        const carImages = {
            "300": "https://placehold.co/600x400/FF0000/FFFFFF?text=Mobil+A",
            "250": "https://placehold.co/600x400/00FF00/FFFFFF?text=Mobil+B",
            "200": "https://placehold.co/600x400/0000FF/FFFFFF?text=Mobil+C"
        };

        const carSelectEl = document.getElementById('carSelect');
        const carImageContainer = document.getElementById('carImageContainer');
        const carImageEl = document.getElementById('carImage');

        // Tampilkan gambar sesuai pilihan tanpa reload
        carSelectEl.addEventListener('change', () => {
            const selectedValue = carSelectEl.value;
            if (carImages[selectedValue]) {
                carImageEl.src = carImages[selectedValue];
                carImageContainer.classList.remove('hidden');
            } else {
                carImageContainer.classList.add('hidden');
            }
        });

        // Jika ada nilai terpilih ketika halaman dimuat ulang, tampilkan gambar
        window.addEventListener('DOMContentLoaded', () => {
            const selectedValue = "<?php echo $carSelectVal; ?>";
            if (carImages[selectedValue]) {
                carImageEl.src = carImages[selectedValue];
                carImageContainer.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>