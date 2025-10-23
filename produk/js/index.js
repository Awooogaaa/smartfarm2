$(document).ready(function() {
  
  // ========== TOAST NOTIFICATION ==========
  const toast = $('.custom-toast');
  if (toast.length) {
    setTimeout(function() {
      toast.addClass('show');
    }, 100);

    setTimeout(function() {
      toast.addClass('hide-up');
      toast.on('transitionend', function() {
        $(this).remove();
      });
    }, 4000);
  }

  // ========== IMAGE MODAL ZOOM ==========
  $('#imageModal').on('show.bs.modal', function(event) {
    var triggerElement = $(event.relatedTarget);
    var imageSrc = triggerElement.attr('src');
    var productName = triggerElement.attr('alt');

    var modal = $(this);
    modal.find('.modal-title').text(productName);
    modal.find('#modalImage').attr('src', imageSrc);
  });

  // ========== ENHANCED TABLE ANIMATIONS ==========
  
  // Initial fade-in untuk main card dengan efek smooth
  $('.main-card').css({
    'opacity': '0',
    'transform': 'translateY(30px)'
  }).animate({
    opacity: 1
  }, {
    duration: 800,
    easing: 'swing',
    step: function(now) {
      $(this).css('transform', 'translateY(' + (30 - (30 * now)) + 'px)');
    }
  });

  // Animasi sequential untuk setiap baris tabel (lebih cepat)
  $('.table-clean tbody tr').each(function(index) {
    $(this).css({
      'opacity': '0',
      'transform': 'translateX(-20px)'
    }).delay(50 + (index * 30)).animate({
      opacity: 1
    }, {
      duration: 400,
      easing: 'swing',
      step: function(now) {
        $(this).css('transform', 'translateX(' + (-20 + (20 * now)) + 'px)');
      }
    });
  });

  // Hover effect untuk table rows (seperti kode asli)
  $('.table-clean tbody tr').hover(
    function() {
      $(this).css({
        'background-color': '#f8f9fa',
        'transform': 'scale(1.01)',
        'transition': 'all 0.3s ease',
        'box-shadow': '0 2px 8px rgba(0,0,0,0.1)'
      });
    },
    function() {
      $(this).css({
        'background-color': '',
        'transform': 'scale(1)',
        'box-shadow': ''
      });
    }
  );

  // Smooth scroll ke atas saat pagination diklik
  $('.pagination a').on('click', function(e) {
    $('html, body').animate({
      scrollTop: 0
    }, 600, 'swing');
  });

  // ========== ADDITIONAL ENHANCEMENTS ==========
  
  // Animasi untuk product images saat di-hover
  $('.product-image.zoomable').hover(
    function() {
      $(this).css({
        'transition': 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)',
        'box-shadow': '0 8px 16px rgba(0, 0, 0, 0.2)'
      });
    },
    function() {
      $(this).css({
        'box-shadow': ''
      });
    }
  );

  // Animasi untuk buttons
  $('.btn-edit, .btn-custom, .btn-search').hover(
    function() {
      $(this).css({
        'transition': 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)'
      });
    }
  );

  // Fade-in untuk limit container
  $('.limit-container').css({
    'opacity': '0',
    'transform': 'translateY(10px)'
  }).delay(400).animate({
    opacity: 1
  }, {
    duration: 600,
    step: function(now) {
      $(this).css('transform', 'translateY(' + (10 - (10 * now)) + 'px)');
    }
  });

  // Animasi untuk stats badge
  $('.stats-badge, .mobile-stats-badge').css({
    'opacity': '0',
    'transform': 'scale(0.8)'
  }).delay(600).animate({
    opacity: 1
  }, {
    duration: 500,
    step: function(now) {
      $(this).css('transform', 'scale(' + (0.8 + (0.2 * now)) + ')');
    }
  });

});

$(document).ready(function() {
    // Script untuk modal zoom gambar (dari file lama)
    $('.zoomable').on('click', function() {
        var imgSrc = $(this).data('image-src');
        $('#modalImage').attr('src', imgSrc);
    });

    // Script toast lama (dihapus/dinonaktifkan karena sudah ada di atas)
    /* var toastEl = document.querySelector('.custom-toast');
    if (toastEl) {
        var toast = new bootstrap.Toast(toastEl, { delay: 5000 });
        toastEl.classList.add('show');
        setTimeout(function() {
            toastEl.classList.add('hide-up');
            setTimeout(function() { toast.hide(); }, 400); 
        }, 4600); 
    }
    */
});