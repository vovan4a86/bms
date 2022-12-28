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

class ParseTruba extends Command {

    use ParseFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:truba';
    private $basePath = ProductImage::UPLOAD_URL . 'trubi/';
    public $client;
    public $catalogItemListTagElement = '.catalogItemList>ul>li';//где искать список подразделов

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсим трубу';

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

    //                                                                                                           формат      2-для квадрата/прямоугольника
    //      0          1          2          3         4           5              6  [3,4,5]       7           8[1-30x2, 2-30x20x2]     9
    //[2 колонка, 3 колонка, 5 колонка, 6 колонка, 7 колонка, по какой цене=inStock, measure, искать стенку,             measure2]
    public $priceMap = [
        'Трубы г/д' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы х/д' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+

        'Трубы ВГП' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],
        'Трубы ВГП оцинкованные ГОСТ 3262-75' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы электросварные круглые' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы электросварные квадратные' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы электросварные прямоугольные' => ['steel', 'length', '', 'price', '', 3, 'т', 2, 'м'],//+
        'Трубы электросварные в изоляции ППУ' => ['steel', 'length', '', 'price_per_metr', '', 3, 'м', 1],//++
        'Трубы круглые оцинкованные' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы квадратные оцинкованные' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы прямоугольные оцинкованные' => ['steel', 'length', '', 'price', '', 3, 'т', 2, 'м'],//+

        'Трубы электросварные низколегированные круглые' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы электросварные низколегированные квадратные' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы электросварные низколегированные прямоугольные' => ['steel', 'length', '', 'price', '', 3, 'т', 2, 'м'],//+

        'Трубы оцинкованные круглые' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы оцинкованные квадратные' => ['steel', 'length', '', 'price', '', 3, 'т', 1, 'м'],//+
        'Трубы оцинкованные прямоугольные' => ['steel', 'length', '', 'price', '', 3, 'т', 2, 'м'],//+

        'Трубы нержавеющие электросварные' => ['steel', 'length', 'price_per_item', 'price', 'price_per_metr', 4, 'м', 1, 'т'], //есть за метр
        'Трубы нержавеющие электросварные AISI' => ['steel', 'length', 'price_per_item', 'price', 'price_per_metr', 4, 'м', 1, 'т'],//есть за метр
        'Трубы нержавеющие электросварные AISI квадратные' => ['steel', 'length', '', 'price', 'price_per_metr', 4, 'м', 1, 'т'],//есть за метр
        'Трубы нержавеющие электросварные AISI прямоугольные' => ['steel', 'length', '', 'price', 'price_per_metr', 4, 'м', 2, 'т'], //есть за метр
        'Трубы нержавеющие бесшовные' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 1, 'м'], //есть за метр

        'Сваи винтовые' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
        'Трубы чугунные' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 0],//+

    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
//        $this->test();
//        $this->parseCategory('Трубы электросварные квадратные', 'https://mc.ru/metalloprokat/truby_ehlektrosvarnye_kvadratnye');
//        $this->parseCategory('Трубы электросварные низколегированные круглые', 'https://mc.ru/metalloprokat/truby_elektrosvarnye_nizkolegirovannye_kruglye');
//        exit();

//        $name = 'Трубы прямоугольные оцинкованные 30х20х0.8';
//        $size = '30';
//        $res = $this->parseProductWallFromString($name, $size, true);
//        $this->info($res);
//        exit();

//        $this->parseCategory('Трубы нержавеющие бесшовные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_besshovnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные AISI прямоугольные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_aisi_pryamougolnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_a');
//        exit();
//        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
//            if ($categoryName == 'Трубы чугунные' || $categoryName == 'Сваи винтовые') {
//                $this->parseChugunCat($categoryName, $categoryUrl);
//            } else {
//                $this->parseCategory($categoryName, $categoryUrl);
//            }
//        }

        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            $this->parseCategory($categoryName, $categoryUrl, 1);
        }
        $this->info('The command was successful!');
    }

    public function categoryList(): array {
        return [
            'Трубы г/д' => 'https://mc.ru/metalloprokat/truby_g_d',
            'Трубы х/д' => 'https://mc.ru/metalloprokat/truby_h_d',
            'ВГП, электросварные трубы' => 'https://mc.ru/metalloprokat/vgp_elektrosvarnye_truby',
            'Трубы электросварные низколегированные' => 'https://mc.ru/metalloprokat/truby_elektrosvarnye_nizkolegirovannye',
            'Трубы оцинкованные' => 'https://mc.ru/metalloprokat/truby_ocinkovannye',
            'Трубы нержавеющие' => 'https://mc.ru/metalloprokat/truby_nerzhavejka_a',
            'Сваи винтовые' => 'https://mc.ru/metalloprokat/svai_vintovye',
            'Трубы чугунные' => 'https://mc.ru/metalloprokat/truby_chugun_sml',
        ];
    }

}
