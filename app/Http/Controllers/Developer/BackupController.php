<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function index()
    {
        $backups = collect(File::files(storage_path('app/backups')))
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            })
            ->map(function ($file) {
                return [
                    'filename' => $file->getFilename(),
                    'size' => $this->formatSize($file->getSize()),
                    'date' => date('d M Y H:i', $file->getMTime()),
                ];
            });

        return view('developer.backup.index', compact('backups'));
    }

    public function download()
    {
        // Set max execution time
        ini_set('max_execution_time', 300);

        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Buat folder jika belum ada
        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        // Ambil semua tabel
        $tables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE');
        $tableKey = 'Tables_in_' . $dbName;

        $output = "-- Omzetly Backup\n";
        $output .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n";
        $output .= "-- Database: {$dbName}\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Skip tabel log jika terlalu besar
            if ($tableName === 'activity_logs')
                continue;

            // DROP TABLE
            $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";

            // CREATE TABLE
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $output .= $createTable[0]->{'Create Table'} . ";\n\n";

            // INSERT DATA
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $output .= "LOCK TABLES `{$tableName}` WRITE;\n";
                $output .= "INSERT INTO `{$tableName}` VALUES\n";

                $values = [];
                foreach ($rows as $row) {
                    $row = (array) $row;
                    $vals = array_map(function ($val) {
                        if (is_null($val))
                            return 'NULL';
                        return "'" . str_replace("'", "\'", $val) . "'";
                    }, $row);
                    $values[] = "(" . implode(', ', $vals) . ")";
                }
                $output .= implode(",\n", $values) . ";\n";
                $output .= "UNLOCK TABLES;\n\n";
            }
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // Simpan file
        File::put($path, $output);

        // Log
        ActivityLog::log('backup', 'system', "Backup database dibuat: {$filename}");

        // Download
        return Response::download($path, $filename)->deleteFileAfterSend(false);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:sql,txt|max:51200', // max 50MB
        ]);

        try {
            $file = $request->file('backup_file');
            $sql = File::get($file->getRealPath());

            // Disable foreign key checks
            DB::unprepared('SET FOREIGN_KEY_CHECKS=0;');

            // Split by semicolon
            $statements = array_filter(
                array_map('trim', explode(';', $sql))
            );

            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    DB::unprepared($statement . ';');
                }
            }

            // Enable foreign key checks
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');

            ActivityLog::log('restore', 'system', "Restore database dari file");

            return back()->with('success', 'Database berhasil direstore!');
        } catch (\Exception $e) {
            // Enable foreign key checks if error
            DB::unprepared('SET FOREIGN_KEY_CHECKS=1;');

            ActivityLog::log('restore_failed', 'system', "Gagal restore: " . $e->getMessage());

            return back()->with('error', 'Gagal restore: ' . $e->getMessage());
        }
    }

    public function delete($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (File::exists($path)) {
            File::delete($path);
            ActivityLog::log('delete', 'backup', "Hapus file backup: {$filename}");
            return back()->with('success', 'File backup dihapus!');
        }

        return back()->with('error', 'File tidak ditemukan!');
    }

    private function formatSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }
}