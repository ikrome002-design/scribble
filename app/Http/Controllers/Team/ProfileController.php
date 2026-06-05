<?php

namespace App\Http\Controllers\Team;


use App\Campaigns;
use App\CampaignSubscriptionList;
use App\Client;
use App\Invoices;
use App\Language;
use App\SMSHistory;
use App\SupportTickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Storage;

class Profile extends Controller
{

    public function __construct()
    {
        $this->middleware('team');
    }
}
