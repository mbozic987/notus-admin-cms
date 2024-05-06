<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Main Category 1',
                'subcategories' => [
                    [
                        'name' => 'Subcategory 1.1',
                        'subcategories' => [
                            [
                                'name' => 'Sub-subcategory 1.1.1',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 1.1.1.1'],
                                    ['name' => 'Sub-sub-subcategory 1.1.1.2'],
                                ]
                            ],
                            [
                                'name' => 'Sub-subcategory 1.1.2',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 1.1.2.1'],
                                    ['name' => 'Sub-sub-subcategory 1.1.2.2'],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'Subcategory 1.2',
                        'subcategories' => [
                            [
                                'name' => 'Sub-subcategory 1.2.1',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 1.2.1.1'],
                                    ['name' => 'Sub-sub-subcategory 1.2.1.2'],
                                ]
                            ],
                            [
                                'name' => 'Sub-subcategory 1.2.2',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 1.2.2.1'],
                                    ['name' => 'Sub-sub-subcategory 1.2.2.2'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'Main Category 2',
                'subcategories' => [
                    [
                        'name' => 'Subcategory 2.1',
                        'subcategories' => [
                            [
                                'name' => 'Sub-subcategory 2.1.1',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 2.1.1.1'],
                                    ['name' => 'Sub-sub-subcategory 2.1.1.2'],
                                ]
                            ],
                            [
                                'name' => 'Sub-subcategory 2.1.2',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 2.1.2.1'],
                                    ['name' => 'Sub-sub-subcategory 2.1.2.2'],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'Subcategory 2.2',
                        'subcategories' => [
                            [
                                'name' => 'Sub-subcategory 2.2.1',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 2.2.1.1'],
                                    ['name' => 'Sub-sub-subcategory 2.2.1.2'],
                                ]
                            ],
                            [
                                'name' => 'Sub-subcategory 2.2.2',
                                'subcategories' => [
                                    ['name' => 'Sub-sub-subcategory 2.2.2.1'],
                                    ['name' => 'Sub-sub-subcategory 2.2.2.2'],
                                ]
                            ],
                        ]
                    ],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create([
                'name' => $categoryData['name'],
                'depth' => 0,
            ]);
            $this->createSubcategories($categoryData['subcategories'], $category, 1);
        }
    }

    protected function createSubcategories($subcategories, $parentCategory, $depth)
    {
        foreach ($subcategories as $subcategoryData) {
            $subcategory = Category::create([
                'name' => $subcategoryData['name'],
                'parent_id' => $parentCategory->id,
                'depth' => $depth,
            ]);
            if (isset($subcategoryData['subcategories']) && $depth < 3) {
                $this->createSubcategories($subcategoryData['subcategories'], $subcategory, $depth + 1);
            }
        }
    }
}
