<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambahkan kolom wedding_mood ke tabel invitations jika belum ada
        if (!Schema::hasColumn('invitations', 'wedding_mood')) {
            Schema::table('invitations', function (Blueprint $table) {
                $table->string('wedding_mood')->nullable()->after('description');
            });
        }

        // 2. Buat tabel pivot invitation_music jika belum ada
        if (!Schema::hasTable('invitation_music')) {
            Schema::create('invitation_music', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invitation_id')->constrained()->cascadeOnDelete();
                $table->foreignId('music_id')->constrained('music')->cascadeOnDelete();
                $table->timestamps();
            });
        }

        // 3. Tambahkan kolom master data ke tabel music
        Schema::table('music', function (Blueprint $table) {
            $table->string('title')->nullable()->after('id');
            $table->string('artist')->nullable()->after('title');
            $table->string('album')->nullable()->after('artist');
            $table->string('genre')->nullable()->after('album');
            $table->string('mood')->nullable()->after('genre');
            $table->string('language')->nullable()->after('mood');
            $table->string('duration')->nullable()->after('language');
            $table->string('cover')->nullable()->after('duration');
            $table->string('preview_url')->nullable()->after('cover');
            $table->string('status')->default('active')->after('file');
        });

        // 4. Migrasi data lama dari music ke invitation_music
        if (Schema::hasColumn('music', 'invitation_id')) {
            $oldMusic = DB::table('music')->whereNotNull('invitation_id')->get();
            foreach ($oldMusic as $item) {
                // Pastikan entry belum ada di pivot
                $exists = DB::table('invitation_music')
                    ->where('invitation_id', $item->invitation_id)
                    ->where('music_id', $item->id)
                    ->exists();

                if (!$exists) {
                    DB::table('invitation_music')->insert([
                        'invitation_id' => $item->invitation_id,
                        'music_id' => $item->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                // Berikan default meta data untuk data lama agar valid
                $filename = basename($item->file);
                DB::table('music')->where('id', $item->id)->update([
                    'title' => pathinfo($filename, PATHINFO_FILENAME),
                    'artist' => 'Unknown Artist',
                    'genre' => 'Wedding',
                    'mood' => 'Romantic',
                    'status' => 'active',
                ]);
            }

            // 5. Hapus kolom invitation_id dari tabel music
            Schema::table('music', function (Blueprint $table) {
                $table->dropForeign(['invitation_id']);
            });

            Schema::table('music', function (Blueprint $table) {
                $table->dropUnique(['invitation_id']);
                $table->dropColumn('invitation_id');
            });
        }

        // 6. Ubah kolom title, artist, genre, mood menjadi NOT NULL setelah data terisi
        Schema::table('music', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
            $table->string('artist')->nullable(false)->change();
            $table->string('genre')->nullable(false)->change();
            $table->string('mood')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom invitation_id ke tabel music
        if (!Schema::hasColumn('music', 'invitation_id')) {
            Schema::table('music', function (Blueprint $table) {
                $table->unsignedBigInteger('invitation_id')->nullable()->after('id');
            });

            // Kembalikan relasi dari pivot ke tabel music (jika ada)
            $relations = DB::table('invitation_music')->get();
            foreach ($relations as $rel) {
                DB::table('music')->where('id', $rel->music_id)->update([
                    'invitation_id' => $rel->invitation_id
                ]);
            }

            Schema::table('music', function (Blueprint $table) {
                $table->foreignId('invitation_id')->nullable(false)->unique()->change()->constrained()->cascadeOnDelete();
            });
        }

        // Hapus kolom-kolom baru
        Schema::table('music', function (Blueprint $table) {
            $table->dropColumn([
                'title', 'artist', 'album', 'genre', 'mood',
                'language', 'duration', 'cover', 'preview_url', 'status'
            ]);
        });

        // Hapus tabel pivot
        Schema::dropIfExists('invitation_music');

        // Hapus kolom wedding_mood dari invitations
        if (Schema::hasColumn('invitations', 'wedding_mood')) {
            Schema::table('invitations', function (Blueprint $table) {
                $table->dropColumn('wedding_mood');
            });
        }
    }
};
