<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('shares', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique();
            $table->string('path');                      // /srv/hnas/shares/media
            $table->boolean('smb_export')->default(true);
            $table->boolean('nfs_export')->default(false);
            $table->unsignedInteger('quota_gb')->nullable();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('share_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('share_id')->constrained('shares')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('perm', ['read','write','admin']);
            $table->unique(['share_id','user_id']);
        });

        Schema::create('apps', function (Blueprint $table) {
            $table->string('id')->primary();             // pl. jellyfin
            $table->string('title');
            $table->enum('status',['installed','running','stopped'])->default('installed');
            $table->unsignedInteger('http_port')->nullable();
            $table->timestamps();
        });

        Schema::create('app_instances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('app_id');
            $table->string('name');                      // jellyfin-main
            $table->string('bind_path');                 // /srv/hnas/appdata/jellyfin-main
            $table->json('env_json')->nullable();
            $table->enum('status',['running','stopped'])->default('stopped');
            $table->timestamps();

            $table->foreign('app_id')->references('id')->on('apps')->cascadeOnDelete();
            $table->unique(['app_id','name']);
        });

        Schema::create('audit_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->json('payload_json')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('audit_log');
        Schema::dropIfExists('app_instances');
        Schema::dropIfExists('apps');
        Schema::dropIfExists('share_permissions');
        Schema::dropIfExists('shares');
    }
};
