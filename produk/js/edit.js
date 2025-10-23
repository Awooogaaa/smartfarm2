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
  $('.form-label, .form-control, .form-select, .upload-area, .current-image-section').each(function(index) {
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

  // Animasi untuk tombol action
  $('.action-buttons-mobile, .d-none.d-md-flex').css({
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

  // ========== FUNGSI TOGGLE HAPUS GAMBAR ==========
  
  const currentImageWrapper = $('#currentImageWrapper');
  const hapusGambarInput = $('#hapusGambarInput');
  const deleteImageBtn = $('#deleteImageBtn');

  // Fungsi untuk mengaktifkan mode hapus
  function markForDeletion() {
    currentImageWrapper.addClass('marked-for-deletion');
    hapusGambarInput.val('1');
    deleteImageBtn.removeClass('btn-danger').addClass('btn-warning');
    deleteImageBtn.find('i').removeClass('bi-trash').addClass('bi-arrow-counterclockwise');
    deleteImageBtn.attr('title', 'Batalkan Hapus Gambar');
  }

  // Fungsi untuk membatalkan mode hapus
  function unmarkForDeletion() {
    currentImageWrapper.removeClass('marked-for-deletion');
    hapusGambarInput.val('0');
    deleteImageBtn.removeClass('btn-warning').addClass('btn-danger');
    deleteImageBtn.find('i').removeClass('bi-arrow-counterclockwise').addClass('bi-trash');
    deleteImageBtn.attr('title', 'Hapus Gambar Ini');
  }

  // Event handler untuk tombol hapus (toggle)
  deleteImageBtn.on('click', function() {
    if (currentImageWrapper.hasClass('marked-for-deletion')) {
      unmarkForDeletion();
    } else {
      markForDeletion();
    }
  });

  // ========== FUNGSI FORM ==========

  // Fungsi untuk menampilkan modal error
  function showErrorModal(message) {
    $('#errorModalBody').text(message);
    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    errorModal.show();
  }

  // Fungsi untuk preview gambar baru
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
        // Jika memilih gambar baru, otomatis batalkan penghapusan gambar lama
        unmarkForDeletion();
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

  // ========== HOVER EFFECTS ==========

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

// ========== FUNGSI KONFIRMASI HAPUS PRODUK ==========
function confirmDelete(id) {
  document.getElementById('confirmDeleteBtn').href = 'edit.php?id=' + id + '&delete=' + id;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Memastikan fungsi confirmDelete diperbarui untuk menggunakan 'kode'
        function confirmDelete(kode) {
            var deleteUrl = "edit.php?delete=" + kode;
            $('#confirmDeleteBtn').attr('href', deleteUrl);
            var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        $(document).ready(function() {
            // Logika Hapus Gambar
            var isMarkedForDeletion = false;
            
            $('#deleteImageBtn').on('click', function() {
                isMarkedForDeletion = !isMarkedForDeletion;
                if (isMarkedForDeletion) {
                    $('#currentImageWrapper').addClass('marked-for-deletion');
                    $('#hapusGambarInput').val('1');
                    $(this).removeClass('btn-danger').addClass('btn-success').html('<i class="bi bi-arrow-counterclockwise"></i> Batal Hapus');
                } else {
                    $('#currentImageWrapper').removeClass('marked-for-deletion');
                    $('#hapusGambarInput').val('0');
                    $(this).removeClass('btn-success').addClass('btn-danger').html('<i class="bi bi-trash"></i>');
                }
            });

            // Logika Preview Gambar Baru
            $('#gambar').on('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                        $('#previewContainer').show();
                        // Jika ada gambar baru, batalkan status hapus gambar lama
                        if (isMarkedForDeletion) {
                            $('#deleteImageBtn').trigger('click'); // Membatalkan status hapus
                        }
                    }
                    reader.readAsDataURL(this.files[0]);
                } else {
                    $('#previewContainer').hide();
                }
            });

            $('#removePreviewBtn').on('click', function() {
                $('#gambar').val('');
                $('#preview').attr('src', '');
                $('#previewContainer').hide();
            });
        });