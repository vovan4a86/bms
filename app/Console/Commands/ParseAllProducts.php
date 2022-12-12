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

class ParseAllProducts extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:all';
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
//		foreach ($this->categoryList() as $categoryName => $categoryUrl) {
//            $this->parseCategory($categoryName, $categoryUrl);
//		}
//        $this->parseProfnastil('Профнастил','https://www.spk.ru/catalog/krovlya/profnastil/');
//        $this->parseList('Лист', 'https://www.spk.ru/catalog/metalloprokat/listovoy-prokat/');
        $this->parseTest();

        $this->info('The command was successful!');
    }

    private $tubeCategories = [
        'Профильная труба',
        'Труба электросварная',
        'Труба водогазопроводная',
        'Лист'
    ];

    public function categoryList() {
        return [
            'Профильная труба' => 'https://www.spk.ru/catalog/metalloprokat/trubniy-prokat/truba-profilnaya/',
            'Труба электросварная' => 'https://www.spk.ru/catalog/metalloprokat/trubniy-prokat/truba-e-s/',
            'Труба водогазопроводная' => 'https://www.spk.ru/catalog/metalloprokat/trubniy-prokat/truba-vgp/',
            'Швеллер' => 'https://www.spk.ru/catalog/metalloprokat/fasonniy-prokat/shveller/',
            'Уголок' => 'https://www.spk.ru/catalog/metalloprokat/fasonniy-prokat/ugolok/',
            'Арматура' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/armatura/',
            'Круг' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/krug/',
            'Балка' => 'https://www.spk.ru/catalog/metalloprokat/fasonniy-prokat/balka/',
            'Катанка' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/katanka/',
            'Шестигранник' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/shestigrannik/',
            'Квадрат' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/kvadrat/',
            'Проволока' => 'https://www.spk.ru/catalog/metalloprokat/sortovoy-prokat/provoloka/',
            'Сетка арматурная' => 'https://www.spk.ru/catalog/metalloprokat/izdeliya-iz-armatury/setka-armaturnaya/',
        ];
    }

    public function parseCategory($categoryName, $categoryUrl, $subcatname = null) {
        $this->info('parse $categoryName: ' . $categoryName);
        $this->info('parse $subcatname: ' . $subcatname);
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);

        $catalog = $this->getCatalogByName($categoryName);

        $crawler->filter('.product-card__wrap_list-alt-wrap .product-card')
            ->each(function (Crawler $node, $i) use ($catalog, $categoryName, $subcatname) {
            $name = trim($node->filter('a.product-card__title-link')->first()->text());

            if ($node->filter('.product__stock-in')->count()) {
                $in_stock = 1;
            }
            if ($node->filter('.product__stock-out')->count()) {
                $stock_text = trim($node->filter('.product__stock-out')->first()->text());
                $stock_text == 'Под заказ' ? $in_stock = 2 : $in_stock = 0;
            }

            $url = $this->baseUrl . trim($node->filter('a.product-card__title-link')->first()->attr('href'));

            $inner_product = $this->client->get($url);
            $inner_html = $inner_product->getBody()->getContents();
            $inner_crawler = new Crawler($inner_html);
            $price = preg_replace("/[^,.0-9]/", '', $inner_crawler->filter('.product-multiple__price-item')->first()->text());
            $find_slash_offset = stripos($inner_crawler->filter('.product-multiple__price-item')->first()->text(), '/');
            $measure = trim(substr($inner_crawler->filter('.product-multiple__price-item')->first()->text(), $find_slash_offset + 1));

            if (in_array($categoryName, $this->tubeCategories)) {
                $length_elem = $inner_crawler->filter('.product-multiple__item-wrap .form__select')->first();
                if ($length_elem->filter('.form__select-element option')->count() !== 0) {
                    $length = preg_replace("/[^,.0-9]/", '', $length_elem->filter('.form__select-element option')->first()->text());
                } else {
                    $length = null;
                }
            }

            $char_keys = [];
            $char_values = [];
            $inner_crawler->filter('.product__char')
                ->each(function (Crawler $node, $i) use (&$char_values, &$char_keys) {
                $char_keys[$i] = trim($node->filter('.product__char-key')->first()->text());
                $char_values[$i] = trim($node->filter('.product__char-val')->first()->text());
            });

            if($subcatname == null) {
                $subname = $categoryName . ' ' . $char_values[0];
                $sub_catalog = $this->getSubCatalogByName($subname, $catalog->id);
            } else {
                $sub_catalog = $this->getSubCatalogByName($subcatname, $catalog->id);
            }

            $root = $catalog;
            while($root->parent_id !== 0) {
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

            $product = Product::whereParseUrl($url)->first();
            if (!$product) {
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
                ], $data));
            } else {
                $product->price = $price;
                $product->in_stock = $in_stock;
                $product->save();
            }
        });

        sleep(rand(1,2));
        if ($crawler->filter('a.pager__right')->count()) {
            $nextUrl = $this->baseUrl . $crawler->filter('a.pager__right')->first()->attr('href');
            $this->parseCategory($categoryName, $nextUrl, $subcatname);
        }
    }

    public function parseProfnastil($categoryName, $categoryUrl) {
        $this->info('parseProfnastil url: ' . $categoryUrl);
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
//                $profname = trim(str_ireplace($name, '', $proffullname));

                $profsubcatalog = $this->getSubCatalogByName($proffullname, $subcatalog->id);

                $this->parseCategory($proffullname, $profurl, $profsubcatalog->name);
            });
        });
    }

    public function parseList($categoryName, $categoryUrl) {
        $this->info('parse url: ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);

        $crawler->filter('.metal-tile__content-block')->each(function (Crawler $subcrawler) use ($categoryName) {
            $subname = $subcrawler->filter('.metal-tile-item__description a')->first()->text();
            $subcaturl = $this->baseUrl . trim($subcrawler->filter('.metal-tile-item__description a')->first()->attr('href'));

            $this->parseCategory($categoryName, $subcaturl, $subname);
        });
    }

    public function parseProduct(Product $product) {
        $this->info("product_id: {$product->id}, url: {$product->parse_url}");
        $res = $this->client->get($product->parse_url);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);
        $articul = $crawler->filter('.vendorcode')->text();
        $data = [];
        $data['articul'] = str_after($articul, 'Артикул: Двигатель Deutz ');
        $data['brand'] = 'Deutz';
        $crawler->filter('.main_info table tr')->each(function (Crawler $node, $i) use (&$data) {
            $name = trim($node->filter('td')->eq(0)->text(), ":\ \t\n\r\0\x0B");
            $val = trim($node->filter('td')->eq(1)->text(), ":\ \t\n\r\0\x0B");
            switch ($name) {
                case 'Тип двигателя':
                    $data['engine_type'] = $val;
                    break;
                case 'Мощность генератора (LTP) 50 Гц, кВт':
                case 'Мощность, кВт':
                    $data['power'] = $val;
                    break;
                case 'Объем, л':
                    $data['capacity'] = $val;
                    break;
            }
        });
        $specifications = '<table><tbody>';
        $crawler->filter('.specifications__table .specifications__table-row')->each(function (Crawler $node, $i) use (&$specifications) {
            $name = trim($node->filter('.specifications__table-row-col')->eq(0)->text(), ":\ \t\n\r\0\x0B");
            $val = trim($node->filter('.specifications__table-row-col')->eq(1)->text(), ":\ \t\n\r\0\x0B");
            $specifications .= "<tr><td>$name</td><td>$val</td></tr>";
        });
        $specifications .= '</tbody></table>';
        $data['specifications'] = $specifications;
        $data['text_description'] = trim($crawler->filter('.allspecifications-tabs .tab-content .tab-pane')->eq(1)->html());
        $data['text_description'] = preg_replace('/ style=\'[^\']+\'/', '', $data['text_description']);
        if (!count($crawler->filter('.price_noauth .price'))) {
            $price = 0;
        } else {
            $price = trim($crawler->filter('.price_noauth .price')->first()->text());
            $price = preg_replace("/[^0-9]/", '', $price);
        }
        $data['price'] = $price;
        $data['parse_date'] = Carbon::now();
        $product->fill($data)->save();
        if (!$product->images()->count()) {
            $src = $this->baseUrl . $crawler->filter('.product_slider_img-one .item img')->first()->attr('src');
            $image = SiteHelper::uploadImage($src, 'engine_images');
            if ($image) {
                EngineImage::create([
                    'engine_id' => $product->id,
                    'order' => 0,
                    'image' => $image
                ]);
            }
        }
    }

    public function parseTest() {
        $res = $this->client->get('https://www.spk.ru/catalog/metalloprokat/trubniy-prokat/truba-profilnaya/60/truba-profilnaya-60-60-4-sto/');
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html);

        $settings_row = $crawler->filter('.product-multiple__item')->attr('data-widget-settings');
        $settings = json_decode($settings_row);
        $mult = 0;
        if($settings) {
            $mult = $settings->multiplicity;
            $this->info('settings: ' . $settings->matrix->$mult->factor);
        }
        $this->info('no_settings: ' . $mult);
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
