<!DOCUMENT html>
<html lang="ru"> 
	<head>
		
		<link rel="stylesheet" href="/tpl/css/style.css"/>
		 <link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|PT+Sans+Narrow|Roboto:100,300&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
	</head>
	<body>
	<div class="container"> 
	<div  id="wrapper" >
		
			<header>
			<a id="logo" href="/">
			   <span>NaviStom</span>
			</a>
			 <span class='menu'> <img src='/tpl/image/menu.png'/></span>
			 
			 <!---------------------Подать Объявление---------------------------------->
				<div class=" all_bottom">
			<div class="add_oby">
			  <span>+</span> ПОДАТЬ ОБЪЯВЛЕНИЕ
			</div>
			<div class="menu_top_right">
			   <div class="l_m_a">
					<span>Выберите раздел:</span>
			   </div>
			  <ul>
			  			   
                <li>
                   &gt;<a title="Продам новое" class="ajax-link" target="_self" href="/product/add"> Продам новое</a>
			    </li><li>
                   &gt;<a title="Продам Б/У" class="ajax-link" target="_self" href="/ads/add"> Продам Б/У</a>
			    </li><li>
                   &gt;<a title="Сервис/Запчасти" class="ajax-link" target="_self" href="/service/add"> Сервис/Запчасти</a>
			    </li><li>
                   &gt;<a title="Спрос" class="ajax-link" target="_self" href="/demand/add"> Спрос</a>
			    </li><li>
                   &gt;<a title="Мероприятия" class="ajax-link" target="_self" href="/activity/add"> Мероприятия</a>
			    </li><li>
                   &gt;<a title="З/Т лаборатории" class="ajax-link" target="_self" href="/lab/add"> З/Т лаборатории</a>
			    </li><li>
                   &gt;<a title="Недвижимость" class="ajax-link" target="_self" href="/realty/add"> Недвижимость</a>
			    </li><!--li>
                   &gt;<a href="/diagnostic/add" target="_self" class='ajax-link' title="Диагностика"> Диагностика</a>
			    </li--><li>
                   &gt;<a title="Резюме" class="ajax-link" target="_self" href="/work/resume/add"> Резюме</a>
			    </li><li>
                   &gt;<a title="Вакансии" class="ajax-link" target="_self" href="/work/vacancy/add"> Вакансии</a>
			    </li>
			</ul>
				<div class="close">
					<div class="plus "><i class="a-icon-remove"></i></div>
					
				</div>
			</div>
          </div>
			 <!------------------------------------------------------------------------>
			 
			 <ul id="auth-menu">
				 <li>
					<a href="/login" class="a-color-gray a-font-small ajax-link" title="Вход">Вход</a>
				</li>
				<li class="a-color-gray">|</li>
				<li>
					<a href="/registration" class="a-color-gray a-font-small ajax-link" title="Регистрация">Регистрация</a>
				</li>
            </ul>
		   </header>
		  <div class="clear" id="main">
		  <!-----------------------Реклама ------------------------------>
		  <div id="reklama" >
                	             <a target="_blank" href="/banner-117">
                <img src="/uploads/banners/04997499088284393408.jpg">
            </a>
                            </div>
		  </div>
		<!------------------------------------------------------------->
		<section class="n-main-nav">
            <div class="left">
                <form action="/search" method="get" id="global-search">
                    <input type="text" id="global-search-input" name="q" placeholder="Поиск по сайту...">
                    <button type="submit" id="search-submit"></button>
                </form>

                <nav id="main-navigation">
                    <ul class="clear">
                                                    <li>
                                <a href="/products" target="_self" title="Продам новое">
                                    <i class="navi-icon-products-new"></i>

                                    <div>
                                                                                    Продам новое
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/ads" target="_self" title="Продам Б/У">
                                    <i class="navi-icon-ads"></i>

                                    <div>
                                                                                    Продам Б/У
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/products/filter-stocks" target="_self" title="Акции">
                                    <i class="navi-icon-stock"></i>

                                    <div>
                                                                                    Акции
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/services" target="_self" title="Сервис/Запчасти">
                                    <i class="navi-icon-service"></i>

                                    <div>
                                                                                    Сервис <br>Запчасти
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/demand" target="_self" title="Спрос">
                                    <i class="navi-icon-demand"></i>

                                    <div>
                                                                                    Спрос
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/activity" target="_self" title="Мероприятия">
                                    <i class="navi-icon-activity"></i>

                                    <div>
                                                                                    Мероприятия
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/work/resume" target="_self" title="Резюме">
                                    <i class="navi-icon-work"></i>

                                    <div>
                                                                                    Резюме
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/work/vacancy" target="_self" title="Вакансии">
                                    <i class="navi-icon-vacancy"></i>

                                    <div>
                                                                                    Вакансии
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/labs" target="_self" title="З/Т лаборатории">
                                    <i class="navi-icon-labs"></i>

                                    <div>
                                                                                    З/Т лаборатории
                                                                            </div>
                                </a>
                            </li>
                                                    <li>
                                <a href="/realty" target="_self" title="Недвижимость">
                                    <i class="navi-icon-realty"></i>

                                    <div>
                                                                                    Недвижимость
                                                                            </div>
                                </a>
                            </li>
                                            </ul>
                </nav>
            </div>
            <div class="right">
                <div class="clear" id="main-scroller">
                    <div class="scroller-items">
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1426-otbelivanie-stoilo-patsientke-perednih-zubov" class="ajax-link" title="Отбеливание стоило пациентке передних зубов"><img src="/uploads/images/articles/100x150/55045568625002741075.jpg" title="Отбеливание стоило пациентке передних зубов" alt="Отбеливание стоило пациентке передних зубов">
                                    Отбеливание стоило пациентке передних зубов</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1414-tolko-top-obyyavleniya-publikuyutsya-na-glavnoy-stranitse" class="ajax-link" title="Только ТОП-объявления публикуются на Главной странице NaviStom"><img src="/uploads/images/articles/100x150/16330929273170720381.jpg" title="Только ТОП-объявления публикуются на Главной странице NaviStom" alt="Только ТОП-объявления публикуются на Главной странице NaviStom">
                                    Только ТОП-объявления публикуются на Главной странице NaviStom</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1424-hiii-mijnarodna-stomatologichna-vistavka-dental-ukrayina-ta" class="ajax-link" title="ХІІІ Міжнародна стоматологічна виставка «Дентал-Україна» та ХІІІ Міжнародний стоматологічний форум"><img src="/uploads/images/articles/100x150/29505790405463194931.jpg" title="ХІІІ Міжнародна стоматологічна виставка «Дентал-Україна» та ХІІІ Міжнародний стоматологічний форум" alt="ХІІІ Міжнародна стоматологічна виставка «Дентал-Україна» та ХІІІ Міжнародний стоматологічний форум">
                                    ХІІІ Міжнародна стоматологічна виставка «Дентал-Україна» та ХІІІ Міжнародний стоматологічний форум</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1423-17-prichin-nepriyatnogo-zapaha-izo-rta-patsienta" class="ajax-link" title="17 причин неприятного запаха изо рта пациента"><img src="/uploads/images/articles/100x150/05650984095054071135.jpg" title="17 причин неприятного запаха изо рта пациента" alt="17 причин неприятного запаха изо рта пациента">
                                    17 причин неприятного запаха изо рта пациента</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1421-neskolko-slov-o-stomatologicheskih-laboratornyih-3d-skanerah" class="ajax-link" title="Несколько слов о стоматологических лабораторных 3D сканерах"><img src="/uploads/images/articles/100x150/46672942273789931214.jpg" title="Несколько слов о стоматологических лабораторных 3D сканерах" alt="Несколько слов о стоматологических лабораторных 3D сканерах">
                                    Несколько слов о стоматологических лабораторных 3D сканерах</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1419-stomatologicheskie-ustanovki-novogo-pokoleniya-fona-1000" class="ajax-link" title="Стоматологические установки нового поколения FONA 1000"><img src="/uploads/images/articles/100x150/29774689628942373786.jpg" title="Стоматологические установки нового поколения FONA 1000" alt="Стоматологические установки нового поколения FONA 1000">
                                    Стоматологические установки нового поколения FONA 1000</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1417-9-populyarnyih-zablujdeniy-o-detskoy-gigiene-polosti-rta" class="ajax-link" title="9 популярных заблуждений о детской гигиене полости рта"><img src="/uploads/images/articles/100x150/88706431921922122911.jpg" title="9 популярных заблуждений о детской гигиене полости рта" alt="9 популярных заблуждений о детской гигиене полости рта">
                                    9 популярных заблуждений о детской гигиене полости рта</a>
                                </div>
                            </div>
                                                    <div>
                                <div class="scroller-item">
                                    <a href="/article/1416-estetika-tselnokeramicheskoy-restavratsii-s-nondas" class="ajax-link" title="Эстетика цельнокерамической реставрации с Nondas Vlachopolous. Пост-релиз. Фото и видео"><img src="/uploads/images/articles/100x150/09318855867678426767.jpg" title="Эстетика цельнокерамической реставрации с Nondas Vlachopolous. Пост-релиз. Фото и видео" alt="Эстетика цельнокерамической реставрации с Nondas Vlachopolous. Пост-релиз. Фото и видео">
                                    Эстетика цельнокерамической реставрации с Nondas Vlachopolous. Пост-релиз. Фото и видео</a>
                                </div>
                            </div>
                                            </div>
                </div>
                <div class="scroller-navi">
                    <a class="all" title="Все статьи" href="/articles">Все статьи</a>
                    <a class="prev"><i class="a-icon-chevron-left a-icon-gray"></i></a>
                    <a class="next"><i class="a-icon-chevron-right a-icon-gray"></i></a>
                </div>
            </div>
        </section>
		<div class='text_bottom_menu'>
		 СТОМАТОЛОГИЧЕСКИЙ ПОРТАЛ - NAVISTOM - НАВИГАТОР СТОМАТОЛОГИИ
		</div>
		 
		<div class="a-row n-top-providers">
				
							<a href="/provider-1-http:||navistom.com|products|user-6-kmm" title="Укр-Медмаркет" target="_self" class="a-cols-5">
			<img src="/uploads/providers/14159179777448873529.png" alt="Укр-Медмаркет" title="Укр-Медмаркет">
		</a>
							<a href="/provider-12-http:||navistom.com|products|user-51-dlux" title="Делюкс" target="_self" class="a-cols-5">
			<img src="/uploads/providers/53591498795854335627.jpg" alt="Делюкс" title="Делюкс">
		</a>
							<a href="/provider-4-http:||navistom.com|products|user-1-masterdent" title="UDS Мастер-Дент" target="_self" class="a-cols-5">
			<img src="/uploads/providers/13781567628334842459.jpg" alt="UDS Мастер-Дент" title="UDS Мастер-Дент">
		</a>
							<a href="/provider-32-http:||navistom.com|products|user-10-ipst" title="ИПСТ" target="_self" class="a-cols-5">
			<img src="/uploads/providers/69227095807792740222.jpg" alt="ИПСТ" title="ИПСТ">
		</a>
							<a href="/provider-62-http:||navistom.com|products|user-3815-polet|firm-1705-naturelize" title="ПОЛЕТ" target="_self" class="a-cols-5">
			<img src="/uploads/providers/76791711514752224619.jpg" alt="ПОЛЕТ" title="ПОЛЕТ">
		</a>
							<a href="/provider-3-http:||navistom.com|products|user-13-ukrmed-dental" title="Укрмед-Дентал" target="_self" class="a-cols-5">
			<img src="/uploads/providers/81250931913948420642.jpg" alt="Укрмед-Дентал" title="Укрмед-Дентал">
		</a>
							<a href="/provider-10-http:||navistom.com|products|user-12-premerdental" title="Премьер-Дентал" target="_self" class="a-cols-5">
			<img src="/uploads/providers/88525181667755477871.jpg" alt="Премьер-Дентал" title="Премьер-Дентал">
		</a>
							<a href="/provider-49-http:||navistom.com|product|8742-medit-software-design-medit" title="MED-IT" target="_self" class="a-cols-5">
			<img src="/uploads/providers/41448373693972951826.jpg" alt="MED-IT" title="MED-IT">
		</a>
							<a href="/provider-59-http:||navistom.com|products|user-3981-stomadental" title="Стома-Денталь" target="_self" class="a-cols-5">
			<img src="/uploads/providers/56202685540108990504.jpg" alt="Стома-Денталь" title="Стома-Денталь">
		</a>
							<a href="/provider-61-http:||navistom.com|main|user-5-regard" title="Регард" target="_self" class="a-cols-5">
			<img src="/uploads/providers/75266717403565898215.jpg" alt="Регард" title="Регард">
		</a>
							<a href="/provider-57-http:||navistom.com|journals" title="Навигатор Стоматологии" target="_self" class="a-cols-5">
			<img src="/uploads/providers/32939379879211108108.jpg" alt="Навигатор Стоматологии" title="Навигатор Стоматологии">
		</a>
							<a href="/provider-58-http:||navistom.com|diagnostic|6-3d-rentgendiagnostika-planmeca-promax-3d-ot-250-grn" title="С.К. Дент" target="_self" class="a-cols-5">
			<img src="/uploads/providers/88054863310200602767.jpg" alt="С.К. Дент" title="С.К. Дент">
		</a>
                                    </div>	
		<div class='text_bottom_menu'>
			ТОП-объявления
		<div>  
		
		<div class='all' id='pagination-container-main'>
			<div class='item pagination-block  light'>
				<div class="item pagination-block  light       ">
                        <div class="a-row a-offset-0">
                            <div class="a-cols-2 a-font-small a-color-gray-2">
							<span class="l_top"> <span>топ</span></span> 							<a href="/ads">Продам Б/У</a></div>
                            <div style="font-size:10px" class="a-cols-4 a-font-small a-color-gray-2 a-align-right a-float-right">
                                5 минут назад
                            </div>
                        </div>

                        <div class="offer clear">
                                                            <a title="TAKARA BELMONT VOYAGER, Б/У" href="/ads/3800-takara-belmont-voyager" class="ajax-link"><img title="TAKARA BELMONT VOYAGER, Б/У" alt="TAKARA BELMONT VOYAGER, Б/У" src="/uploads/images/offers/80x100/74875668998537658354.jpg"></a>
                                                        <div class="offer-content">
									 
                                <a target="_blank" title="TAKARA BELMONT VOYAGER, Б/У" href="/ads/3800-takara-belmont-voyager" class="modal-window-link"></a>
                                <a title="TAKARA BELMONT VOYAGER, Б/У" href="/ads/3800-takara-belmont-voyager" class="ajax-link"> TAKARA BELMONT VOYAGER, Б/У</a>
                                <div class="a-font-small a-color-gray">Установка стоматологічна</div>

                                <div class="a-row a-offset-0 offer-footer">
                                    <div class="a-cols-2">
									
                                        <a title="Богдан" data-user_id="3449" href="/main/user-3449-bogdan"><i class="a-icon-user a-icon-gray"></i> Богдан</a>
                                    </div>
                                    <div class="a-cols-2 a-align-right">
                                        <div class="price">

                                            17 730.00 грн.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                                            </div>
				   	
			</div>
		</div>
		
	 
		
			<div class="row">
				 <!--div class="col-md-3"><p>Column 1</p></div>
				<div class="col-md-3"><p>Column 2</p></div>
				<div class="col-md-3"><p>Column 3</p></div>
				<div class="col-md-3"><p>Column 4</p></div--> 
			</div>
		
    </div>	
	<footer>
	
	</footer>
  </div>
	</body>

</html>