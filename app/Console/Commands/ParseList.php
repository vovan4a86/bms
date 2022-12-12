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
use SiteHelper;
use Symfony\Component\DomCrawler\Crawler;

class ParseList extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:list';
    private $baseUrl = 'https://www.spk.ru';
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
        $this->parseList('Лист', 'https://www.spk.ru/catalog/metalloprokat/listovoy-prokat/');
//        $this->parseCategory('Лист полимер', 'https://www.spk.ru/catalog/metalloprokat/listovoy-prokat/list-polimer/0-45tu', 'Лист полимер 0.45ТУ');
        $this->info('The command was successful!');
    }

    private $specLength = [
        'Лист'
    ];

    public function parseCategory($categoryName, $categoryUrl, $subcatname = null) {
        $this->info('parse categoryName: ' . $categoryName);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);

        $catalog = $this->getCatalogByName($categoryName);

        $crawler->filter('.product-card__wrap_list-alt-wrap .product-card')
            ->each(function (Crawler $node, $i) use ($catalog, $categoryName, $subcatname) {
                $name = trim($node->filter('a.product-card__title-link')->first()->text());

                //наличие на складе
                $in_stock = 0;
                if ($node->filter('.product__stock-in')->count()) {
                    $in_stock = 1;
                }
                if ($node->filter('.product__stock-out')->count()) {
                    $stock_text = trim($node->filter('.product__stock-out')->first()->text());
                    $stock_text == 'Под заказ' ? $in_stock = 2 : $in_stock = 0;
                }

                $url = $this->baseUrl . trim($node->filter('a.product-card__title-link')->first()->attr('href'));

                $product = Product::whereParseUrl($url)->first();
                //если новый товар -> заходим на страничку и получаем нужную инфу
                if (!$product) {
                    $inner_product = $this->client->get($url);
                    $inner_html = $inner_product->getBody()->getContents();
                    $inner_crawler = new Crawler($inner_html);
                    $price = preg_replace("/[^,.0-9]/", '', $inner_crawler->filter('.product-multiple__price-item')->first()->text());
                    $find_slash_offset = stripos($inner_crawler->filter('.product-multiple__price-item')->first()->text(), '/');
                    $measure = trim(substr($inner_crawler->filter('.product-multiple__price-item')->first()->text(), $find_slash_offset + 1));

                    //у труб и листа есть select с длиной
                    if (in_array($categoryName, $this->specLength)) {
                        $length_elem = $inner_crawler->filter('.product-multiple__item-wrap .form__select')->first();
                        if ($length_elem->filter('.form__select-element option')->count() !== 0) {
                            $length = preg_replace("/[^,.0-9]/", '', $length_elem->filter('.form__select-element option')->first()->text());
                        } else {
                            $length = null;
                        }
                    }

                    $raw_weight = trim($inner_crawler->filter('.product-multiple-total__weight')->first()->text());
                    $factor_weight = preg_replace("/[^,.0-9]/", '', $raw_weight);

                    //ищем вес 1 листа для расчетов
                    $settings_row = $inner_crawler->filter('.product-multiple__item')->attr('data-widget-settings');
                    $settings = json_decode($settings_row);
                    $factor = null;
                    $factor_m2 = null;
                    if($settings) {
                        $mult = $settings->multiplicity;
                        if($mult) {
                            $factor = $settings->matrix->$mult->factor;
                        }
                        $factor_m2 = $settings->matrix->ST->factor;
                    }

                    $char_keys = [];
                    $char_values = [];
                    $inner_crawler->filter('.product__char')
                        ->each(function (Crawler $node, $i) use (&$char_values, &$char_keys) {
                            $char_keys[$i] = trim($node->filter('.product__char-key')->first()->text());
                            $char_values[$i] = trim($node->filter('.product__char-val')->first()->text());
                        });

                    if ($subcatname == null) {
                        $subname = $categoryName . ' ' . $char_values[0];
                        $sub_catalog = $this->getSubCatalogByName($subname, $catalog->id);
                    } else {
                        $sub_catalog = $this->getSubCatalogByName($subcatname, $catalog->id);
                    }

                    $root = $catalog;
                    while ($root->parent_id !== 0) {
                        $root = $root->findRootCategory($root->parent_id);
                    }

                    $data = [];
                    foreach ($char_keys as $i => $key) {
                        $data[Text::translit($key)] = $char_values[$i];
                        $param = Param::whereName($key)->first();
                        if (!$param) {
                            $param = Param::create([
                                'name' => $key,
                                'title' => $key,
                                'alias' => Text::translit($key)
                            ]);
                            CatalogParam::create([
                                'catalog_id' => $catalog->id,
                                'param_id' => $param->id,
                                'order' => CatalogParam::where('catalog_id', '=', $root->id)->max('order') + 1,
                            ]);
                        } else {
                            $used = CatalogParam::where('catalog_id', '=', $root->id)->pluck('param_id')->all();
                            if (!in_array($param->id, $used)) {
                                CatalogParam::create([
                                    'catalog_id' => $root->id,
                                    'param_id' => $param->id,
                                    'order' => CatalogParam::where('catalog_id', '=', $root->id)->max('order') + 1,
                                ]);
                            }
                        }
                    }

                    Product::create(array_merge([
                        'name' => $name,
                        'catalog_id' => $sub_catalog->id,
                        'title' => $name,
                        'alias' => Text::translit($name),
                        'parse_url' => $url,
                        'published' => 1,
                        'length' => $length ?? null,
                        'order' => $catalog->products()->max('order') + 1,
                        'price' => $price,
                        'in_stock' => $in_stock,
                        'measure' => $measure,
                        'factor' => $factor,
                        'factor_m2' => $factor_m2,
                        'factor_m2_weight' => $factor_weight,
                    ], $data));
                } else {
                    $in_stock = 0;
                    if ($node->filter('.product__stock-in')->count()) {
                        $in_stock = 1;
                    }
                    if ($node->filter('.product__stock-out')->count()) {
                        $stock_text = trim($node->filter('.product__stock-out')->first()->text());
                        $stock_text == 'Под заказ' ? $in_stock = 2 : $in_stock = 0;
                    }

                    $price = preg_replace("/[^,.0-9]/", '', $node->filter('.product-card__size-in > div')->first()->text());
                    $product->price = $price;
                    $product->in_stock = $in_stock;
                    $product->save();
                }
            });

        sleep(rand(1, 2));
        if ($crawler->filter('a.pager__right')->count()) {
            $nextUrl = $this->baseUrl . $crawler->filter('a.pager__right')->first()->attr('href');
            $this->parseCategory($categoryName, $nextUrl, $subcatname);
        }
    }

    public function parseList($categoryName, $categoryUrl) {
        $this->info('parseList url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);

        $catalog = $this->getCatalogByName($categoryName);

        $crawler->filter('.metal-tile__content-block')
            ->each(function (Crawler $subcatnode, $i) use ($categoryName, $catalog) {
                $name = trim($subcatnode->filter('.metal-tile-item__description a')->first()->text()); //ПРОФНАСТИЛ С8
                $subcatalog = $this->getSubCatalogByName($name, $catalog->id);
                $this->info('parse name: ' . $name);

                $subcatnode->filter('.metal-tile-item-patameters__name')
                    ->each(function (Crawler $params, $i) use ($name, $subcatalog) {
                        $proffullname = $params->filter('.metal-tile-item-patameters__name a')->first()->text();
                        $profurl = $this->baseUrl . trim($params->filter('.metal-tile-item-patameters__name a')->first()->attr('href'));
                        $profsubcatalog = $this->getSubCatalogByName($proffullname, $subcatalog->id);

                        $this->parseCategory($proffullname, $profurl, $profsubcatalog->name);
                    });
            });
    }


    /**
     * @param string $categoryName
     *
     * @return Catalog
     */
    private function getCatalogByName($categoryName) {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => 0,
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
