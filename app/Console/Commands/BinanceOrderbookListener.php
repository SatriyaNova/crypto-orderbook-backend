<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use WebSocket\Client;

class BinanceOrderbookListener extends Command
{
    protected $signature = 'orderbook:binance';
    protected $description = 'Listen to Binance orderbook via WebSocket';

    public function handle()
    {
        $this->info('Connecting to Binance orderbook WebSocket...');

        $symbol = 'btcusdt';
        $stream = "{$symbol}@depth";
        $url = "ws://stream.binance.com:9443/ws/{$stream}";

        $client = new Client($url);

        while (true) {
            try {
                $message = $client->receive();
                $data = json_decode($message, true);

                // Tampilkan contoh data orderbook di terminal
                $this->line('BID: ' . $data['b'][0][0] . ' | QTY: ' . $data['b'][0][1]);
                $this->line('ASK: ' . $data['a'][0][0] . ' | QTY: ' . $data['a'][0][1]);
                $this->line('---');

                // (Nanti di langkah berikutnya, data ini kita simpan ke database)
            } catch (\Exception $e) {
                $this->error('WebSocket error: ' . $e->getMessage());
                sleep(1);
            }
        }
    }
}
