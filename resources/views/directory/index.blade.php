@extends('template')
@section('content')
    @include('blocks.bread')
    <main>
        <main>
            <!-- x-data-->
            <div x-data="{view: 'ГОСТы и Ту'}">
                <div class="page-head page-head--dark">
                    <div class="page-head__container container">
                        <div class="page-head__content">
                            <div class="page-head__title">Справочник</div>
                            <div class="page-head__actions">
                                <div class="page-head__action" @click="view = 'ГОСТы и Ту'"
                                     :class="view == 'ГОСТы и Ту' &amp;&amp; 'is-active'"
                                     aria-label="Показать ГОСТы и Т">ГОСТы и Ту
                                </div>
                                <div class="page-head__action" @click="view = 'FAQ'"
                                     :class="view == 'FAQ' &amp;&amp; 'is-active'" aria-label="Показать FAQ">FAQ
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="infos">
                    <div class="infos__container container">
                        <div class="infos__view" x-show="view == 'ГОСТы и Ту'" x-transition.duration.250ms>
                            <div class="gost">
                                <div class="gost__title">{!! $gostPageText !!}</div>
                                @if($titlePages)
                                    <ul class="gost__list list-reset">
                                        @foreach($titlePages as $page)
                                            <li>
                                                <a href="{{ $page->url }}">{{ $page->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="gost__data">
                                    @foreach($pagesWithFiles as $page)
                                        @if(count($children = $page->children))
                                            <div class="gost__block">
                                                <div class="gost__subtitle">{{ $page->name }}</div>
                                                @foreach($children as $child)
                                                    <div class="gost__subcategory">
                                                        <a class="gost__link"
                                                           href="{{ $child->url }}">{{ $child->name }}
                                                        </a>
                                                        <div class="gost__grid">
                                                            @foreach($child->gostFiles as $file)
                                                                <div class="gost__col">
                                                                    <a class="document" href="{{ $file->fileSrc }}"
                                                                       data-fancybox="data-fancybox" data-type="pdf"
                                                                       title="{{ $file->file_name }}">
                                                                        <span class="document__icon lazy"
                                                                              data-bg="static/images/common/ico_pdf.svg"></span>
                                                                        <span class="document__content">
                                                                                       <span class="document__title">{{ $file->file_name }}</span>
                                                                                       <span class="document__text">{{ $file->file_description }}</span>
                                                                                       <span class="document__size">{{ $file->fileSize }}</span>
                                                                        </span>
                                                                        <span class="document__link">
                                                                            <svg width="23" height="23"
                                                                                 viewBox="0 0 23 23" fill="none"
                                                                                 xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M11.5801 3.62402V19.2165"
                                                                                      stroke="currentColor"
                                                                                      stroke-linecap="round"
                                                                                      stroke-linejoin="round"/>
                                                                                <path d="M5.20117 12.8379L11.5799 19.2166L17.9587 12.8379"
                                                                                      stroke="currentColor"
                                                                                      stroke-linecap="round"
                                                                                      stroke-linejoin="round"/>
                                                                            </svg>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                                @else
                                                    <div class="gost__block">
                                                        <div class="gost__subtitle">{{ $page->name }}</div>
                                                        <div class="gost__grid">
                                                            @foreach($page->gostFiles as $file)
                                                                <div class="gost__col">
                                                                    <a class="document"
                                                                       href="{{ $file->fileSrc }}"
                                                                       data-fancybox="data-fancybox" data-type="pdf"
                                                                       title="{{ $file->file_name }}">
                                                                <span class="document__icon lazy"
                                                                      data-bg="/static/images/common/ico_pdf.svg"
                                                                      style="background-image: url('/static/images/common/ico_pdf.svg');">

                                                                </span>
                                                                        <span class="document__content">
                                                                    <span class="document__title">{{ $file->file_name }}</span>
                                                                    <span class="document__text">{{ $file->file_description }}</span>
                                                                    <span class="document__size">{{ $file->fileSize }}</span>
                                                                </span>
                                                                        <span class="document__link">
                                                                    <svg width="23" height="23"
                                                                         viewBox="0 0 23 23" fill="none"
                                                                         xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M11.5801 3.62402V19.2165"
                                                                              stroke="currentColor"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"></path>
                                                                        <path d="M5.20117 12.8379L11.5799 19.2166L17.9587 12.8379"
                                                                              stroke="currentColor"
                                                                              stroke-linecap="round"
                                                                              stroke-linejoin="round"></path>
                                                                    </svg>
                                                                </span>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @endforeach
                                            </div>
                                </div>
                            </div>
                        </div>
                        <div class="infos__view" x-show="view == 'FAQ'" x-transition.duration.250ms>
                            <div class="faq" x-data="{view: 0}">
                                <div class="faq__title">{!! $faqPageText !!}
                                </div>
                                @if($faqs = Settings::get('faq_list'))
                                <div class="faq__body">
                                    @foreach($faqs as $faq)
                                    <div class="faq__item">
                                        <div class="faq__question" @click="view == {{ $loop->iteration }} ? view = 0 : view = {{ $loop->iteration }}"
                                             :class="view == {{ $loop->iteration }} &amp;&amp; 'is-active'">
                                            <span>{{ $faq['faq_q'] }}</span>
                                            <div class="faq__icon lazy"
                                                 data-bg="static/images/common/ico_faq.svg"></div>
                                        </div>
                                        <div class="faq__answer text-block" x-show="view == {{ $loop->iteration }}"
                                             x-transition.duration.250ms>
                                            {!! $faq['faq_a'] !!}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif

                                <div class="faq__callback">
                                    <div class="faq__row">
                                        <div class="faq__decor lazy"
                                             data-bg="static/images/common/ico_decor.svg"></div>
                                        <div class="faq__label">
                                            <span>Не нашли ответа на свой вопрос? Свяжитесь с нами, и мы предоставим всю необходимую информацию.</span>
                                        </div>
                                        <div class="faq__action">
                                            <button class="button button--primary button--small btn-reset"
                                                    type="button"
                                                    data-question data-src="#question">
                                                <span>Задать вопрос</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </main>
@endsection
