<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$conversations = \Modules\ProjectManagement\Models\ProjectConversation::all();
if ($conversations->isEmpty()) {
    echo "No conversations found.\n";
    exit;
}

foreach ($conversations as $convo) {
    echo "ID: " . $convo->id . " | Type: " . $convo->type . " | Name: " . $convo->name . "\n";
    echo "  Participants:\n";
    foreach ($convo->participants as $p) {
        $u = $p->user;
        $name = $u ? $u->name : 'Unknown';
        echo "    - User ID: " . $p->user_id . " | Type: " . $p->user_type . " | Name: " . $name . "\n";
    }
    echo "  Messages:\n";
    foreach ($convo->messages as $msg) {
        $senderName = $msg->sender ? $msg->sender->name : 'Unknown';
        echo "    - Msg ID: " . $msg->id . " | Sender: " . $senderName . " | Content: " . $msg->content . " | Read At: " . ($msg->read_at ? $msg->read_at->toDateTimeString() : 'NULL') . "\n";
    }
    echo "----------------------------------------\n";
}
