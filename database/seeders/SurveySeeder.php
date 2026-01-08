<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SurveySeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan Foreign Key Check sementara untuk membersihkan data
        Schema::disableForeignKeyConstraints();
        DB::table('submission_values')->truncate();
        DB::table('options')->truncate(); // Pastikan nama tabelnya 'options'
        DB::table('questions')->truncate();
        Schema::enableForeignKeyConstraints();

        $ids = []; // Array untuk memetakan 'code' (misal q9) ke 'id' database (misal 5)

        // DAFTAR PERTANYAAN LENGKAP
        $questions = [
            // ============================================================
            // DIMENSI 1: IDENTITAS PERGURUAN TINGGI
            // ============================================================
            [
                'dim' => 'IDENTITAS PERGURUAN TINGGI',
                'code' => 'q5',
                'question' => 'Q5. Jumlah perkiraan mahasiswa aktif secara keseluruhan di perguruan tinggi dalam satu (1) tahun terakhir',
                'type' => 'number',
                'options' => [],
            ],
            [
                'dim' => 'IDENTITAS PERGURUAN TINGGI',
                'code' => 'q6',
                'question' => 'Q6. Jumlah perkiraan mahasiswa disabilitas di perguruan tinggi dalam satu (1) tahun terakhir',
                'type' => 'number',
                'options' => [],
            ],
            [
                'dim' => 'IDENTITAS PERGURUAN TINGGI',
                'code' => 'q6a',
                'question' => 'Q6a. Mahasiswa disabilitas di perguruan tinggi memiliki tipe disabilitas berikut ini (Isi angka per tipe):',
                'type' => 'matrix', 
                'options' => [
                    'Gangguan pengelihatan (netra) total', 'Low Vision', 'Buta warna',
                    'Gangguan pendengaran (rungu) total', 'Kesulitan mendengar',
                    'Disabilitas fisik sedang', 'Disabilitas fisik berat', 'Disabilitas fisik ringan',
                    'Disabilitas intelektual', 'Spektrum autisme', 'ADHD', 'Gangguan Psikososial', 'Multidisabilitas'
                ],
            ],
            [
                'dim' => 'IDENTITAS PERGURUAN TINGGI',
                'code' => 'q7',
                'question' => 'Q7. Jumlah perkiraan pegawai perguruan tinggi (dosen dan tenaga penunjang akademik) secara keseluruhan',
                'type' => 'number',
                'options' => [],
            ],
            [
                'dim' => 'IDENTITAS PERGURUAN TINGGI',
                'code' => 'q8',
                'question' => 'Q8. Jumlah perkiraan dosen dan tenaga penunjang akademik (TPA) penyandang disabilitas',
                'type' => 'number',
                'options' => [],
            ],

            // ============================================================
            // DIMENSI 2: INKLUSI DISABILITAS DALAM KELEMBAGAAN
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q9',
                'question' => 'Q9. Apakah perguruan tinggi telah memiliki Unit Layanan Disabilitas (ULD) atau unit serupa?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q9b',
                'question' => 'Q9b. Deskripsikan tantangan dan hambatan yang dialami jika perguruan tinggi belum memiliki ULD',
                'type' => 'textarea',
                'options' => [],
                'dependency' => ['parent' => 'q9', 'value' => 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q10',
                'question' => 'Q10. Apakah perguruan tinggi telah memiliki aturan resmi/SOP untuk menjalankan ULD/unit serupa?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q10a',
                'question' => 'Q10a. Unggah dokumen pendukung SOP sebagai rujukan',
                'type' => 'file',
                'options' => [],
                'dependency' => ['parent' => 'q10', 'value' => 'Sudah'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q11',
                'question' => 'Q11. Apakah inklusi disabilitas sudah termasuk dalam rencana strategis perguruan tinggi?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q12',
                'question' => 'Q12. Peran staf dan mahasiswa disabilitas dalam ULD atau unit serupa adalah sebagai:',
                'type' => 'checkbox',
                'options' => ['Pimpinan', 'Anggota atau pengurus', 'Belum ada staf/mhs disabilitas terlibat', 'Lainnya'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q13',
                'question' => 'Q13. Dalam upaya membangun inklusi disabilitas, perguruan tinggi telah bermitra dengan:',
                'type' => 'checkbox',
                'options' => ['Perguruan tinggi lainnya', 'Industri', 'Pemerintah', 'LSM', 'Lainnya', 'Belum ada'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KELEMBAGAAN',
                'code' => 'q15',
                'question' => 'Q15. Kinerja ULD atau unit serupa diukur dan dievaluasi dalam periode:',
                'type' => 'select',
                'options' => ['Belum pernah dievaluasi', 'Per tahun', 'Per kuartil', 'Per semester', 'Tidak menentu'],
            ],

            // ============================================================
            // DIMENSI 3: KEMAHASISWAAN & KEPEGAWAIAN
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEMAHASISWAAN DAN KEPEGAWAIAN',
                'code' => 'q16',
                'question' => 'Q16. Inklusi disabilitas telah ditanamkan dalam program orientasi mahasiswa dan staf?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEMAHASISWAAN DAN KEPEGAWAIAN',
                'code' => 'q17',
                'question' => 'Q17. Perguruan tinggi telah menyediakan berbagai pelatihan berikut ini:',
                'type' => 'checkbox',
                'options' => [
                    'Pelatihan mentor/relawan', 'Pelatihan staf penerimaan', 'Pelatihan dosen (pengajaran inklusif)', 
                    'Pelatihan awareness staf', 'Pelatihan aksesibilitas digital', 'Pelatihan LMS untuk disabilitas', 'Lainnya'
                ],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEMAHASISWAAN DAN KEPEGAWAIAN',
                'code' => 'q17a',
                'question' => 'Q17a. Unggah dokumen pendukung pelatihan',
                'type' => 'file',
                'options' => [],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEMAHASISWAAN DAN KEPEGAWAIAN',
                'code' => 'q19',
                'question' => 'Q19. Staf dan mahasiswa disabilitas berpartisipasi dalam perancangan:',
                'type' => 'checkbox',
                'options' => ['Layanan dukungan', 'Desain kurikulum', 'Penelitian', 'Pengabdian masyarakat', 'Evaluasi kinerja PT', 'Kebijakan inklusi'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEMAHASISWAAN DAN KEPEGAWAIAN',
                'code' => 'q21',
                'question' => 'Q21. Mahasiswa disabilitas terlibat aktif dalam organisasi mahasiswa (BEM/HIMA)?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],

            // ============================================================
            // DIMENSI 4: PROSES ADMISI
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES ADMISI',
                'code' => 'q23',
                'question' => 'Q23. Lowongan kerja khusus untuk staf disabilitas telah tersedia?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES ADMISI',
                'code' => 'q24',
                'question' => 'Q24. Telah tersedia jalur penerimaan khusus untuk calon mahasiswa disabilitas?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES ADMISI',
                'code' => 'q26',
                'question' => 'Q26. Penyaringan awal kebutuhan dukungan sebelum masuk dilakukan?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],

            // ============================================================
            // DIMENSI 5: PROSES PEMBELAJARAN
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES PEMBELAJARAN',
                'code' => 'q28',
                'question' => 'Q28. Modifikasi kurikulum (CPL/Metode/Penilaian) telah diterapkan?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES PEMBELAJARAN',
                'code' => 'q30',
                'question' => 'Q30. Format materi alternatif tersedia:',
                'type' => 'checkbox',
                'options' => ['Braille', 'Audio book/video', 'PPT Aksesibel', 'Diktat/modul', 'Tools interaktif', 'Lainnya'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES PEMBELAJARAN',
                'code' => 'q31',
                'question' => 'Q31. Accessibility checker (Word/PPT) digunakan?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM PROSES PEMBELAJARAN',
                'code' => 'q32',
                'question' => 'Q32. Penerapan Universal Design for Learning (UDL)?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],

            // ============================================================
            // DIMENSI 6: RISET
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM RISET',
                'code' => 'q33',
                'question' => 'Q33. Publikasi pengetahuan ilmiah terkait inklusi:',
                'type' => 'checkbox',
                'options' => ['Penelitian', 'Pengabdian masyarakat', 'Bahan ajar', 'Policy brief', 'Lainnya'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM RISET',
                'code' => 'q35',
                'question' => 'Q35. Keterbukaan data mahasiswa & penelitian disabilitas:',
                'type' => 'radio',
                'options' => ['Hanya staf PT', 'Seluruh civitas academica', 'Umum/Publik', 'Sebagian Umum'],
            ],

            // ============================================================
            // DIMENSI 7: SARANA DAN PRASARANA
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM SARANA DAN PRASARANA',
                'code' => 'q36',
                'question' => 'Q36. Inklusi jadi pertimbangan pengadaan/tender?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM SARANA DAN PRASARANA',
                'code' => 'q38',
                'question' => 'Q38. Fasilitas pendukung tersedia:',
                'type' => 'checkbox',
                'options' => [
                    'Petunjuk Braille', 'Blok pemandu', 'Toilet disabilitas', 'Lift', 'Ramp', 
                    'Parkir disabilitas', 'Layanan mobilitas', 'Text-to-Speech', 'Screen reader', 
                    'Alat bantu dengar', 'LMS Aksesibel', 'Peta interaktif', 'Lainnya'
                ],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM SARANA DAN PRASARANA',
                'code' => 'q38a',
                'question' => 'Q38a. Unggah foto fasilitas pendukung',
                'type' => 'file',
                'options' => [],
            ],

            // ============================================================
            // DIMENSI 8: DUKUNGAN SOSIAL & KARIR
            // ============================================================
            [
                'dim' => 'DUKUNGAN SOSIAL, MENTAL, DAN KARIR',
                'code' => 'q39',
                'question' => 'Q39. Tersedia mentor/relawan/asisten mahasiswa disabilitas?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'DUKUNGAN SOSIAL, MENTAL, DAN KARIR',
                'code' => 'q40',
                'question' => 'Q40. Dukungan karir:',
                'type' => 'checkbox',
                'options' => ['Konseling karir', 'Magang', 'Kewirausahaan', 'Job fair khusus', 'Lainnya'],
            ],
            [
                'dim' => 'DUKUNGAN SOSIAL, MENTAL, DAN KARIR',
                'code' => 'q42',
                'question' => 'Q42. Dukungan psikologis:',
                'type' => 'checkbox',
                'options' => ['Psikolog', 'Psikiater/SPKJ', 'Lainnya', 'Belum ada'],
            ],

            // ============================================================
            // DIMENSI 9: INFO & KOMUNIKASI
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM INFORMASI & KOMUNIKASI',
                'code' => 'q43',
                'question' => 'Q43. Pedoman tertulis tersedia untuk:',
                'type' => 'checkbox',
                'options' => [
                    'Admisi', 'Etika interaksi', 'Mentoring', 'Konseling', 'Skripsi/TA', 
                    'Magang', 'Pembelajaran daring', 'Bahan ajar aksesibel'
                ],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM INFORMASI & KOMUNIKASI',
                'code' => 'q45',
                'question' => 'Q45. Tersedia hotline/pengaduan khusus?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM INFORMASI & KOMUNIKASI',
                'code' => 'q47',
                'question' => 'Q47. Laporan evaluasi publik tersedia?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],

            // ============================================================
            // DIMENSI 10: KEUANGAN
            // ============================================================
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEUANGAN',
                'code' => 'q49',
                'question' => 'Q49. Ada pendanaan khusus inklusi?',
                'type' => 'radio',
                'options' => ['Sudah', 'Belum'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEUANGAN',
                'code' => 'q49a',
                'question' => 'Q49a. Sumber pendanaan:',
                'type' => 'checkbox',
                'options' => ['Tuition fee', 'Non-tuition fee', 'Dana abadi', 'Hibah eksternal', 'CSR'],
                'dependency' => ['parent' => 'q49', 'value' => 'Sudah'],
            ],
            [
                'dim' => 'INKLUSI DISABILITAS DALAM KEUANGAN',
                'code' => 'q50',
                'question' => 'Q50. Alokasi dana:',
                'type' => 'checkbox',
                'options' => ['Pendidikan', 'Penelitian', 'Pengabdian', 'Sarpras', 'Layanan sosial', 'Komunikasi', 'Beasiswa'],
                'dependency' => ['parent' => 'q49', 'value' => 'Sudah'],
            ],
        ];

        // LOGIKA INSERT KE DATABASE
        $order = 1;
        foreach ($questions as $q) {
            
            // Cek Dependency
            $dependsOnId = null;
            $dependsOnValue = null;

            if (isset($q['dependency'])) {
                $parentCode = $q['dependency']['parent'];
                if (isset($ids[$parentCode])) {
                    $dependsOnId = $ids[$parentCode];
                    $dependsOnValue = $q['dependency']['value'];
                }
            }

            // Insert Soal
            $questionId = DB::table('questions')->insertGetId([
                'dimension' => $q['dim'], 
                'code' => $q['code'],
                'question' => $q['question'],
                'type' => $q['type'],
                'depends_on_question_id' => $dependsOnId,
                'depends_on_value' => $dependsOnValue,
                'is_required' => true,
                'is_active' => true,
                'order_position' => $order++,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Simpan mapping ID
            $ids[$q['code']] = $questionId;

            // Insert Options
            if (!empty($q['options'])) {
                foreach ($q['options'] as $optLabel) {
                    
                    // --- LOGIKA "LAINNYA" ---
                    // Set has_text_input = true jika labelnya "Lainnya"
                    $hasText = ($optLabel === 'Lainnya');

                    DB::table('options')->insert([
                        'question_id' => $questionId,
                        'option_label' => $optLabel,
                        'value' => $optLabel,
                        'has_text_input' => $hasText, // Masukkan ke kolom baru
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}