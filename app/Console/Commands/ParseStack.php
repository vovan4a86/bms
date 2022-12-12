<?php

namespace App\Console\Commands;

use App\SiteHelper;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;
use Fanky\Admin\Text;
use function foo\func;
use function GuzzleHttp\Psr7\str;
use Symfony\Component\DomCrawler\Crawler;

class ParseStack extends Parsing {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:stack';
    public $convert_to_utf = false;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse site';

    public function handle() {
        $this->parseCatalogs();
//		$this->parseProducts();
    }

    private function parseProducts(){
        $catalogs = Catalog::where('parse_url', 'like', '%stack-systems.com.ua%')
            ->where('id', '>', 201)
            ->orderBy('id')
            ->get();
        foreach ($catalogs as $catalog){
            if(!$catalog->children()->count()){ //если нет дочерних, ищем товары
                $html = $this->load_html($catalog->parse_url);
                $dom = new Crawler();
                $dom->addHtmlContent($html);
                $this->parseProductList($catalog->parse_url, $dom, $catalog);
            }
        }

    }

    private function parseCatalogs() {
        $root_urls = [
//            'https://stack-systems.com.ua/switch/cisco-switch/cisco-catalyst-9200-series',
            'https://www.spk.ru/catalog/metalloprokat/trubniy-prokat/'
        ];
        $root = $this->getDefaultCategory();

        foreach ($root_urls as $url) {
            $html = $this->load_html($url);
            $dom = new Crawler();
            $dom->addHtmlContent($html);
            $name = trim($dom->filter('h1')->first()->text());
            $child = $root->children()->whereName('name')->first();
            if (!$child) {
                $child = Catalog::create([
                    'parent_id' => $root->id,
                    'name'      => $name,
                    'title'     => $name,
                    'alias'     => Text::translit($name),
                    'parse_url' => $url,
                    'published' => 1
                ]);
            }
            $this->detectPage($url, $dom, $child);
        }
    }

    private function getDefaultCategory() {
        $root = Catalog::whereAlias('stack')->first();
        if (!$root) {
            $root = Catalog::create([
                'alias'     => 'stack',
                'name'      => 'stack',
                'title'     => 'stack',
                'published' => 0,
            ]);
        }

        return $root;
    }

    /**
     * @param         $url
     * @param Crawler $dom
     * @param Catalog $currCategory
     * @throws \Exception
     */
    private function detectPage($url, Crawler $dom, Catalog $currCategory) {
        $this->info('detect ' . $url);
        if (count($dom->filter('div.category-products'))) {
//            $this->parseProductList($url, $dom, $currCategory);
        } elseif (count($dom->filter('div.product-view'))) {
//            $this->parseProduct($url, $dom, $currCategory);
        } elseif (count($dom->filter('div.page-banners.grid-container'))) {
            $this->parseCategoryList($url, $dom, $currCategory);
        }
    }

    /**
     * @param         $url
     * @param Crawler $dom
     * @param Catalog $currCategory
     * @throws \Exception
     */
    public function parseCategoryList($url, Crawler $dom, Catalog $currCategory) {
        $this->info('parseCategoryList ' . $url);
        $dom->filter('.page-banners.grid-container > .banner')->each(function ($banner) use ($currCategory) {
            /** @var Crawler $banner */
            $name = trim($banner->filter('h2 a span')->first()->text());
            $url = trim($banner->filter('h2 a')->first()->attr('href'));
            $image = $banner->filter('img.fade-on-hover')->first();
            $child = $currCategory->children()->whereName($name)->first();
            if (!$child) {
                $child = Catalog::create([
                    'parent_id' => $currCategory->id,
                    'name'      => $name,
                    'title'     => $name,
                    'alias'     => Text::translit($name),
                    'parse_url' => $url,
                    'published' => 1
                ]);
            }
            if (!$child->image && $image) {
                $src = $image->attr('src');
                $this->info('load image: ' . $src);
                $image_name = SiteHelper::uploadImage($src, 'catalogs');
                if (strlen($image_name)) {
                    $child->update(['image' => $image_name]);
                }
            }
            $child_html = $this->load_html($url);
            $child_dom = new Crawler();
            $child_dom->addHtmlContent($child_html);
            $this->detectPage($url, $child_dom, $child);
        });
    }

    /**
     * @param         $url
     * @param Crawler $dom
     * @param Catalog $currCategory
     * @throws \Exception
     */
    public function parseProductList($url, Crawler $dom, Catalog $currCategory) {
        $this->info('parseProductList ' . $url);
        if ($dom->filter('.products-list .item')->count()) {
            $dom->filter('.products-list .item')->each(function ($item) use ($currCategory) {
                /** @var Crawler $item */
                $a = $item->filter('h2.product-name a')->first();
                if ($a) {
                    $url = $a->attr('href');
                    $html = $this->load_html($url);
                    $dom = new Crawler();
                    $dom->addHtmlContent($html);
                    $this->detectPage($url, $dom, $currCategory);
                }
            });
        }
        if ($dom->filter('.category-products .pager a.next')->count()) {
            $url = $dom->filter('.category-products .pager a.next')->first()->attr('href');
            $html = $this->load_html($url);
            $dom = new Crawler();
            $dom->addHtmlContent($html);
            $this->detectPage($url, $dom, $currCategory);
        }
    }

    /**
     * @param         $url
     * @param Crawler $dom
     * @param Catalog $currCategory
     */
    public function parseProduct($url, Crawler $dom, Catalog $currCategory) {
        $this->info('parseProduct ' . $url);
        $product = Product::whereParseUrl($url)->first();
        if (!$product) $product = new Product();

        $sku = $dom->filter('.product-primary-column .sku')->first();
        if (!$sku) return;

        $article = trim($sku->filter('.value')->first()->text());
        if (Product::whereArticle($article)->count()) return;

        $name = trim($dom->filter('h1')->first()->text());
        $text = '';
        if ($std = $dom->filter('.panel .std')) {
            $text = trim($std->html());
        }

        if(!$dom->filter('.product-primary-column .old-price')->count()){
            $price = 0;
        } else {
            $price = $dom->filter('.product-primary-column .old-price')->first();
            $price = $price->filter('span.price')->first()->text();
        }


        $product->fill([
            'name'       => $name,
            'catalog_id' => $currCategory->id,
            'h1'         => $name,
            'announce'   => $text,
            'price'      => $price,
            'published'  => 1,
            'parse_url'  => $url,
            'article'    => $article,
            'alias'      => Text::translit($name)
        ])->save();

        $images = $dom->filter('.product-img-column a.product-image-gallery')->count();
        if (!$product->images()->count() && $images) {
            $image_a = $dom->filter('.product-img-column a.product-image-gallery')->first();
            $src = $image_a->attr('href');
            $image_name = SiteHelper::uploadImage($src, 'products');
            if (strlen($image_name)) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image'      => $image_name,
                    'order'      => 0
                ]);
            }
        }


        if ($dom->filter('.product-primary-column .attachment-widget-link')->count()) {
            $dom->filter('.product-primary-column .attachment-widget-link a')->each(function ($a) use (&$text) {
                /** @var Crawler $a */
                $href = $a->attr('href');
                $a_text = trim($a->text());
                $file_name = SiteHelper::downloadFile($href, 'files');
                if ($file_name) {
                    $text .= "<p><a class='download' href='/uploads/files/$file_name'>$a_text</a></p>";
                }
            });

            $product->update(['announce' => $text]);
        }
    }
}
