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
    //      0          1          2          3         4           5              6  [3,4,5]       7           8[1-30x2, 2-30x20x2]
    //[1 колонка, 2 колонка, 3 колонка, 5 колонка, 6 колонка, 7 колонка, по какой цене=inStock, measure, искать стенку]
    public $priceMap = [
        'Трубы г/д' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы х/д' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],

        'Трубы ВГП' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы ВГП оцинкованные ГОСТ 3262-75' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы электросварные круглые' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы электросварные квадратные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы электросварные прямоугольные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 2], //стенку искать в другом месте
        'Трубы электросварные в изоляции ППУ' => ['size', 'steel', 'length', '', 'price', '', 4, 'м', 1],
        'Трубы круглые оцинкованные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы квадратные оцинкованные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы прямоугольные оцинкованные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 2], //стенку искать в другом месте

        'Трубы электросварные низколегированные круглые' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы электросварные низколегированные квадратные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы электросварные низколегированные прямоугольные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 2], //стенку искать в другом месте

        'Трубы оцинкованные круглые' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы оцинкованные квадратные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы оцинкованные прямоугольные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 2], //стенку искать в другом месте

        'Трубы нержавеющие электросварные' => ['size', 'steel', 'length', 'price_per_item', 'price', '', 4, 'т', 1], //есть за метр
        'Трубы нержавеющие электросварные AISI' => ['size', 'steel', 'length', 'price_per_item', 'price', '', 4, 'т', 1],//есть за метр
        'Трубы нержавеющие электросварные AISI квадратные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],//есть за метр
        'Трубы нержавеющие электросварные AISI прямоугольные' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 2], //стенку искать в другом месте //есть за метр
        'Трубы нержавеющие бесшовные' => ['size', 'steel', 'length', '', 'price_per_metr', 'price', 5, 'т', 1], //есть за метр

        'Сваи винтовые' => ['size', 'steel', 'length', '', 'price', '', 4, 'т', 1],
        'Трубы чугунные' => ['size', 'steel', 'length', '', '', 'price_per_item', 5, 'шт', 0],

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

    //парсим список товаров
    public function parseListProducts($catalog, $categoryUrl, $subcatName, $priceMap) {
        $this->info('Parse one product from: ' . $catalog->name);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id) : null;

        if (!$subCatalog) {
            $uploadPath = $this->basePath . $catalog->alias . '/';
        } else {
            $uploadPath = $this->basePath . $catalog->alias . '/' . $subCatalog->alias . '/';
        }

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')
            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $uploadPath, $priceMap) {
                $this->info('Parse: ' . ++$n . ' element');

                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $data = [];
                    $usedPrice = $priceMap[6]; //по какому столбцу проверяем наличие
                    if ($priceMap[3] !== null) {
                        $data[$priceMap[3]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(5)->text())); //5 колонка цены
                    }
                    if ($priceMap[4] !== null) {
                        $data[$priceMap[4]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(6)->text())); //6 колонка цены
                    }
                    if ($priceMap[5] !== null) {
                        $data[$priceMap[5]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //7 колонка цены
                    }
                    $data['raw_price'] = $data['price'];
                    $data['price'] = (ceil($data['raw_price'] / 100)) * 100; //округляем в большую сторону
                    $data['measure'] = $priceMap[7];
                    $data['inStock'] = $data[$priceMap[$usedPrice]] ? 1 : 0;

                    $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение и мин.длину
                    if (!$product) {
                        $name = trim($node->filter('.refstr')->first()->text());
                        $data[$priceMap[0]] = trim($node->filter('td')->eq(1)->text());
                        $data[$priceMap[1]] = trim($node->filter('td')->eq(2)->text());
                        $data[$priceMap[2]] = trim($node->filter('td')->eq(3)->text());

                        //если 1 ищем стенку
                        if ($priceMap[8] == 1) {
                            $data['wall'] = $this->parseProductWallFromString($name, $data['size']);
                        } elseif ($priceMap[8] == 2) {
                            $data['wall'] = $this->parseProductWallFromString($name, $data['size'], true);
                        }

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);

                        $order = $subCatalog ? $subCatalog->products()->max('order') + 1 : $catalog->products()->max('order') + 1;

                        $newProd = Product::create(array_merge([
                            'name' => $name,
                            'catalog_id' => $subCatalog ? $subCatalog->id : $catalog->id,
                            'title' => $name,
                            'h1' => $h1,
                            'alias' => $alias,
                            'parse_url' => $url,
                            'published' => 1,
                            'order' => $order,
                        ], $data));

                        $section = $subCatalog ?: $catalog;
                        $product_crawler->filter('.TovInfo img')->each(function ($img, $i) use ($alias, $newProd, $section, $uploadPath) {
                            $imageSrc = $img->attr('src');
                            $fileName = $uploadPath . $alias . '-' . ++$i;
                            $fileName .= $this->checkIsImageJpg($imageSrc) ? '.jpg' : '.svg';

                            if ($this->checkIsImageJpg($imageSrc)) {
                                //делаем изображение для раздела
                                if (!$section->section_image) {
                                    $fileName = $uploadPath . $section->alias . '.jpg';
                                    $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
                                    if ($res) {
                                        $section->section_image = $fileName;
                                        $section->save();
                                    }
                                }
                            } else {
                                $res = $this->downloadSvgFile($imageSrc, $uploadPath, $fileName);
                                if ($res) {
                                    ProductImage::create([
                                        'product_id' => $newProd->id,
                                        'image' => $fileName,
                                        'order' => ProductImage::where('product_id', $newProd->id)->max('order') + 1,
                                    ]);
                                }
                            }

                        });
                        sleep(rand(1, 2));
                    } else {
                        $product->update($data);
                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                        $product->save();
                    }
                } catch (\Exception $e) {
                    $this->info('error: ' . $e->getMessage());
                }
            });

//        проход по страницам
//        $pages = $crawler->filter('.catalogPaginator ul li');
//        $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
//        if ($currentPage < $pages->count()) {
//            $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
//            $this->info('parse: ' . $nextUrl . ' / ' . $pages->count());
//            sleep(rand(1, 2));
//            $this->parseListProducts($categoryName, $nextUrl, $subcatname);
//        }
    }

}
