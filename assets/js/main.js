var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function () {
        this.classList.toggle("active");


        var panel = this.nextElementSibling;
        if (panel.style.display === "block") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block";
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const accordions = document.querySelectorAll('.accordion');
    const container = document.querySelector('.accordion-container');
    let activeAccordion = null;
    let isClick = false;

    accordions.forEach(accordion => {
        const panel = accordion.nextElementSibling;

        // Клик по аккордеону
        accordion.addEventListener('click', function (e) {
            e.stopPropagation();
            isClick = true;

            // Если это уже активный аккордеон - закрываем
            if (activeAccordion === accordion) {
                panel.classList.remove('show');
                activeAccordion = null;
                isClick = false;
                return;
            }

            // Закрываем все другие панели
            document.querySelectorAll('.panel').forEach(p => {
                p.classList.remove('show');
            });

            // Открываем текущую
            panel.classList.add('show');
            activeAccordion = accordion;
        });

        // Наведение на аккордеон
        accordion.addEventListener('mouseenter', function () {
            if (isClick) return;

            // Закрываем все другие панели
            document.querySelectorAll('.panel').forEach(p => {
                p.classList.remove('show');
            });

            // Открываем текущую
            panel.classList.add('show');
            activeAccordion = accordion;
        });

        // Уход с аккордеона
        accordion.addEventListener('mouseleave', function () {
            if (isClick) return;

            panel.classList.remove('show');
            if (activeAccordion === accordion) {
                activeAccordion = null;
            }
        });

        // Наведение на панель
        panel.addEventListener('mouseenter', function () {
            if (isClick) return;
            panel.classList.add('show');
        });

        // Уход с панели
        panel.addEventListener('mouseleave', function () {
            if (isClick) return;

            panel.classList.remove('show');
            if (activeAccordion === accordion) {
                activeAccordion = null;
            }
        });
    });

    // Клик по документу - сброс
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.accordion') && !e.target.closest('.panel')) {
            document.querySelectorAll('.panel').forEach(panel => {
                panel.classList.remove('show');
            });
            activeAccordion = null;
            isClick = false;
        } else {
            // Сбрасываем флаг клика после небольшой задержки
            setTimeout(() => { isClick = false; }, 100);
        }
    });


});


// SLIDER
// SLIDER
document.addEventListener('DOMContentLoaded', function () {
    let arrowLeftBtn = document.querySelector('.arrowLeftBtnSlider'),
        arrowRightBtn = document.querySelector('.arrowRightBtnSlider');

    let checkWidthElSlider = () => {
        let widthElSlider = document.querySelector('.osnov_card');
        if (!widthElSlider) return 0;
        return parseInt(getComputedStyle(widthElSlider).width);
    };

    let widthSize = checkWidthElSlider();
    let sliderElThis = document.querySelector('.popukar_katalog');
    let indexScroll = 0;

    // Обновляем ширину при ресайзе
    const updateWidth = () => {
        widthSize = checkWidthElSlider();
    };

    arrowLeftBtn.addEventListener('click', () => {
        if (indexScroll > 0) {
            indexScroll--;
            sliderElThis.scrollTo({
                top: 0,
                left: (widthSize + 20) * indexScroll,
                behavior: "smooth"
            });
        }
    });

    let lenElslider = document.querySelectorAll('.popukar_katalog .osnov_card').length;

    arrowRightBtn.addEventListener('click', () => {
        if (indexScroll < lenElslider - 1) {
            indexScroll++;
            sliderElThis.scrollTo({
                top: 0,
                left: (widthSize + 20) * indexScroll,
                behavior: "smooth"
            });
        }
    });

    window.addEventListener('resize', updateWidth);
});


// MINISLIDER

let AllSliderEls = document.getElementsByClassName('popukar_katalog')[0].getElementsByClassName('osnov_card');


for (let i = 0; i < AllSliderEls.length; i++) {
    let arrowLeftBtnSlider = document.createElement('button');
    let arrowRightBtnSlider = document.createElement('button');
    let elIndex = 0;

    arrowLeftBtnSlider.classList = 'arrowLeftBtnElSlider';
    arrowRightBtnSlider.classList = 'arrowRightBtnElSlider';

    arrowLeftBtnSlider.innerHTML = "<"
    arrowRightBtnSlider.innerHTML = ">"

    AllSliderEls[i].appendChild(arrowLeftBtnSlider);
    AllSliderEls[i].appendChild(arrowRightBtnSlider);

    arrowLeftBtnSlider.addEventListener('click', function () {
        let ImgEl = this.parentElement.getElementsByClassName('image_tovar')[0];
        let sizeWidthSliderEl = Number(getComputedStyle(ImgEl).width.slice(0, (getComputedStyle(ImgEl).width.length - 2)));
        let blockForImg = this.parentElement.getElementsByClassName('blockForImg')[0]
        if (elIndex != 0 && elIndex > 0) {
            elIndex = elIndex - 1;
        };

        blockForImg.scrollTo({
            top: 0,
            left: elIndex * sizeWidthSliderEl,
            behavior: "smooth",
        });
    })
    arrowRightBtnSlider.addEventListener('click', function () {
        let ImgEl = this.parentElement.getElementsByClassName('image_tovar')[0];
        let ImgElLen = this.parentElement.getElementsByClassName('image_tovar')
        let sizeWidthSliderEl = Number(getComputedStyle(ImgEl).width.slice(0, (getComputedStyle(ImgEl).width.length - 2)));
        let blockForImg = this.parentElement.getElementsByClassName('blockForImg')[0]
        if (elIndex != ImgElLen.length && elIndex < (ImgElLen.length - 1)) {
            elIndex = elIndex + 1;
        };
        blockForImg.scrollTo({
            top: 0,
            left: elIndex * sizeWidthSliderEl,
            behavior: "smooth",
        });
    })

}