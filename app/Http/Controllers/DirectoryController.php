<?php namespace App\Http\Controllers;

use App;
use Fanky\Admin\Models\News;
use Fanky\Admin\Models\NewsTag;
use Fanky\Admin\Models\Page;
//use Request;
use Fanky\Admin\Models\Partner;
use Illuminate\Http\Request;
use Settings;
use View;

class DirectoryController extends Controller {
	public $bread = [];
	protected $directory_page;

	public function __construct() {
		$this->directory_page = Page::whereAlias('directory')
			->get()
			->first();
		$this->bread[] = [
			'url'  => $this->directory_page['url'],
			'name' => $this->directory_page['name']
		];
	}

	public function index(Request $request) {
		$page = $this->directory_page;
        $page->setSeo();

        if (!$page)
			abort(404, 'Страница не найдена');
		$bread = $this->bread;

        if (count($request->query())) {
            View::share('canonical', $this->directory_page->alias);
        }

        $gostPage = Page::where('id', '14')->first();
        $faqPage = Page::where('id', '15')->first();
//        $dirPages = $page->children;
        $titlePages = $gostPage->getPublicChildren();

        //выборка страниц ГОСТов с файлами
        $pagesWithFiles = collect();
        foreach($titlePages as $p) {
            $children = $p->children;
            if(count($children)) {
                foreach ($children as $child) {
                    if($child->gostFiles) {
                        $pagesWithFiles->push($p);
                        break;
                    }
                }
            } else {
                if(count($p->gostFiles)) {
                    $pagesWithFiles->push($p);
                }
            }
        }

        return view('directory.index', [
            'bread' => $bread,
            'h1' => $page->h1,
            'title' => $page->title,
            'text' => $page->text,
            'gostPageText' => $gostPage->text,
            'faqPageText' => $faqPage->text,
            'headerIsBlack' => true,
            'titlePages' => $titlePages,
            'pagesWithFiles' => $pagesWithFiles,
        ]);
	}

}
