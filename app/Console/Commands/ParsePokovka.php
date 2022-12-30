<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\CatalogParam;
use Fanky\Admin\Models\Param;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;
use Fanky\Admin\Text;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
use SiteHelper;
use Symfony\Component\DomCrawler\Crawler;
use SVG\SVG;
use App\Traits\ParseFunctions;

class ParsePokovka extends Command {

    use ParseFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:pokovka';
    private $basePath = ProductImage::UPLOAD_URL . 'pokovka/';
    public $client;
    public $catalogItemListTagElement = '.catalogItemList>ul>li';//где искать список подразделов

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсим поковку';

    public $priceMap = [
        'Поковки' => ['steel', 'length', 'price', 'т']
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->client = new Client([
            'headers' => ['User-Agent' => $this->userAgents[rand(0, count($this->userAgents))]],
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $this->parsePokovkaCategory('Поковки', 'https://mc.ru/metalloprokat/pokovka', 5);
        $this->info('The command was successful!');
    }
}
