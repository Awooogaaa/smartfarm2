$(document).ready(function() {

  // ========== ANIMASI EASE-IN ==========
  
  // Main card fade-in dengan slide up
  $('.main-card').css({
    'opacity': '0',
    'transform': 'translateY(30px)'
  }).animate({
    opacity: 1
  }, {
    duration: 600,
    easing: 'swing',
    step: function(now) {
      $(this).css('transform', 'translateY(' + (30 - (30 * now)) + 'px)');
    }
  });

  // Animasi untuk setiap form group
  $('.form-label, .form-control, .form-select, .upload-area').each(function(index) {
    $(this).css({
      'opacity': '0',
      'transform': 'translateX(-15px)'
    }).delay(200 + (index * 40)).animate({
      opacity: 1
    }, {
      duration: 400,
      easing: 'swing',
      step: function(now) {
        $(this).css('transform', 'translateX(' + (-15 + (15 * now)) + 'px)');
      }
    });
  });

  // Animasi untuk tombol submit
  $('button[type="submit"]').css({
    'opacity': '0',
    'transform': 'scale(0.9)'
  }).delay(800).animate({
    opacity: 1
  }, {
    duration: 500,
    step: function(now) {
      $(this).css('transform', 'scale(' + (0.9 + (0.1 * now)) + ')');
    }
  });

  // ========== FUNGSI FORM ==========

  // Fokus ke input kode saat halaman dimuat
  $('input[name="kode"]').focus();

  // Fungsi untuk menampilkan modal error
  function showErrorModal(message) {
    $('#errorModalBody').text(message);
    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  }

  // Fungsi untuk preview gambar
  function previewImage(file) {
    if (file) {
      const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
      if (!allowedTypes.includes(file.type)) {
        showErrorModal('Hanya file gambar (JPG, JPEG, PNG) yang diperbolehkan!');
        $('#gambar').val('');
        return;
      }

      if (file.size > 2 * 1024 * 1024) {
        showErrorModal('Ukuran file terlalu besar! Maksimal 2MB.');
        $('#gambar').val('');
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        $('#preview').attr('src', e.target.result);
        $('#previewContainer').fadeIn();
      };
      reader.readAsDataURL(file);
    }
  }

  // Event handler saat memilih file gambar
  $('#gambar').on('change', function(event) {
    previewImage(event.target.files[0]);
  });

  // Fungsi untuk hapus preview
  $('#removePreviewBtn').on('click', function() {
    $('#gambar').val('');
    $('#previewContainer').fadeOut();
  });

  // Batasi input harga hanya angka dan maksimal 10 digit
  $('input[name="harga"]').on('input', function(e) {
    let value = $(this).val().replace(/[^\d]/g, '');
    if (value.length > 10) {
      value = value.substring(0, 10);
    }
    $(this).val(value);
  });

  // Validasi form sebelum submit
  $('#productForm').on('submit', function(e) {
    const kode = $('input[name="kode"]').val().trim();
    const nama = $('input[name="nama"]').val().trim();
    const satuan = $('select[name="satuan"]').val().trim();
    const harga = $('input[name="harga"]').val();
    
    if (!kode || !nama || !satuan || !harga || harga < 1) {
      e.preventDefault();
      showErrorModal('Mohon isi semua field yang wajib diisi dengan benar!');
      return false;
    }
  });

  // ========== DRAG AND DROP ==========

  const dropArea = $('.upload-area');

  // Mencegah default browser behavior
  dropArea.on('dragenter dragover dragleave drop', function(e) {
    e.preventDefault();
    e.stopPropagation();
  });

  // Menambahkan highlight saat file di atas area
  dropArea.on('dragenter dragover', function() {
    $(this).css({
      'border-color': '#1976d2',
      'background': '#e8f4fd',
      'transform': 'scale(1.02)',
      'transition': 'all 0.3s ease'
    });
  });

  // Menghilangkan highlight
  dropArea.on('dragleave drop', function() {
    $(this).css({
      'border-color': '#2196f3',
      'background': '#f3f9ff',
      'transform': 'scale(1)'
    });
  });

  // Menangani file yang di-drop
  dropArea.on('drop', function(e) {
    const files = e.originalEvent.dataTransfer.files;
    if (files.length > 0) {
      $('#gambar').prop('files', files);
      previewImage(files[0]);
    }
  });

  // Enhanced hover untuk form inputs
  $('.form-control, .form-select').hover(
    function() {
      $(this).css({
        'transform': 'translateY(-2px)',
        'transition': 'all 0.3s ease'
      });
    },
    function() {
      $(this).css({
        'transform': 'translateY(0)'
      });
    }
  );

});