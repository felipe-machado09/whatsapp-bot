<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableNewsMirrorsChangeLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_mirrors', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('link')->change();
            $table->text('guid')->change();
            $table->text('author')->nullable()->after('description');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_mirrors', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('link')->change();
            $table->text('guid')->change();
            $table->dropColumn('author');

        });
    }
}
