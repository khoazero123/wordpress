<?php
	require __DIR__ . '/vendor/autoload.php';

    $options = array(
        'cluster' => 'ap1',
        'encrypted' => false
    );
    $pusher = new Pusher\Pusher(
        '7e863eb7e72ea08042e2',
        '43155791c83d338bbd72',
        '370782',
        $options
    );

    $data = [
        'name' => 'admin',
        'message' => "Có xem ko",
    ];
    $pusher->trigger('my-channel', 'my-event', $data);
