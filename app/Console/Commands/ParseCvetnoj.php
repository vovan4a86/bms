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
    //[1 колонка, 2 колонка, 3 колонка, 5 колонка, 6 колонка, 7 колонка, по какой цене=inStock, measure, искать стенку]
    public $priceMap = [
        'Круг алюминиевый (пруток)' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0], //+ метр
        'Круг дюралевый (пруток)' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0],//+ метр
        'Лента алюминиевая' => ['size', 'steel', 'length', '', 'price_per_kilo', 'price', 5, 'т', 0], //=кг
        'Лист алюминиевый' => ['size', 'steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+ шт
        'Лист алюминиевый рифленый' => ['size', 'steel', 'riffl', 'price_per_m2', 'price_per_item', 'price_per_kilo', 5, 'кг', 0], //есть проблемы с кг кг=шт, кг=0
        'Лист дюралевый' => ['size', 'steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],
        'Плита алюминиевая' => ['size', 'steel', 'length', 'price_per_item', 'price_per_m2', 'price_per_kilo', 5, 'кг', 0], //+m2
        'Плита дюралевая' => ['size', 'steel', 'length', 'price_per_item', 'price_per_m2', 'price_per_kilo', 5, 'кг', 0],//+m2
        'Проволока алюминиевая' => ['size', 'steel', 'length', '', 'price_per_kilo', 'price', 5, 'т', 0],//=кг
        'Труба алюминиевая' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Труба дюралевая' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Уголок алюминиевый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Чушка алюминиевая' => ['size', 'steel', 'length', '', 'price_per_kilo', 'price', 5, 'т', 0],//=кг
        'Швеллер алюминиевый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0],//+м
        'Шестигранник дюралевый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0],//+м
        'Шина алюминиевая' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0],//+м

        'Полоса' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 0],//+м
        'Профиль квадратный трубчатый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Профиль круглый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Профиль П-образный' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м
        'Профиль прямоугольный трубчатый' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 3, 'кг', 1],//+м
        'Профиль угловой' => ['size', 'steel', 'length', 'price_per_item', 'price_per_metr', 'price_per_kilo', 5, 'кг', 1],//+м

        'Квадрат латунный' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Круг бронзовый (пруток)' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Круг латунный (пруток)' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Круг медный (пруток)' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Лента латунная' => ['size', 'steel', 'length', '', 'price_per_kilo', 'price', 5, 'т', 0],//=кг
        'Лента медная' => ['size', 'steel', 'length', '', 'price_per_kilo', 'price', 5, 'т', 0],//=кг
        'Лист латунный' => ['size', 'steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Лист медный' => ['size', 'steel', 'length', 'price_per_m2', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Труба латунная' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 1],//+шт
        'Труба медная' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 1],//+шт
        'Шестигранник латунный' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
        'Шина медная' => ['size', 'steel', 'length', 'price_per_metr', 'price_per_item', 'price_per_kilo', 5, 'кг', 0],//+шт
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
//            'Медь, бронза, латунь' => 'https://mc.ru/metalloprokat/med_bronza_latun',
//            'Олово, cвинец, цинк' => 'https://mc.ru/metalloprokat/olovo_svinec_cynk', //пока нет информации о наличии товаров
        ];
    }

    //парсим список товаров
