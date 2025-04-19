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

    accordions.forEach(accordion => {
        const panel = accordion.nextElementSibling;

        accordion.addEventListener('click', function () {
            document.querySelectorAll('.panel').forEach(p => {
                if (p !== panel) {
                    p.classList.remove('show');
                }
            });

            panel.classList.toggle('show');
        });

        accordion.addEventListener('mouseenter', function () {
            document.querySelectorAll('.accordion').forEach(acc => {
                acc.style.transform = '';
                acc.style.zIndex = '1';
            });

            this.style.zIndex = '3';
            if (panel) {
                panel.style.zIndex = '3';
            }

            let nextAccordion = this.nextElementSibling;
            let totalHeight = 0;

            while (nextAccordion) {
                if (nextAccordion.classList.contains('accordion')) {
                    const prevPanel = nextAccordion.previousElementSibling;
                    if (prevPanel && prevPanel.classList.contains('panel')) {
                        totalHeight += prevPanel.offsetHeight;
                    }
                    nextAccordion.style.transform = `translateY(${totalHeight}px)`;
                }
                nextAccordion = nextAccordion.nextElementSibling;
            }
        });

        accordion.addEventListener('mouseleave', function () {
            document.querySelectorAll('.accordion').forEach(acc => {
                acc.style.transform = '';
                acc.style.zIndex = '1';
            });

            if (panel) {
                panel.style.zIndex = '2';
            }
        });

        panel.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.matches('.accordion') && !e.target.matches('.panel') && !e.target.matches('.panel a')) {
            document.querySelectorAll('.panel').forEach(panel => {
                panel.classList.remove('show');
            });
        }
    });
});


var acc = document.getElementsByClassName("accordion_2");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click_2", function () {
        this.classList.toggle("active_2");


        var panel = this.nextElementSibling;
        if (panel.style.display === "block_2") {
            panel.style.display = "none";
        } else {
            panel.style.display = "block_2";
        }
    });
}



var acc = document.getElementsByClassName("accordion_2");
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
    // Аккордеон для .fylytr_accordeon
    const filterAccordions = document.querySelectorAll('.fylytr_accordeon .accordion_2');

    filterAccordions.forEach(accordion => {
        accordion.addEventListener('click', function () {
            // Закрываем все другие панели
            filterAccordions.forEach(acc => {
                if (acc !== this) {
                    acc.classList.remove('active_2');
                    const otherPanel = acc.nextElementSibling;
                    if (otherPanel) {
                        otherPanel.style.display = 'none';
                    }
                }
            });

            // Переключаем текущую панель
            this.classList.toggle('active_2');
            const panel = this.nextElementSibling;
            if (panel) {
                panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
            }
        });
    });
});

// Слайдер для popular_content
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.popukar_katalog');
    const leftArrow = document.querySelector('.slider button:first-child');
    const rightArrow = document.querySelector('.slider button:last-child');
    const cards = document.querySelectorAll('.popukar_katalog .osnov_card');

    if (!slider || !leftArrow || !rightArrow || cards.length === 0) return;

    let currentIndex = 0;
    const cardWidth = cards[0].offsetWidth;
    const gap = 20; // Отступ между карточками
    const cardsPerView = window.innerWidth <= 490 ? 1 : window.innerWidth <= 1000 ? 2 : 4;

    function updateSlider() {
        const offset = -currentIndex * (cardWidth + gap);
        slider.style.transform = `translateX(${offset}px)`;

        // Обновляем состояние кнопок
        leftArrow.disabled = currentIndex === 0;
        rightArrow.disabled = currentIndex >= cards.length - cardsPerView;
    }

    leftArrow.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            updateSlider();
        }
    });

    rightArrow.addEventListener('click', () => {
        if (currentIndex < cards.length - cardsPerView) {
            currentIndex++;
            updateSlider();
        }
    });

    // Обновляем при изменении размера окна
    window.addEventListener('resize', () => {
        const newCardsPerView = window.innerWidth <= 490 ? 1 : window.innerWidth <= 1000 ? 2 : 4;
        if (newCardsPerView !== cardsPerView) {
            currentIndex = 0;
            updateSlider();
        }
    });

    // Инициализация
    updateSlider();
});

// Слайдер для many_slider_left
document.addEventListener('DOMContentLoaded', function() {
    const sliders = document.querySelectorAll('.many_slider_left');
    
    sliders.forEach(slider => {
        const images = slider.querySelector('.images');
        const prevBtn = slider.querySelector('.str_slider:first-child');
        const nextBtn = slider.querySelector('.str_slider:last-child');
        const imageItems = slider.querySelectorAll('.images img');
        
        if (!images || !prevBtn || !nextBtn || imageItems.length === 0) return;
        
        let currentIndex = 0;
        const imageWidth = imageItems[0].offsetWidth;
        const gap = 20;
        
        function updateSlider() {
            const offset = -currentIndex * (imageWidth + gap);
            images.style.transform = `translateX(${offset}px)`;
            
            // Обновляем состояние кнопок
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex >= imageItems.length - 1;
        }
        
        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateSlider();
            }
        });
        
        nextBtn.addEventListener('click', () => {
            if (currentIndex < imageItems.length - 1) {
                currentIndex++;
                updateSlider();
            }
        });
        
        // Обновляем при изменении размера окна
        window.addEventListener('resize', () => {
            const newImageWidth = imageItems[0].offsetWidth;
            if (newImageWidth !== imageWidth) {
                updateSlider();
            }
        });
        
        // Инициализация
        updateSlider();
    });
});


