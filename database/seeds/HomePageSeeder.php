<?php

use App\Page;
use App\EthnicOrigin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class HomePageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        Page::create([
            'title'     => 'Introducing IserveU',
            'text'      => '<p>Welcome to IserveU, the world-leading eDemocracy and collaberate decision making tool</p><h2>Democratic Progress</h2><p>Enhance your democracy with a tool that We’re excited to provide this open-source software to let you vote on, engage with, and influence decisions about issues you find important.</p><p>Vote on issues, start conversations, and engage with representatives using the tool in real-time and from the convience of your phone.</p><h2>Decision Making</h2><p>Enhance your non-profit or workplace by using the wisdom of crowds. Empower your people by giving them a meaningful stake in your organisation.</p>'
        
        ]);

    }
}
