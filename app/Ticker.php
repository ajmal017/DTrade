<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{

    private $market;

    public function __construct($symbol = null)
    {
        parent::__construct();

        $this->market = new Market();
        $updatedAt = $this->getUpdatedAtColumn();
        $currentTime = $this->freshTimestamp();
        // Automatically update the model at most every 30 minutes
        if ($updatedAt == null || $currentTime->diffInMinutes($updatedAt) > 30) {
            $this->data = $this->market->realTime($this->symbol);
            $this->save();
        }
    }

    public function history()
    {
        return $this->hasMany(TickerHistory::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

}
