<?php namespace App\Traits;

use Carbon\Carbon;
use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Filter;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;
use Fanky\Admin\Text;
use SVG\SVG;
use Symfony\Component\DomCrawler\Crawler;

trait ParseFunctions {

    public $baseUrl = 'https://mc.ru';

    private $updateOneTime = false;

    public $userAgents = [
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

    //парсим категории
    public function parseCategory($categoryName, $categoryUrl, $parentId) {
        $this->info($categoryName . ' => ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, $parentId, 'steel/length');

        $catalogItemList = $crawler->filter($this->catalogItemListTagElement);

        //парсим список подразделов
        if (count($catalogItemList)) {
            $catalogItemList->each(function ($subcatItem) use ($categoryName, $catalog, $parentId) {
                $subcatName = trim($subcatItem->filter('a')->first()->text());
                $subcatUrl = $this->baseUrl . $subcatItem->filter('a')->first()->attr('href');

                $this->parseCategory($subcatName, $subcatUrl, $catalog->id);
            });
        } else {
            //если нет подразделов, парсим товары
            try {
//                if($categoryName == 'Лента латунная')  $this->parseListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
                $this->parseListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
            } catch (\Exception $e) {
                $this->warn('Error Parse From List: ' . $e->getMessage());
            }
        }
    }
    //парсим список товаров
    public function parseListProducts($catalog, $categoryUrl, $subcatName, $priceMap) {
        $this->info('Parse products from: ' . $catalog->name);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catFilters = $priceMap[0] . '/' . $priceMap[1];

        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id, $catFilters) : null;

        $uploadPath = $this->basePath . $catalog->alias . '/';

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')->reduce(function (Crawler $nnode, $i) {
            return ($i < 1);
        })
            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $uploadPath, $priceMap) {
                $this->info('Parse: ' . ++$n . ' element');

                $scriptUrl = $this->getInnerSiteScript($node); //строка с адресом внутреннего скрипта с инфой

                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $data = [];
                    $usedPrice = $priceMap[5]; //по какому столбцу проверяем наличие
                    if ($priceMap[2] !== null) {
                        $data[$priceMap[2]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(5)->text())); //5 колонка цены
                    }
                    if ($priceMap[3] !== null) {
                        $data[$priceMap[3]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(6)->text())); //6 колонка цены
                    }
                    if ($priceMap[4] !== null) {
                        $data[$priceMap[4]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')->eq(8)->text())); //7 колонка цены
                    }
                    if (isset($data['price']) && $data['price']) {
                        $data['raw_price'] = $data['price'];
                        $data['price'] = (ceil($data['raw_price'] / 100)) * 100; //округляем в большую сторону
                    }

                    $data['measure'] = $priceMap[6];
                    if (isset($priceMap[8])) $data['measure2'] = $priceMap[8];
                    $data['inStock'] = $data[$priceMap[$usedPrice]] ? 1 : 0;

                    $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение и мин.длину
                    if (!$product) {
                        //ищем коэффициент k
                        $data['k'] = $this->getKFromScriptUrl($scriptUrl);

                        $name = trim($node->filter('.refstr')->first()->text());
                        $data['size'] = trim($node->filter('td')->eq(1)->text());
                        $data[$priceMap[0]] = trim($node->filter('td')->eq(2)->text());
                        $data[$priceMap[1]] = trim($node->filter('td')->eq(3)->text());

                        //лист рифленый
                        if (isset($data['comment']) && $data['comment'] != null) {
                            if ($data['price_per_item'] == $data['price_per_kilo']) $data['measure'] = 'шт';
                        }

                        //если 1 ищем стенку
                        if ($priceMap[7] == 1) {
                            $data['wall'] = $this->parseProductWallFromString($name, $data['size']);
                        } elseif ($priceMap[7] == 2) {
                            $data['wall'] = $this->parseProductWallFromString($name, $data['size'], true);
                        }

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);

                        //находим минимальную длину, если есть
                        if ($product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->count() != 0) {
                            $minLengthRaw = $product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->text();
                            $data['min_length'] = preg_replace("/[^,.0-9]/", null, $minLengthRaw);
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
                            'order' => $order,
                        ], $data));

                        $section = $subCatalog ?: $catalog;
                        $product_crawler->filter('.TovInfo img')->each(function ($img, $i) use ($alias, $newProd, $section, $uploadPath) {
                            $imageSrc = $img->attr('src');
                            $fileName = $uploadPath . $alias . '-' . ++$i;
                            $fileName .= $this->checkIsImageJpg($imageSrc) ? '.jpg' : '.svg';

                            if ($this->checkIsImageJpg($imageSrc)) {
                                //делаем изображение для раздела
                                $fileName = $uploadPath . $section->alias . '.jpg';
                                $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
                                if ($res) {
                                    if (!file_exists($fileName)) {
                                        $section->section_image = $fileName;
                                        $section->save();
                                    } else {
                                        ProductImage::create([
                                            'product_id' => $newProd->id,
                                            'image' => $fileName,
                                            'order' => ProductImage::where('product_id', $newProd->id)->max('order') + 1,
                                        ]);
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
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($section);
                            $this->updateOneTime = true;
                        }
                    } else {
                        $product->update($data);
                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                        $product->save();
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($subCatalog ?: $catalog);
                            $this->updateOneTime = true;
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn('error: ' . $e->getMessage());
                    $this->warn('see line: '. $e->getLine());
                }
            });

//        проход по страницам
        if($crawler->filter('.catalogPaginator ul li')->count() != 0) {
            $pages = $crawler->filter('.catalogPaginator ul li');
            $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
            if ($currentPage < $pages->count()) {
                $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
                $this->info('parse: ' . $nextUrl . ' / ' . $pages->count());
                $this->parseListProducts($catalog, $nextUrl, $subcatName, $priceMap);
            }
        }
    }


    //для сантехарматуры
    public function parseSantehCategory($categoryName, $categoryUrl, $parentId, $catFilters) {
        $this->info($categoryName . ' => ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, $parentId, $catFilters);
        $catalogItemList = $crawler->filter($this->catalogItemListTagElement);

        //парсим список подразделов
        if (count($catalogItemList)) {
            $catalogItemList->each(function ($subcatItem) use ($categoryName, $catalog, $parentId) {
                $subcatName = trim($subcatItem->filter('a')->first()->text());
                $subcatUrl = $this->baseUrl . $subcatItem->filter('a')->first()->attr('href');

                if (isset($this->priceMap[$subcatName])) {
                    $catFilters = $this->priceMap[$subcatName][0] . '/' . $this->priceMap[$subcatName][1];
                }

                $this->parseSantehCategory($subcatName, $subcatUrl, $catalog->id, $catFilters ?? '');
            });
        } else {
            //если нет подразделов, парсим товары
            try {
//                if ($categoryName == 'Задвижки стальные') $this->parseSantehListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
                $this->parseSantehListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
            } catch (\Exception $e) {
                $this->warn('Error Parse From List: ' . $e->getMessage());
            }
        }
    }
    //для сантехарматуры
    public function parseSantehListProducts($catalog, $categoryUrl, $subcatName, $priceMap) {
        $this->info('Parse products from: ' . $catalog->name);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catFilters = $priceMap[0] . '/' . $priceMap[1];

        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id, $catFilters) : null;

        $uploadPath = $this->basePath . $catalog->alias . '/';

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')->reduce(function (Crawler $nnode, $i) {
            return ($i < 1);
        })
            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $uploadPath, $priceMap) {
                $this->info('Parse: ' . ++$n . ' element');

                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $data = [];
                    $priceToFloat = preg_replace("/[^,.0-9]/", null, ($node->filter('td')
                        ->eq(8)->text())); //7 колонка цены
                    $data[$priceMap[2]] = str_replace(',', '.', $priceToFloat); //меняем запятую, иначе в БД десятки не запишутся

                    $data['measure'] = $priceMap[3];
                    $data['inStock'] = $data[$priceMap[2]] ? 1 : 0;

                    $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение и мин.длину
                    if (!$product) {
                        $name = trim($node->filter('.refstr')->first()->text());
                        $data['size'] = trim($node->filter('td')->eq(1)->text());
                        $data[$priceMap[0]] = trim($node->filter('td')->eq(2)->text());
                        $data[$priceMap[1]] = trim($node->filter('td')->eq(3)->text());

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);

                        $order = $subCatalog ? $subCatalog->products()->max('order') + 1 :
                            $catalog->products()->max('order') + 1;

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

                        //два вида картинок
                        if ($product_crawler->filter('.TovInfo img')->count() > 0) {
                            $prodPictureSrc = '.TovInfo img';
                        } elseif ($product_crawler->filter('img.fl_left')->count() > 0) {
                            $prodPictureSrc = 'img.fl_left';
                        } else {
                            $prodPictureSrc = null;
                        }

                        if ($prodPictureSrc) {
                            $product_crawler->filter($prodPictureSrc)
                                ->each(function ($img, $i) use ($alias, $newProd, $section, $uploadPath, $priceMap) {
                                    $imageSrc = $img->attr('src');
                                    $fileName = $uploadPath . $alias . '-' . ++$i;
                                    $fileName .= $this->checkIsImageJpg($imageSrc) ? '.jpg' : '.svg';

                                    if ($this->checkIsImageJpg($imageSrc)) {
                                        if (isset($priceMap[4]) && $priceMap[4] == 1) {
                                            //делаем изображение для раздела
                                            $fileName = $uploadPath . $section->alias . '.jpg';
                                            $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
                                            if (!file_exists($fileName)) {
                                                if ($res) {
                                                    $section->section_image = $fileName;
                                                    $section->save();
                                                }
                                            }
                                        } else {
                                            $res = $this->downloadJpgFile($imageSrc, $uploadPath, $fileName);
                                            if ($res) {
                                                ProductImage::create([
                                                    'product_id' => $newProd->id,
                                                    'image' => $fileName,
                                                    'order' => ProductImage::where('product_id', $newProd->id)->max('order') + 1,
                                                ]);
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
                        }

                        sleep(rand(1, 2));
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($section);
                            $this->updateOneTime = true;
                        }
                    } else {
                        $product->update($data);
                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                        $product->save();
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($subCatalog ?: $catalog);
                            $this->updateOneTime = true;
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn('error: ' . $e->getMessage());
                    $this->warn('see line: '. $e->getLine());
                }
            });

//        проход по страницам
        if($crawler->filter('.catalogPaginator ul li')->count() != 0) {
            $pages = $crawler->filter('.catalogPaginator ul li');
            $currentPage = $crawler->filter('.catalogPaginator .selected')->first()->text();
            if ($currentPage < $pages->count()) {
                $nextUrl = $this->baseUrl . $pages->eq($currentPage)->filter('a')->attr('href');
                $this->info('parse next page: ' . $nextUrl . ' / ' . $pages->count());
                $this->parseSantehListProducts($catalog, $nextUrl, $subcatName, $priceMap);
            }
        }
    }


    //для поковки
    public function parsePokovkaCategory($categoryName, $categoryUrl, $parentId) {
        $this->info($categoryName . ' => ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catalog = $this->getCatalogByName($categoryName, $parentId, '');

        try {
            $this->parsePokovkaListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
        } catch (\Exception $e) {
            $this->warn('Error Parse From List: ' . $e->getMessage());
        }
    }
    //для поковки
    public function parsePokovkaListProducts($catalog, $categoryUrl, $subcatName, $priceMap) {
        $this->info('Parse products from: ' . $catalog->name);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        $catFilters = $priceMap[0] . '/' . $priceMap[1];

        $subCatalog = $subcatName ? $this->getCatalogByName($subcatName, $catalog->id, $catFilters) : null;

        $table = $crawler->filter('table')->first(); //table of products
        $table->filter('tbody tr')->reduce(function (Crawler $nnode, $i) {
            return ($i <= 2);
        })
            ->each(function (Crawler $node, $n) use ($catalog, $subCatalog, $priceMap) {
                $this->info('Parse: ' . ++$n . ' element');

                try {
                    $url = $this->baseUrl . trim($node->filter('a')->first()->attr('href'));

                    $data = [];
                    $data[$priceMap[2]] = preg_replace("/[^,.0-9]/", null, ($node->filter('td')
                        ->eq(8)->text())); //7 колонка цены

                    $data['measure'] = $priceMap[3];
                    $data['inStock'] = $data[$priceMap[2]] ? 1 : 0;

                    $product = Product::whereParseUrl($url)->first();
//                если новый товар -> заходим на страничку и получаем изображение и мин.длину
                    if (!$product) {
                        $name = trim($node->filter('.refstr')->first()->text());
                        $data['size'] = trim($node->filter('td')->eq(1)->text());
                        $data[$priceMap[0]] = trim($node->filter('td')->eq(2)->text());
                        $data[$priceMap[1]] = trim($node->filter('td')->eq(3)->text());

                        $html_product = $this->client->get($url);
                        $inner_html = $html_product->getBody()->getContents();
                        $product_crawler = new Crawler($inner_html);
                        $h1 = $product_crawler->filter('.catalogHeader h1')->first()->text();
                        $alias = Text::translit($h1);

                        //находим минимальную длину, если есть
                        if ($product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->count() != 0) {
                            $minLengthRaw = $product_crawler->filter('.catalogInfo > .catalogInfoWrap')->eq(2)->text();
                            $data['min_length'] = preg_replace("/[^,.0-9]/", null, $minLengthRaw);
                        }

                        $order = $subCatalog ? $subCatalog->products()->max('order') + 1 :
                            $catalog->products()->max('order') + 1;

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

                        sleep(rand(1, 2));
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($section);
                            $this->updateOneTime = true;
                        }
                    } else {
                        $product->update($data);
                        $product->catalog_id = $subCatalog ? $subCatalog->id : $catalog->id;
                        $product->save();
                        if (!$this->updateOneTime) {
                            $this->updateCatalogUpdatedAt($subCatalog ?: $catalog);
                            $this->updateOneTime = true;
                        }
                    }
                } catch (\Exception $e) {
                    $this->warn('error: ' . $e->getMessage());
                }
            });
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
        $safeUrl = str_replace(' ', '%20', $url);
        $this->info('downloadJpgFile url: ' . $safeUrl);
        $file = file_get_contents($this->baseUrl . $safeUrl);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($fileName), $file);
            return true;
        } catch (\Exception $e) {
            $this->warn('download jpg error: ' . $e->getMessage());
            return false;
        }
    }

    public function downloadSvgFile($url, $uploadPath, $fileName): bool {
        $safeUrl = str_replace(' ', '%20', $url);

        $image = SVG::fromFile($this->baseUrl . $safeUrl);
        if (!is_dir(public_path($uploadPath))) {
            mkdir(public_path($uploadPath), 0777, true);
        }
        try {
            file_put_contents(public_path($fileName), $image->toXMLString());
            return true;
        } catch (\Exception $e) {
            $this->warn('download svg error: ' . $e->getMessage());
            return false;
        }
    }

    public function parseProductWallFromString($str, $productSize, $rectangle = null) {
        if (!$productSize) return null;
        if (!$rectangle) {
            $sizePos = mb_stripos($str, $productSize); //находим место в строке с текущим размером
            $subStr = mb_substr($str, $sizePos + mb_strlen($productSize) + 1); //вырезаем подстроку в которой есть размер стенки
            $charX = null;
        } else {
            //для прямоугольника, напр: 'трубы нерж. электросварные ЭСВ прямоугольные 30x15x1.5 шлиф';
            $sizeTempPos = mb_stripos($str, $productSize); //находим size 30
            $tempSubStr = mb_substr($str, $sizeTempPos + mb_strlen($productSize)); //'x15x1.5 шлиф'
            $charX = $tempSubStr[0];
            $sizeTempPos = mb_strripos($tempSubStr, $tempSubStr[0]); // 3 символ = последняя x
            $subStr = mb_substr($tempSubStr, $sizeTempPos + 1); // '1.5 шлиф'
        }

        if (mb_stripos($subStr, ' ')) {
            // если есть пробел в подстроке, отбрасываем лишнее и берем первую часть
            $arr = explode(' ', $subStr);
            return $arr[0];
        } else {
            // если в подстроке нет пробелов, т.е. строка заканчивается размером стенки
            if ($charX) {
                $arr = array_reverse(explode($charX, $subStr));
                return $arr[0];
            } else {
                return $subStr;
            }
        }
    }

    public function getKFromScriptUrl($scriptUrl) {
        try {
            $scriptPage = $this->client->get($scriptUrl);
            $scriptHtml = $scriptPage->getBody()->getContents();
            $scriptCrawler = new Crawler($scriptHtml);
            $scriptText = $scriptCrawler->filter('script[language="Javascript"]')->first()->text();
            $findStart = stripos($scriptText, 'var k=');
            $findEnd = stripos($scriptText, ';', $findStart);
            return substr($scriptText, $findStart + 6, $findEnd - $findStart - 6);
        } catch (\Exception $e) {
            $this->warn('/extract inner script problem/ => ' . $e->getMessage());
        }
    }

    /**
     * @param string $categoryName
     * @param int $parentId
     * @return Catalog
     */
    private function getCatalogByName(string $categoryName, int $parentId, string $catFilters = null): Catalog {
        $catalog = Catalog::whereName($categoryName)->first();
        if (!$catalog) {
            $catalog = Catalog::create([
                'name' => $categoryName,
                'title' => $categoryName,
                'h1' => $categoryName,
                'parent_id' => $parentId,
                'filters' => $catFilters,
                'alias' => Text::translit($categoryName),
                'slug' => Text::translit($categoryName),
                'order' => Catalog::whereParentId($parentId)->max('order') + 1,
                'published' => 1,
            ]);
        } else {
            $catalog->filters = $catFilters;
            $catalog->save();
        }
        return $catalog;
    }

    private function updateCatalogUpdatedAt(Catalog $catalog) {
        $catalog->updated_at = Carbon::now();
        $catalog->save();
        if($catalog->parent_id !== 0) {
            $cat = Catalog::find($catalog->parent_id);
            $this->updateCatalogUpdatedAt($cat);
        }
    }

    public function getInnerSiteScript($node): string {
        $idt = $node->attr('idt');
        $idf = $node->attr('idf');
        $idb = $node->attr('idb');
        //mc.ru//pages/blocks/add_basket.asp/id/XY12/idf/5/idb/1
        return 'mc.ru//pages/blocks/add_basket.asp/id/' . $idt . '/idf/' . $idf . '/idb/' . $idb;
    }

}
