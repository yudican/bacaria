<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignUuid('author_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignUuid('approved_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignUuid('rejected_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('uid_post', 11);
            $table->string('title');
            $table->enum('type', ['post', 'story'])->default('post');
            $table->string('slug');
            $table->string('image')->nullable();
            $table->text('caption')->nullable();
            $table->text('content');
            $table->enum('status', ['draft', 'publish'])->default('draft');
            $table->enum('comment_status', ['open', 'close'])->default('open');
            $table->enum('publish_status', ['rejected', 'published', 'waiting'])->default('waiting');
            $table->string('editor')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
