<?php

declare(strict_types=1);

use Tests\Datasets\CategoryRefreshed;

it('expects to refresh model attributes', function () {
    $user = new CategoryRefreshed();
    $user->save();
    $user->refresh();

    expect($user->sort)->not->toBeNull();

});
