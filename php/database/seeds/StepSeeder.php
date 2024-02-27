<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curriculum = DB::table('curriculums')
            ->where('name', 'カリキュラム1')
            ->first();
        DB::table('steps')->insert([
            [
                'curriculum_id' => '1',
                'name' => 'ステップ1',
                'content' => 'ステップ説明ステップ説明ステップ説明ステップ説明ステップ説明ステップ説明ステップ説明ステップ説明',
                'image' => 'img.png',
                'target_days' => '3',
                'deadline_days' => '5',
                'order' => '1',
                'created_at' => '2020-01-01 00:00:00',
                'updated_at' => '2020-01-01 00:00:00'
            ]
        ]);
    }
}
