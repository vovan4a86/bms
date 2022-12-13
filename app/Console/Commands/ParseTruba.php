<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\CatalogParam;
use Fanky\Admin\Models\Param;
use Fanky\Admin\Models\Product;
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
    private $basePath = '/uploads/products/schemas/trubi/';
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
//        $number = 53790;
//        $price = (ceil($number / 100)) * 100; //округляем в большую сторону
//        $this->info($price);
//        exit();

        foreach ($this->categoryList() as $categoryName => $categoryUrl) {
            $this->parseCategory($categoryName, $categoryUrl);
        }
        $this->info('The command was successful!');
    }

    public function categoryList() {
        return [
//            'Трубы г/д' => 'https://mc.ru/metalloprokat/truby_g_d',
//            'Трубы х/д' => 'https://mc.ru/metalloprokat/truby_h_d',
            'ВГП, электросварные трубы' => 'https://mc.ru/metalloprokat/vgp_elektrosvarnye_truby',
        ];
    }

    public function parseCategory($categoryName, $categoryUrl) {
        $this->info('parse categoryName: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalogItemList = $crawler->filter('.catalogItemList>ul>li');
//        $this->info('catalogItemList: ' . $catalogItemList);
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
    public function parseListProducts($categoryName, $categoryUrl, $subcatname = null) {
        $this->info('parseListProducts: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, 1); //трубный прокат
        $subcatalog = $subcatname ? $this->getSubCatalogByName($subcatname, $catalog->id) : null;

        if (!$subcatalog) {
            $uploadPath = $this->basePath . $catalog->alias;
        } else {
            $uploadPath = $this->basePath . $catalog->alias . '/' . $subcatalog->alias;
        }

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')
            ->each(function (Crawler $node) use ($catalog, $subcatalog, $categoryName, $uploadPath) {
                $url = $this->baseUrl . trim($node->filter('a.refstr')->first()->attr('href'));
                $rawPrice = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(6)->text()));
                $price = (ceil($rawPrice / 100)) * 100; //округляем в большую сторону

                $product = Product::whereParseUrl($url)->first();
                //если новый товар -> заходим на страничку и получаем изображение и мин.длину
                if (!$product) {
                    $name = trim($node->filter('a.refstr')->first()->text());
                    $size = trim($node->filter('td')->eq(1)->text());
                    $steel = trim($node->filter('td')->eq(2)->text());
                    $length = trim($node->filter('td')->eq(3)->text());
                    $alias = Text::translit($name . ' ' . $steel);

                    $html_product = $this->client->get($url);
                    $inner_html = $html_product->getBody()->getContents();
                    $product_crawler = new Crawler($inner_html);
                    $minLengthRaw = $product_crawler->filter('.catalogInfoWrap')->eq(2)->text();
                    $minLength = $minLengthRaw ? preg_replace("/[^,.0-9]/", null, $minLengthRaw) : null;

                    $imageSrc = $product_crawler->filter('#defphoto')->attr('src');
                    $imageUrl = $imageSrc ? $this->baseUrl . $imageSrc : null;
                    $file_path = null;
                    if ($imageUrl) {
                        $file_path = $uploadPath . '/' . $alias . '.svg';
                        $image = SVG::fromFile($imageUrl);
                        file_put_contents(public_path($file_path), $image->toXMLString());
                    }

                    $order = $subcatalog ? $subcatalog->products()->max('order') + 1 : $catalog->products()->max('order') + 1;

                    Product::create([
                        'name' => $name,
                        'catalog_id' => $subcatalog ? $subcatalog->id : $catalog->id,
                        'title' => $name,
                        'alias' => $alias,
                        'image' => $file_path,
                        'parse_url' => $url,
                        'parse_image_url' => $imageUrl,
                        'published' => 1,
                        'in_stock' => 1,
                        'order' => $order,
                        'raw_price' => $rawPrice,
                        'size' => $size,
                        'steel' => $steel,
                        'length' => $length,
                        'price' => $price,
                        'min_length' => $minLength,
                        'measure' => 'т',
                    ]);
                } else {
                    $product->raw_price = $rawPrice;
                    $product->price = $price;
                    $product->catalog_id = $subcatalog ? $subcatalog->id : $catalog->id;
                    $product->save();
                }
            });

        $pages = $crawler->filter('.catalogPaginator ul li');
        $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
        if ($currentPage < $pages->count()) {
            $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
            $this->info('parse: ' . $nextUrl . ' / ' . $pages->count());
            sleep(rand(1, 2));
            $this->parseListProducts($categoryName, $nextUrl, $subcatname);
        }
    }

    //парсим список подкатегорий
    public function parseListSubcategories($categoryName, $categoryUrl, $subcatname = null) {

    }

    /**
     * @param string $categoryName
     *
     * @return Catalog
     */
    private function getCatalogByName($categoryName, $parentId) {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parentId,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => Catalog::whereParentId(0)->max('order') + 1,
                'published' => 1,
            ]);
        }

        return $catalog;
    }

    private function getSubCatalogByName($categoryName, $parent_id) {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parent_id,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => Catalog::whereParentId($parent_id)->max('order') + 1,
                'published' => 1,
            ]);
        }

        return $catalog;
    }
}
