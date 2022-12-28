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

class ParseSanteh extends Command {

    use ParseFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:santeh';
    private $basePath = ProductImage::UPLOAD_URL . 'santeharm/';
    public $client;
    public $catalogItemListTagElement = '.gr_spis>ul>li'; //где искать список подразделов

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Парсим сантехарматуру';

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

    //      0          1          2          3         4
    //[2 колонка, 3 колонка, по какой цене=inStock, measure]
    public $priceMap = [
        'Фитинги, арматура PP-R' => ['type', 'comment', 'price_per_item', 'шт'],

        'Трубы PP-R армированные стекло волокном' => ['py', 'length', 'price_per_metr', 'м'],
        'Трубы PP-R армированные фольгой' => ['py', 'length', 'price_per_metr', 'м'],
        'Трубы PP-R не армированные' => ['py', 'length', 'price_per_metr', 'м'],

        'Фитинги резьбовые латунные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Фитинги резьбовые стальные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Фитинги резьбовые чугунные' => ['type', 'comment', 'price_per_item', 'шт'],

        'Трубы полиэтиленовые' => ['py', 'length', 'price_per_metr', 'м'],

        'Краны шаровые латунные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Краны шаровые стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Краны латунные для манометров' => ['py', 'comment', 'price_per_item', 'шт'],
        'Задвижки стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Задвижки чугунные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны и затворы обратные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны стальные запорные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны и затворы чугунные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны пожарные' => ['py', 'comment', 'price_per_item', 'шт'],

        'Фланцы стальные' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Отводы стальные' => ['wall', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Тройники стальные' => ['wall', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Заглушки стальные' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Переходы стальные' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Сгоны, бочата, резьбы' => ['length', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Опоры стальные' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Хомуты' => ['diameter', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Фильтры' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Грязевики' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Трубы полипропиленовые и соединительные детали PP-H' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Трубы из поливинилхлорида ПВХ' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Трубы чугунные безраструбные SML' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Трубы чугунные ЧК и соединительные детали' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],

        'Теплоизоляция полиэтилен' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Теплоизоляция каучук' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Паронит листовой' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Прокладки' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Лента ФУМ и нить уплотнительная' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Радиаторы THERMA Q' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Радиаторы биметаллические' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Радиаторы алюминиевые' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Радиаторы стальные панельные' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Радиаторы чугунные' => ['type', 'length', '', '', 'price_per_item', 4, 'шт', 0],
        'Полотенцесушители' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Узлы подключения' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Комплектующие для радиаторов' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Регулирующая, предохранительная арматура и автоматика' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Коллекторы и коллекторные группы' => ['py', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Инструмент для монтажа PP-R' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Комплектующие для КИПиА' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Манометры' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Термоманометры' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],
        'Термометры биметаллические' => ['type', 'comment', '', '', 'price_per_item', 4, 'шт', 0],

        'Электроинструменты' => ['brand', 'model', '', '', 'price_per_item', 4, 'шт', 0],
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        foreach ($this->categoryList() as $categoryName => [$categoryUrl, $catFilters]) {
//            $this->info($categoryName);
//            $this->info($catFilters[0]);
            $this->parseSantehCategory($categoryName, $categoryUrl, 4, $catFilters);
        }
        $this->info('The command was successful!');
    }

    public function categoryList(): array {
        return [
            'Трубы и фитинги' => ['https://mc.ru/metalloprokat/fitingi', 'type/comment'],
//            'Запорная арматура и электроприводы' => 'https://mc.ru/metalloprokat/zapornaya_armatura',
//            'Детали трубопроводов, хомуты и крепеж' => 'https://mc.ru/metalloprokat/dettrub',
//            'Фильтры, грязевики, элеваторы' => 'https://mc.ru/metalloprokat/filtry_gryazeviki_elevatory',
//            'Трубы канализационные и соединительные детали' => 'https://mc.ru/metalloprokat/truby_kanalizacionnye',
//            'Теплоизоляция, уплотнения, защитные покрытия' => 'https://mc.ru/metalloprokat/teploizolyaciya',
//            'Радиаторы, полотенцесушители, конвекторы и комплектующие' => 'https://mc.ru/metalloprokat/radiatory_konvektory',
//            'Регулирующая, предохранительная арматура и автоматика' => 'https://mc.ru/metalloprokat/regulyatory_davleniya',
//            'Коллекторы и коллекторные группы' => 'https://mc.ru/metalloprokat/kollektory_raspredel',
//            'Инструмент, оборудование и материалы' => 'https://mc.ru/metalloprokat/instrument_engineering',
//            'Электроинструменты' => 'https://mc.ru/metalloprokat/elektroinstrument',
        ];
    }
}
