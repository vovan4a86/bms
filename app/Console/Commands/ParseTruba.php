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

class ParseTruba extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:truba';
    private $baseUrl = 'https://mc.ru';
    private $basePath = ProductImage::UPLOAD_URL . 'trubi/';
    public $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

    private $userAgents = [
        "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13.0; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Linux i686; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (X11; Fedora; Linux x86_64; rv:106.0) Gecko/20100101 Firefox/106.0",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Safari/605.1.15",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)",
        "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0)",
        "Mozilla/4.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)",
        "Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)",
        "Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 6.2; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 10.0; Trident/7.0; rv:11.0) like Gecko",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/106.0.1370.52",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Edg/106.0.1370.52",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 Vivaldi/5.5.2805.38",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Windows NT 10.0; WOW64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36 OPR/92.0.4561.21",
        "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
        "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 13_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 YaBrowser/22.9.1 Yowser/2.5 Safari/537.36",
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

//        $name = 'трубы горячедеформированные 45x5';
//        $size = '45';
//        $res = $this->parseProductWallFromString($name, $size);
//        $this->info($res);
//        exit();


//        $this->parseCategory('Трубы нержавеющие бесшовные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_besshovnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные AISI прямоугольные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_aisi_pryamougolnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_a');
//        exit();
        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            if ($categoryName == 'Трубы чугунные' || $categoryName == 'Сваи винтовые') {
                $this->parseChugunCat($categoryName, $categoryUrl);
            } else {
                $this->parseCategory($categoryName, $categoryUrl);
            }
        }
        $this->info('The command was successful!');
    }

    public function categoryList() {
        return [
//            'Трубы г/д' => 'https://mc.ru/metalloprokat/truby_g_d',
//            'Трубы х/д' => 'https://mc.ru/metalloprokat/truby_h_d',
//            'ВГП, электросварные трубы' => 'https://mc.ru/metalloprokat/vgp_elektrosvarnye_truby',
//            'Трубы электросварные низколегированные' => 'https://mc.ru/metalloprokat/truby_elektrosvarnye_nizkolegirovannye',
//            'Трубы оцинкованные' => 'https://mc.ru/metalloprokat/truby_ocinkovannye',
//            'Трубы нержавеющие' => 'https://mc.ru/metalloprokat/truby_nerzhavejka_a',
//            'Сваи винтовые' => 'https://mc.ru/metalloprokat/svai_vintovye',
//            'Трубы чугунные' => 'https://mc.ru/metalloprokat/truby_chugun_sml',
        ];
    }

    public function parseCategory($categoryName, $categoryUrl) {
        $this->info('parse categoryName: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalogItemList = $crawler->filter('.catalogItemList>ul>li');

        //если подразделов нет, а сразу товары
        if (!$catalogItemList->count()) {
            $this->parseListProducts($categoryName, $categoryUrl);
        } else {
            //если есть список подразделов
            $catalogItemList->each(function ($subcatItem) use ($categoryName) {
                $subcatName = $subcatItem->filter('a')->first()->text();
                $subcatUrl = $this->baseUrl . $subcatItem->filter('a')->first()->attr('href');
                $this->parseListProducts($categoryName, $subcatUrl, $subcatName);
            });
        }
    }

    //парсим список товаров
    public function parseListProducts($categoryName, $categoryUrl, $subcatName = null) {
        $this->info('parseListProducts: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, 1); //трубный прокат
        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id) : null;

        if (!$subCatalog) {
            $uploadPath = $this->basePath . $catalog->alias . '/';
        } else {
            $uploadPath = $this->basePath . $catalog->alias . '/' . $subCatalog->alias . '/';
        }

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')
            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $categoryName, $uploadPath) {
                $this->info('parse: ' . $n . ' element');
                if ($n == 10) exit();

                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $rawPrice = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(6)->text())); //цена за т.
                    $price = null;
                    $inStock = 0;
                    if ($rawPrice) {
                        $price = (ceil($rawPrice / 100)) * 100; //округляем в большую сторону
                        $inStock = 1;
                    }

                    //значения в разных столбцах в некоторых подразделах
                    if ($catalog->name == 'Трубы нержавеющие бесшовные') {
                        $pricePerMetr = null;
                        if ($node->filter('td')->eq(6)->text() != null) {
                            $pricePerMetr = $node->filter('td')->eq(6)->text(); //цена за шт.
                        }

                        $rawPrice = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //цена за т.
                        $price = null;
                        $inStock = 0;
                        if ($rawPrice) {
                            $price = (ceil($rawPrice / 100)) * 100; //округляем в большую сторону
                            $inStock = 1;
                        }

                        $dataPrices = [
                            'raw_price' => $rawPrice,
                            'price' => $price,
                            'price_per_item' => null,
                            'price_per_metr' => $pricePerMetr,
                        ];
                    } elseif ($catalog->name == 'Трубы нержавеющие электросварные AISI квадратные' ||
                        $catalog->name == 'Трубы нержавеющие электросварные AISI прямоугольные') {

                        $pricePerMetr = null;
                        if ($node->filter('td')->eq(8)->text() != null) {
                            $pricePerMetr = $node->filter('td')->eq(8)->text(); //цена за м.
                        }
                        $dataPrices = [
                            'raw_price' => $rawPrice,
                            'price' => $price,
                            'price_per_item' => null,
                            'price_per_metr' => $pricePerMetr,
                        ];

                    } else {
                        $pricePerItem = null;
                        if ($node->filter('td')->eq(5)->text() != null) {
                            $pricePerItem = $node->filter('td')->eq(5)->text(); //цена за шт.
                        }

                        $pricePerMetr = null;
                        if ($node->filter('td')->eq(8)->text() != null) {
                            $pricePerMetr = $node->filter('td')->eq(8)->text(); //цена за м.
                        }

                        $dataPrices = [
                            'raw_price' => $rawPrice,
                            'price' => $price,
                            'price_per_item' => $pricePerItem,
                            'price_per_metr' => $pricePerMetr,
                        ];
                    }

                    $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение и мин.длину
                    if (!$product) {
                        $name = trim($node->filter('.refstr')->first()->text());
                        $size = trim($node->filter('td')->eq(1)->text());
                        $steel = trim($node->filter('td')->eq(2)->text());
                        $length = trim($node->filter('td')->eq(3)->text());
                        $wall = $this->parseProductWallFromString($name, $size);

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);

                        //находим минимальную длину, если есть
                        $minLength = null;
                        if ($product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->count() != 0) {
                            $minLengthRaw = $product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->text();
                            $minLength = preg_replace("/[^,.0-9]/", null, $minLengthRaw);
                        }

                        $order = $subCatalog ? $subCatalog->products()->max('order') + 1 : $catalog->products()->max('order') + 1;

                        $newProd = Product::create(array_merge([
                            'name' => $name,
                            'catalog_id' => $subCatalog ? $subCatalog->id : $catalog->id,
                            'title' => $name,
                            'h1' => $h1,
                            'alias' => $alias,
                            'parse_url' => $url,
                            'published' => 1,
                            'in_stock' => 1,
                            'order' => $order,
                            'size' => $size,
                            'wall' => $wall ?? null,
                            'steel' => $steel,
                            'length' => $length,
                            'min_length' => $minLength,
                            'measure' => 'т',
                        ], $dataPrices));

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
                        sleep(rand(1, 3));
                    } else {
                        $product->raw_price = $rawPrice;
                        $product->price = $price;
                        $product->in_stock = $inStock;
                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                        $product->save();
                    }

                } catch (\Exception $e) {
                    $this->info('error: ' . $e->getMessage());
                    $this->info('link: ' . ++$n);
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

    //парсим сваи и чугунные отдельно (есть особенности)
    public function parseChugunCat($categoryName, $categoryUrl) {
        $this->info('parseListProducts: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, 1); //трубный прокат

        $uploadPath = $this->basePath . $catalog->alias . '/';

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')
            ->each(function (Crawler $node, $n) use ($catalog, $categoryName, $uploadPath) {
                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $pricePerItem = null;
                    $inStock = 0;
                    if ($node->filter('td')->eq(8)->text()) {
                        if ($node->filter('td')->eq(8)->text() != null) {
                            $pricePerItem = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //цена за шт.
                            $inStock = 1;
                        }
                    }
                    $product = Product::whereParseUrl($url)->first();

                    if (!$product) {  //если новый товар -> заходим на страничку и получаем изображение
                        $name = trim($node->filter('.refstr')->first()->text());
                        $size = trim($node->filter('td')->eq(1)->text());
                        $steel = trim($node->filter('td')->eq(2)->text());
                        $length = trim($node->filter('td')->eq(3)->text());
                        if($categoryName != 'Трубы чугунные') { //в чугунных трубах пропускаем стенку
                            $wall = $this->parseProductWallFromString($name, $size);
                        }

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);
                        $minLength = null;
                        $measure = 'шт';

                        $order = $catalog->products()->max('order') + 1;

                        $newProd = Product::create([
                            'name' => $name,
                            'catalog_id' => $catalog->id,
                            'title' => $name,
                            'h1' => $h1,
                            'alias' => $alias,
                            'parse_url' => $url,
                            'published' => 1,
                            'in_stock' => 1,
                            'raw_price' => null,
                            'price' => null,
                            'price_per_item' => $pricePerItem,
                            'price_per_metr' => null,
                            'order' => $order,
                            'size' => $size,
                            'wall' => $wall ?? null,
                            'steel' => $steel,
                            'length' => $length,
                            'min_length' => $minLength,
                            'measure' => $measure,
                        ]);

                        $section = $catalog;
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
                    } else {
                        $product->price_per_item = $pricePerItem;
                        $product->in_stock = $inStock;
                        $product->save();
                    }
                } catch (\Exception $e) {
                    $this->info('error: ' . $e->getMessage());
                    $this->info('link: ' . ++$n);
                }
            });
    }

    /**
     * @param string $categoryName
     * @param int $parentId
     * @return Catalog
     */
    private function getCatalogByName(string $categoryName, int $parentId): Catalog {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parentId,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => Catalog::whereParentId($parentId)->max('order') + 1,
                'published' => 1,
            ]);
        }
        return $catalog;
    }

    public function test(): void {
//        $number = 53790;
//        $price = (ceil($number / 100)) * 100; //округляем в большую сторону
//        $this->info($price);

//        $res = $this->client->get('https://mc.ru/metalloprokat/truby_elektrosvarnye_kvadratnye_10x0.8_u_razmer_10_dlina_6000');
//        $html = $res->getBody()->getContents();
//        $crawler = new Crawler($html);
//
//        $crawler->filter('.TovInfo img')->each(function ($img, $i) {
//            $imageSrc = $img->attr('src');
//            $this->info($imageSrc . ' = ' . $this->checkIsImageJpg($imageSrc));
//
//            $uploadPath = $this->basePath;
//            $fileName = $uploadPath . 'truby_electro_' . ++$i;
//            $fileName .= $this->checkIsImageJpg($imageSrc) ? '.jpg' : '.svg';
//
//            if($this->checkIsImageJpg($imageSrc)) {
//                $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
//            } else {
//                $res = $this->downloadSvgFile($imageSrc, $uploadPath, $fileName);
//            }
//            if($res) {
//                ProductImage::create([
//                    'product_id' => 1,
//                    'image' => $fileName,
//                    'order' => ProductImage::where('product_id', 1)->max('order') + 1 ?? 0,
//                ]);
//            }
//        });
    }

    /**
     * @param string $str
     * @return bool
     */
    public function checkIsImageJpg(string $str): bool {
        $imgEnds = ['.jpg', 'jpeg', 'png'];
        foreach ($imgEnds as $ext) {
            if (str_ends_with($str, $ext)) {
                return true;
            }
        }
        return false;
    }

    public function downloadJpgFile($url, $uploadPath, $fileName): bool {
        $file = file_get_contents($this->baseUrl . $url);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($fileName), $file);
            return true;
        } catch (\Exception $e) {
            $e->getMessage();
            return false;
        }
    }

    public function downloadSvgFile($url, $uploadPath, $fileName): bool {
        $image = SVG::fromFile($this->baseUrl . $url);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($fileName), $image->toXMLString());
            return true;
        } catch (\Exception $e) {
            $e->getMessage();
            return false;
        }
    }

    public function parseProductWallFromString($str, $productSize) {
        if (!$productSize) return null;
        $sizePos = mb_stripos($str, $productSize); //находим место в строке с текущим размером
        $subStr = mb_substr($str, $sizePos + mb_strlen($productSize) + 1); //вырезаем подстроку в которой есть размер стенки
        if (mb_stripos($subStr, ' ')) {
            // если есть пробел в подстроке, отбрасываем лишнее и берем первую часть
            $arr = explode(' ', $subStr);
            return $arr[0];
        } else {
            // если в подстроке нет пробелов, т.е. строка заканчивается размером стенки
            return $subStr;
        }
    }
}
