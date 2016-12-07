<?php

$factory->define(App\File::class, function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.png';
    \File::copy(base_path().'/tests/unit/File/test.png', storage_path('app/'.$fileName));

    return [
        'description'   => $faker->sentence,
        'title'         => $faker->sentence,
        'user_id'       => Setting::get('editor.house_writer_id', 1),
        'filename'      => $fileName,
    ];
});

$factory->defineAs(App\File::class, 'image', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.jpg';
    \File::copy(base_path().'/tests/unit/File/test_a.jpg', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});

$factory->defineAs(App\File::class, 'pdf', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.pdf';
    \File::copy(base_path().'/tests/unit/File/test_b.pdf', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'doc', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.doc';
    \File::copy(base_path().'/tests/unit/File/test_doc.doc', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'docx', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.docx';
    \File::copy(base_path().'/tests/unit/File/test_docx.docx', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'ppt', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.ppt';
    \File::copy(base_path().'/tests/unit/File/test_ppt.ppt', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'pptx', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.pptx';
    \File::copy(base_path().'/tests/unit/File/test_pptx.pptx', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'gif', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.gif';
    \File::copy(base_path().'/tests/unit/File/test_gif.gif', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'rar', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.rar';
    \File::copy(base_path().'/tests/unit/File/test_rar.rar', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'zip', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.zip';
    \File::copy(base_path().'/tests/unit/File/test_zip.zip', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'xls', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.xls';
    \File::copy(base_path().'/tests/unit/File/test_xls.xls', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'xlsx', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.xlsx';
    \File::copy(base_path().'/tests/unit/File/test_xlsx.xlsx', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'bmp', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.bmp';
    \File::copy(base_path().'/tests/unit/File/test_bmp.bmp', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'avi', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.avi';
    \File::copy(base_path().'/tests/unit/File/test_avi.avi', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'flv', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.flv';
    \File::copy(base_path().'/tests/unit/File/test_flv.flv', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'wmv', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.wmv';
    \File::copy(base_path().'/tests/unit/File/test_wmv.wmv', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
$factory->defineAs(App\File::class, 'mp3', function (Faker\Generator $faker) use ($factory) {
    $fileName = str_random(64).'.mp3';
    \File::copy(base_path().'/tests/unit/File/test_mp3.mp3', storage_path('app/'.$fileName));

    return array_merge($factory->raw(App\File::class), [
            'filename'      => $fileName,
    ]);
});
