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

    //      0          1              2                3               4
    //[2 колонка, 3 колонка, по какой цене=inStock, measure, делать 1 картинку картинкой раздела]
    public $priceMap = [
        'Фитинги, арматура PP-R' => ['type', 'comment', 'price_per_item', 'шт'],

        'Трубы полипропиленовые PP-R' => ['py', 'length'],
        'Трубы PP-R армированные стекло волокном' => ['py', 'length', 'price_per_metr', 'м', 1],
        'Трубы PP-R армированные фольгой' => ['py', 'length', 'price_per_metr', 'м', 1],
        'Трубы PP-R не армированные' => ['py', 'length', 'price_per_metr', 'м', 1],

        'Фитинги резьбовые латунные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Фитинги резьбовые стальные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Фитинги резьбовые чугунные' => ['type', 'comment', 'price_per_item', 'шт'],

        'Трубы полиэтиленовые' => ['py', 'length', 'price_per_metr', 'м', 1],

        'Краны шаровые латунные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Краны шаровые стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Краны латунные для манометров' => ['py', 'comment', 'price_per_item', 'шт'],
        'Задвижки стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Задвижки чугунные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны и затворы обратные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Клапаны стальные запорные' => ['py', 'comment', 'price_per_item', 'шт', 1],
        'Клапаны и затворы чугунные' => ['py', 'comment', 'price_per_item', 'шт', 1],
        'Клапаны пожарные' => ['py', 'comment', 'price_per_item', 'шт'],

        'Фланцы стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Отводы стальные' => ['wall', 'comment', 'price_per_item', 'шт'],
        'Тройники стальные' => ['wall', 'comment', 'price_per_item', 'шт'],
        'Заглушки стальные' => ['py', 'comment', 'price_per_item', 'шт'],
        'Переходы стальные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Сгоны, бочата, резьбы' => ['length', 'comment', 'price_per_item', 'шт'],
        'Опоры стальные' => ['type', 'comment', 'price_per_item', 'шт'],
        'Хомуты' => ['diameter', 'comment', 'price_per_item', 'шт'],

        'Фильтры' => ['py', 'comment', 'price_per_item', 'шт'],
        'Грязевики' => ['type', 'comment', 'price_per_item', 'шт'],

        'Трубы полипропиленовые и соединительные детали PP-H' => ['type', 'length', 'price_per_item', 'шт'],
        'Трубы из поливинилхлорида ПВХ' => ['type', 'length', 'price_per_item', 'шт'],
        'Трубы чугунные безраструбные SML' => ['type', 'length', 'price_per_item', 'шт'],
        'Трубы чугунные ЧК и соединительные детали' => ['type', 'length', 'price_per_item', 'шт'],

        'Теплоизоляция полиэтилен' => ['type', 'length', 'price_per_item', 'шт'],
        'Теплоизоляция каучук' => ['type', 'length', 'price_per_item', 'шт'],
        'Паронит листовой' => ['type', 'comment', 'price_per_item', 'шт'],
        'Прокладки' => ['type', 'comment', 'price_per_item', 'шт'],
        'Лента ФУМ и нить уплотнительная' => ['type', 'comment', 'price_per_item', 'шт'],

        'Радиаторы THERMA Q' => ['type', 'comment', 'price_per_item', 'шт'],
        'Радиаторы биметаллические' => ['type', 'comment', 'price_per_item', 'шт'],
        'Радиаторы алюминиевые' => ['type', 'comment', 'price_per_item', 'шт'],
        'Радиаторы стальные панельные' => ['type', 'comment', 'price_per_item', 'шт', 1],
        'Радиаторы чугунные' => ['type', 'length', 'price_per_item', 'шт'],
        'Полотенцесушители' => ['type', 'comment', 'price_per_item', 'шт'],
        'Узлы подключения' => ['py', 'comment', 'price_per_item', 'шт'],
        'Комплектующие для радиаторов' => ['type', 'comment', 'price_per_item', 'шт'],

        'Регулирующая, предохранительная арматура и автоматика' => ['py', 'comment', 'price_per_item', 'шт'],
        'Коллекторы и коллекторные группы' => ['py', 'comment', 'price_per_item', 'шт'],

        'Инструмент для монтажа PP-R' => ['type', 'comment', 'price_per_item', 'шт'],

        'Комплектующие для КИПиА' => ['type', 'comment', 'price_per_item', 'шт'],
        'Манометры' => ['type', 'comment', 'price_per_item', 'шт'],
        'Термоманометры' => ['type', 'comment', 'price_per_item', 'шт'],
        'Термометры биметаллические' => ['type', 'comment', 'price_per_item', 'шт'],

        'Электроинструменты' => ['brand', 'model', 'price_per_item', 'шт'],
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        foreach ($this->categoryList() as $categoryName => [$categoryUrl, $catFilters]) {
            $this->parseSantehCategory($categoryName, $categoryUrl, 4, $catFilters);
        }
        $this->info('The command was successful!');
    }

    public function categoryList(): array {
        return [
            'Трубы и фитинги' => ['https://mc.ru/metalloprokat/fitingi', 'type/comment'],
            'Запорная арматура и электроприводы' => ['https://mc.ru/metalloprokat/zapornaya_armatura', 'py/comment'],
            'Детали трубопроводов, хомуты и крепеж' => ['https://mc.ru/metalloprokat/dettrub', 'py/comment'],
            'Фильтры, грязевики, элеваторы' => ['https://mc.ru/metalloprokat/filtry_gryazeviki_elevatory', 'py/comment'],
            'Трубы канализационные и соединительные детали' => ['https://mc.ru/metalloprokat/truby_kanalizacionnye', 'type/length'],
            'Теплоизоляция, уплотнения, защитные покрытия' => ['https://mc.ru/metalloprokat/teploizolyaciya', 'type/length'],
            'Радиаторы, полотенцесушители, конвекторы и комплектующие' => ['https://mc.ru/metalloprokat/radiatory_konvektory', 'type/comment'],
            'Регулирующая, предохранительная арматура и автоматика' => ['https://mc.ru/metalloprokat/regulyatory_davleniya', 'py/comment'],
            'Коллекторы и коллекторные группы' => ['https://mc.ru/metalloprokat/kollektory_raspredel', 'py/comment'],
            'Инструмент, оборудование и материалы' => ['https://mc.ru/metalloprokat/instrument_engineering', 'type/comment'],
            'Электроинструменты' => ['https://mc.ru/metalloprokat/elektroinstrument', 'brand/model'], //отдельно
        ];
    }

}
