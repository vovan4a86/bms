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

class ParseNerzhav extends Command {

    use ParseFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:nerzhav';
    private $basePath = ProductImage::UPLOAD_URL . 'nerzhaveyuschy/';
    public $client;
    public $catalogItemListTagElement = '.catalogItemList>ul>li';//где искать список подразделов

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсим нержавейку';

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
    //      0          1          2          3         4           5              6  [3,4,5]       7           8[1-30x2, 2-30x20x2]
    //[2 колонка, 3 колонка, 5 колонка, 6 колонка, 7 колонка, по какой цене=inStock, measure, искать стенку, measure2]
    public $priceMap = [
        'Круг нержавеющий безникелевый жаропрочный' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 0, 'м'],//+
        'Круг нержавеющий никельсодержащий' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 0, 'м'],//+
        'Квадрат нержавеющий никельсодержащий' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 0, 'м'],//+
        'Шестигранник нержавеющий безникелевый жаропрочный' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 0, 'м'],//+
        'Шестигранник нержавеющий никельсодержащий' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 0, 'м'],//+

        'Полоса нержавеющая никельсодержащая' => ['steel', 'length', '', 'price_per_metr', 'price', 4, 'т', 1, 'м'],
        'Уголок нержавеющий никельсодержащий' => ['steel', 'length', 'price_per_item', 'price_per_metr', 'price', 4, 'т', 1, 'м'],

        'Лист нержавеющий без никеля' => ['steel', 'length', '', 'price_per_item', 'price', 4, 'т', 0, 'шт'], //++
        'Лист нержавеющий никельсодержащий' => ['steel', 'length', '', 'price_per_item', 'price', 4, 'т', 0, 'шт'],//++
        'Лист нержавеющий ПВЛ' => ['steel', 'length', '', 'price_per_item', 'price', 4, 'т', 0, 'шт'],//++

        'Электроды нержавеющие' => ['steel', 'length', '', 'price', '', 3, 'т', 0, 'кг'],//+
        'Проволока нержавеющая' => ['steel', 'length', '', 'price', '', 3, 'т', 0, 'кг'],//+

        'Отводы нержавеющие' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
        'Переходы нержавеющие' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
        'Тройники нержавеющие' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
        'Фланцы нержавеющие воротниковые' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
        'Фланцы нержавеющие плоские' => ['steel', 'length', '', '', 'price_per_item', 4, 'шт', 1],//+
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            $this->parseCategory($categoryName, $categoryUrl, 3);
        }
        $this->info('The command was successful!');
    }

    public function categoryList(): array {
        return [
            'Круг, квадрат, шестигранник' => 'https://mc.ru/metalloprokat/krug_kvadrat_shestigrannik_nerzhavejka',
            'Полоса, уголок' => 'https://mc.ru/metalloprokat/polosa_ugolok_nerzhavejka',
            'Лист нержавеющий' => 'https://mc.ru/metalloprokat/list_nerzhavejka',
            'Нержавеющие метизы' => 'https://mc.ru/metalloprokat/nerzhaveyuschie_metizy',
//            'Комплектующие для лестничных ограждений' => 'https://mc.ru/metalloprokat/kompl_lest_ogr', //нужно ????
            'Детали трубопровода' => 'https://mc.ru/metalloprokat/detali_truboprovoda',
        ];
    }
}
