<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryNavToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->boolean('category_nav')->default(0)->after('column_name'); // replace 'column_name' with an actual column if you want to place it after a specific column
    });
}

public function down()
{
    Schema::table('categories', function (Blueprint $table) {
        $table->dropColumn('category_nav');
    });
}

}
