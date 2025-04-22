<?php

if (!app()->bound('files')) {
    app()->singleton('files', function () {
        return new \Illuminate\Filesystem\Filesystem;
    });
}
