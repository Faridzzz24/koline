<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) return;

        $articles = [
            [
                'title' => '7 Kebiasaan Sehat yang Wajib Dilakukan Setiap Hari',
                'category' => 'kesehatan_umum',
                'excerpt' => 'Kebiasaan sehat harian tidak harus rumit. Berikut 7 kebiasaan sederhana yang bisa membuat tubuh Anda lebih sehat dan bugar setiap hari.',
                'content' => '<p>Menjaga kesehatan tidak harus dimulai dari hal yang besar. Perubahan kecil namun konsisten dalam rutinitas harian Anda bisa memberikan dampak yang luar biasa bagi kesehatan jangka panjang.</p><h2>1. Minum Air Putih yang Cukup</h2><p>Tubuh kita terdiri dari 60% air. Kekurangan cairan dapat menyebabkan kelelahan, sakit kepala, dan penurunan konsentrasi. Target minimal 8 gelas (2 liter) air per hari.</p><h2>2. Tidur 7-9 Jam Per Malam</h2><p>Tidur yang cukup adalah fondasi kesehatan. Selama tidur, tubuh memperbaiki sel, mengkonsolidasi memori, dan mengatur hormon. Kurang tidur dikaitkan dengan obesitas, diabetes, dan penyakit jantung.</p><h2>3. Bergerak 30 Menit Sehari</h2><p>Aktivitas fisik tidak harus ke gym. Berjalan kaki, bersepeda, atau bahkan naik tangga sudah cukup untuk menjaga kesehatan jantung dan metabolisme.</p><h2>4. Konsumsi Sayur dan Buah</h2><p>Penuhi setengah piring Anda dengan sayuran dan buah-buahan. Serat, vitamin, dan mineral dari makanan alami ini tidak bisa digantikan suplemen.</p><h2>5. Kelola Stres dengan Bijak</h2><p>Stres kronis merusak sistem imun dan kesehatan mental. Meditasi 10 menit, pernapasan dalam, atau hobi menyenangkan bisa sangat membantu.</p><h2>6. Batasi Gula dan Garam</h2><p>Konsumsi gula berlebih meningkatkan risiko diabetes dan obesitas, sementara garam berlebih memicu hipertensi. Biasakan membaca label nutrisi makanan.</p><h2>7. Rutin Pemeriksaan Kesehatan</h2><p>Jangan tunggu sakit untuk ke dokter. Pemeriksaan rutin tahunan dapat mendeteksi penyakit lebih awal saat masih mudah diobati.</p>',
                'views' => 1250,
            ],
            [
                'title' => 'Mengenal Tanda-tanda Awal Diabetes yang Sering Diabaikan',
                'category' => 'penyakit',
                'excerpt' => 'Diabetes sering disebut penyakit "silent killer" karena gejalanya tidak selalu terasa jelas. Kenali tanda-tanda awalnya sebelum terlambat.',
                'content' => '<p>Diabetes mellitus adalah kondisi kronis yang ditandai dengan kadar gula darah tinggi. Sayangnya, banyak penderita tidak menyadari kondisi ini hingga komplikasi serius terjadi.</p><h2>Tanda-tanda yang Perlu Diwaspadai</h2><h3>Sering Haus dan Buang Air Kecil</h3><p>Gula darah tinggi menyebabkan ginjal bekerja keras memfilter kelebihan glukosa. Ini memicu dehidrasi dan rasa haus yang tidak hilang.</p><h3>Penglihatan Kabur</h3><p>Kadar gula tinggi dapat merusak pembuluh darah kecil di retina mata, menyebabkan penglihatan buram.</p><h3>Luka Sembuh Lambat</h3><p>Hiperglikemia merusak pembuluh darah dan saraf, menghambat proses penyembuhan luka alami tubuh.</p><h3>Kesemutan di Tangan dan Kaki</h3><p>Neuropati diabetik, kerusakan saraf akibat gula darah tinggi, menyebabkan rasa kesemutan, kebas, atau seperti terbakar.</p><h2>Kapan Harus ke Dokter?</h2><p>Jika Anda mengalami kombinasi gejala di atas, segera konsultasikan ke dokter untuk pemeriksaan gula darah. Deteksi dini adalah kunci manajemen diabetes yang efektif.</p>',
                'views' => 2100,
            ],
            [
                'title' => 'Panduan Nutrisi Lengkap untuk Ibu Hamil Trimester Pertama',
                'category' => 'ibu_anak',
                'excerpt' => 'Trimester pertama adalah periode kritis tumbuh kembang janin. Pahami kebutuhan nutrisi yang tepat untuk ibu dan bayi yang sehat.',
                'content' => '<p>Tiga bulan pertama kehamilan adalah masa paling krusial. Organ vital bayi mulai terbentuk, sehingga nutrisi yang tepat sangat menentukan perkembangan janin.</p><h2>Nutrisi Prioritas Trimester 1</h2><h3>Asam Folat</h3><p>Nutrisi terpenting di awal kehamilan. Asam folat mencegah cacat tabung saraf seperti spina bifida. Butuhkan 600-800 mcg per hari dari sayuran hijau, kacang-kacangan, dan suplemen.</p><h3>Zat Besi</h3><p>Volume darah meningkat 50% selama kehamilan. Zat besi dari daging merah tanpa lemak, bayam, dan kacang polong membantu mencegah anemia.</p><h3>Kalsium dan Vitamin D</h3><p>Penting untuk pembentukan tulang dan gigi bayi. Sumber terbaik: susu, yogurt, keju, dan paparan matahari pagi yang cukup.</p><h2>Makanan yang Harus Dihindari</h2><ul><li>Ikan tinggi merkuri (hiu, todak, king mackerel)</li><li>Daging dan telur mentah atau setengah matang</li><li>Kafein berlebihan (max 200mg/hari)</li><li>Alkohol dalam bentuk apapun</li></ul>',
                'views' => 1800,
            ],
            [
                'title' => 'Cara Efektif Mengatasi Insomnia Tanpa Obat',
                'category' => 'mental_health',
                'excerpt' => 'Susah tidur bukan hanya masalah fisik, tapi juga mental. Coba teknik-teknik terbukti secara ilmiah ini sebelum beralih ke obat tidur.',
                'content' => '<p>Insomnia mempengaruhi kualitas hidup, produktivitas, dan kesehatan mental. Sebelum menggunakan obat tidur, ada banyak pendekatan non-farmakologi yang terbukti efektif.</p><h2>Sleep Hygiene yang Baik</h2><p>Sleep hygiene adalah sekumpulan kebiasaan yang mendukung tidur berkualitas:</p><ul><li>Tidur dan bangun di jam yang sama setiap hari, termasuk akhir pekan</li><li>Hindari layar (HP, laptop, TV) minimal 1 jam sebelum tidur</li><li>Pastikan kamar tidur gelap, sejuk, dan tenang</li><li>Gunakan tempat tidur hanya untuk tidur, bukan bekerja</li></ul><h2>Teknik Relaksasi</h2><h3>4-7-8 Breathing</h3><p>Tarik napas 4 detik, tahan 7 detik, hembuskan 8 detik. Teknik ini mengaktifkan sistem saraf parasimpatik yang menenangkan.</p><h3>Progressive Muscle Relaxation</h3><p>Tegangkan dan lepaskan setiap kelompok otot secara bergantian dari kaki hingga kepala. Efektif mengurangi ketegangan fisik yang menghambat tidur.</p>',
                'views' => 980,
            ],
            [
                'title' => 'BMI Normal Tidak Selalu Berarti Sehat: Kenali Body Recomposition',
                'category' => 'olahraga',
                'excerpt' => 'Angka di timbangan dan BMI sering menipu. Pelajari konsep body recomposition untuk memahami komposisi tubuh yang sesungguhnya.',
                'content' => '<p>BMI (Body Mass Index) adalah alat skrining sederhana, bukan diagnosis kesehatan. Seseorang dengan BMI normal bisa memiliki kadar lemak visceral tinggi yang berbahaya.</p><h2>Apa itu Body Recomposition?</h2><p>Body recomposition adalah proses membangun massa otot sekaligus mengurangi lemak tubuh. Berbeda dari diet biasa yang hanya fokus menurunkan berat badan, body recomposition mengubah komposisi tubuh secara keseluruhan.</p><h2>Mengapa Ini Penting?</h2><p>Otot memiliki kepadatan lebih tinggi dari lemak. Seseorang yang berotot mungkin memiliki berat sama dengan seseorang yang gemuk, tapi profil kesehatan metaboliknya jauh lebih baik.</p><h2>Cara Memulai</h2><ul><li>Latihan kekuatan (resistance training) minimal 3x seminggu</li><li>Konsumsi protein cukup: 1.6-2.2g per kg berat badan</li><li>Defisit kalori moderat, bukan ekstrem</li><li>Tidur cukup untuk pemulihan otot optimal</li></ul>',
                'views' => 750,
            ],
            [
                'title' => 'Makanan Super untuk Meningkatkan Imunitas Tubuh',
                'category' => 'gizi',
                'excerpt' => 'Sistem imun yang kuat dimulai dari dapur. Kenali 10 superfood yang terbukti secara ilmiah meningkatkan daya tahan tubuh.',
                'content' => '<p>Tidak ada obat ajaib untuk imunitas, tapi kombinasi nutrisi yang tepat dapat memperkuat pertahanan alami tubuh secara signifikan.</p><h2>Top 10 Makanan untuk Imunitas</h2><h3>1. Bawang Putih</h3><p>Mengandung allicin yang memiliki sifat antivirus dan antibakteri. Konsumsi mentah atau dimasak sebentar untuk efek maksimal.</p><h3>2. Jahe</h3><p>Gingerol dalam jahe memiliki efek anti-inflamasi dan antioksidan kuat. Bisa diminum sebagai teh atau ditambahkan ke masakan.</p><h3>3. Kunyit</h3><p>Curcumin, pigmen aktif kunyit, adalah anti-inflamasi alami yang powerful. Kombinasikan dengan lada hitam untuk meningkatkan penyerapan 2000%.</p><h3>4. Brokoli</h3><p>Satu cangkir brokoli mengandung lebih banyak vitamin C dari jeruk. Kukus jangan direbus untuk menjaga kandungan nutrisinya.</p><h3>5. Yogurt</h3><p>Probiotik dalam yogurt mendukung kesehatan mikrobioma usus yang merupakan 70% dari sistem imun kita.</p>',
                'views' => 1560,
            ],
        ];

        foreach ($articles as $article) {
            Article::create([
                'author_id' => $admin->id,
                'title' => $article['title'],
                'slug' => Str::slug($article['title']),
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'category' => $article['category'],
                'is_published' => true,
                'published_at' => now()->subDays(rand(1, 30)),
                'views' => $article['views'],
            ]);
        }
    }
}
