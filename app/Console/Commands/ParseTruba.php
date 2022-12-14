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
        $this->client = new Client();
    }

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

//        $this->parseCategory('Трубы нержавеющие бесшовные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_besshovnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные AISI прямоугольные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_aisi_pryamougolnye_a');
//        $this->parseCategory('Трубы нержавеющие электросварные', 'https://mc.ru/metalloprokat/truby_nerzhaveyushchie_ehlektrosvarnye_a');
//        exit();
        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            if ($categoryName == 'Трубы чугунные' || $categoryName == 'Сваи винтовые') {
                $this->parseChugunCat($categoryName, $categoryUrl);
            }
            $this->parseCategory($categoryName, $categoryUrl);
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
            'Сваи винтовые' => 'https://mc.ru/metalloprokat/svai_vintovye',
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
                $url = $this->baseUrl . trim($node->filter('a.refstr')->first()->attr('href'));

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
                    $name = trim($node->filter('a.refstr')->first()->text());
                    $size = trim($node->filter('td')->eq(1)->text());
                    $steel = trim($node->filter('td')->eq(2)->text());
                    $length = trim($node->filter('td')->eq(3)->text());

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
                } else {
                    $product->raw_price = $rawPrice;
                    $product->price = $price;
                    $product->in_stock = $inStock;
                    $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                    $product->save();
                }
                exit();
            });

//        $pages = $crawler->filter('.catalogPaginator ul li');
//        $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
//        if ($currentPage < $pages->count()) {
//            $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
//            $this->info('parse: ' . $nextUrl . ' / ' . $pages->count());
//            sleep(rand(1, 2));
//            $this->parseListProducts($categoryName, $nextUrl, $subcatname);
//        }
    }

    //парсим сваи и чугунные отдельно
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
                $url = $this->baseUrl . trim($node->filter('a.refstr')->first()->attr('href'));

                $pricePerItem = null;
                $inStock = 0;
                if ($node->filter('td')->eq(8)->text()) {
                    if ($node->filter('td')->eq(8)->text() != null) {
                        $pricePerItem = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //цена за шт.
                        $inStock = 1;
                    }
                }

                $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение
                if (!$product) {
                    $name = trim($node->filter('a.refstr')->first()->text());
                    $size = trim($node->filter('td')->eq(1)->text());
                    $steel = trim($node->filter('td')->eq(2)->text());
                    $length = trim($node->filter('td')->eq(3)->text());

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
                exit();
            });
    }


    /**
     * @param string $categoryName
     * @param int $parentId
     * @return Catalog
     */
    private
    function getCatalogByName(string $categoryName, int $parentId): Catalog {
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

    public
    function test(): void {
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
    public
    function checkIsImageJpg(string $str): bool {
        $imgEnds = ['.jpg', 'jpeg', 'png'];
        foreach ($imgEnds as $ext) {
            if (str_ends_with($str, $ext)) {
                return true;
            }
        }
        return false;
    }

    public
    function downloadJpgFile($url, $uploadPath, $fileName): bool {
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

    public
    function downloadSvgFile($url, $uploadPath, $fileName): bool {
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
}