//    public function parseListProducts($catalog, $categoryUrl, $subcatName, $priceMap) {
//        if (!$priceMap) $this->info('No PRICE MAP!');
//        $this->info('[section] ' . $catalog->name);
//        $res = $this->client->get($categoryUrl);
//        $html = $res->getBody()->getContents();
//        $crawler = new Crawler($html); //page from url
//
//        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id) : null;
//
//        if (!$subCatalog) {
//            $uploadPath = $this->basePath . $catalog->alias . '/';
//        } else {
//            $uploadPath = $this->basePath . $catalog->alias . '/' . $subCatalog->alias . '/';
//        }
//
//        $table = $crawler->filter('table')->first(); //table of products
//        $table->filter('tbody tr')
//            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $uploadPath, $priceMap) {
//                $this->info('Parse: ' . ++$n . ' element');
//
//                try {
//                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));
//
//                    $data = [];
//                    $usedPrice = $priceMap[6]; //по какому столбцу проверяем наличие
//                    if ($priceMap[3] !== null) {
//                        $data[$priceMap[3]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(5)->text())); //5 колонка цены
//                    }
//                    if ($priceMap[4] !== null) {
//                        $data[$priceMap[4]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(6)->text())); //6 колонка цены
//                    }
//                    if ($priceMap[5] !== null) {
//                        $data[$priceMap[5]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //7 колонка цены
//                    }
//                    $data['measure'] = $priceMap[7];
//
//                    $data['inStock'] = $data[$priceMap[$usedPrice]] ? 1 : 0;
//
//                    $product = Product::whereParseUrl($url)->first();
////                если новый товар -> заходим на страничку и получаем изображение и мин.длину
//                    if (!$product) {
//                        $name = trim($node->filter('.refstr')->first()->text());
//                        $data[$priceMap[0]] = trim($node->filter('td')->eq(1)->text());
//                        $data[$priceMap[1]] = trim($node->filter('td')->eq(2)->text());
//                        $data[$priceMap[2]] = trim($node->filter('td')->eq(3)->text());
//                        if ($priceMap[8] == 1) $data['wall'] = $this->parseProductWallFromString($name, $data['size']);
//
//                        $html_product = $this->client->get($url);
//                        $inner_html = $html_product->getBody()->getContents();
//                        $product_crawler = new Crawler($inner_html);
//                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
//                        $alias = Text::translit($h1);
//
//                        $order = $subCatalog ? $subCatalog->products()->max('order') + 1 : $catalog->products()->max('order') + 1;
//
//                        $newProd = Product::create(array_merge([
//                            'name' => $name,
//                            'catalog_id' => $subCatalog ? $subCatalog->id : $catalog->id,
//                            'title' => $name,
//                            'h1' => $h1,
//                            'alias' => $alias,
//                            'parse_url' => $url,
//                            'published' => 1,
//                            'order' => $order,
//                        ], $data));
//
//                        $section = $subCatalog ?: $catalog;
//                        $product_crawler->filter('.TovInfo img')->each(function ($img, $i) use ($alias, $newProd, $section, $uploadPath) {
//                            $imageSrc = $img->attr('src');
//                            $fileName = $uploadPath . $alias . '-' . ++$i;
//                            $fileName .= $this->checkIsImageJpg($imageSrc) ? '.jpg' : '.svg';
//
//                            if ($this->checkIsImageJpg($imageSrc)) {
//                                //делаем изображение для раздела
//                                if (!$section->section_image) {
//                                    $fileName = $uploadPath . $section->alias . '.jpg';
//                                    $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
//                                    if ($res) {
//                                        $section->section_image = $fileName;
//                                        $section->save();
//                                    }
//                                }
//                            } else {
//                                $res = $this->downloadSvgFile($imageSrc, $uploadPath, $fileName);
//                                if ($res) {
//                                    ProductImage::create([
//                                        'product_id' => $newProd->id,
//                                        'image' => $fileName,
//                                        'order' => ProductImage::where('product_id', $newProd->id)->max('order') + 1,
//                                    ]);
//                                }
//                            }
//
//                        });
//                        sleep(rand(1, 2));
//                    } else {
//                        $product->update($data);
//                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
//                        $product->save();
//                    }
//                } catch (\Exception $e) {
//                    $this->info('error: ' . $e->getMessage());
//                }
//            });
//
////        проход по страницам
////        $pages = $crawler->filter('.catalogPaginator ul li');
////        $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
////        if ($currentPage < $pages->count()) {
////            $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
////            $this->info('parse: ' . $nextUrl . ' / ' . $pages->count());
////            sleep(rand(1, 2));
////            $this->parseListProducts($categoryName, $nextUrl, $subcatname);
////        }
//    }

}
