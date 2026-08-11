<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            // Obat Bebas
            ['name' => 'Paracetamol 500mg', 'category' => 'obat_bebas', 'brand' => 'Paramex', 'price' => 8500, 'stock' => 500, 'description' => 'Analgesik dan antipiretik untuk meredakan demam dan nyeri ringan hingga sedang.', 'requires_prescription' => false],
            ['name' => 'Ibuprofen 400mg', 'category' => 'obat_bebas', 'brand' => 'Advil', 'price' => 12000, 'stock' => 300, 'description' => 'Anti-inflamasi non-steroid untuk nyeri, demam, dan peradangan.', 'requires_prescription' => false],
            ['name' => 'Antasida Doen', 'category' => 'obat_bebas', 'brand' => 'Promag', 'price' => 6500, 'stock' => 400, 'description' => 'Menetralkan asam lambung untuk sakit maag dan kembung.', 'requires_prescription' => false],
            ['name' => 'OBH Combi Batuk', 'category' => 'obat_bebas', 'brand' => 'OBH Combi', 'price' => 18000, 'stock' => 250, 'description' => 'Obat batuk berdahak dengan formula triple action.', 'requires_prescription' => false],
            ['name' => 'CTM Antihistamin', 'category' => 'obat_bebas', 'brand' => 'Chlorpheniramine', 'price' => 5500, 'stock' => 600, 'description' => 'Antihistamin untuk alergi, pilek, dan gatal-gatal.', 'requires_prescription' => false],

            // Vitamin & Suplemen
            ['name' => 'Vitamin C 1000mg', 'category' => 'vitamin', 'brand' => 'Ester-C', 'price' => 45000, 'stock' => 200, 'description' => 'Suplemen vitamin C dosis tinggi untuk imunitas dan antioksidan.', 'requires_prescription' => false],
            ['name' => 'Vitamin D3 5000IU', 'category' => 'vitamin', 'brand' => 'Blackmores', 'price' => 85000, 'stock' => 150, 'description' => 'Vitamin D untuk kesehatan tulang dan imun tubuh.', 'requires_prescription' => false],
            ['name' => 'Omega-3 Fish Oil', 'category' => 'suplemen', 'brand' => 'Scott', 'price' => 120000, 'stock' => 100, 'description' => 'Minyak ikan kaya omega-3 untuk kesehatan jantung dan otak.', 'requires_prescription' => false],
            ['name' => 'Multivitamin Dewasa', 'category' => 'vitamin', 'brand' => 'Centrum', 'price' => 95000, 'stock' => 180, 'description' => 'Multivitamin dan mineral lengkap untuk vitalitas harian.', 'requires_prescription' => false],
            ['name' => 'Zinc 10mg', 'category' => 'suplemen', 'brand' => 'Zincplex', 'price' => 35000, 'stock' => 220, 'description' => 'Suplemen zinc untuk imunitas, pertumbuhan, dan kesehatan kulit.', 'requires_prescription' => false],

            // Herbal
            ['name' => 'Temulawak Extract', 'category' => 'herbal', 'brand' => 'Sido Muncul', 'price' => 28000, 'stock' => 300, 'description' => 'Ekstrak temulawak untuk kesehatan hati dan nafsu makan.', 'requires_prescription' => false],
            ['name' => 'Jahe Merah Kapsul', 'category' => 'herbal', 'brand' => 'Nyonya Meneer', 'price' => 32000, 'stock' => 250, 'description' => 'Kapsul jahe merah untuk menghangatkan tubuh dan imunitas.', 'requires_prescription' => false],
            ['name' => 'Minyak Kayu Putih', 'category' => 'herbal', 'brand' => 'Cap Lang', 'price' => 25000, 'stock' => 400, 'description' => 'Minyak kayu putih untuk meredakan masuk angin dan nyeri otot.', 'requires_prescription' => false],

            // Obat Keras (butuh resep)
            ['name' => 'Amoxicillin 500mg', 'category' => 'obat_keras', 'brand' => 'Amoxan', 'price' => 35000, 'stock' => 200, 'description' => 'Antibiotik broad-spectrum untuk infeksi bakteri. Hanya dengan resep dokter.', 'requires_prescription' => true],
            ['name' => 'Metformin 500mg', 'category' => 'obat_keras', 'brand' => 'Glucophage', 'price' => 42000, 'stock' => 150, 'description' => 'Antidiabetik oral untuk diabetes tipe 2. Hanya dengan resep dokter.', 'requires_prescription' => true],

            // Alat Kesehatan
            ['name' => 'Masker Medis 3-Ply (50pcs)', 'category' => 'alat_kesehatan', 'brand' => 'OneMed', 'price' => 45000, 'stock' => 500, 'description' => 'Masker medis 3 lapisan untuk perlindungan virus dan bakteri.', 'requires_prescription' => false],
            ['name' => 'Termometer Digital', 'category' => 'alat_kesehatan', 'brand' => 'Omron', 'price' => 85000, 'stock' => 80, 'description' => 'Termometer digital akurat dan cepat untuk pengukuran suhu tubuh.', 'requires_prescription' => false],
            ['name' => 'Tensimeter Digital', 'category' => 'alat_kesehatan', 'brand' => 'Beurer', 'price' => 350000, 'stock' => 40, 'description' => 'Alat pengukur tekanan darah digital otomatis dengan indikator IHB.', 'requires_prescription' => false],
            ['name' => 'Pulse Oximeter', 'category' => 'alat_kesehatan', 'brand' => 'CMS', 'price' => 95000, 'stock' => 60, 'description' => 'Alat pengukur saturasi oksigen dan detak jantung dengan layar OLED.', 'requires_prescription' => false],
        ];

        foreach ($medicines as $m) {
            Medicine::create([
                'name' => $m['name'],
                'slug' => Str::slug($m['name']),
                'category' => $m['category'],
                'brand' => $m['brand'],
                'description' => $m['description'],
                'price' => $m['price'],
                'stock' => $m['stock'],
                'requires_prescription' => $m['requires_prescription'],
                'is_active' => true,
            ]);
        }
    }
}
