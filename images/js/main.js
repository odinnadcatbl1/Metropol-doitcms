$(document).ready(function () {

    //INDEX.HTML

    // закрепленная шапка
    const header = $(".jsHeader"); // селектор jsHeader
    let scrollPos = $(window).scrollTop(); // позиция скролла от верха окна
    // чтобы следить за событием скролла, загрузки и изменения размеров окна страницы:
    $(window).on("scroll load resize", function () {
        headerHeight = header.innerHeight();
        scrollPos = $(this).scrollTop();

        if (scrollPos > headerHeight) {
            header.addClass("fixed"); // добавляем класс fixed
        } else {
            header.removeClass("fixed"); // добавляем класс fixed
        }
    });

    // слайдеры
    if ($(".js-catalog__slider").length) {
        const catalogSlider = $(".js-catalog__slider");

        catalogSlider.slick({
            infinite: true, // если элементы заканчиваются - они повторяются заново
            fade: false, // чтобы затемнялись отзывы
            arrows: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            responsive: [ 

                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
        });
    }
    

    if ($(".js-reviews_slider").length) {
        const reviewsSlider = $(".js-reviews__slider");

        reviewsSlider.slick({
            infinite: true, // если элементы заканчиваются - они повторяются заново
            slidesToShow: 3,
            slidesToScroll: 1,
            fade: false, // чтобы затемнялись отзывы
            arrows: true,
            dots: true,
        });
    }


    // выравнивание по высоте
    if ($(".js-descr-height").length) {
        $(".js-descr-height").matchHeight();
    }


    // выпадающие блоки
    $(document).on("click", ".js-filter-btn", function () {
        const filterParent = $(this).parents(
            '.js-filter-parent[data-parent="' + $(this).data("btn") + '"]'
        );
        if (
            typeof $(this).data("opened") !== "undefined" &&
            typeof $(this).data("closed") !== "undefined"
        ) {
            if ($(this).hasClass("active")) {
                $(this).text($(this).data("closed"));
            } else {
                $(this).text($(this).data("opened"));
            }
        }

        $(this).toggleClass("active");
        filterParent
            .find(
                '.js-filter-content[data-child="' + $(this).data("btn") + '"]'
            )
            .slideToggle(function () {
                $(this).toggleClass("active");
            });

        return false;
    });

    // Параллакс
    $.fn.exists = function (callback) {
        const args = [].slice.call(arguments, 1);
        if (this.length && callback) {
            callback.call(this, args);
        }
        return this;
    };
    $(".js-parallax").exists(function () {
        if ($(window).width() > 767) {
            let _parallax = $(this);
            function _parallax_run() {
                _parallax.each(function () {
                    this.style.backgroundPosition =
                        "50% " +
                        (($(this).offset().top -
                            (window.pageYOffset ||
                                document.documentElement.scrollTop)) /
                            2) *
                            1 +
                        "px";
                });
            }

            $(window).scroll(function () {
                _parallax_run();
            });

            _parallax_run();
        }
    });

    //CATALOG.HTML
    
    // noUiSlider (ползунок для цены)
    if ($(".js-filter-price__slider").length) {
        const rangeSlider = document.querySelector(".js-filter-price__slider");
        const input1 = document.querySelector("#price-input-1");
        const input2 = document.querySelector("#price-input-2");
        const inputs = [input1, input2];

        if (rangeSlider) {
            noUiSlider.create(rangeSlider, {
                start: [0, 99999],
                connect: true,
                step: 1,
                range: {
                    min: 0,
                    max: 10000,
                },
            });
        }

        const setRangeSlider = (i, value) => {
            let arr = [null, null]; // положим пустые элементы, чтобы их потом менять
            arr[i] = value;
            rangeSlider.noUiSlider.set(arr);
        };

        //values - от первого ползунка до второго ползунка
        //handle - сам ползунок
        rangeSlider.noUiSlider.on("update", function (values, handle) {
            if (+input1.value <= +input2.value) {
                inputs[handle].value = Math.round(values[handle]);
            } 
        });

        inputs.forEach((el, index) => {
            $(el).on("change keyup", (e) => {
                setRangeSlider(index, e.currentTarget.value);
            });
        });

    }

    // selects

    let selectToggle = function () {
        $(this).toggleClass("select-active");
    };


    $(document).on("click", ".js-select", selectToggle);
    $(document).on("click", ".js-select__item", function() {
        $('.js-select__current[data-input="'+ $(this).data('input') +'"]').text($(this).text());
        $('.js-catalog-input[data-input="'+ $(this).data('input') +'"]').val($(this).data('value'));
        let parent_body = $(this).parents('.js-select__body');
        parent_body.find(".js-select__item").removeClass("active");
        $(this).addClass("active");
    });


    //нажатие вне div
	$(document).mouseup(function (e){ // событие клика по веб-документу
		const selectBody = $(".js-select"); // тут указываем элемент
		if (!selectBody.is(e.target) // если клик был не по нашему блоку
		    && selectBody.has(e.target).length === 0) { // и не по его дочерним элементам
                selectBody.removeClass("select-active"); // скрываем его
		}
	});

    // CARD.HTML
    if ($("[data-fancybox]").length) {
        $('[data-fancybox]').fancybox({
            buttons: [
                "close"
            ]
        });
    }

    $(document).on("click", ".js-fold__link", function () {
        if (
            typeof $(this).data("opened") !== "undefined" &&
            typeof $(this).data("closed") !== "undefined"
        ) {
            if ($(this).hasClass("active")) {
                $(this).text($(this).data("closed"));
            } else {
                $(this).text($(this).data("opened"));
            }
        }

        $(this).toggleClass("active");

        if ($(".js-hidden__shops").hasClass("active")) {
            $(".js-hidden__shops").slideUp();
        } else {
            $(".js-hidden__shops").slideDown();
        }

        $(".js-hidden__shops").toggleClass("active");
        

        return false;
    });


    // TABS

    (function($){				
        jQuery.fn.easyTabs = function(options){
    
            const createTabs = function(){
                tabs = this;
                i = 0;
                
                showPage = function(i){
                    $(tabs).children(".js-tabs__items").children(".js-tabs__item").hide();
                    $(tabs).children(".js-tabs__items").children(".js-tabs__item").eq(i).show();
                    $(tabs).children(".js-tabs__options").children(".js-tabs__option").removeClass("active");
                    $(tabs).children(".js-tabs__options").children(".js-tabs__option").eq(i).addClass("active");
                }
                                    
                showPage(0);				
                
                $(tabs).children(".js-tabs__options").children(".js-tabs__option").each(function(index, element){
                    $(element).attr("data-page", i);
                    i++;                        
                });
                
                $(tabs).children(".js-tabs__options").children(".js-tabs__option").click(function(){
                    showPage(parseInt($(this).attr("data-page")));
                });				
            };		
            return this.each(createTabs);
        };	
    })(jQuery);
    
    
    if ($(".js-tabs").length) {
        if ($(window).width() > 767) {
            $(".js-tabs").easyTabs();
        }
    
    
        (function($){				
            jQuery.fn.modalTabs = function(options){
        
                const createModalTabs = function(){
                    modal = this;
                    i = 0;
                    
                    showModalPage = function(i){
                        $(modal).children(".js-modal__content").hide();
                        $(modal).children(".js-modal__content").eq(i).show();
                        $(modal).find(".js-modal__btns").children(".js-modal__tab-btn").removeClass("active");
                        $(modal).find(".js-modal__btns").children(".js-modal__tab-btn").eq(i).addClass("active");
                    };
                                        
                    showModalPage(0);				
                    
                    $(modal).find(".js-modal__btns").children(".js-modal__tab-btn").each(function(index, element){
                        $(element).attr("data-page", i);
                        i++;                        
                    });
                    
                    $(modal).find(".js-modal__btns").children(".js-modal__tab-btn").click(function(){
                        showModalPage(parseInt($(this).attr("data-page")));
                    });				
                };		
                return this.each(createModalTabs);
            }; 
        })(jQuery);

        
        $(document).on("click", ".js-tabs__button", function () {
            $(this).toggleClass("active");
            const tabsParent = $(this).parents(".js-tabs__item");
            if (tabsParent.hasClass("active")) {
                tabsParent.children(".js-tabs__inner").slideUp();
                
            } else {
                tabsParent.children(".js-tabs__inner").slideDown();
            }
    
            tabsParent.toggleClass("active");
        });
    }
    

    /*burger-menu*/
    $(document).on("click", ".js-burger", function () {
        $(this).toggleClass("active");
        if ($(".js-md-menu").hasClass("active")) {
            $(".js-md-menu").slideUp();
            
        } else {
            $(".js-md-menu").slideDown();
        }

        $(".js-md-menu").toggleClass("active");
        $(".js-xs-menu").toggleClass("active");
        if ($(".js-search__div").hasClass("active")) {
            $(".js-search__div").toggleClass("active");
            $(".js-search__form").toggleClass("active");
            $(".js-search__form").css('display', 'none');
        }
    });


    $(document).on("click", ".js-nav__img", function () {
        const menuLinkParent = $(this).parents(".js-nav__link");
        $(this).toggleClass("active");

        if (menuLinkParent.children(".js-nav__sub-box").children(".js-nav__sub").hasClass("active")) {
            menuLinkParent.children(".js-nav__sub-box").children(".js-nav__sub").slideUp();
        } else {
            menuLinkParent.children(".js-nav__sub-box").children(".js-nav__sub").slideDown();
        }
    
        menuLinkParent.children(".js-nav__sub-box").children(".js-nav__sub").toggleClass("active");

    });

    if ($(".js-search__form").length) {
        if ($(window).width() < 768) {
            $('.js-search__form').css('display', 'none');
        }
    }

    $(document).on("click", ".js-search__div", function () {
        $(this).toggleClass("active"); 

        if ($(".js-search__form").hasClass("active")) {
            $(".js-search__form").slideUp();
        } else {
            $(".js-search__form").slideDown();
        }

        $(".js-search__form").toggleClass("active");
    });
    

    $(document).on("click", ".js-xs-filter__btn", function () {
        $(this).toggleClass("active"); 
        $(".js-filter__form").toggleClass("active");
        $(".js-xs-body").toggleClass("active");
    });


    // SVG-IMG
    if ($(".js-svg-img").length) {
        $(".js-svg-img").each(function () {
            const $img = $(this);
            const imgID = $img.attr("id");
            const imgClass = $img.attr("class");
            const imgURL = $img.attr("src");

            $.get(
                imgURL,
                function (data) {
                    let $svg = $(data).find("svg");

                    if (typeof imgID !== "undefined") {
                        $svg = $svg.attr("id", imgID);
                    }
                    if (typeof imgClass !== "undefined") {
                        $svg = $svg.attr("class", imgClass + " replaced-svg");
                    }
                    $svg = $svg.removeAttr("xmlns:a");
                    if (
                        !$svg.attr("viewBox") &&
                        $svg.attr("height") &&
                        $svg.attr("width")
                    ) {
                        $svg.attr(
                            "viewBox",
                            "0 0 " +
                                $svg.attr("height") +
                                " " +
                                $svg.attr("width")
                        );
                    }
                    $img.replaceWith($svg);
                    $svg.addClass("ready");
                },
                "xml"
            );
        });
    }

    // Modal search  
    if ($('.js-modal__search-input').length) {
        $(document).on("input", '.js-modal__search-input', function () {
            let searchValue = $(this).val().toLowerCase();
            let searchItems = document.querySelectorAll('.shop__address');
            if (searchValue != '') {
                searchItems.forEach(function (elem) {
                    if (elem.innerText.toLowerCase().search(searchValue) == -1) {
                        elem.parentNode.classList.add('hide');
                    } else {
                        elem.parentNode.classList.remove('hide');
                    }
                    
                });
            } else {
                searchItems.forEach(function (elem) {
                    elem.parentNode.classList.remove('hide');
                });
            }
        });
    }

    if (('.js-modal__map-btn').length) {
        $(document).on('click', '.js-modal__tab-btn', function () {
            if ($('.js-modal__map-btn').hasClass('active')) {
                $('.js-modal__search').css('display', 'none');
            } else {
                $('.js-modal__search').css('display', 'block');
            }
        });
    }
});
