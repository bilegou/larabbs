<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $categories = [
            [
                'name'        => 'laravel讨论',
                'description' => '分享学习方法，探讨发现',
            ],
            [
                'name'        => '前端技术讨论',
                'description' => '前端框架开发、js,css推荐扩展包等',
            ],
            [
                'name'        => '后端技术讨论',
                'description' => '后端框架开发，数据库以及composer依赖',
            ],
            [
                'name'        => '综合讨论区',
                'description' => '开发爱好者综合讨论，水贴地方',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         DB::table('categories')->truncate();
    }
}
