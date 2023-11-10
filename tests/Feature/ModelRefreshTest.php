<?php

declare(strict_types=1);

use Tests\Datasets\CategoryRefreshed;

it('expects to refresh model attributes', function () {
    $category = new CategoryRefreshed(['title' => 'test']);
    $category->save();

    CategoryRefreshed::find($category->id)->update(['title' => 'test updated']); // indirect update

    $category->refresh();

    expect($category->title)->toBe('test updated');

});
