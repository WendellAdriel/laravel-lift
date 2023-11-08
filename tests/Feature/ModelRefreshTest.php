<?php

declare(strict_types=1);

use Tests\Datasets\CategoryRefreshed;

it('expects to refresh model attributes', function () {
    $category = new CategoryRefreshed([
        'title' => 'test',
    ]);
    $category->title = 'test updated';
    $category->save();
    $category->refresh();

    expect($category->sort)->not->toBeNull();
    expect($category->title)->toBe('test updated');

});
