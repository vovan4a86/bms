<?php namespace Fanky\Admin;

use Auth;
use Closure;
use Menu;

class AdminMenuMiddleware {

	/**
	 * Run the request filter.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$cur_user = Auth::user();
		Menu::make('main_menu', function (\Lavary\Menu\Builder $menu) use($cur_user, $request) {
			$menu->add('Структура сайта', ['route' => 'admin.pages', 'icon' => 'fa-sitemap'])
				->active('/admin/pages/*');
			$menu->add('Каталог', ['route' => 'admin.catalog', 'icon' => 'fa-list'])
				->active('/admin/catalog/*');

//			$menu->add('Акции', ['route' => 'admin.actions', 'icon' => 'fa-percent'])
//				->active('/admin/actions/*');

//            $menu->add('Спецпредложения', ['route' => 'admin.offers', 'icon' => 'fa-star'])
//				->active('/admin/offers/*');

			$menu->add('Заказы', ['route' => 'admin.orders', 'icon' => 'fa-dollar'])
				->active('/admin/orders/*');

//			$menu->add('Галереи', ['route' => 'admin.gallery', 'icon' => 'fa-image'])
//				->active('/admin/gallery/*');
//			$menu->add('Отзывы', ['route' => 'admin.reviews', 'icon' => 'fa-star'])
//				->active('/admin/reviews/*');
			$menu->add('Региональность', ['route' => 'admin.cities', 'icon' => 'fa-globe'])
				->active('/admin/cities/*');

			$menu->add('Новости', ['route' => 'admin.news', 'icon' => 'fa-calendar'])
				->active('/admin/news/*');

			$menu->add('Вакансии', ['route' => 'admin.vacancies', 'icon' => 'fa-user-circle'])
				->active('/admin/vacancies/*');

			$menu->add('Партнеры', ['route' => 'admin.partners', 'icon' => 'fa-handshake-o'])
				->active('/admin/partners/*');

			$menu->add('Способы доставки', ['route' => 'admin.delivery', 'icon' => 'fa-truck'])
				->active('/admin/delivery/*');

            $menu->add('Контакты в городах', ['route' => 'admin.contacts', 'icon' => 'fa-id-card'])
                ->active('/admin/contacts/*');

//			$menu->add('Статьи', ['route' => 'admin.publications', 'icon' => 'fa-calendar'])
//				->active('/admin/publications/*');

			$menu->add('Настройки', ['icon' => 'fa-cogs'])
				->nickname('settings');
			$menu->settings->add('Настройки', ['route' => 'admin.settings', 'icon' => 'fa-gear'])
				->active('/admin/settings/*');
			$menu->settings->add('Редиректы', ['route' => 'admin.redirects', 'icon' => 'fa-retweet'])
				->active('/admin/redirects/*');
//			$menu->add('Медиаменеджер', ['route' => 'admin.pages.imagemanager', 'icon' => 'fa-picture-o'])
//				->active('/admin/pages/imagemanager');
			$menu->add('Файловый менеджер', ['route' => 'admin.pages.filemanager', 'icon' => 'fa-file'])
				->active('/admin/pages/filemanager');
		});

		return $next($request);
	}

}
