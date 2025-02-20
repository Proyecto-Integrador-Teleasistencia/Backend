<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('calls', function() {
    return true;
});