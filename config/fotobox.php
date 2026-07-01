<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Photo Retention
    |--------------------------------------------------------------------------
    |
    | Photos and their associated jobs older than this many hours will be
    | automatically deleted by the photos:cleanup scheduled command.
    |
    */

    'photo_retention_hours' => (int) env('PHOTO_RETENTION_HOURS', 24),

];
