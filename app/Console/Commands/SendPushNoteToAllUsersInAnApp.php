<?php

namespace App\Console\Commands;

use App\Actions\Dropdowns\GetClientsOptions;
use App\Actions\PushNotes\FireMassPushNotes;

class SendPushNoteToAllUsersInAnApp extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push-notes:send-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send A Push Note To All Users In An App';

    public $cron_name = 'the Push Notes Send-All Command';
    public $cron_log = 'push-notes-send-all-command-log';

    protected $actions;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(GetClientsOptions $options, FireMassPushNotes $fire)
    {
        $this->start = microtime(true);
        parent::__construct();

        $this->actions = [
            'getClients' => $options,
            'fireNotes' => $fire
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function start()
    {
        // Ask for the client
        $client_id = $this->getClient();

        // Ask for the Message Type
        $message_type = $this->getMessageType();

        // Ask for the title
        $msg_title = $this->getMessageTitle();

        // Ask for the description
        $msg_msg = $this->getMessageDesc();

        // Ask for the URL
        $msg_url = $this->getWebHookURL();

        // Execute process
        $this->actions['fireNotes']->execute($client_id, $message_type, $msg_title, $msg_msg, $msg_url, $this);
    }

    private function getClient()
    {
        $options = $this->actions['getClients']->execute('push-notes');
        $this->info('Select a Client');
        foreach($options['select'] as $option)
        {
            $this->info("[{$options['link'][$option['value']]}] - {$option['text']}");
        }

        return $this->ask('Make a selection:');
    }

    private function getMessageType()
    {
        $msg_types = [
            'Custom (One-Time) Message', 'Custom (One-Time) Msg & Page Load'
        ];
        $this->info('Select a Message Type');
        foreach($msg_types as $idx => $msg_type)
        {
            $this->info("[{$idx}] - $msg_type");
        }

        return $this->ask('Make a selection:');
    }

    private function getMessageTitle()
    {
        return $this->ask('Set the Message Title: ');
    }

    private function getMessageDesc()
    {
        return $this->ask('Set the Message Proper: ');
    }

    private function getWebHookURL()
    {
        return $this->ask('Set the URL that will open when the user taps the notification: ');
    }
}
