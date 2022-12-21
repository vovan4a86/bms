<?php namespace App\Traits;

use Fanky\Admin\Models\Catalog;
use Fanky\Admin\Models\Product;
use Fanky\Admin\Models\ProductImage;
use Fanky\Admin\Text;
use SVG\SVG;
use Symfony\Component\DomCrawler\Crawler;

trait ParseFunctions {

    public $baseUrl = 'https://mc.ru';

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

    public function parseCategory($categoryName, $categoryUrl, $parentId) {
        $this->info($categoryName . ' => ' . $categoryUrl);
        $res = $this->client->get($categoryUrl);
        $html = $res->getBody()->getContents();
        $crawler = new Crawler($html); //page from url

        if (!$parentId) {
            $catalog = $this->getCatalogByName($categoryName, 2);
        } else {
            $catalog = $this->getCatalogByName($categoryName, $parentId);
        }

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
                //1 товар парсим?
                $this->parseOneProductFromList($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
                //или все?
//            $this->parseListProducts($catalog, $categoryUrl, $categoryName, $this->priceMap[$catalog->name]);
            } catch(\Exception $e) {
                $this->info('Error Parse From List: ' . $e->getMessage());
                $this->info('Check priceMap values for '. $categoryName);
            }
        }
    }

    //парсим по 1 товару из категории
    public function parseOneProductFromList($catalog, $categoryUrl, $subcatName, $priceMap) {
        $this->info('[section] ' . $catalog->name);
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
        $node = $table->filter('tbody tr')->first();

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

            if(isset($data['price']) && $data['price'] != null) {
                $data['raw_price'] = $data['price'];
                $data['price'] = (ceil($data['raw_price'] / 100)) * 100; //округляем в большую сторону
            }

            $data['measure'] = $priceMap[7];
            $data['inStock'] = $data[$priceMap[$usedPrice]] ? 1 : 0;

            $product = Product::whereParseUrl($url)->first();
//          если новый товар
            if (!$product) {
                $name = trim($node->filter('.refstr')->first()->text());

                $colName = $priceMap[0];
                $data[$colName] = trim($node->filter('td')->eq(1)->text());
                $colName = $priceMap[1];
                $data[$colName] = trim($node->filter('td')->eq(2)->text());
                $colName = $priceMap[2];
                $data[$colName] = trim($node->filter('td')->eq(3)->text());

                //если 1 ищем стенку
                if ($priceMap[8] == 1) {
                    $data['wall'] = $this->parseProductWallFromString($name, $data['size']);
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

                $this->info('[+] ' . $name);

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

    public function parseProductWallFromString($str, $productSize, $rectangle = null) {
        if (!$productSize) return null;
        if(!$rectangle) {
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
            if($charX) {
                $arr = array_reverse(explode($charX, $subStr));
                return $arr[0];
            } else {
                return $subStr;
            }
        }
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

}
