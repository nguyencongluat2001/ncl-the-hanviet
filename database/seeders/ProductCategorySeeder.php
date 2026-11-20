<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Tạo các danh mục sản phẩm dùng cho trang chủ (config/landing.php)
     */
    public function run(): void
    {
        $cate = config('landing.cate');
        if (!\DB::table('cates')->where('code_cate', $cate)->exists()) {
            \DB::table('cates')->insert([
                'id' => (string)\Str::uuid(),
                'name' => 'Danh mục sản phẩm',
                'code_cate' => $cate,
                'decision' => '',
                'order' => \DB::table('cates')->count() + 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
        foreach (config('landing.sections') as $key => $section) {
            if (\DB::table('categorys')->where('code_category', $section['code_category'])->exists()) {
                continue;
            }
            \DB::table('categorys')->insert([
                'id' => (string)\Str::uuid(),
                'cate' => $cate,
                'name_category' => $section['menu'],
                'code_category' => $section['code_category'],
                'decision' => '',
                'order' => $key + 1,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
