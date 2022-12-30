<?php

namespace App\Console\Commands;

use App\Traits\ParseFunctions;
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

class ParseCvetnoj extends Command {

    use ParseFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:cvetnoj';
    private $basePath = ProductImage::UPLOAD_URL . 'cvetnoj/';
    public $client;
    public $catalogItemListTagElement = '.gr_spis>ul>li'; //где искать список подразделов

    //      0          1          2          3         4           5              6  [3,4,5]       7           8
    //[2 колонка, 3 колонка, 5 колонка, 6 колонка, 7 колонка, по какой цене=inStock, measure, искать стенку, measure2]
    public $priceMap = [
        'Круг алюминиевый (пруток)' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'], //+
        'Круг дюралевый (пруток)' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'],//+
        'Лента алюминиевая' => ['steel', 'length', '', 'price_per_kilo', 'price', 4, 'т', 0, 'кг'], //++
        'Лист алюминиевый' => ['steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//++
        'Лист алюминиевый рифленый' => ['steel', 'comment', 'price_per_m2', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'], //++
        'Лист дюралевый' => ['steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Плита алюминиевая' => ['steel', 'length', 'price_per_item', 'price_per_m2', 'price_per_kilo', 4, 'кг', 0, 'м2'], //+m2
        'Плита дюралевая' => ['steel', 'length', 'price_per_item', 'price_per_m2', 'price_per_kilo', 4, 'кг', 0, 'м2'],//+m2
        'Проволока алюминиевая' => ['steel', 'length', '', 'price_per_kilo', 'price', 4, 'т', 0, 'кг'],//+
        'Труба алюминиевая' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Труба дюралевая' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Уголок алюминиевый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Чушка алюминиевая' => ['steel', 'length', '', 'price_per_kilo', 'price', 4, 'т', 0, 'кг'],//+
        'Швеллер алюминиевый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'],//+
        'Шестигранник дюралевый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'],//+
        'Шина алюминиевая' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'],//+

        'Полоса' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 0, 'м'],//+
        'Профиль квадратный трубчатый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Профиль круглый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Профиль П-образный' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+
        'Профиль прямоугольный трубчатый' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 2, 'кг', 1, 'м'],//+
        'Профиль угловой' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 4, 'кг', 1, 'м'],//+

        'Квадрат латунный' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Круг бронзовый (пруток)' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Круг латунный (пруток)' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Круг медный (пруток)' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Лента латунная' => ['steel', 'length', '', 'price_per_kilo', 'price', 4, 'т', 0, 'кг'],//+
        'Лента медная' => ['steel', 'length', '', 'price_per_kilo', 'price', 4, 'т', 0, 'кг'],//+
        'Лист латунный' => ['steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Лист медный' => ['steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Труба латунная' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 1, 'шт'],//+
        'Труба медная' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 1, 'шт'],//+
        'Шестигранник латунный' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
        'Шина медная' => ['steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 4, 'кг', 0, 'шт'],//+
    ];

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсим цветмет';

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
        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            $this->parseCategory($categoryName, $categoryUrl, 2);
        }
        $this->info('The command was successful!');
    }

    public function categoryList(): array {
        return [
            'Алюминий, дюраль' => 'https://mc.ru/metalloprokat/alyuminy_dyural',
            'Медь, бронза, латунь' => 'https://mc.ru/metalloprokat/med_bronza_latun',
//            'Олово, cвинец, цинк' => 'https://mc.ru/metalloprokat/olovo_svinec_cynk', //пока нет информации о наличии товаров
        ];
    }
}
