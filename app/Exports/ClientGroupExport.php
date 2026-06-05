<?php

namespace App\Exports;

use App\Client;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientGroupExport implements FromCollection
{

    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return  Client::where('groupid', $this->id)->get();
    }
}
