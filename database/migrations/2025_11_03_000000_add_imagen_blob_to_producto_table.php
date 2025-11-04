<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('producto', 'imagen_blob')) {
            Schema::table('producto', function (Blueprint $table) {
                // Use binary for blob storage (BLOB). For very large images consider storing in filesystem or base64 text.
                $table->binary('imagen_blob')->nullable()->after('imagen');
                $table->string('imagen_mime', 100)->nullable()->after('imagen_blob');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('producto', 'imagen_blob')) {
            Schema::table('producto', function (Blueprint $table) {
                $table->dropColumn(['imagen_blob', 'imagen_mime']);
            });
        }
    }
};
