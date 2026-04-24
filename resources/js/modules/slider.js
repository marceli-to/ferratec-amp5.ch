import Swiper from 'swiper';
import { Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';

export function initSliders() {
  document.querySelectorAll('[data-slider]').forEach((el) => {
    const swiperEl = el.querySelector('.swiper');
    if (!swiperEl) return;

    new Swiper(swiperEl, {
      modules: [Pagination, Navigation],
      loop: true,
      slidesPerView: 1,
      pagination: {
        el: el.querySelector('[data-slider-pagination]'),
        clickable: true,
      },
      navigation: {
        prevEl: el.querySelector('[data-slider-prev]'),
        nextEl: el.querySelector('[data-slider-next]'),
      },
    });
  });
}
